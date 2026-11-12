<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Website "Apply" form submissions. */
class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::with('course')
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', "%$v%")->orWhere('email', 'like', "%$v%")->orWhere('phone', 'like', "%$v%")))
            ->when($request->course, fn ($q, $v) => $q->where('course_id', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->date, fn ($q, $v) => $q->whereDate('created_at', $v))
            ->latest()->paginate(10)->withQueryString();

        $courses = Course::orderBy('title')->get(['id', 'title']);

        return view('admin.applications.index', compact('applications', 'courses'));
    }

    public function update(Request $request, Application $application)
    {
        $request->validate(['status' => ['required', Rule::in(array_keys(Application::STATUSES))]]);
        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application marked as ' . Application::STATUSES[$request->status] . '.');
    }

    public function destroy(Application $application)
    {
        $application->delete();

        return back()->with('success', 'Application deleted successfully.');
    }
}
