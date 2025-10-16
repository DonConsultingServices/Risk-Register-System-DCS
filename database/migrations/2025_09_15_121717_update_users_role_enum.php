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
        // First, update any existing 'analyst' or 'viewer' roles to 'manager' temporarily
        DB::table('users')
            ->whereIn('role', ['analyst', 'viewer'])
            ->update(['role' => 'manager']);
        
        // Then modify the role column to include 'staff' and remove 'analyst' and 'viewer'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'staff') DEFAULT 'staff'");
        
        // Finally, update the temporary 'manager' roles back to 'staff' if they were originally 'analyst' or 'viewer'
        // Note: This is a simplified approach - in production you might want to track original roles
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'manager', 'analyst', 'viewer') DEFAULT 'viewer'");
    }
};