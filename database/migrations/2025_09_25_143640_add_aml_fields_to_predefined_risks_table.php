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
        Schema::table('predefined_risks', function (Blueprint $table) {
            $table->string('risk_id', 20)->nullable()->after('id')->comment('Unique risk identifier (e.g., CR-01, SR-01)');
            $table->string('owner', 100)->nullable()->after('mitigation_measures')->comment('Risk owner/manager');
            $table->enum('status', ['Open', 'Closed'])->default('Open')->after('owner')->comment('Risk status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('predefined_risks', function (Blueprint $table) {
            $table->dropColumn(['risk_id', 'owner', 'status']);
        });
    }
};