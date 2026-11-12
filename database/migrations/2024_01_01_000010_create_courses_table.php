<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->string('short_desc')->nullable();
            $table->text('long_desc')->nullable();
            $table->string('duration')->nullable();
            $table->string('classes')->nullable();
            $table->string('instructor')->nullable();
            $table->string('instructor_role')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('mode')->nullable();
            $table->string('certificate')->nullable();
            $table->decimal('rating', 2, 1)->default(4.8);
            $table->json('curriculum')->nullable();
            $table->json('outcomes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
