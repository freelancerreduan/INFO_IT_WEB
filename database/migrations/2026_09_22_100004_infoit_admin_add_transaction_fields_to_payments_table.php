<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * [Info-IT Admin] Admin "Transactions" = existing `payments` table.
 * Transaction ID + Account No. add, status enum -> varchar (rejected status er jonno).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('payments', 'trx_id')) {
            Schema::table('payments', fn (Blueprint $t) => $t->string('trx_id')->nullable()->unique());
        }
        if (! Schema::hasColumn('payments', 'account_no')) {
            Schema::table('payments', fn (Blueprint $t) => $t->string('account_no')->nullable());
        }

        // status: paid | pending | due | rejected
        $driver = DB::connection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE `payments` MODIFY `status` VARCHAR(20) NOT NULL DEFAULT 'paid'");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE payments DROP CONSTRAINT IF EXISTS payments_status_check');
        } else {
            Schema::table('payments', fn (Blueprint $t) => $t->string('status', 20)->default('paid')->change());
        }

        DB::table('payments')->whereNull('trx_id')->orderBy('id')->get(['id', 'created_at', 'paid_at'])
            ->each(fn ($p) => DB::table('payments')->where('id', $p->id)->update([
                'trx_id'  => strtoupper(Str::random(9)),
                'paid_at' => $p->paid_at ?? substr((string) ($p->created_at ?? now()), 0, 10),
            ]));
    }

    public function down(): void
    {
        if (Schema::hasColumn('payments', 'trx_id')) {
            Schema::table('payments', function (Blueprint $t) {
                $t->dropUnique(['trx_id']);
                $t->dropColumn('trx_id');
            });
        }
        if (Schema::hasColumn('payments', 'account_no')) {
            Schema::table('payments', fn (Blueprint $t) => $t->dropColumn('account_no'));
        }
    }
};
