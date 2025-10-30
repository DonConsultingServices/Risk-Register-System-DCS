<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the document_type enum to include new document types
        DB::statement("ALTER TABLE client_documents MODIFY COLUMN document_type ENUM(
            'id_document',
            'birth_certificate',
            'passport_document',
            'proof_of_residence',
            'kyc_form',
            'source_of_earnings',
            'registration_document',
            'foreign_registration',
            'tax_certificate',
            'other'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE client_documents MODIFY COLUMN document_type ENUM(
            'id_document',
            'birth_certificate',
            'passport_document',
            'proof_of_residence',
            'kyc_form',
            'other'
        ) NOT NULL");
    }
};
