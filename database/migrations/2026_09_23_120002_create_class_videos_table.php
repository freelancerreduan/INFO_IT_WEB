<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Homepage "ক্লাস ভিডিও" section. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('course_label')->nullable();               // course select na korle ei text dekhabe
            $table->string('batch')->nullable();                      // "ব্যাচ ২৪"
            $table->date('class_date')->nullable();
            $table->string('date_label')->nullable();                 // "১৮ সেপ্টেম্বর ২০২৬"
            $table->string('duration')->nullable();                   // "১২:৪০"
            $table->enum('source', ['upload', 'youtube'])->default('upload');
            $table->string('video_path')->nullable();                 // storage path / public path / full url
            $table->string('youtube_id')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_videos');
    }
};
