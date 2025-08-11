<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = $this->getSettings();
        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'system_email' => 'required|email',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'enable_notifications' => 'boolean',
            'notification_email' => 'nullable|email',
            'auto_backup' => 'boolean',
            'backup_frequency' => 'required_if:auto_backup,1|string',
            'session_timeout' => 'required|integer|min:5|max:480',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'password_expiry_days' => 'required|integer|min:30|max:365',
            'risk_threshold_high' => 'required|integer|min:1|max:20',
            'risk_threshold_medium' => 'required|integer|min:1|max:20',
            'risk_threshold_low' => 'required|integer|min:1|max:20',
        ]);

        // Update settings in cache/database
        foreach ($validated as $key => $value) {
            Cache::put('setting_' . $key, $value, now()->addYear());
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Get current settings
     */
    private function getSettings()
    {
        return [
            'company_name' => Cache::get('setting_company_name', 'DCS Risk Management'),
            'system_email' => Cache::get('setting_system_email', 'admin@dcs.com'),
            'timezone' => Cache::get('setting_timezone', 'UTC'),
            'date_format' => Cache::get('setting_date_format', 'Y-m-d'),
            'enable_notifications' => Cache::get('setting_enable_notifications', true),
            'notification_email' => Cache::get('setting_notification_email', 'notifications@dcs.com'),
            'auto_backup' => Cache::get('setting_auto_backup', false),
            'backup_frequency' => Cache::get('setting_backup_frequency', 'daily'),
            'session_timeout' => Cache::get('setting_session_timeout', 30),
            'max_login_attempts' => Cache::get('setting_max_login_attempts', 5),
            'password_expiry_days' => Cache::get('setting_password_expiry_days', 90),
            'risk_threshold_high' => Cache::get('setting_risk_threshold_high', 11),
            'risk_threshold_medium' => Cache::get('setting_risk_threshold_medium', 6),
            'risk_threshold_low' => Cache::get('setting_risk_threshold_low', 5),
        ];
    }

    /**
     * Reset settings to defaults
     */
    public function reset()
    {
        $defaultSettings = [
            'company_name' => 'DCS Risk Management',
            'system_email' => 'admin@dcs.com',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'enable_notifications' => true,
            'notification_email' => 'notifications@dcs.com',
            'auto_backup' => false,
            'backup_frequency' => 'daily',
            'session_timeout' => 30,
            'max_login_attempts' => 5,
            'password_expiry_days' => 90,
            'risk_threshold_high' => 11,
            'risk_threshold_medium' => 6,
            'risk_threshold_low' => 5,
        ];

        foreach ($defaultSettings as $key => $value) {
            Cache::put('setting_' . $key, $value, now()->addYear());
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings reset to defaults successfully.');
    }

    /**
     * Export settings
     */
    public function export()
    {
        $settings = $this->getSettings();
        
        $filename = 'dcs_settings_' . date('Y-m-d') . '.json';
        
        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Import settings
     */
    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json|max:1024'
        ]);

        try {
            $content = file_get_contents($request->file('settings_file')->getPathname());
            $settings = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON file');
            }

            foreach ($settings as $key => $value) {
                Cache::put('setting_' . $key, $value, now()->addYear());
            }

            return redirect()->route('settings.index')
                ->with('success', 'Settings imported successfully.');

        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Failed to import settings: ' . $e->getMessage());
        }
    }
} 