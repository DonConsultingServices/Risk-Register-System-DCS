<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing risks to have proper client_id relationships
        $this->updateRiskClientRelationships();

        // Make client_name nullable for backward compatibility
        Schema::table('risks', function (Blueprint $table) {
            $table->string('client_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert client_name to not nullable if needed
        Schema::table('risks', function (Blueprint $table) {
            $table->string('client_name')->nullable(false)->change();
        });
    }

    /**
     * Update risk records to have proper client_id relationships
     */
    private function updateRiskClientRelationships(): void
    {
        // Get all risks that have client_name but no client_id
        $risks = DB::table('risks')
            ->whereNotNull('client_name')
            ->whereNull('client_id')
            ->get();

        foreach ($risks as $risk) {
            // Find the client by name
            $client = DB::table('clients')
                ->where('name', $risk->client_name)
                ->first();

            if ($client) {
                // Update the risk with the client_id
                DB::table('risks')
                    ->where('id', $risk->id)
                    ->update(['client_id' => $client->id]);
            }
        }
    }
};
