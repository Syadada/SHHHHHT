<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai pengaturan berdasarkan key.
     */
    public static function get(string $key, $default = null)
    {
        try {
            if (Schema::hasTable('app_settings')) {
                $setting = static::where('key', $key)->first();
                if ($setting && $setting->value !== null && $setting->value !== '') {
                    return $setting->value;
                }
            }
        } catch (\Throwable $e) {
            // fallback
        }

        // Cek fail-safe file storage/app/settings.json jika ada
        try {
            $filePath = storage_path('app/settings.json');
            if (file_exists($filePath)) {
                $json = json_decode(file_get_contents($filePath), true);
                if (isset($json[$key])) {
                    return $json[$key];
                }
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return $default;
    }

    /**
     * Simpan nilai pengaturan.
     */
    public static function set(string $key, $value)
    {
        try {
            if (Schema::hasTable('app_settings')) {
                static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
            }
        } catch (\Throwable $e) {
            // fallback
        }

        // Simpan juga ke storage/app/settings.json sebagai fail-safe
        try {
            $filePath = storage_path('app/settings.json');
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $json = file_exists($filePath) ? (json_decode(file_get_contents($filePath), true) ?: []) : [];
            $json[$key] = $value;
            @file_put_contents($filePath, json_encode($json, JSON_PRETTY_PRINT));
        } catch (\Throwable $e) {
            // ignore
        }

        return $value;
    }
}

