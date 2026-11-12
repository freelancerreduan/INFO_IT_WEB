<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Student ID (INFO-0001) — users table e notun column + ager shob student ke ID.
 * (Migrate na chalaleo Student List page khulle User::ensureStudentIds() eki kaj kore.)
 */
return new class extends Migration
{
    public function up(): void
    {
        User::ensureStudentIds();
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'student_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['student_id']);
                $table->dropColumn('student_id');
            });
        }
    }
};
