<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend the ENUM to include source_of_earnings
        DB::statement("ALTER TABLE client_documents MODIFY COLUMN document_type ENUM('id_document','birth_certificate','passport_document','proof_of_residence','kyc_form','source_of_earnings','other') NOT NULL");
    }

    public function down(): void
    {
        // Revert to previous ENUM without source_of_earnings
        DB::statement("ALTER TABLE client_documents MODIFY COLUMN document_type ENUM('id_document','birth_certificate','passport_document','proof_of_residence','kyc_form','other') NOT NULL");
    }
};


