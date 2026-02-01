<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'joined_date',
        'employment_status',
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
        ];
    }

    /**
     * Get the user that owns the staff profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the loans processed by this staff member.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'staff_id');
    }

    /**
     * Get the payments received by this staff member.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    /**
     * Get the fines waived by this staff member.
     */
    public function waivedFines(): HasMany
    {
        return $this->hasMany(Fine::class, 'waived_by');
    }
}
