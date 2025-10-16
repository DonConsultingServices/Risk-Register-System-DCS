<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => static::prepareValue($value, $type),
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    /**
     * Get multiple settings as an array
     */
    public static function getMultiple($keys)
    {
        $settings = static::whereIn('key', $keys)->get();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = static::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }

    /**
     * Set multiple settings
     */
    public static function setMultiple($settings)
    {
        foreach ($settings as $key => $data) {
            $value = is_array($data) ? $data['value'] : $data;
            $type = is_array($data) && isset($data['type']) ? $data['type'] : 'string';
            $description = is_array($data) && isset($data['description']) ? $data['description'] : null;
            
            static::set($key, $value, $type, $description);
        }
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     */
    private static function prepareValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'integer':
                return (string) $value; // Store as string but cast back to int
            case 'json':
                return json_encode($value);
            default:
                return (string) $value;
        }
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAll()
    {
        $settings = static::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = static::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }

    /**
     * Initialize default settings
     */
    public static function initializeDefaults()
    {
        $defaults = [
            'risk_assessment_frequency' => [
                'value' => 'monthly',
                'type' => 'string',
                'description' => 'How often risk assessments should be conducted'
            ],
            'auto_risk_scoring' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Enable automatic risk score calculation'
            ],
            'risk_threshold_high' => [
                'value' => 15,
                'type' => 'integer',
                'description' => 'Risk score threshold for high risk classification'
            ],
            'risk_threshold_critical' => [
                'value' => 20,
                'type' => 'integer',
                'description' => 'Risk score threshold for critical risk classification'
            ],
            'email_notifications' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Enable email notifications for risk updates'
            ],
            'high_risk_alerts' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Send immediate alerts for high and critical risks'
            ],
            'overdue_notifications' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Notify when risks become overdue'
            ],
            'notification_frequency' => [
                'value' => 'immediate',
                'type' => 'string',
                'description' => 'Frequency of notifications'
            ],
        ];

        static::setMultiple($defaults);
    }
}