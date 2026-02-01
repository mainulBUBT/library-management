<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'member_code',
        'member_type',
        'status',
        'joined_date',
        'expiry_date',
        'qr_code_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joined_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the member profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the loans for the member.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get the active loans for the member.
     */
    public function activeLoans(): HasMany
    {
        return $this->hasMany(Loan::class)->where('status', 'active');
    }

    /**
     * Get the reservations for the member.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the fines for the member.
     */
    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * Get the unpaid fines for the member.
     */
    public function unpaidFines(): HasMany
    {
        return $this->hasMany(Fine::class)->whereIn('status', ['pending', 'partially_paid']);
    }

    /**
     * Get the payments for the member.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if member can borrow more items.
     */
    public function canBorrow(int $maxLoans = 5): bool
    {
        return $this->activeLoans()->count() < $maxLoans;
    }
}
