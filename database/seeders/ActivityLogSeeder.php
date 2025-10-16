<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $activities = [
            [
                'action' => 'login',
                'description' => 'Logged into the system',
                'metadata' => ['ip' => '192.168.1.100', 'browser' => 'Chrome']
            ],
            [
                'action' => 'create_risk',
                'description' => 'Created new risk assessment for Client ABC',
                'metadata' => ['client_id' => 1, 'risk_type' => 'Financial']
            ],
            [
                'action' => 'send_message',
                'description' => 'Sent message to John Doe',
                'metadata' => ['recipient_id' => 2, 'message_type' => 'urgent']
            ],
            [
                'action' => 'update_risk',
                'description' => 'Updated risk assessment for Client XYZ',
                'metadata' => ['client_id' => 2, 'changes' => ['status', 'priority']]
            ],
            [
                'action' => 'create_client',
                'description' => 'Added new client: TechCorp Solutions',
                'metadata' => ['client_name' => 'TechCorp Solutions', 'industry' => 'Technology']
            ],
            [
                'action' => 'view_report',
                'description' => 'Viewed monthly risk report',
                'metadata' => ['report_type' => 'monthly', 'date_range' => '2025-01']
            ],
            [
                'action' => 'create_notification',
                'description' => 'Created system notification',
                'metadata' => ['notification_type' => 'system', 'priority' => 'high']
            ],
            [
                'action' => 'update_profile',
                'description' => 'Updated profile information',
                'metadata' => ['fields_updated' => ['name', 'email']]
            ],
            [
                'action' => 'change_password',
                'description' => 'Changed account password',
                'metadata' => ['security_action' => true]
            ],
            [
                'action' => 'update_preferences',
                'description' => 'Updated user preferences',
                'metadata' => ['preferences' => ['theme', 'notifications']]
            ]
        ];

        foreach ($users as $user) {
            // Create 5-10 activities per user
            $userActivityCount = rand(5, 10);
            
            for ($i = 0; $i < $userActivityCount; $i++) {
                $activity = $activities[array_rand($activities)];
                $daysAgo = rand(0, 30);
                $createdAt = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => $activity['action'],
                    'description' => $activity['description'],
                    'metadata' => $activity['metadata'],
                    'ip_address' => '192.168.1.' . rand(100, 200),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt
                ]);
            }
        }

        $this->command->info('Activity logs seeded successfully!');
    }
}