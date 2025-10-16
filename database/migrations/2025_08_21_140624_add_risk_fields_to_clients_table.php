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
            // Add risk-related fields to match Excel structure
            $table->enum('client_identification_done', ['Yes', 'No', 'Pending'])->nullable()->after('status');
            $table->date('client_screening_date')->nullable()->after('client_identification_done');
            $table->enum('client_screening_result', ['Pass', 'Fail', 'Pending', 'Review Required', 'Not Done'])->nullable()->after('client_screening_date');
            $table->string('risk_category')->nullable()->after('client_screening_result');
            $table->string('risk_id')->nullable()->after('risk_category');
            $table->integer('overall_risk_points')->nullable()->after('risk_id');
            $table->enum('overall_risk_rating', ['Low', 'Medium', 'Medium-risk', 'High', 'Critical'])->nullable()->after('overall_risk_points');
            $table->enum('client_acceptance', ['Accept client', 'Reject client', 'Pending', 'Under Review'])->nullable()->after('overall_risk_rating');
            $table->enum('ongoing_monitoring', ['Yes', 'No', 'Periodic', 'Continuous', 'Bi-Annually'])->nullable()->after('client_acceptance');
            $table->enum('dcs_risk_appetite', ['Conservative', 'Moderate', 'Aggressive'])->nullable()->after('ongoing_monitoring');
            $table->text('dcs_comments')->nullable()->after('dcs_risk_appetite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Remove added risk fields
            $table->dropColumn([
                'client_identification_done',
                'client_screening_date',
                'client_screening_result',
                'risk_category',
                'risk_id',
                'overall_risk_points',
                'overall_risk_rating',
                'client_acceptance',
                'ongoing_monitoring',
                'dcs_risk_appetite',
                'dcs_comments'
            ]);
        });
    }
};
