<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskThresholdSetting;

class RiskThresholdSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default risk threshold settings
        $settings = [
            [
                'setting_key' => 'auto_rejection_enabled',
                'setting_value' => '1',
                'description' => 'Enable or disable automatic client rejection based on risk threshold',
                'is_active' => true
            ],
            [
                'setting_key' => 'auto_rejection_threshold',
                'setting_value' => '20',
                'description' => 'Risk score threshold for automatic client rejection (20 = Very High risk)',
                'is_active' => true
            ],
            [
                'setting_key' => 'notification_on_auto_rejection',
                'setting_value' => '1',
                'description' => 'Send notifications when clients are automatically rejected',
                'is_active' => true
            ],
            [
                'setting_key' => 'auto_rejection_reason_template',
                'setting_value' => 'Automatically rejected due to high risk assessment (Score: {score}, Rating: {rating}, Threshold: {threshold})',
                'description' => 'Template for automatic rejection reason',
                'is_active' => true
            ]
        ];

        foreach ($settings as $setting) {
            RiskThresholdSetting::updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
        }
    }
}