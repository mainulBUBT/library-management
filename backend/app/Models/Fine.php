<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'member_id',
        'loan_id',
        'copy_id',
        'fine_type',
        'amount',
        'paid_amount',
        'status',
        'due_date',
        'calculated_at',
        'description',
        'waiver_reason',
        'waived_by',
        'waived_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
            'calculated_at' => 'datetime',
            'waived_at' => 'datetime',
        ];
    }

    /**
     * Get the member for the fine.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the loan for the fine.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the copy for the fine.
     */
    public function copy(): BelongsTo
    {
        return $this->belongsTo(Copy::class);
    }

    /**
     * Get the staff who waived the fine.
     */
    public function waivedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'waived_by');
    }

    /**
     * Get the payments for the fine.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the remaining balance.
     */
    public function getBalanceAttribute(): float
    {
        return (float) ($this->amount - $this->paid_amount);
    }

    /**
     * Check if fine is fully paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid' || $this->balance <= 0;
    }

    /**
     * Check if fine is partially paid.
     */
    public function isPartiallyPaid(): bool
    {
        return $this->paid_amount > 0 && $this->paid_amount < $this->amount;
    }

    /**
     * Waive the fine.
     */
    public function waive(int $staffId, string $reason): void
    {
        $this->update([
            'status' => 'waived',
            'waived_by' => $staffId,
            'waived_at' => now(),
            'waiver_reason' => $reason,
        ]);
    }
}
