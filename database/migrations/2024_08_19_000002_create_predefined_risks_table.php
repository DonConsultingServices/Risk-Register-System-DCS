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
        Schema::create('predefined_risks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('risk_level', ['Low', 'Medium', 'High']);
            $table->enum('impact', ['Low', 'Medium', 'High']);
            $table->enum('likelihood', ['Low', 'Medium', 'High']);
            $table->text('mitigation_measures')->nullable();
            $table->foreignId('category_id')->constrained('risk_categories')->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['risk_level', 'is_active']);
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predefined_risks');
    }
};
