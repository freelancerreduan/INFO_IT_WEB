<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** [Info-IT Admin] Costing (rent, salary, ads) — Dashboard/Reports "Costing". */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_costings')) {
            return;
        }

        Schema::create('admin_costings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('spent_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_costings');
    }
};
