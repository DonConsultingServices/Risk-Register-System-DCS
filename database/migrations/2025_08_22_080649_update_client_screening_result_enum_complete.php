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
            $table->dropColumn('client_screening_result');
        });
        
        Schema::table('clients', function (Blueprint $table) {
            // Recreate with all possible enum values from the form
            $table->enum('client_screening_result', ['Done', 'Not Done', 'In Progress', 'Pass', 'Fail', 'Pending', 'Review Required'])->nullable()->after('client_screening_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('client_screening_result');
        });
        
        Schema::table('clients', function (Blueprint $table) {
            // Revert to previous enum values
            $table->enum('client_screening_result', ['Pass', 'Fail', 'Pending', 'Review Required', 'Not Done'])->nullable()->after('client_screening_date');
        });
    }
};
