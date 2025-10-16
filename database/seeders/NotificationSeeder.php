<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        if ($users->count() < 1) {
            $this->command->warn('Need at least 1 user to create sample notifications. Skipping notification seeding.');
            return;
        }
        
        $sampleNotifications = [
            [
                'type' => 'system',
                'title' => 'Welcome to DCS-Best',
                'message' => 'Welcome to the DCS-Best Risk Register system! You can now manage risks, communicate with your team, and track important notifications.',
                'priority' => 'normal',
                'action_url' => '/dashboard'
            ],
            [
                'type' => 'message',
                'title' => 'New Message Received',
                'message' => 'You have received a new message from a team member. Click to view and respond.',
                'priority' => 'normal',
                'action_url' => '/messages'
            ],
            [
                'type' => 'risk',
                'title' => 'High Priority Risk Alert',
                'message' => 'A new high-priority risk has been identified that requires immediate attention.',
                'priority' => 'urgent',
                'action_url' => '/risks'
            ],
            [
                'type' => 'client',
                'title' => 'New Client Added',
                'message' => 'A new client has been added to the system and requires risk assessment.',
                'priority' => 'high',
                'action_url' => '/clients'
            ],
            [
                'type' => 'approval',
                'title' => 'Risk Approval Required',
                'message' => 'There are pending risk approvals waiting for your review.',
                'priority' => 'high',
                'action_url' => '/risks/approval'
            ],
            [
                'type' => 'report',
                'title' => 'Monthly Report Available',
                'message' => 'Your monthly risk management report is now available for download.',
                'priority' => 'normal',
                'action_url' => '/reports'
            ],
            [
                'type' => 'system',
                'title' => 'System Maintenance',
                'message' => 'Scheduled system maintenance will occur tonight from 2:00 AM to 4:00 AM.',
                'priority' => 'low',
                'action_url' => null
            ],
            [
                'type' => 'risk',
                'title' => 'Risk Status Updated',
                'message' => 'A risk you are monitoring has been updated and requires your review.',
                'priority' => 'normal',
                'action_url' => '/risks'
            ]
        ];
        
        // Create notifications for each user
        foreach ($users as $user) {
            foreach ($sampleNotifications as $index => $notificationData) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => $notificationData['type'],
                    'title' => $notificationData['title'],
                    'message' => $notificationData['message'],
                    'priority' => $notificationData['priority'],
                    'action_url' => $notificationData['action_url'],
                    'read' => $index < 3 ? false : true, // First 3 notifications are unread
                    'read_at' => $index < 3 ? null : now()->subDays(rand(1, 5)),
                    'created_at' => now()->subDays(rand(1, 10)),
                ]);
            }
        }
        
        $this->command->info('Sample notifications created successfully!');
    }
}