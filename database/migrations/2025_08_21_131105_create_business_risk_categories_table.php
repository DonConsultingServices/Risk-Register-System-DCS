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
        Schema::create('business_risk_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // CR, SR, PR, DR
            $table->string('name'); // Client Risk, Service Risk, Payment Risk, Delivery Risk
            $table->text('description');
            $table->string('icon_class'); // FontAwesome icon class
            $table->string('color', 7)->default('#00072D'); // Hex color
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create the risk examples table
        Schema::create('risk_examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_risk_category_id')->constrained()->onDelete('cascade');
            $table->string('risk_id', 20); // CR-01, SR-01, PR-01, DR-01
            $table->string('title');
            $table->text('detail');
            $table->enum('impact', ['High', 'Medium', 'Low']);
            $table->enum('likelihood', ['High', 'Medium', 'Low']);
            $table->enum('risk_rating', ['High', 'Medium', 'Low']);
            $table->text('mitigation_strategies');
            $table->string('owner'); // Compliance Officer, Service Manager, etc.
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->timestamps();
        });

        // Create the key controls table
        Schema::create('key_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_risk_category_id')->constrained()->onDelete('cascade');
            $table->string('control_name');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
        Schema::dropIfExists('key_controls');
        Schema::dropIfExists('risk_examples');
        Schema::dropIfExists('business_risk_categories');
    }
};
