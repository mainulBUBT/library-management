<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'isbn',
        'resource_type',
        'description',
        'category_id',
        'publisher_id',
        'publication_year',
        'language',
        'pages',
        'cover_image',
        'file_path',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'pages' => 'integer',
        ];
    }

    /**
     * Get the category that owns the resource.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the publisher that owns the resource.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * The authors that belong to the resource.
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_resource')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the first author (for compatibility with singular author usage).
     */
    public function author(): BelongsToMany
    {
        return $this->authors()->limit(1);
    }

    /**
     * Accessor for the first author.
     */
    public function getAuthorAttribute()
    {
        return $this->authors->first();
    }

    /**
     * Get the copies for the resource.
     */
    public function copies(): HasMany
    {
        return $this->hasMany(Copy::class);
    }

    /**
     * Get the available copies for the resource.
     */
    public function availableCopies(): HasMany
    {
        return $this->hasMany(Copy::class)->where('status', 'available');
    }

    /**
     * Get the reservations for the resource.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Check if resource is available for borrowing.
     */
    public function isAvailable(): bool
    {
        return $this->availableCopies()->count() > 0;
    }
}
