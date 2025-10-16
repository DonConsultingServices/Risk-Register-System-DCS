<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientAssessmentHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ClientHistoryService
{
    /**
     * Check if a client with the same name already exists
     */
    public static function findExistingClient($name, $email = null)
    {
        $query = Client::where('name', $name);
        
        if ($email) {
            $query->orWhere('email', $email);
        }
        
        return $query->first();
    }

    /**
     * Handle client creation with history tracking
     * If client exists, move current data to history and update with new data
     * If client doesn't exist, create new client
     */
    public static function createOrUpdateClientWithHistory($clientData, $riskData, $assessmentData)
    {
        DB::beginTransaction();
        
        try {
            $existingClient = self::findExistingClient($clientData['name'], $clientData['email'] ?? null);
            
            if ($existingClient) {
                // Move existing client data to history
                self::moveClientToHistory($existingClient);
                
                // Update existing client with new data
                $existingClient->update(array_merge($clientData, $riskData, $assessmentData));
                
                Log::info('Client updated with new assessment', [
                    'client_id' => $existingClient->id,
                    'client_name' => $existingClient->name,
                    'new_risk_score' => $riskData['overall_risk_points'] ?? null,
                    'new_risk_rating' => $riskData['overall_risk_rating'] ?? null
                ]);
                
                // Clear all relevant caches
                self::clearAllCaches();
                
                DB::commit();
                return $existingClient;
            } else {
                // Create new client
                $client = Client::create(array_merge($clientData, $riskData, $assessmentData));
                
                Log::info('New client created', [
                    'client_id' => $client->id,
                    'client_name' => $client->name,
                    'risk_score' => $riskData['overall_risk_points'] ?? null,
                    'risk_rating' => $riskData['overall_risk_rating'] ?? null
                ]);
                
                // Clear all relevant caches
                self::clearAllCaches();
                
                DB::commit();
                return $client;
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in client history service', [
                'error' => $e->getMessage(),
                'client_data' => $clientData
            ]);
            throw $e;
        }
    }

    /**
     * Move current client data to history before updating
     */
    private static function moveClientToHistory(Client $client)
    {
        ClientAssessmentHistory::create([
            'client_id' => $client->id,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'company' => $client->company,
            'industry' => $client->industry,
            'overall_risk_points' => $client->overall_risk_points,
            'overall_risk_rating' => $client->overall_risk_rating,
            'client_acceptance' => $client->client_acceptance,
            'ongoing_monitoring' => $client->ongoing_monitoring,
            'dcs_risk_appetite' => $client->dcs_risk_appetite,
            'dcs_comments' => $client->dcs_comments,
            'assessment_status' => $client->assessment_status,
            'rejection_reason' => $client->rejection_reason,
            'approval_notes' => $client->approval_notes,
            'created_by' => $client->created_by,
            'approved_by' => $client->approved_by,
            'approved_at' => $client->approved_at,
            'assessment_date' => $client->created_at
        ]);
    }

    /**
     * Get client with full history
     */
    public static function getClientWithHistory($clientId)
    {
        return Client::with(['assessmentHistory.creator', 'assessmentHistory.approver'])
                    ->find($clientId);
    }

    /**
     * Get all clients with their latest assessment info
     */
    public static function getClientsWithLatestAssessment()
    {
        return Client::with(['latestAssessmentHistory', 'creator'])
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    /**
     * Get assessment history for a specific client
     */
    public static function getClientAssessmentHistory($clientId)
    {
        return ClientAssessmentHistory::with(['creator', 'approver'])
                                    ->where('client_id', $clientId)
                                    ->orderBy('assessment_date', 'desc')
                                    ->get();
    }

    /**
     * Clear all relevant caches when client data changes
     */
    private static function clearAllCaches()
    {
        // Clear dashboard caches
        Cache::forget('dashboard_stats_' . auth()->id());
        Cache::forget('recent_risks');
        Cache::forget('client_stats_' . auth()->id());
        
        // Clear any other relevant caches
        Cache::forget('pending_assessments_' . auth()->id());
        Cache::forget('rejected_clients_data');
        
        // Clear general dashboard cache
        Cache::forget('dashboard_stats');
    }
}
