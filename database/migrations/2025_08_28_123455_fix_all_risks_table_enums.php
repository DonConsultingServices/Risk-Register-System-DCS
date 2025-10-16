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
        Schema::table('risks', function (Blueprint $table) {
            // Drop the existing enum columns
            $table->dropColumn([
                'client_acceptance',
                'ongoing_monitoring'
            ]);
        });
        
        Schema::table('risks', function (Blueprint $table) {
            // Recreate client_acceptance with correct enum values
            $table->enum('client_acceptance', [
                'Accept client',
                'Do not accept client',
                'Accepted',
                'Rejected', 
                'Pending', 
                'Under Review'
            ])->nullable()->after('overall_risk_rating');
            
            // Recreate ongoing_monitoring with correct enum values
            $table->enum('ongoing_monitoring', [
                'Annually',
                'Quarterly review',
                'Bi-Annually',
                'N/A',
                'Yes', 
                'No', 
                'Periodic', 
                'Continuous'
            ])->nullable()->after('client_acceptance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            // Drop the updated enum columns
            $table->dropColumn([
                'client_acceptance',
                'ongoing_monitoring'
            ]);
        });
        
        Schema::table('risks', function (Blueprint $table) {
            // Revert to previous enum values
            $table->enum('client_acceptance', [
                'Accepted', 
                'Rejected', 
                'Pending', 
                'Under Review'
            ])->nullable()->after('overall_risk_rating');
            
            $table->enum('ongoing_monitoring', [
                'Yes', 
                'No', 
                'Periodic', 
                'Continuous'
            ])->nullable()->after('client_acceptance');
        });
    }
};
