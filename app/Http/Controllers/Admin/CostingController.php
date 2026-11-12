<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Costing;
use Illuminate\Http\Request;

/** Institute costing (Dashboard / Reports "Costing"). */
class CostingController extends Controller
{
    public function index(Request $request)
    {
        $expenses = Costing::with('course')
            ->when($request->q, fn ($q, $v) => $q->where('title', 'like', "%$v%"))
            ->when($request->course, fn ($q, $v) => $q->where('course_id', $v))
            ->when($request->date, fn ($q, $v) => $q->whereDate('spent_at', $v))
            ->latest('spent_at')->latest('id')->paginate(10)->withQueryString();

        $courses   = Course::orderBy('title')->get(['id', 'title']);
        $monthSum  = Costing::whereYear('spent_at', now()->year)->whereMonth('spent_at', now()->month)->sum('amount');
        $totalSum  = Costing::sum('amount');

        return view('admin.costing.index', compact('expenses', 'courses', 'monthSum', 'totalSum'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => ['required', 'string', 'max:150'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'amount'    => ['required', 'numeric', 'min:1'],
            'spent_at'  => ['required', 'date'],
        ]);

        Costing::create($data);

        return back()->with('success', 'Costing added successfully.');
    }

    public function destroy(Costing $costing)
    {
        $costing->delete();

        return back()->with('success', 'Costing deleted successfully.');
    }
}
