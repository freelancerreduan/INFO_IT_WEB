<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Services\CsvExport;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public const MONTH_NAMES = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function index(Request $request, FinanceService $finance)
    {
        $report  = $this->build($request, $finance);
        $courses = Course::orderBy('title')->get(['id', 'title']);

        $minYear = (int) (Payment::min('paid_at') ? date('Y', strtotime(Payment::min('paid_at'))) : now()->year);
        $years   = range(now()->year, min($minYear, now()->year - 2));

        return view('admin.reports.index', $report + [
            'courses' => $courses,
            'years'   => $years,
            'months'  => self::MONTH_NAMES,
        ]);
    }

    public function export(Request $request, FinanceService $finance)
    {
        $report = $this->build($request, $finance);

        $rows = collect($report['rows'])->map(fn ($r) => [$r['label'], $r['earnings'], $r['costing'], $r['profit'], $r['students']]);
        $rows->push(['Total', $report['totals']['earnings'], $report['totals']['costing'], $report['totals']['profit'], $report['totals']['students']]);

        return CsvExport::download('report-' . now()->format('Y-m-d') . '.csv',
            [$report['filters']['type'] === 'Yearly' ? 'Year' : 'Month', 'Earnings', 'Costing', 'Profit', 'Students'], $rows);
    }

    private function build(Request $request, FinanceService $finance): array
    {
        $filters = [
            'type'      => $request->input('type', 'Monthly') === 'Yearly' ? 'Yearly' : 'Monthly',
            'month'     => $request->filled('month') ? (int) $request->month : null,
            'year'      => (int) $request->input('year', now()->year),
            'course_id' => $request->input('course_id'),
            'method'    => $request->input('method', 'All'),
            'status'    => $request->input('status', 'All'),
            'from'      => $request->input('from'),
            'to'        => $request->input('to'),
        ];

        $f = array_intersect_key($filters, array_flip(['course_id', 'method', 'status', 'from', 'to']));

        if ($filters['type'] === 'Yearly') {
            $series = $finance->yearly($filters['year'], 6, $f);
        } else {
            $series = $finance->monthly($filters['year'], $f);
        }

        $rows = [];
        foreach ($series['labels'] as $i => $label) {
            // Monthly + a specific month selected -> only that month
            if ($filters['type'] === 'Monthly' && $filters['month'] && $series['keys'][$i] !== $filters['month']) {
                continue;
            }
            $rows[] = [
                'label'    => $label,
                'earnings' => $series['earnings'][$i],
                'costing'  => $series['costing'][$i],
                'profit'   => $series['profit'][$i],
                'students' => $series['students'][$i],
            ];
        }

        $totals = [
            'earnings' => array_sum(array_column($rows, 'earnings')),
            'costing'  => array_sum(array_column($rows, 'costing')),
            'profit'   => array_sum(array_column($rows, 'profit')),
            'students' => array_sum(array_column($rows, 'students')),
        ];

        $cards = [
            ['label' => 'Total Earnings', 'value' => money_lakh($totals['earnings']), 'icon' => 'bi-cash-stack', 'color' => '#178A4C', 'soft' => '#E3F7EC'],
            ['label' => 'Total Costing', 'value' => money_lakh($totals['costing']), 'icon' => 'bi-receipt', 'color' => '#B8650F', 'soft' => '#FCEEDD'],
            ['label' => 'Net Profit', 'value' => money_lakh($totals['profit']), 'icon' => 'bi-graph-up-arrow', 'color' => '#4C3AE3', 'soft' => '#EEEBFC'],
            ['label' => 'Total Students', 'value' => number_format($totals['students']), 'icon' => 'bi-people-fill', 'color' => '#C5304A', 'soft' => '#FBE7EB'],
        ];

        $chart = [
            'labels'   => array_column($rows, 'label'),
            'earnings' => array_column($rows, 'earnings'),
            'costing'  => array_column($rows, 'costing'),
            'students' => array_column($rows, 'students'),
        ];

        return compact('filters', 'rows', 'totals', 'cards', 'chart');
    }
}
