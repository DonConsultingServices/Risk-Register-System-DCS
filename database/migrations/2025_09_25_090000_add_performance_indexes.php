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
            // Add composite indexes for common queries (only if they don't exist)
            if (!$this->indexExists('clients', 'idx_clients_status_deleted')) {
                $table->index(['assessment_status', 'deleted_at'], 'idx_clients_status_deleted');
            }
            if (!$this->indexExists('clients', 'idx_clients_rating_status')) {
                $table->index(['overall_risk_rating', 'assessment_status'], 'idx_clients_rating_status');
            }
            if (!$this->indexExists('clients', 'idx_clients_created_status')) {
                $table->index(['created_at', 'assessment_status'], 'idx_clients_created_status');
            }
            if (!$this->indexExists('clients', 'idx_clients_name_deleted')) {
                $table->index(['name', 'deleted_at'], 'idx_clients_name_deleted');
            }
        });

        Schema::table('risks', function (Blueprint $table) {
            // Add composite indexes for common queries (only if they don't exist)
            if (!$this->indexExists('risks', 'idx_risks_deleted_status')) {
                $table->index(['deleted_at', 'status'], 'idx_risks_deleted_status');
            }
            if (!$this->indexExists('risks', 'idx_risks_rating_deleted')) {
                $table->index(['risk_rating', 'deleted_at'], 'idx_risks_rating_deleted');
            }
            if (!$this->indexExists('risks', 'idx_risks_client_deleted')) {
                $table->index(['client_id', 'deleted_at'], 'idx_risks_client_deleted');
            }
            if (!$this->indexExists('risks', 'idx_risks_created_deleted')) {
                $table->index(['created_at', 'deleted_at'], 'idx_risks_created_deleted');
            }
            if (!$this->indexExists('risks', 'idx_risks_category_deleted')) {
                $table->index(['risk_category', 'deleted_at'], 'idx_risks_category_deleted');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Add indexes for user queries (only if they don't exist)
            if (!$this->indexExists('users', 'idx_users_active_role')) {
                $table->index(['is_active', 'role'], 'idx_users_active_role');
            }
            if (!$this->indexExists('users', 'idx_users_last_login')) {
                $table->index(['last_login_at'], 'idx_users_last_login');
            }
        });

        Schema::table('comprehensive_risk_assessments', function (Blueprint $table) {
            // Add indexes for comprehensive risk assessments (only if they don't exist)
            if (!$this->indexExists('comprehensive_risk_assessments', 'idx_cra_risk_id')) {
                $table->index(['risk_id'], 'idx_cra_risk_id');
            }
            if (!$this->indexExists('comprehensive_risk_assessments', 'idx_cra_created_at')) {
                $table->index(['created_at'], 'idx_cra_created_at');
            }
        });

        Schema::table('client_assessment_history', function (Blueprint $table) {
            // Add indexes for client assessment history (only if they don't exist)
            if (!$this->indexExists('client_assessment_history', 'idx_cah_client_date')) {
                $table->index(['client_id', 'assessment_date'], 'idx_cah_client_date');
            }
            if (!$this->indexExists('client_assessment_history', 'idx_cah_status_date')) {
                $table->index(['assessment_status', 'assessment_date'], 'idx_cah_status_date');
            }
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('idx_clients_status_deleted');
            $table->dropIndex('idx_clients_rating_status');
            $table->dropIndex('idx_clients_created_status');
            $table->dropIndex('idx_clients_name_deleted');
        });

        Schema::table('risks', function (Blueprint $table) {
            $table->dropIndex('idx_risks_deleted_status');
            $table->dropIndex('idx_risks_rating_deleted');
            $table->dropIndex('idx_risks_client_deleted');
            $table->dropIndex('idx_risks_created_deleted');
            $table->dropIndex('idx_risks_category_deleted');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_active_role');
            $table->dropIndex('idx_users_last_login');
        });

        Schema::table('comprehensive_risk_assessments', function (Blueprint $table) {
            $table->dropIndex('idx_cra_risk_deleted');
            $table->dropIndex('idx_cra_created_deleted');
        });

        Schema::table('client_assessment_history', function (Blueprint $table) {
            $table->dropIndex('idx_cah_client_date');
            $table->dropIndex('idx_cah_status_date');
        });
    }
};
