<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * [Info-IT Admin] Student info -> existing `users` table (role = user).
 * Notun table na — shudhu column add. Column age theke thakle skip.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'parent_phone')) {
                $table->string('parent_phone', 30)->nullable();
            }
            if (! Schema::hasColumn('users', 'nid')) {
                $table->string('nid', 40)->nullable();
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('active');   // active | inactive
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['parent_phone', 'nid', 'status'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
