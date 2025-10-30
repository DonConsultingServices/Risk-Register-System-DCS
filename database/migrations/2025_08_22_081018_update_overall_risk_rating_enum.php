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
            // Drop the existing enum column
            $table->dropColumn('overall_risk_rating');
        });
        
        Schema::table('clients', function (Blueprint $table) {
            // Recreate with all possible enum values including 'Low-risk'
            $table->enum('overall_risk_rating', ['Low', 'Low-risk', 'Medium', 'Medium-risk', 'High', 'High-risk', 'Critical'])->nullable()->after('overall_risk_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('overall_risk_rating');
        });
        
        Schema::table('clients', function (Blueprint $table) {
            // Revert to previous enum values
            $table->enum('overall_risk_rating', ['Low', 'Medium', 'Medium-risk', 'High', 'Critical'])->nullable()->after('overall_risk_points');
        });
    }
};
