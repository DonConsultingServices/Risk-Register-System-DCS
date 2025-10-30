<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Drop all existing enum columns that need fixing
            $table->dropColumn([
                'overall_risk_rating',
                'client_acceptance', 
                'ongoing_monitoring'
            ]);
        });
        
        Schema::table('clients', function (Blueprint $table) {
            // Recreate overall_risk_rating with ALL possible values
            $table->enum('overall_risk_rating', [
                'Low', 'Low-risk', 'Medium', 'Medium-risk', 
                'High', 'High-risk', 'Very High-risk', 'Critical'
            ])->nullable()->after('overall_risk_points');
            
            // Recreate client_acceptance with ALL possible values
            $table->enum('client_acceptance', [
                'Accept client', 'Do not accept client', 'Reject client', 
                'Pending', 'Under Review'
            ])->nullable()->after('overall_risk_rating');
            
            // Recreate ongoing_monitoring with ALL possible values
            $table->enum('ongoing_monitoring', [
                'Yes', 'No', 'N/A', 'Periodic', 'Continuous', 
                'Annually', 'Bi-Annually', 'Quarterly review'
            ])->nullable()->after('client_acceptance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'overall_risk_rating',
                'client_acceptance',
                'ongoing_monitoring'
            ]);
        });
        
        Schema::table('clients', function (Blueprint $table) {
            // Revert to previous enum values
            $table->enum('overall_risk_rating', [
                'Low', 'Low-risk', 'Medium', 'Medium-risk', 'High', 'High-risk', 'Critical'
            ])->nullable()->after('overall_risk_points');
            
            $table->enum('client_acceptance', [
                'Accept client', 'Reject client', 'Pending', 'Under Review'
            ])->nullable()->after('overall_risk_rating');
            
            $table->enum('ongoing_monitoring', [
                'Yes', 'No', 'Periodic', 'Continuous', 'Bi-Annually'
            ])->nullable()->after('client_acceptance');
        });
    }
};
