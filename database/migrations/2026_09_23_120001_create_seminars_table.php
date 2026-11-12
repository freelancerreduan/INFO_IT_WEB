<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Homepage "আপকামিং সেমিনার সময়সূচী" section. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('title');                                  // Name / Course (table e dekhano hoy)
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->date('seminar_date');
            $table->string('date_label')->nullable();                 // "শুক্রবার, ২৫ সেপ্টেম্বর" (khali rakhle auto)
            $table->string('time_label');                             // "বিকাল ৪:০০ টা"
            $table->enum('venue', ['online', 'offline'])->default('online');
            $table->string('registration_link')->nullable();          // khali = /apply page
            $table->string('live_link')->nullable();                  // "আজকের লাইভ সেমিনারে জয়েন করুন"
            $table->boolean('is_featured')->default(true);            // true = shurute dekhabe, false = "সকল সেমিনার দেখুন" e
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
