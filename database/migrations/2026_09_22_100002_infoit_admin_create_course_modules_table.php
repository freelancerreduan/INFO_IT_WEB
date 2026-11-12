<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * [Info-IT Admin] course_modules — protiti course er "Total Modules / Total Classes".
 * `courses` table ke touch kore na; course_id diye link (1 course = 1 row).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('course_modules')) {
            Schema::create('course_modules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->unique()->constrained('courses')->cascadeOnDelete();
                $table->unsignedInteger('total_modules')->default(0);
                $table->unsignedInteger('total_classes')->default(0);
                $table->timestamps();
            });
        }

        // existing course -> "40+ classes" theke 40
        $have = DB::table('course_modules')->pluck('course_id')->all();
        DB::table('courses')->whereNotIn('id', $have)->get(['id', 'classes'])
            ->each(fn ($c) => DB::table('course_modules')->insert([
                'course_id'     => $c->id,
                'total_modules' => 0,
                'total_classes' => (int) preg_replace('/\D/', '', (string) $c->classes),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]));
    }

    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};
