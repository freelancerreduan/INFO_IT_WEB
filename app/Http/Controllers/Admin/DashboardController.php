<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Services\FinanceService;

class DashboardController extends Controller
{
    public function __invoke(FinanceService $finance)
    {
        $now       = now();
        $thisMonth = ['from' => $now->copy()->startOfMonth()->toDateString(), 'to' => $now->copy()->endOfMonth()->toDateString()];
        $lastMonth = ['from' => $now->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(), 'to' => $now->copy()->subMonthNoOverflow()->endOfMonth()->toDateString()];

        $all  = $finance->totals();
        $cur  = $finance->totals($thisMonth);
        $prev = $finance->totals($lastMonth);

        $newStudentsCur  = User::students()->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])->count();
        $newStudentsPrev = User::students()->whereBetween('created_at', [$now->copy()->subMonthNoOverflow()->startOfMonth(), $now->copy()->subMonthNoOverflow()->endOfMonth()])->count();
        $newCourses      = Course::where('created_at', '>=', $now->copy()->startOfMonth())->count();

        $stats = [
            ['label' => 'Total Students', 'value' => number_format(User::students()->count()),
             'change' => $this->pct($newStudentsCur, $newStudentsPrev), 'up' => $newStudentsCur >= $newStudentsPrev,
             'icon' => 'bi-people-fill', 'color' => '#4C3AE3', 'soft' => '#EEEBFC'],
            ['label' => 'Total Courses', 'value' => number_format(Course::count()),
             'change' => '+' . $newCourses . ' new', 'up' => true,
             'icon' => 'bi-journal-bookmark-fill', 'color' => '#0F8B8D', 'soft' => '#E1F5F5'],
            ['label' => 'Total Earnings', 'value' => money_lakh($all['earnings']),
             'change' => $this->pct($cur['earnings'], $prev['earnings']), 'up' => $cur['earnings'] >= $prev['earnings'],
             'icon' => 'bi-cash-stack', 'color' => '#178A4C', 'soft' => '#E3F7EC'],
            ['label' => 'Total Costing', 'value' => money_lakh($all['costing']),
             'change' => $this->pct($cur['costing'], $prev['costing']), 'up' => $cur['costing'] <= $prev['costing'],
             'icon' => 'bi-receipt', 'color' => '#B8650F', 'soft' => '#FCEEDD'],
            ['label' => 'Net Profit', 'value' => money_lakh($all['profit']),
             'change' => $this->pct($cur['profit'], $prev['profit']), 'up' => $cur['profit'] >= $prev['profit'],
             'icon' => 'bi-graph-up-arrow', 'color' => '#C5304A', 'soft' => '#FBE7EB'],
        ];

        $monthly = $finance->monthly($now->year);
        $yearly  = $finance->yearly($now->year, 6);

        $recentOrders = Enrollment::with(['user', 'course'])
            ->withSum(['payments as paid_sum' => fn ($q) => $q->where('status', 'paid')], 'amount')
            ->latest('enrolled_at')->latest('id')->take(5)->get();
        $recentTransactions = Payment::with(['user', 'enrollment.course'])->latest('paid_at')->latest('id')->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'monthly', 'yearly', 'recentOrders', 'recentTransactions'));
    }

    private function pct(float $cur, float $prev): string
    {
        if ($prev == 0) {
            return $cur > 0 ? '+100%' : '0%';
        }
        $p = round((($cur - $prev) / abs($prev)) * 100, 1);

        return ($p >= 0 ? '+' : '') . $p . '%';
    }
}
