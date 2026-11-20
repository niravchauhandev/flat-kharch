<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyPoolContribution extends Model
{
    use HasFactory;

    protected $table = 'monthly_pool_contributions';

    protected $fillable = [
        'monthly_pool_id',
        'user_id',
        'expected_amount',
        'paid_amount',
        'payment_status',
        'payment_mode',
        'transaction_reference',
        'payment_date',
        'notes',
        'status',
        'added_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'monthly_pool_id' => 'integer',
            'user_id'         => 'integer',
            'expected_amount' => 'decimal:2',
            'paid_amount'     => 'decimal:2',
            'payment_status'  => 'integer',
            'payment_mode'    => 'integer',
            'payment_date'    => 'date',
            'status'          => 'integer',
            'added_by'        => 'integer',
            'updated_by'      => 'integer',
        ];
    }

    public function monthlyPool(): BelongsTo
    {
        return $this->belongsTo(MonthlyPool::class, 'monthly_pool_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function isPaid(): bool
    {
        return (int) $this->payment_status === 1;
    }

    public function isPending(): bool
    {
        return (int) $this->payment_status === 2;
    }

    public function isPartial(): bool
    {
        return (int) $this->payment_status === 3;
    }

    public function getPaymentModeNameAttribute(): string
    {
        return match ((int) $this->payment_mode) {
            1 => 'Cash',
            2 => 'UPI / GPay',
            3 => 'Bank Transfer',
            4 => 'Other',
            default => '-',
        };
    }

    public function getPaymentStatusNameAttribute(): string
    {
        return match ((int) $this->payment_status) {
            1 => 'Paid',
            2 => 'Pending',
            3 => 'Partial',
            default => '-',
        };
    }
}
