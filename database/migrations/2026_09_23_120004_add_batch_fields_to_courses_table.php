<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Homepage "আসন্ন ব্যাচ" section — protita course er ekta upcoming batch. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('batch_no')->nullable()->after('status');          // "ব্যাচ #২৪"
            $table->string('batch_start')->nullable()->after('batch_no');     // "১০ অক্টোবর ২০২৬"
            $table->string('batch_schedule')->nullable()->after('batch_start');
            $table->string('batch_deadline')->nullable()->after('batch_schedule');
            $table->unsignedInteger('total_seats')->nullable()->after('batch_deadline');
            $table->unsignedInteger('seats_left')->nullable()->after('total_seats');
            $table->boolean('show_batch')->default(false)->after('seats_left');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['batch_no', 'batch_start', 'batch_schedule', 'batch_deadline', 'total_seats', 'seats_left', 'show_batch']);
        });
    }
};
