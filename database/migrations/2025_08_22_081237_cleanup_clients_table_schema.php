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
            // Drop the old duplicate fields
            $table->dropColumn(['screening_result', 'screening_date', 'risk_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Recreate the old fields if needed
            $table->string('screening_result')->nullable();
            $table->date('screening_date')->nullable();
            $table->enum('risk_level', ['Low', 'Medium', 'High'])->default('Low');
        });
    }
};
