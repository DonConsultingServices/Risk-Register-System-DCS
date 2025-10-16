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
        Schema::create('client_assessment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('industry')->nullable();
            $table->integer('overall_risk_points')->nullable();
            $table->string('overall_risk_rating')->nullable();
            $table->string('client_acceptance')->nullable();
            $table->string('ongoing_monitoring')->nullable();
            $table->string('dcs_risk_appetite')->nullable();
            $table->text('dcs_comments')->nullable();
            $table->string('assessment_status');
            $table->text('rejection_reason')->nullable();
            $table->text('approval_notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('assessment_date')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['client_id', 'assessment_date']);
            $table->index('assessment_status');
            $table->index('overall_risk_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_assessment_history');
    }
};