<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\ClientAssessmentHistory;

class CheckClientData extends Command
{
    protected $signature = 'clients:check-data';
    protected $description = 'Check client data consistency';

    public function handle()
    {
        $this->info('=== CLIENT DATA CHECK ===');
        
        // Check clients table
        $totalClients = Client::count();
        $approvedClients = Client::where('assessment_status', 'approved')->count();
        $pendingClients = Client::where('assessment_status', 'pending')->count();
        $rejectedClients = Client::where('assessment_status', 'rejected')->count();
        
        $this->info("Total clients: {$totalClients}");
        $this->info("Approved clients: {$approvedClients}");
        $this->info("Pending clients: {$pendingClients}");
        $this->info("Rejected clients: {$rejectedClients}");
        
        // Check history table
        $historyCount = ClientAssessmentHistory::count();
        $this->info("History records: {$historyCount}");
        
        // Show client details
        $this->info("\n=== CLIENT DETAILS ===");
        Client::all()->each(function($client) {
            $this->line("ID: {$client->id} | Name: {$client->name} | Status: {$client->assessment_status} | Rating: {$client->overall_risk_rating} | Created: {$client->created_at}");
        });
        
        // Check for duplicates
        $duplicates = Client::selectRaw('name, COUNT(*) as count')
            ->groupBy('name')
            ->having('count', '>', 1)
            ->get();
            
        if ($duplicates->count() > 0) {
            $this->warn("\n=== DUPLICATE CLIENTS FOUND ===");
            $duplicates->each(function($dup) {
                $this->warn("Name: {$dup->name} - Count: {$dup->count}");
            });
        } else {
            $this->info("\nNo duplicate clients found.");
        }
    }
}
