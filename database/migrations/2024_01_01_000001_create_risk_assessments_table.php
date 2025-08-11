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
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            
            // Basic Client Information
            $table->string('client_name');
            $table->enum('client_identification', ['Yes', 'No', 'In-progress']);
            
            // Client Screening
            $table->string('screening_risk_id')->nullable();
            $table->string('screening_description')->nullable();
            $table->string('screening_impact')->nullable();
            $table->string('screening_likelihood')->nullable();
            $table->string('screening_risk_rating')->nullable();
            
            // Category of Client
            $table->string('client_category_risk_id')->nullable();
            $table->string('client_category_description')->nullable();
            $table->string('client_category_impact')->nullable();
            $table->string('client_category_likelihood')->nullable();
            $table->string('client_category_risk_rating')->nullable();
            
            // Requested Services
            $table->string('services_risk_id')->nullable();
            $table->string('services_description')->nullable();
            $table->string('services_impact')->nullable();
            $table->string('services_likelihood')->nullable();
            $table->string('services_risk_rating')->nullable();
            
            // Anticipated Payment Option
            $table->string('payment_risk_id')->nullable();
            $table->string('payment_description')->nullable();
            $table->string('payment_impact')->nullable();
            $table->string('payment_likelihood')->nullable();
            $table->string('payment_risk_rating')->nullable();
            
            // Anticipated Service Delivery Method
            $table->string('delivery_risk_id')->nullable();
            $table->string('delivery_description')->nullable();
            $table->string('delivery_impact')->nullable();
            $table->string('delivery_likelihood')->nullable();
            $table->string('delivery_risk_rating')->nullable();
            
            // Overall Assessment
            $table->integer('overall_risk_points')->default(0);
            $table->string('overall_risk_rating')->nullable();
            $table->string('client_acceptance')->nullable();
            $table->string('ongoing_monitoring')->nullable();
            
            // DCS Assessment
            $table->enum('dcs_risk_appetite', ['Conservative', 'Moderate', 'Aggressive'])->nullable();
            $table->text('dcs_comments')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
}; 