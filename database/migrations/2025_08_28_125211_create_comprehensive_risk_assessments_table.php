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
        Schema::create('comprehensive_risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_id')->constrained('risks')->onDelete('cascade');
            
            // Service Risk (SR) Details
            $table->string('sr_risk_id')->nullable();
            $table->string('sr_risk_name')->nullable();
            $table->enum('sr_impact', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('sr_likelihood', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('sr_risk_rating', ['Low', 'Medium', 'High'])->nullable();
            $table->integer('sr_points')->nullable();
            $table->text('sr_mitigation')->nullable();
            $table->string('sr_owner')->nullable();
            $table->enum('sr_status', ['Open', 'Closed'])->nullable();
            
            // Client Risk (CR) Details
            $table->string('cr_risk_id')->nullable();
            $table->string('cr_risk_name')->nullable();
            $table->enum('cr_impact', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('cr_likelihood', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('cr_risk_rating', ['Low', 'Medium', 'High'])->nullable();
            $table->integer('cr_points')->nullable();
            $table->text('cr_mitigation')->nullable();
            $table->string('cr_owner')->nullable();
            $table->enum('cr_status', ['Open', 'Closed'])->nullable();
            
            // Payment Risk (PR) Details
            $table->string('pr_risk_id')->nullable();
            $table->string('pr_risk_name')->nullable();
            $table->enum('pr_impact', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('pr_likelihood', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('pr_risk_rating', ['Low', 'Medium', 'High'])->nullable();
            $table->integer('pr_points')->nullable();
            $table->text('pr_mitigation')->nullable();
            $table->string('pr_owner')->nullable();
            $table->enum('pr_status', ['Open', 'Closed'])->nullable();
            
            // Delivery Risk (DR) Details
            $table->string('dr_risk_id')->nullable();
            $table->string('dr_risk_name')->nullable();
            $table->enum('dr_impact', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('dr_likelihood', ['Low', 'Medium', 'High'])->nullable();
            $table->enum('dr_risk_rating', ['Low', 'Medium', 'High'])->nullable();
            $table->integer('dr_points')->nullable();
            $table->text('dr_mitigation')->nullable();
            $table->string('dr_owner')->nullable();
            $table->enum('dr_status', ['Open', 'Closed'])->nullable();
            
            // Overall Assessment
            $table->integer('total_points');
            $table->string('overall_risk_rating');
            $table->string('client_acceptance');
            $table->string('ongoing_monitoring');
            
            // Audit Trail
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['risk_id', 'total_points']);
            $table->index('overall_risk_rating');
            $table->index('client_acceptance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprehensive_risk_assessments');
    }
};
