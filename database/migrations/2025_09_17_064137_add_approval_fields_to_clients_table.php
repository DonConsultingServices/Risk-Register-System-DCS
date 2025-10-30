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
            $table->enum('assessment_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['assessment_status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropIndex(['assessment_status', 'created_at']);
            $table->dropColumn([
                'assessment_status',
                'approved_by',
                'approved_at',
                'approval_notes',
                'rejection_reason'
            ]);
        });
    }
};