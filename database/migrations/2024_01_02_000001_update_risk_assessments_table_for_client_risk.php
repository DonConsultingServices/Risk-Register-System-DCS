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
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'client_identification',
                'screening_risk_id',
                'screening_description',
                'screening_impact',
                'screening_likelihood',
                'screening_risk_rating',
                'client_category_description',
                'services_risk_id',
                'services_description',
                'services_impact',
                'services_likelihood',
                'services_risk_rating',
                'payment_risk_id',
                'payment_description',
                'payment_impact',
                'payment_likelihood',
                'payment_risk_rating',
                'delivery_risk_id',
                'delivery_description',
                'delivery_impact',
                'delivery_likelihood',
                'delivery_risk_rating',
                'overall_risk_points',
                'ongoing_monitoring'
            ]);

            // Add new columns
            $table->string('client_identification_status')->nullable();
            $table->date('client_screening_date')->nullable();
            $table->string('client_screening_result')->nullable();
            $table->string('client_screening_risk_id')->nullable();
            $table->string('client_screening_impact')->nullable();
            $table->string('client_screening_likelihood')->nullable();
            $table->string('client_screening_risk_rating')->nullable();
            $table->string('requested_services_risk_id')->nullable();
            $table->string('requested_services_impact')->nullable();
            $table->string('requested_services_likelihood')->nullable();
            $table->string('requested_services_risk_rating')->nullable();
            $table->string('payment_option_risk_id')->nullable();
            $table->string('payment_option_impact')->nullable();
            $table->string('payment_option_likelihood')->nullable();
            $table->string('payment_option_risk_rating')->nullable();
            $table->string('delivery_method_risk_id')->nullable();
            $table->string('delivery_method_impact')->nullable();
            $table->string('delivery_method_likelihood')->nullable();
            $table->string('delivery_method_risk_rating')->nullable();
            $table->integer('total_points')->default(0);
            $table->string('monitoring_frequency')->nullable();
            $table->json('selected_risk_ids')->nullable();
            $table->timestamp('assessment_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'client_identification_status',
                'client_screening_date',
                'client_screening_result',
                'client_screening_risk_id',
                'client_screening_impact',
                'client_screening_likelihood',
                'client_screening_risk_rating',
                'requested_services_risk_id',
                'requested_services_impact',
                'requested_services_likelihood',
                'requested_services_risk_rating',
                'payment_option_risk_id',
                'payment_option_impact',
                'payment_option_likelihood',
                'payment_option_risk_rating',
                'delivery_method_risk_id',
                'delivery_method_impact',
                'delivery_method_likelihood',
                'delivery_method_risk_rating',
                'total_points',
                'monitoring_frequency',
                'selected_risk_ids',
                'assessment_date'
            ]);

            // Add back old columns
            $table->enum('client_identification', ['Yes', 'No', 'In-progress']);
            $table->string('screening_risk_id')->nullable();
            $table->string('screening_description')->nullable();
            $table->string('screening_impact')->nullable();
            $table->string('screening_likelihood')->nullable();
            $table->string('screening_risk_rating')->nullable();
            $table->string('client_category_description')->nullable();
            $table->string('services_risk_id')->nullable();
            $table->string('services_description')->nullable();
            $table->string('services_impact')->nullable();
            $table->string('services_likelihood')->nullable();
            $table->string('services_risk_rating')->nullable();
            $table->string('payment_risk_id')->nullable();
            $table->string('payment_description')->nullable();
            $table->string('payment_impact')->nullable();
            $table->string('payment_likelihood')->nullable();
            $table->string('payment_risk_rating')->nullable();
            $table->string('delivery_risk_id')->nullable();
            $table->string('delivery_description')->nullable();
            $table->string('delivery_impact')->nullable();
            $table->string('delivery_likelihood')->nullable();
            $table->string('delivery_risk_rating')->nullable();
            $table->integer('overall_risk_points')->default(0);
            $table->string('ongoing_monitoring')->nullable();
        });
    }
}; 