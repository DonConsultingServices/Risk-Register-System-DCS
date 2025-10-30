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
            // Add missing columns to match Excel structure
            $table->string('risk_id', 20)->nullable()->after('name');
            $table->string('risk_name')->nullable()->after('risk_id');
            $table->text('risk_detail')->nullable()->after('risk_name');
            $table->string('risk_category')->nullable()->after('risk_detail');
            $table->enum('impact', ['High', 'Medium', 'Low'])->nullable()->after('risk_category');
            $table->enum('likelihood', ['High', 'Medium', 'Low'])->nullable()->after('impact');
            $table->enum('risk_rating', ['High', 'Medium', 'Low'])->nullable()->after('likelihood');
            $table->text('mitigation_strategies')->nullable()->after('risk_rating');
            $table->string('owner')->nullable()->after('mitigation_strategies');
            $table->enum('status', ['Open', 'Closed'])->nullable()->after('owner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_categories', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
                'risk_id',
                'risk_name',
                'risk_detail',
                'risk_category',
                'impact',
                'likelihood',
                'risk_rating',
                'mitigation_strategies',
                'owner',
                'status'
            ]);
        });
    }
};
