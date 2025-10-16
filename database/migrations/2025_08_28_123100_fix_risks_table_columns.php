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
            // Check and add missing columns based on the Risk model fillable array
            $columnsToAdd = [
                'title' => 'string',
                'description' => 'text',
                'mitigation_strategies' => 'text',
                'assigned_user_id' => 'foreignId',
            ];

            foreach ($columnsToAdd as $column => $type) {
                if (!Schema::hasColumn('risks', $column)) {
                    if ($type === 'foreignId') {
                        $table->foreignId($column)->nullable()->constrained('users')->onDelete('set null');
                    } else {
                        $table->{$type}($column)->nullable();
                    }
                }
            }

            // Rename risk_level to risk_rating if it exists
            if (Schema::hasColumn('risks', 'risk_level') && !Schema::hasColumn('risks', 'risk_rating')) {
                $table->renameColumn('risk_level', 'risk_rating');
            }

            // Add risk_rating if it doesn't exist
            if (!Schema::hasColumn('risks', 'risk_rating')) {
                $table->enum('risk_rating', ['Low', 'Medium', 'High'])->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            // This migration is additive, so we don't need to reverse it
            // The columns will remain in the table
        });
    }
};
