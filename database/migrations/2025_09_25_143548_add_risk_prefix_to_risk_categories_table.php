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
        Schema::table('risk_categories', function (Blueprint $table) {
            $table->string('risk_prefix', 10)->nullable()->after('color')->comment('Prefix for risk IDs (e.g., CR, SR, PR, DR)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_categories', function (Blueprint $table) {
            $table->dropColumn('risk_prefix');
        });
    }
};