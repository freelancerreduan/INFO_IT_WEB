<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** [Info-IT Admin] Settings page (institute name, email, currency, timezone). */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_settings')) {
            return;
        }

        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
