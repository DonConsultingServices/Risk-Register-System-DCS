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
        // Add indexes for risks table
        Schema::table('risks', function (Blueprint $table) {
            $table->index(['deleted_at', 'status'], 'idx_risks_perf_1');
            $table->index(['deleted_at', 'approval_status'], 'idx_risks_perf_2');
            $table->index(['deleted_at', 'due_date'], 'idx_risks_perf_3');
            $table->index(['client_id', 'deleted_at'], 'idx_risks_perf_4');
            $table->index(['risk_category', 'deleted_at'], 'idx_risks_perf_5');
            $table->index(['overall_risk_rating', 'deleted_at'], 'idx_risks_perf_6');
            $table->index(['created_at', 'deleted_at'], 'idx_risks_perf_7');
        });

        // Add indexes for clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->index(['assessment_status', 'deleted_at'], 'idx_clients_perf_1');
            $table->index(['name', 'deleted_at'], 'idx_clients_perf_2');
            $table->index(['deleted_at'], 'idx_clients_perf_3');
        });

        // Add indexes for users table (users table doesn't have deleted_at)
        Schema::table('users', function (Blueprint $table) {
            $table->index(['created_at'], 'idx_users_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes for risks table
        Schema::table('risks', function (Blueprint $table) {
            $table->dropIndex('idx_risks_perf_1');
            $table->dropIndex('idx_risks_perf_2');
            $table->dropIndex('idx_risks_perf_3');
            $table->dropIndex('idx_risks_perf_4');
            $table->dropIndex('idx_risks_perf_5');
            $table->dropIndex('idx_risks_perf_6');
            $table->dropIndex('idx_risks_perf_7');
        });

        // Drop indexes for clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('idx_clients_perf_1');
            $table->dropIndex('idx_clients_perf_2');
            $table->dropIndex('idx_clients_perf_3');
        });

        // Drop indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_created');
        });
    }
};