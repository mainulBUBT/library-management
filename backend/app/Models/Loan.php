<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'copy_id',
        'member_id',
        'staff_id',
        'borrowed_date',
        'due_date',
        'return_date',
        'status',
        'renewed_count',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'borrowed_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
            'renewed_count' => 'integer',
        ];
    }

    /**
     * Get the copy for the loan.
     */
    public function copy(): BelongsTo
    {
        return $this->belongsTo(Copy::class);
    }

    /**
     * Get the member for the loan.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the staff who processed the loan.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the fines associated with the loan.
     */
    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * Check if loan is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && $this->due_date->isPast();
    }

    /**
     * Get the number of days overdue.
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->startOfDay()->diffInDays($this->due_date);
    }

    /**
     * Check if loan can be renewed.
     */
    public function canRenew(int $maxRenewals = 2): bool
    {
        return $this->status === 'active' &&
            $this->renewed_count < $maxRenewals &&
            !$this->isOverdue();
    }

    /**
     * Calculate the new due date after renewal.
     */
    public function calculateRenewalDate(int $loanPeriodDays = 14): \Carbon\Carbon
    {
        return $this->due_date->addDays($loanPeriodDays);
    }
}
