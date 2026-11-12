<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Costing;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Earnings (payments) / Costing (admin_costings) / Students (enrollments)
 * Dashboard + Reports er shob hishab ekhane. MySQL / MariaDB / PostgreSQL / SQLite supported.
 *
 * $f filters: course_id, method, status ('All' | 'Completed' | 'Pending'), from, to, month
 * status key na thakle -> shudhu paid (asol earnings).
 */
class FinanceService
{
    public const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    public function payments(array $f = []): Builder
    {
        return Payment::query()
            ->when(! array_key_exists('status', $f), fn ($q) => $q->where('status', 'paid'))
            ->when(array_key_exists('status', $f), function ($q) use ($f) {
                match ($f['status'] ?: 'All') {
                    'Completed' => $q->where('status', 'paid'),
                    'Pending'   => $q->whereIn('status', ['pending', 'due']),
                    default     => $q->whereIn('status', ['paid', 'pending', 'due']),
                };
            })
            ->when($f['course_id'] ?? null, fn ($q, $v) => $q->whereHas('enrollment', fn ($e) => $e->where('course_id', $v)))
            ->when(($f['method'] ?? null) && $f['method'] !== 'All', fn ($q) => $q->where('method', $f['method']))
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('paid_at', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('paid_at', '<=', $v))
            ->when($f['month'] ?? null, fn ($q, $v) => $q->whereMonth('paid_at', $v));
    }

    public function expenses(array $f = []): Builder
    {
        return Costing::query()
            ->when($f['course_id'] ?? null, fn ($q, $v) => $q->where('course_id', $v))
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('spent_at', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('spent_at', '<=', $v))
            ->when($f['month'] ?? null, fn ($q, $v) => $q->whereMonth('spent_at', $v));
    }

    public function enrollments(array $f = []): Builder
    {
        return Enrollment::query()
            ->where('order_status', '!=', 'deleted')   // admin delete kora order student count e ashbe na
            ->when($f['course_id'] ?? null, fn ($q, $v) => $q->where('course_id', $v))
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('enrolled_at', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('enrolled_at', '<=', $v))
            ->when($f['month'] ?? null, fn ($q, $v) => $q->whereMonth('enrolled_at', $v));
    }

    public function monthly(int $year, array $f = []): array
    {
        $tx  = $this->groupSum($this->payments($f)->whereYear('paid_at', $year), 'paid_at', 'amount', 'month');
        $ex  = $this->groupSum($this->expenses($f)->whereYear('spent_at', $year), 'spent_at', 'amount', 'month');
        $stu = $this->groupCount($this->enrollments($f)->whereYear('enrolled_at', $year), 'enrolled_at', 'month');

        $out = ['labels' => self::MONTHS, 'keys' => range(1, 12), 'earnings' => [], 'costing' => [], 'profit' => [], 'students' => []];
        foreach (range(1, 12) as $m) {
            $e = (float) ($tx[$m] ?? 0);
            $c = (float) ($ex[$m] ?? 0);
            $out['earnings'][] = $e;
            $out['costing'][]  = $c;
            $out['profit'][]   = $e - $c;
            $out['students'][] = (int) ($stu[$m] ?? 0);
        }

        return $out;
    }

    public function yearly(int $toYear, int $count = 6, array $f = []): array
    {
        $from  = $toYear - $count + 1;
        $range = fn ($q, $col) => $q->whereYear($col, '>=', $from)->whereYear($col, '<=', $toYear);

        $tx  = $this->groupSum($range($this->payments($f), 'paid_at'), 'paid_at', 'amount', 'year');
        $ex  = $this->groupSum($range($this->expenses($f), 'spent_at'), 'spent_at', 'amount', 'year');
        $stu = $this->groupCount($range($this->enrollments($f), 'enrolled_at'), 'enrolled_at', 'year');

        $out = ['labels' => [], 'keys' => [], 'earnings' => [], 'costing' => [], 'profit' => [], 'students' => []];
        foreach (range($from, $toYear) as $y) {
            $e = (float) ($tx[$y] ?? 0);
            $c = (float) ($ex[$y] ?? 0);
            $out['labels'][]   = (string) $y;
            $out['keys'][]     = $y;
            $out['earnings'][] = $e;
            $out['costing'][]  = $c;
            $out['profit'][]   = $e - $c;
            $out['students'][] = (int) ($stu[$y] ?? 0);
        }

        return $out;
    }

    public function totals(array $f = []): array
    {
        $e = (float) $this->payments($f)->sum('amount');
        $c = (float) $this->expenses($f)->sum('amount');

        return ['earnings' => $e, 'costing' => $c, 'profit' => $e - $c, 'students' => $this->enrollments($f)->count()];
    }

    private function part(string $col, string $part): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "CAST(strftime('" . ($part === 'month' ? '%m' : '%Y') . "', $col) AS INTEGER)",
            'pgsql'  => 'CAST(EXTRACT(' . strtoupper($part) . " FROM $col) AS INTEGER)",
            default  => strtoupper($part) . "($col)",
        };
    }

    private function groupSum(Builder $q, string $dateCol, string $valCol, string $part): array
    {
        $p = $this->part($dateCol, $part);

        return $q->toBase()->selectRaw("$p as p, SUM($valCol) as v")->groupByRaw($p)->pluck('v', 'p')->toArray();
    }

    private function groupCount(Builder $q, string $dateCol, string $part): array
    {
        $p = $this->part($dateCol, $part);

        return $q->toBase()->selectRaw("$p as p, COUNT(*) as v")->groupByRaw($p)->pluck('v', 'p')->toArray();
    }
}
