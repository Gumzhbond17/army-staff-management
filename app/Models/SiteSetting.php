<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key (cached).
     * Usage: SiteSetting::get('ministry_name', 'fallback')
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return static::pluck('value', 'key')->all();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Create or update a setting and refresh the cache.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site_settings');
    }
}
