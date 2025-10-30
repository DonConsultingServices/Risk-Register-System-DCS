<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('risk_id')->nullable()->constrained('risks')->onDelete('cascade');
            $table->enum('document_type', [
                'id_document',
                'birth_certificate',
                'passport_document',
                'proof_of_residence',
                'kyc_form',
                'other'
            ]);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['client_id', 'risk_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_documents');
    }
};


