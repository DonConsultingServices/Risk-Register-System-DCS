<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        if ($users->count() < 2) {
            $this->command->warn('Need at least 2 users to create sample messages. Skipping message seeding.');
            return;
        }
        
        $sampleMessages = [
            [
                'subject' => 'Welcome to DCS-Best Risk Register',
                'body' => 'Welcome to the DCS-Best Risk Register system! This is your internal messaging system where you can communicate with team members about risk management activities, client updates, and important notifications.',
                'priority' => 'normal',
                'is_important' => true,
            ],
            [
                'subject' => 'New Client Risk Assessment Required',
                'body' => 'A new client has been added to the system and requires a comprehensive risk assessment. Please review the client details and complete the risk assessment within 48 hours.',
                'priority' => 'high',
                'is_important' => true,
            ],
            [
                'subject' => 'Risk Approval Pending',
                'body' => 'There are 3 high-priority risks awaiting your approval. Please review them at your earliest convenience to ensure timely risk management.',
                'priority' => 'urgent',
                'is_important' => true,
            ],
            [
                'subject' => 'Monthly Risk Report Available',
                'body' => 'The monthly risk report for September 2025 is now available. You can view it in the Reports section of the dashboard.',
                'priority' => 'normal',
                'is_important' => false,
            ],
            [
                'subject' => 'System Maintenance Scheduled',
                'body' => 'Scheduled system maintenance will occur on Sunday, September 22nd from 2:00 AM to 4:00 AM. The system will be temporarily unavailable during this time.',
                'priority' => 'normal',
                'is_important' => false,
            ],
            [
                'subject' => 'Training Session Reminder',
                'body' => 'Don\'t forget about the risk management training session tomorrow at 2:00 PM in the conference room. All team members are required to attend.',
                'priority' => 'high',
                'is_important' => false,
            ],
            [
                'subject' => 'Client Meeting Follow-up',
                'body' => 'Following up on yesterday\'s client meeting. Please ensure all action items are documented in the risk register and assigned to appropriate team members.',
                'priority' => 'normal',
                'is_important' => false,
            ],
            [
                'subject' => 'Risk Category Update',
                'body' => 'We have updated the risk categories based on industry best practices. Please review the new categories and update any existing risks accordingly.',
                'priority' => 'low',
                'is_important' => false,
            ],
        ];
        
        // Create messages between different users
        foreach ($sampleMessages as $index => $messageData) {
            $sender = $users->random();
            $recipient = $users->where('id', '!=', $sender->id)->random();
            
            Message::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'subject' => $messageData['subject'],
                'body' => $messageData['body'],
                'priority' => $messageData['priority'],
                'is_important' => $messageData['is_important'],
                'status' => $index < 3 ? 'unread' : 'read', // First 3 messages are unread
                'read_at' => $index < 3 ? null : now()->subDays(rand(1, 7)),
                'created_at' => now()->subDays(rand(1, 14)),
            ]);
        }
        
        $this->command->info('Sample messages created successfully!');
    }
}