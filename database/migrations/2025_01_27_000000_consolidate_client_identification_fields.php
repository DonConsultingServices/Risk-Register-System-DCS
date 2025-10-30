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
        // Remove client_identification_done from clients table if it exists
        if (Schema::hasColumn('clients', 'client_identification_done')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('client_identification_done');
            });
        }

        // Remove client_identification_done from risks table if it exists
        if (Schema::hasColumn('risks', 'client_identification_done')) {
            Schema::table('risks', function (Blueprint $table) {
                $table->dropColumn('client_identification_done');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back client_identification_done to clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('client_identification_done', ['Yes', 'No', 'Pending'])->nullable()->after('status');
        });

        // Add back client_identification_done to risks table
        Schema::table('risks', function (Blueprint $table) {
            $table->enum('client_identification_done', ['Yes', 'No', 'Pending'])->nullable()->after('client_name');
        });
    }
};
