<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is an admin (super admin, librarian, or assistant librarian)
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'librarian', 'assistant_librarian']);
    }

    /**
     * Check if user is a member
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Get the staff profile associated with the user.
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Get the member profile associated with the user.
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }
}
