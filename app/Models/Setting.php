<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /** Table: admin_settings */
    protected $table = 'admin_settings';

    protected $fillable = ['key', 'value'];

    public const DEFAULTS = [
        'institute_name' => 'Info-IT Training Institute',
        'support_email'  => 'support@info-it.com',
        'currency'       => 'BDT',
        'timezone'       => 'Asia/Dhaka',
    ];

    public static function allCached(): array
    {
        try {
            return Cache::rememberForever('infoit_admin_settings', fn () =>
                array_merge(self::DEFAULTS, static::pluck('value', 'key')->toArray())
            );
        } catch (\Throwable $e) {
            return self::DEFAULTS; // cache/settings table not migrated yet
        }
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('infoit_admin_settings');
    }
}
