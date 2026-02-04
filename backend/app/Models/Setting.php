<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'display_name',
        'description',
    ];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, string $type = 'string', ?string $displayName = null, ?string $description = null, ?string $group = null): void
    {
        $displayValue = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => (string) $value,
        };

        $data = [
            'value' => $displayValue,
            'type' => $type,
        ];

        // Add optional fields if provided, otherwise use key as display name
        $data['display_name'] = $displayName ?? str_replace('_', ' ', ucwords($key, '_'));
        
        if ($description !== null) {
            $data['description'] = $description;
        }
        
        if ($group !== null) {
            $data['group'] = $group;
        }

        static::updateOrCreate(
            ['key' => $key],
            $data
        );
    }

    /**
     * Get loan period setting.
     */
    public static function loanPeriod(string $memberType = 'public'): int
    {
        return (int) static::get("loan_period_{$memberType}", 14);
    }

    /**
     * Get max loans setting for member type.
     */
    public static function maxLoans(string $memberType = 'public'): int
    {
        return (int) static::get("max_loans_{$memberType}", 5);
    }

    /**
     * Get fine per day setting.
     */
    public static function finePerDay(): float
    {
        return (float) static::get('fine_per_day', 0.50);
    }

    /**
     * Get max renewals setting.
     */
    public static function maxRenewals(): int
    {
        return (int) static::get('max_renewals', 2);
    }
}
