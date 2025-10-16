<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for clients table
        $this->addIndexIfNotExists('clients', 'assessment_status');
        $this->addIndexIfNotExists('clients', 'overall_risk_rating');
        $this->addIndexIfNotExists('clients', 'risk_level');
        $this->addIndexIfNotExists('clients', 'name');
        $this->addCompositeIndexIfNotExists('clients', ['assessment_status', 'overall_risk_rating']);
        $this->addCompositeIndexIfNotExists('clients', ['assessment_status', 'risk_level']);
        $this->addIndexIfNotExists('clients', 'created_at');

        // Add indexes for risks table
        $this->addIndexIfNotExists('risks', 'status');
        $this->addIndexIfNotExists('risks', 'risk_rating');
        $this->addIndexIfNotExists('risks', 'approval_status');
        $this->addIndexIfNotExists('risks', 'client_id');
        $this->addCompositeIndexIfNotExists('risks', ['status', 'risk_rating']);
        $this->addCompositeIndexIfNotExists('risks', ['status', 'created_at']);
        $this->addIndexIfNotExists('risks', 'created_at');
        $this->addIndexIfNotExists('risks', 'due_date');

        // Add indexes for users table
        $this->addIndexIfNotExists('users', 'created_at');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes for clients table
        $this->dropIndexIfExists('clients', 'assessment_status');
        $this->dropIndexIfExists('clients', 'overall_risk_rating');
        $this->dropIndexIfExists('clients', 'risk_level');
        $this->dropIndexIfExists('clients', 'name');
        $this->dropCompositeIndexIfExists('clients', ['assessment_status', 'overall_risk_rating']);
        $this->dropCompositeIndexIfExists('clients', ['assessment_status', 'risk_level']);
        $this->dropIndexIfExists('clients', 'created_at');

        // Drop indexes for risks table
        $this->dropIndexIfExists('risks', 'status');
        $this->dropIndexIfExists('risks', 'risk_rating');
        $this->dropIndexIfExists('risks', 'approval_status');
        $this->dropIndexIfExists('risks', 'client_id');
        $this->dropCompositeIndexIfExists('risks', ['status', 'risk_rating']);
        $this->dropCompositeIndexIfExists('risks', ['status', 'created_at']);
        $this->dropIndexIfExists('risks', 'created_at');
        $this->dropIndexIfExists('risks', 'due_date');

        // Drop indexes for users table
        $this->dropIndexIfExists('users', 'created_at');
    }

    /**
     * Add index if it doesn't exist
     */
    private function addIndexIfNotExists($table, $column)
    {
        $indexName = $table . '_' . $column . '_index';
        if (!$this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->index($column);
            });
        }
    }

    /**
     * Add composite index if it doesn't exist
     */
    private function addCompositeIndexIfNotExists($table, $columns)
    {
        $indexName = $table . '_' . implode('_', $columns) . '_index';
        if (!$this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                $table->index($columns);
            });
        }
    }

    /**
     * Drop index if it exists
     */
    private function dropIndexIfExists($table, $column)
    {
        $indexName = $table . '_' . $column . '_index';
        if ($this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        }
    }

    /**
     * Drop composite index if it exists
     */
    private function dropCompositeIndexIfExists($table, $columns)
    {
        $indexName = $table . '_' . implode('_', $columns) . '_index';
        if ($this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        }
    }

    /**
     * Check if index exists
     */
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }
        return false;
    }
};