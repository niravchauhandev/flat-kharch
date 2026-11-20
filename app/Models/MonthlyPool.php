<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyPool extends Model
{
    use HasFactory;

    protected $table = 'monthly_pools';

    protected $fillable = [
        'pool_code',
        'target_month',
        'target_year',
        'target_amount_per_member',
        'total_target_amount',
        'opening_balance',
        'notes',
        'pool_status',
        'status',
        'added_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'target_month'             => 'integer',
            'target_year'              => 'integer',
            'target_amount_per_member' => 'decimal:2',
            'total_target_amount'      => 'decimal:2',
            'opening_balance'          => 'decimal:2',
            'pool_status'              => 'integer',
            'status'                   => 'integer',
            'added_by'                 => 'integer',
            'updated_by'               => 'integer',
        ];
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(MonthlyPoolContribution::class, 'monthly_pool_id');
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

    public function scopeOngoing($query)
    {
        return $query->where('pool_status', 1);
    }

    public function isOngoing(): bool
    {
        return (int) $this->pool_status === 1;
    }

    public function isClosed(): bool
    {
        return (int) $this->pool_status === 2;
    }

    public function getMonthNameAttribute(): string
    {
        $months = config('constants.MONTHS', []);
        return $months[$this->target_month] ?? '-';
    }

    public function getTotalCollectedAttribute(): float
    {
        return (float) $this->contributions()->where('status', 1)->sum('paid_amount');
    }

    public function getRemainingTargetAttribute(): float
    {
        $remaining = (float) $this->total_target_amount - $this->total_collected;
        return max(0.00, $remaining);
    }

    public function getCollectionProgressPercentageAttribute(): int
    {
        if ((float) $this->total_target_amount <= 0) {
            return 0;
        }

        $percentage = ($this->total_collected / (float) $this->total_target_amount) * 100;
        return min(100, max(0, (int) round($percentage)));
    }
}
