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
            // Add missing columns that the Risk model expects
            if (!Schema::hasColumn('risks', 'mitigation_strategies')) {
                $table->text('mitigation_strategies')->nullable()->after('mitigation_measures');
            }
            
            // Add other missing columns if they don't exist
            if (!Schema::hasColumn('risks', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('risks', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            
            if (!Schema::hasColumn('risks', 'assigned_user_id')) {
                $table->foreignId('assigned_user_id')->nullable()->after('assigned_to')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
                'mitigation_strategies',
                'title',
                'description',
                'assigned_user_id'
            ]);
        });
    }
};
