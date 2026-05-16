<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SuperAdminSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'super_admin_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("sa_setting_{$key}", 300, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @param string|null $description
     * @return static
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text', ?string $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );

        Cache::forget("sa_setting_{$key}");

        return $setting;
    }

    /**
     * Get all settings for a group.
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getGroup(string $group)
    {
        return Cache::remember("sa_settings_group_{$group}", 300, function () use ($group) {
            return static::where('group', $group)->get();
        });
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("sa_setting_{$setting->key}");
        }

        $groups = static::select('group')->distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("sa_settings_group_{$group}");
        }
    }

    /**
     * Cast a value to the appropriate type.
     *
     * @param string|null $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue(?string $value, string $type)
    {
        if ($value === null) {
            return null;
        }

        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Scope a query to a specific group.
     */
    public function scopeOfGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
