<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\ClientAssessmentHistory;
use Illuminate\Support\Facades\DB;

class MigrateDuplicateClientsToHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:migrate-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate duplicate clients to the new history tracking system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of duplicate clients to history system...');

        // Find clients with duplicate names
        $duplicateClients = DB::select("
            SELECT name, COUNT(*) as count 
            FROM clients 
            WHERE deleted_at IS NULL 
            GROUP BY name 
            HAVING COUNT(*) > 1
        ");

        if (empty($duplicateClients)) {
            $this->info('No duplicate clients found. Migration complete.');
            return;
        }

        $this->info('Found ' . count($duplicateClients) . ' clients with duplicate names.');

        foreach ($duplicateClients as $duplicate) {
            $this->info("Processing duplicates for: {$duplicate->name}");

            // Get all clients with this name, ordered by creation date
            $clients = Client::where('name', $duplicate->name)
                           ->whereNull('deleted_at')
                           ->orderBy('created_at', 'asc')
                           ->get();

            if ($clients->count() < 2) {
                continue;
            }

            // Keep the first (oldest) client as the primary
            $primaryClient = $clients->first();
            $duplicateClients = $clients->skip(1);

            $this->info("  - Keeping client ID {$primaryClient->id} as primary");
            $this->info("  - Moving " . $duplicateClients->count() . " duplicates to history");

            foreach ($duplicateClients as $duplicateClient) {
                // Create history record
                ClientAssessmentHistory::create([
                    'client_id' => $primaryClient->id,
                    'name' => $duplicateClient->name,
                    'email' => $duplicateClient->email,
                    'phone' => $duplicateClient->phone,
                    'company' => $duplicateClient->company,
                    'industry' => $duplicateClient->industry,
                    'overall_risk_points' => $duplicateClient->overall_risk_points,
                    'overall_risk_rating' => $duplicateClient->overall_risk_rating,
                    'client_acceptance' => $duplicateClient->client_acceptance,
                    'ongoing_monitoring' => $duplicateClient->ongoing_monitoring,
                    'dcs_risk_appetite' => $duplicateClient->dcs_risk_appetite,
                    'dcs_comments' => $duplicateClient->dcs_comments,
                    'assessment_status' => $duplicateClient->assessment_status,
                    'rejection_reason' => $duplicateClient->rejection_reason,
                    'approval_notes' => $duplicateClient->approval_notes,
                    'created_by' => $duplicateClient->created_by,
                    'approved_by' => $duplicateClient->approved_by,
                    'approved_at' => $duplicateClient->approved_at,
                    'assessment_date' => $duplicateClient->created_at
                ]);

                // Soft delete the duplicate client
                $duplicateClient->delete();

                $this->info("    - Moved client ID {$duplicateClient->id} to history");
            }
        }

        $this->info('Migration completed successfully!');
    }
}
