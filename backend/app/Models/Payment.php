<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fine_id',
        'member_id',
        'received_by',
        'amount',
        'payment_method',
        'check_number',
        'transaction_reference',
        'payment_date',
        'notes',
        'receipt_number',
        'receipt_path',
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
            'payment_date' => 'datetime',
        ];
    }

    /**
     * Get the fine for the payment.
     */
    public function fine(): BelongsTo
    {
        return $this->belongsTo(Fine::class);
    }

    /**
     * Get the member for the payment.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the staff who received the payment.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'received_by');
    }
}
