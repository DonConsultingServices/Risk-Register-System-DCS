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
            // Add juristic-specific fields
            $table->enum('company_nationality', ['Namibian', 'Foreign'])->nullable()->after('entity_type');
            $table->string('registration_document_path')->nullable()->after('kyc_form_path');
            $table->string('foreign_registration_path')->nullable()->after('registration_document_path');
            $table->string('tax_certificate_path')->nullable()->after('foreign_registration_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn([
                'company_nationality',
                'registration_document_path',
                'foreign_registration_path',
                'tax_certificate_path'
            ]);
        });
    }
};
