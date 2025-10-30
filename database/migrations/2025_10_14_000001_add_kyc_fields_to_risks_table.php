<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->enum('client_type', ['Individual', 'Juristic'])->nullable()->after('client_name');
            // Individual fields
            $table->enum('gender', ['Male', 'Female'])->nullable()->after('client_type');
            $table->enum('nationality', ['Namibian', 'Foreign'])->nullable()->after('gender');
            $table->boolean('is_minor')->nullable()->after('nationality');
            $table->string('id_number')->nullable()->after('is_minor');
            $table->string('passport_number')->nullable()->after('id_number');
            // Juristic fields
            $table->string('registration_number')->nullable()->after('passport_number');
            $table->string('entity_type')->nullable()->after('registration_number');
            $table->string('trading_address')->nullable()->after('entity_type');
            $table->string('income_source')->nullable()->after('trading_address');
            // Document paths
            $table->string('id_document_path')->nullable()->after('income_source');
            $table->string('birth_certificate_path')->nullable()->after('id_document_path');
            $table->string('passport_document_path')->nullable()->after('birth_certificate_path');
            $table->string('proof_of_residence_path')->nullable()->after('passport_document_path');
            $table->string('kyc_form_path')->nullable()->after('proof_of_residence_path');
        });
    }

    public function down(): void
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn([
                'client_type', 'gender', 'nationality', 'is_minor', 'id_number', 'passport_number',
                'registration_number', 'entity_type', 'trading_address', 'income_source',
                'id_document_path', 'birth_certificate_path', 'passport_document_path',
                'proof_of_residence_path', 'kyc_form_path'
            ]);
        });
    }
};


