<?php

namespace App\Services;

use App\Models\MonthlyPool;
use App\Models\MonthlyPoolContribution;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class MonthlyPoolService
{
    /**
     * Generate a unique standardized pool reference code
     */
    public function generatePoolCode(int $year, int $month): string
    {
        $monthPadded = str_pad((string) $month, 2, '0', STR_PAD_LEFT);
        $baseCode = "POL-{$year}-{$monthPadded}";

        $existingCount = MonthlyPool::where('pool_code', 'like', "{$baseCode}%")->count();
        if ($existingCount === 0) {
            return $baseCode;
        }

        $sequence = str_pad((string) ($existingCount + 1), 2, '0', STR_PAD_LEFT);
        return "{$baseCode}-{$sequence}";
    }

    /**
     * Store and initialize a new monthly pool cycle with contributions for all active flatmates
     */
    public function storeMonthlyPool(array $data, int $userId): MonthlyPool
    {
        $targetMonth = (int) ($data['target_month'] ?? Carbon::now()->month);
        $targetYear = (int) ($data['target_year'] ?? Carbon::now()->year);
        $targetAmountPerMember = (float) ($data['target_amount_per_member'] ?? 0.00);
        $openingBalance = (float) ($data['opening_balance'] ?? 0.00);
        $notes = !empty($data['notes']) ? trim($data['notes']) : null;

        if ($targetAmountPerMember <= 0) {
            throw new Exception('Target contribution amount per flatmate must be greater than zero.');
        }

        // Prevent duplicate ongoing pool for the same month and year
        $existingPool = MonthlyPool::where('target_month', $targetMonth)
            ->where('target_year', $targetYear)
            ->where('status', '!=', 3)
            ->first();

        if ($existingPool) {
            $monthName = config("constants.MONTHS.{$targetMonth}") ?? '-';
            throw new Exception("A monthly pool cycle for {$monthName} {$targetYear} already exists ({$existingPool->pool_code}).");
        }

        DB::beginTransaction();
        try {
            // Retrieve all currently active flatmates
            $activeMembers = User::where('status', 1)
                ->where('is_active', 1)
                ->orderBy('name', 'asc')
                ->get();

            if ($activeMembers->isEmpty()) {
                throw new Exception('No active flatmates found to assign pool contributions.');
            }

            $totalTargetAmount = (float) ($activeMembers->count() * $targetAmountPerMember);
            $poolCode = $this->generatePoolCode($targetYear, $targetMonth);

            $pool = MonthlyPool::create([
                'pool_code'                => $poolCode,
                'target_month'             => $targetMonth,
                'target_year'              => $targetYear,
                'target_amount_per_member' => $targetAmountPerMember,
                'total_target_amount'      => $totalTargetAmount,
                'opening_balance'          => $openingBalance,
                'notes'                    => $notes,
                'pool_status'              => 1, // Ongoing
                'status'                   => 1, // Active
                'added_by'                 => $userId,
                'updated_by'               => $userId,
            ]);

            // Assign individual contribution ledger rows for each roommate
            foreach ($activeMembers as $member) {
                MonthlyPoolContribution::create([
                    'monthly_pool_id'       => $pool->id,
                    'user_id'               => $member->id,
                    'expected_amount'       => $targetAmountPerMember,
                    'paid_amount'           => 0.00,
                    'payment_status'        => 2, // Pending
                    'payment_mode'          => null,
                    'transaction_reference' => null,
                    'payment_date'          => null,
                    'notes'                 => null,
                    'status'                => 1, // Active
                    'added_by'              => $userId,
                    'updated_by'            => $userId,
                ]);
            }

            DB::commit();
            return $pool;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update monthly pool details
     */
    public function updateMonthlyPool(int $poolId, array $data, int $userId): MonthlyPool
    {
        DB::beginTransaction();
        try {
            $pool = MonthlyPool::where('status', '!=', 3)->findOrFail($poolId);

            $targetAmountPerMember = isset($data['target_amount_per_member'])
                ? (float) $data['target_amount_per_member']
                : (float) $pool->target_amount_per_member;

            $openingBalance = isset($data['opening_balance'])
                ? (float) $data['opening_balance']
                : (float) $pool->opening_balance;

            $notes = isset($data['notes']) ? trim($data['notes']) : $pool->notes;
            $poolStatus = isset($data['pool_status']) ? (int) $data['pool_status'] : $pool->pool_status;

            // If per-member target changed, recalculate expected amount for pending/unpaid members
            if ($targetAmountPerMember > 0 && $targetAmountPerMember !== (float) $pool->target_amount_per_member) {
                $contributions = MonthlyPoolContribution::where('monthly_pool_id', $pool->id)
                    ->where('status', 1)
                    ->get();

                foreach ($contributions as $contribution) {
                    $contribution->expected_amount = $targetAmountPerMember;
                    if ((float) $contribution->paid_amount >= $targetAmountPerMember) {
                        $contribution->payment_status = 1; // Paid
                    } elseif ((float) $contribution->paid_amount > 0) {
                        $contribution->payment_status = 3; // Partial
                    } else {
                        $contribution->payment_status = 2; // Pending
                    }
                    $contribution->updated_by = $userId;
                    $contribution->save();
                }

                $activeMembersCount = $contributions->count();
                $pool->total_target_amount = (float) ($activeMembersCount * $targetAmountPerMember);
            }

            $pool->target_amount_per_member = $targetAmountPerMember;
            $pool->opening_balance = $openingBalance;
            $pool->notes = $notes;
            $pool->pool_status = $poolStatus;
            $pool->updated_by = $userId;
            $pool->save();

            DB::commit();
            return $pool;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Record or update a roommate's payment contribution
     */
    public function recordContributionPayment(int $contributionId, array $data, int $userId): MonthlyPoolContribution
    {
        DB::beginTransaction();
        try {
            $contribution = MonthlyPoolContribution::with(['monthlyPool', 'user'])
                ->where('status', '!=', 3)
                ->findOrFail($contributionId);

            $paidAmount = isset($data['paid_amount']) ? (float) $data['paid_amount'] : 0.00;
            $paymentMode = isset($data['payment_mode']) ? (int) $data['payment_mode'] : 1; // 1: Cash, 2: UPI
            $transactionReference = !empty($data['transaction_reference']) ? trim($data['transaction_reference']) : null;
            $paymentDate = !empty($data['payment_date']) ? Carbon::parse($data['payment_date'])->toDateString() : Carbon::now()->toDateString();
            $notes = !empty($data['notes']) ? trim($data['notes']) : null;

            if ($paidAmount < 0) {
                throw new Exception('Paid amount cannot be negative.');
            }

            // Determine payment status
            $expectedAmount = (float) $contribution->expected_amount;
            if ($paidAmount >= $expectedAmount && $expectedAmount > 0) {
                $paymentStatus = 1; // Paid
            } elseif ($paidAmount > 0) {
                $paymentStatus = 3; // Partial
            } else {
                $paymentStatus = 2; // Pending
            }

            $contribution->update([
                'paid_amount'           => $paidAmount,
                'payment_status'        => $paymentStatus,
                'payment_mode'          => $paymentMode,
                'transaction_reference' => $transactionReference,
                'payment_date'          => $paymentDate,
                'notes'                 => $notes,
                'updated_by'            => $userId,
            ]);

            DB::commit();
            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Retrieve complete details and statistics for a monthly pool cycle
     */
    public function getPoolDetails(int $poolId): array
    {
        $pool = MonthlyPool::with([
            'contributions' => function ($q) {
                $q->where('status', 1)->with('user');
            },
            'creator',
            'updater'
        ])
            ->where('status', '!=', 3)
            ->findOrFail($poolId);

        $totalMembers = $pool->contributions->count();
        $totalCollected = (float) $pool->contributions->sum('paid_amount');
        $totalTarget = (float) $pool->total_target_amount;
        $remainingTarget = max(0.00, $totalTarget - $totalCollected);

        $paidCount = $pool->contributions->where('payment_status', 1)->count();
        $pendingCount = $pool->contributions->where('payment_status', 2)->count();
        $partialCount = $pool->contributions->where('payment_status', 3)->count();

        $percentage = $totalTarget > 0 ? (int) round(($totalCollected / $totalTarget) * 100) : 0;

        return [
            'pool'             => $pool,
            'contributions'    => $pool->contributions,
            'total_members'    => $totalMembers,
            'total_target'     => $totalTarget,
            'total_collected'  => $totalCollected,
            'remaining_target' => $remainingTarget,
            'paid_count'       => $paidCount,
            'pending_count'    => $pendingCount,
            'partial_count'    => $partialCount,
            'percentage'       => min(100, max(0, $percentage)),
        ];
    }

    /**
     * Format WhatsApp payment reminder message link for roommates with pending/partial contributions
     */
    public function generateWhatsAppReminderUrl(int $contributionId): string
    {
        $contribution = MonthlyPoolContribution::with(['monthlyPool', 'user'])
            ->where('status', 1)
            ->findOrFail($contributionId);

        $user = $contribution->user;
        $pool = $contribution->monthlyPool;

        $phone = preg_replace('/[^0-9]/', '', $user->phone ?? '');
        // Prefix with India country code 91 if 10-digit number
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        $monthName = $pool->month_name;
        $year = $pool->target_year;
        $expected = number_format((float) $contribution->expected_amount, 2);
        $paid = number_format((float) $contribution->paid_amount, 2);
        $pending = number_format(max(0.00, (float) $contribution->expected_amount - (float) $contribution->paid_amount), 2);

        $message = "Hey {$user->name}! 👋\n\n"
            . "Friendly reminder regarding our flat's Monthly Pool for *{$monthName} {$year}* ({$pool->pool_code}).\n"
            . "• Share Assigned: ₹{$expected}\n"
            . "• Deposited: ₹{$paid}\n"
            . "• Pending Due: *₹{$pending}*\n\n"
            . "Please deposit your contribution via UPI / Cash into the flat pool drawer when convenient. Thank you! 🏠✨";

        return 'https://api.whatsapp.com/send?phone=' . urlencode($phone) . '&text=' . urlencode($message);
    }

    /**
     * Delete or toggle status of a monthly pool
     */
    public function togglePoolStatus(int $poolId, int $newStatus, int $userId): bool
    {
        $pool = MonthlyPool::findOrFail($poolId);
        $result = $pool->update([
            'status'     => $newStatus,
            'updated_by' => $userId,
        ]);

        // If logically deleted (3), also soft delete associated contributions
        if ($result && $newStatus === 3) {
            MonthlyPoolContribution::where('monthly_pool_id', $poolId)->update([
                'status'     => 3,
                'updated_by' => $userId,
            ]);
        }

        return (bool) $result;
    }
}
