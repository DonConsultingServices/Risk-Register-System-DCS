<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskThresholdSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get a setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('setting_key', $key)
            ->where('is_active', true)
            ->first();
            
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Set a setting value
     */
    public static function setValue($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'description' => $description,
                'is_active' => true
            ]
        );
    }

    /**
     * Get automatic rejection threshold
     */
    public static function getAutoRejectionThreshold()
    {
        return (int) self::getValue('auto_rejection_threshold', 20);
    }

    /**
     * Set automatic rejection threshold
     */
    public static function setAutoRejectionThreshold($threshold)
    {
        return self::setValue(
            'auto_rejection_threshold', 
            $threshold, 
            'Risk score threshold for automatic client rejection'
        );
    }

    /**
     * Check if automatic rejection is enabled
     */
    public static function isAutoRejectionEnabled()
    {
        return (bool) self::getValue('auto_rejection_enabled', true);
    }

    /**
     * Enable or disable automatic rejection
     */
    public static function setAutoRejectionEnabled($enabled)
    {
        return self::setValue(
            'auto_rejection_enabled', 
            $enabled ? '1' : '0', 
            'Enable or disable automatic client rejection based on risk threshold'
        );
    }
}