<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * [Info-IT Admin] Admin "Orders" = existing `enrollments` table.
 * Order ID + Order Status column add, purano row te auto Order ID.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('enrollments', 'order_no')) {
            Schema::table('enrollments', fn (Blueprint $t) => $t->string('order_no')->nullable()->unique());
        }
        if (! Schema::hasColumn('enrollments', 'order_status')) {
            Schema::table('enrollments', fn (Blueprint $t) => $t->string('order_status', 20)->default('pending')); // pending|completed|rejected|canceled
        }

        DB::table('enrollments')->whereNull('order_no')->orderBy('id')->get(['id', 'created_at', 'enrolled_at'])
            ->each(fn ($e) => DB::table('enrollments')->where('id', $e->id)->update([
                'order_no'     => 'ORD-' . (1000 + $e->id),
                'order_status' => 'completed',
                'enrolled_at'  => $e->enrolled_at ?? substr((string) ($e->created_at ?? now()), 0, 10),
            ]));
    }

    public function down(): void
    {
        if (Schema::hasColumn('enrollments', 'order_no')) {
            Schema::table('enrollments', function (Blueprint $t) {
                $t->dropUnique(['order_no']);
                $t->dropColumn('order_no');
            });
        }
        if (Schema::hasColumn('enrollments', 'order_status')) {
            Schema::table('enrollments', fn (Blueprint $t) => $t->dropColumn('order_status'));
        }
    }
};
