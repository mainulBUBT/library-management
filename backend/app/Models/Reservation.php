<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'resource_id',
        'member_id',
        'copy_id',
        'status',
        'reserved_at',
        'ready_at',
        'expires_at',
        'fulfilled_at',
        'cancelled_at',
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
            'reserved_at' => 'datetime',
            'ready_at' => 'datetime',
            'expires_at' => 'datetime',
            'fulfilled_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Get the resource for the reservation.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Get the member for the reservation.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the copy for the reservation.
     */
    public function copy(): BelongsTo
    {
        return $this->belongsTo(Copy::class);
    }

    /**
     * Check if reservation is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'pending' &&
            $this->expires_at &&
            $this->expires_at->isPast();
    }

    /**
     * Check if reservation is ready for pickup.
     */
    public function isReady(): bool
    {
        return $this->status === 'ready';
    }

    /**
     * Cancel the reservation.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }
}
