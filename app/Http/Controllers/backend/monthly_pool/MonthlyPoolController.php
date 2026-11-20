<?php

namespace App\Http\Controllers\backend\monthly_pool;

use App\Http\Controllers\Controller;
use App\Models\MonthlyPool;
use App\Models\MonthlyPoolContribution;
use App\Services\MonthlyPoolService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MonthlyPoolController extends Controller
{
    protected MonthlyPoolService $monthlyPoolService;

    public function __construct(MonthlyPoolService $monthlyPoolService)
    {
        $this->monthlyPoolService = $monthlyPoolService;
    }

    public function index()
    {
        $data['title'] = 'Monthly Pool & Advance Fund || ' . get_project_name();
        $data['header'] = [
            'title'      => 'Monthly Pool Management',
            'subtitle'   => 'Track Central Flat Pool, Monthly Contributions & Cash Reserves',
            'breadcrumb' => [
                'Home'         => route('dashboard'),
                'Monthly Pool' => '',
            ],
        ];

        $data['css'] = [
            'style.css',
        ];
        $data['js'] = [
            'comman_function.js',
            'monthly_pool.js',
        ];
        $data['funinit'] = [
            'MonthlyPool.init()',
        ];

        // Overall Active Pool Summary Cards
        $activePool = MonthlyPool::with('contributions')
            ->where('pool_status', 1)
            ->where('status', 1)
            ->latest()
            ->first();

        $data['activePoolSummary'] = null;
        if ($activePool) {
            $data['activePoolSummary'] = $this->monthlyPoolService->getPoolDetails($activePool->id);
        }

        return view('backend.pages.monthly_pool.list', $data);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->can('monthly-pool add')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized access.',
                'jscode'  => '$("#loader").hide();',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'target_month'             => 'required|integer|between:1,12',
            'target_year'              => 'required|integer|min:2020|max:2099',
            'target_amount_per_member' => 'required|numeric|min:1',
            'opening_balance'          => 'nullable|numeric|min:0',
            'notes'                    => 'nullable|string|max:500',
        ], [
            'target_month.required'             => 'Please select target month.',
            'target_year.required'              => 'Please select target year.',
            'target_amount_per_member.required' => 'Please enter contribution amount per member.',
            'target_amount_per_member.min'      => 'Contribution amount must be at least 1 rupee.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'jscode'  => '$("#loader").hide();',
            ]);
        }

        try {
            $pool = $this->monthlyPoolService->storeMonthlyPool($request->all(), $user->id);

            return response()->json([
                'status'   => 'success',
                'message'  => "Monthly pool cycle {$pool->pool_code} successfully initialized.",
                'jscode'   => '$("#loader").hide();$("#add_monthly_pool_modal").modal("hide");',
                'ajaxcall' => 'MonthlyPool.reload();',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'jscode'  => '$("#loader").hide();',
            ]);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->can('monthly-pool edit')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized access.',
                'jscode'  => '$("#loader").hide();',
            ]);
        }

        $poolId = (int) $request->input('id');
        $validator = Validator::make($request->all(), [
            'id'                       => 'required|exists:monthly_pools,id',
            'target_amount_per_member' => 'required|numeric|min:1',
            'opening_balance'          => 'nullable|numeric|min:0',
            'pool_status'              => 'required|in:1,2',
            'notes'                    => 'nullable|string|max:500',
        ], [
            'target_amount_per_member.required' => 'Please enter contribution amount per member.',
            'pool_status.required'              => 'Please select pool cycle status.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'jscode'  => '$("#loader").hide();',
            ]);
        }

        try {
            $pool = $this->monthlyPoolService->updateMonthlyPool($poolId, $request->all(), $user->id);

            return response()->json([
                'status'   => 'success',
                'message'  => "Monthly pool cycle {$pool->pool_code} successfully updated.",
                'jscode'   => '$("#loader").hide();$("#edit_monthly_pool_modal").modal("hide");',
                'ajaxcall' => 'MonthlyPool.reload();',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'jscode'  => '$("#loader").hide();',
            ]);
        }
    }

    public function ajaxcall(Request $request)
    {
        $action = $request->input('action');
        $user = Auth::user();

        switch ($action) {
            case 'getdatatable':
                $page = max(1, (int) $request->input('page', 1));
                $limit = max(1, min(100, (int) $request->input('limit', 10)));
                $search = trim((string) $request->input('q', ''));
                $orderCol = $request->input('order_col', null);
                $orderDir = strtolower((string) $request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

                $columns = [
                    0 => 'id',
                    1 => 'pool_code',
                    2 => 'target_year',
                    3 => 'target_amount_per_member',
                    4 => 'total_target_amount',
                    5 => 'pool_status',
                    6 => 'status',
                ];

                $query = MonthlyPool::with('contributions')->where('status', '!=', 3);

                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->where('pool_code', 'like', "%{$search}%")
                            ->orWhere('target_year', 'like', "%{$search}%")
                            ->orWhere('notes', 'like', "%{$search}%");
                    });
                }

                $totalCount = $query->count();
                $orderColumn = $columns[$orderCol] ?? 'id';
                $records = $query->orderBy($orderColumn, $orderDir)
                    ->skip(($page - 1) * $limit)
                    ->take($limit)
                    ->get();

                $rows = [];
                $i = 0;
                $start = ($page - 1) * $limit;

                foreach ($records as $row) {
                    $collected = $row->total_collected;
                    $target = (float) $row->total_target_amount;
                    $percent = $row->collection_progress_percentage;

                    $poolStatusBadge = $row->pool_status == 1
                        ? '<span class="chip ok">Ongoing</span>'
                        : '<span class="chip dmg">Closed</span>';

                    $progressBar = '
                        <div style="min-width: 140px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11.5px; margin-bottom: 3px;">
                                <span style="font-weight: 600;">' . format_rupee($collected) . '</span>
                                <span style="color: var(--muted, #64748b);">' . $percent . '%</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: rgba(255,255,255,0.08); border-radius: 999px;">
                                <div class="progress-bar" role="progressbar" style="width: ' . $percent . '%; background: linear-gradient(135deg, #10B981, #06B6D4); border-radius: 999px;" aria-valuenow="' . $percent . '" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    ';

                    $actionButtons = '<div style="display: flex; gap: 6px; justify-content: center; align-items: center;">';

                    // View Contributions
                    $actionButtons .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary view-contributions" data-id="' . $row->id . '" title="View Member Contributions">
                        <i class="ki-outline ki-profile-user fs-5"></i>
                    </button>';

                    // Edit
                    if ($user && $user->can('monthly-pool edit')) {
                        $actionButtons .= '<button type="button" class="btn btn-sm btn-icon btn-light-warning edit-monthly-pool" data-id="' . $row->id . '" title="Edit Pool">
                            <i class="ki-outline ki-pencil fs-5"></i>
                        </button>';
                    }

                    // Delete
                    if ($user && $user->can('monthly-pool delete')) {
                        $actionButtons .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger delete-monthly-pool" data-id="' . $row->id . '" title="Delete Pool">
                            <i class="ki-outline ki-trash fs-5"></i>
                        </button>';
                    }

                    $actionButtons .= '</div>';

                    $nestedData = [];
                    // Column 0: 1-Based Sequential Serial Index
                    $nestedData[] = '<div style="text-align: center; font-weight: 600; color: var(--muted, #64748b);">' . ($start + $i + 1) . '</div>';
                    // Column 1: Pool Reference Code
                    $nestedData[] = '<div><strong style="color: #F8FAFC;">' . e($row->pool_code) . '</strong></div>';
                    // Column 2: Target Month & Year
                    $nestedData[] = '<div><strong>' . e($row->month_name) . ' ' . e($row->target_year) . '</strong></div>';
                    // Column 3: Target per Flatmate
                    $nestedData[] = '<div>' . format_rupee($row->target_amount_per_member) . '</div>';
                    // Column 4: Collection Progress
                    $nestedData[] = $progressBar;
                    // Column 5: Pool Status (Centered)
                    $nestedData[] = '<div style="display: flex; justify-content: center; align-items: center; width: 100%;">' . $poolStatusBadge . '</div>';
                    // Column 6: Actions (Centered)
                    $nestedData[] = '<div style="display: flex; justify-content: center; align-items: center; width: 100%;">' . $actionButtons . '</div>';

                    $rows[] = $nestedData;
                    $i++;
                }

                return response()->json([
                    'total' => $totalCount,
                    'data'  => $rows,
                ]);

            case 'add-monthly-pool':
                $nextMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;
                return view('backend.pages.monthly_pool.add', [
                    'nextMonth'   => $nextMonth,
                    'currentYear' => $currentYear,
                ]);

            case 'edit-monthly-pool':
                $poolId = (int) $request->input('id');
                $pool = MonthlyPool::where('status', '!=', 3)->findOrFail($poolId);
                return view('backend.pages.monthly_pool.edit', [
                    'item' => $pool,
                ]);

            case 'view-contributions':
                $poolId = (int) $request->input('id');
                $details = $this->monthlyPoolService->getPoolDetails($poolId);
                return view('backend.pages.monthly_pool.contributions_modal', $details);

            case 'record-payment-modal':
                $contributionId = (int) $request->input('id');
                $contribution = MonthlyPoolContribution::with(['monthlyPool', 'user'])
                    ->where('status', 1)
                    ->findOrFail($contributionId);

                return view('backend.pages.monthly_pool.record_payment_modal', [
                    'contribution' => $contribution,
                ]);

            case 'save-contribution-payment':
                $contributionId = (int) $request->input('contribution_id');
                $validator = Validator::make($request->all(), [
                    'contribution_id'       => 'required|exists:monthly_pool_contributions,id',
                    'paid_amount'           => 'required|numeric|min:0',
                    'payment_mode'          => 'required|in:1,2,3,4',
                    'transaction_reference' => 'nullable|string|max:100',
                    'payment_date'          => 'required|date',
                    'notes'                 => 'nullable|string|max:300',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => $validator->errors()->first(),
                        'jscode'  => '$("#loader").hide();',
                    ]);
                }

                try {
                    $this->monthlyPoolService->recordContributionPayment($contributionId, $request->all(), $user->id);

                    return response()->json([
                        'status'   => 'success',
                        'message'  => 'Flatmate contribution payment successfully recorded.',
                        'jscode'   => '$("#loader").hide();$("#record_payment_modal").modal("hide");',
                        'ajaxcall' => 'MonthlyPool.refreshContributionsModal(' . $request->input('monthly_pool_id') . '); MonthlyPool.reload();',
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => $e->getMessage(),
                        'jscode'  => '$("#loader").hide();',
                    ]);
                }

            case 'whatsapp-reminder':
                $contributionId = (int) $request->input('id');
                try {
                    $url = $this->monthlyPoolService->generateWhatsAppReminderUrl($contributionId);
                    return response()->json([
                        'status' => 'success',
                        'url'    => $url,
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => $e->getMessage(),
                    ]);
                }

            case 'common-monthly-pool':
                $poolId = (int) $request->input('id');
                $type = (int) $request->input('type'); // 1: Active, 2: Inactive, 3: Delete

                try {
                    $this->monthlyPoolService->togglePoolStatus($poolId, $type, $user->id);

                    $messages = [
                        1 => 'Monthly pool successfully activated.',
                        2 => 'Monthly pool successfully marked inactive.',
                        3 => 'Monthly pool cycle successfully deleted.',
                    ];

                    return response()->json([
                        'status'   => 'success',
                        'message'  => $messages[$type] ?? 'Status successfully updated.',
                        'jscode'   => '$("#loader").hide();$("#confirm_delete_modal").modal("hide");',
                        'ajaxcall' => 'MonthlyPool.reload();',
                    ]);
                } catch (Exception $e) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => $e->getMessage(),
                        'jscode'  => '$("#loader").hide();',
                    ]);
                }

            default:
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid action.',
                ]);
        }
    }
}
