<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
        'description',
        'options',
        'is_public',
        'is_editable',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
        'is_editable' => 'boolean',
        'sort_order' => 'integer',
    ];

    const CACHE_KEY = 'system_settings';
    const CACHE_TTL = 3600; // 1 hora

    /**
     * Obtener valor de configuración
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::getAllCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Establecer valor de configuración
     */
    public static function set(string $key, $value, string $group = 'general'): bool
    {
        $setting = self::where('key', $key)->where('group', $group)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::create([
                'group' => $group,
                'key' => $key,
                'value' => $value,
            ]);
        }

        self::clearCache();
        return true;
    }

    /**
     * Obtener todas las configuraciones cacheadas
     */
    public static function getAllCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Obtener configuraciones por grupo
     */
    public static function getByGroup(string $group): array
    {
        return self::where('group', $group)
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    /**
     * Limpiar caché
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Obtener valor tipado
     */
    public function getTypedValueAttribute()
    {
        return match($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'array', 'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Scope por grupo
     */
    public function scopeOfGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope para configuraciones públicas
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para configuraciones editables
     */
    public function scopeEditable($query)
    {
        return $query->where('is_editable', true);
    }
}
