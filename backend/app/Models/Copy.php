<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Copy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'resource_id',
        'copy_number',
        'barcode',
        'qr_code',
        'status',
        'location',
        'condition',
        'purchased_date',
        'purchase_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchased_date' => 'date',
            'purchase_price' => 'decimal:2',
        ];
    }

    /**
     * Get the resource that owns the copy.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Get the loans for the copy.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get the active loan for the copy.
     */
    public function activeLoan(): HasMany
    {
        return $this->hasMany(Loan::class)->where('status', 'active');
    }

    /**
     * Check if copy is available for borrowing.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
