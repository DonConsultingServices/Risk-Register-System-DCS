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
            // Add description fields for each risk section
            $table->string('client_screening_description')->nullable();
            $table->string('client_category_description')->nullable();
            $table->string('requested_services_description')->nullable();
            $table->string('payment_option_description')->nullable();
            $table->string('delivery_method_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Drop description fields
            $table->dropColumn([
                'client_screening_description',
                'client_category_description',
                'requested_services_description',
                'payment_option_description',
                'delivery_method_description'
            ]);
        });
    }
};
