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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            
            // Risk Identification
            $table->string('risk_identification')->nullable();
            $table->string('risk_name');
            $table->text('risk_description')->nullable();
            
            // Risk Category
            $table->enum('risk_category', ['Service Risk', 'Payment Risk', 'Delivery Risk', 'Client Risk']);
            
            // Risk Levels (H/M/L) as shown in the image
            $table->enum('impact_level', ['H', 'M', 'L'])->nullable();
            $table->enum('likelihood_level', ['H', 'M', 'L'])->nullable();
            $table->enum('risk_rating', ['H', 'M', 'L'])->nullable();
            
            // Mitigation and Ownership
            $table->text('mitigation_strategies')->nullable();
            $table->string('risk_owner')->nullable();
            
            // Risk Status
            $table->enum('risk_status', ['Open', 'Closed'])->default('Open');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
}; 