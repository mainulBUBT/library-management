<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publisher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'website_url',
    ];

    /**
     * Get the resources for the publisher.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
