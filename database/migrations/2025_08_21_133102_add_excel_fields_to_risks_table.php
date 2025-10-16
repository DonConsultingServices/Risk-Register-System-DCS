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
            // Add new Excel fields
            $table->string('client_name')->nullable()->after('id');
            $table->enum('client_identification_done', ['Yes', 'No', 'Pending'])->nullable()->after('client_name');
            $table->date('client_screening_date')->nullable()->after('client_identification_done');
            $table->enum('client_screening_result', ['Pass', 'Fail', 'Pending', 'Review Required'])->nullable()->after('client_screening_date');
            $table->string('risk_description')->nullable()->after('client_screening_result');
            $table->text('risk_detail')->nullable()->after('risk_description');
            $table->string('risk_id')->nullable()->after('risk_detail');
            $table->string('owner')->nullable()->after('mitigation_measures');
            $table->integer('overall_risk_points')->nullable()->after('status');
            $table->enum('overall_risk_rating', ['Low', 'Medium', 'High', 'Critical'])->nullable()->after('overall_risk_points');
            $table->enum('client_acceptance', ['Accepted', 'Rejected', 'Pending', 'Under Review'])->nullable()->after('overall_risk_rating');
            $table->enum('ongoing_monitoring', ['Yes', 'No', 'Periodic', 'Continuous'])->nullable()->after('client_acceptance');
            $table->enum('dcs_risk_appetite', ['Conservative', 'Moderate', 'Aggressive'])->nullable()->after('ongoing_monitoring');
            $table->text('dcs_comments')->nullable()->after('dcs_risk_appetite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            // Remove new Excel fields
            $table->dropColumn([
                'client_name',
                'client_identification_done',
                'client_screening_date',
                'client_screening_result',
                'risk_description',
                'risk_detail',
                'risk_id',
                'owner',
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
