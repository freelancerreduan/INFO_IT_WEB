<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Website er homepage "আপকামিং সেমিনার সময়সূচী" section manage kore.
 */
class SeminarController extends Controller
{
    public function index(Request $request)
    {
        $seminars = Seminar::with('course')
            ->when($request->q, fn ($q, $v) => $q->where('title', 'like', "%$v%"))
            ->when($request->venue, fn ($q, $v) => $q->where('venue', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('sort_order')->orderBy('seminar_date')
            ->paginate(10)->withQueryString();

        return view('admin.seminars.index', compact('seminars'));
    }

    public function create()
    {
        return view('admin.seminars.create', [
            'seminar' => new Seminar(['venue' => 'online', 'status' => 'active', 'is_featured' => true]),
            'courses' => Course::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request)
    {
        Seminar::create($this->validated($request));

        return redirect()->route('admin.seminars.index')->with('success', 'Seminar added successfully.');
    }

    public function edit(Seminar $seminar)
    {
        return view('admin.seminars.create', [
            'seminar' => $seminar,
            'courses' => Course::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Seminar $seminar)
    {
        $seminar->update($this->validated($request));

        return redirect()->route('admin.seminars.index')->with('success', 'Seminar updated successfully.');
    }

    public function destroy(Seminar $seminar)
    {
        $seminar->delete();

        return back()->with('success', 'Seminar deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'course_id'         => ['nullable', 'exists:courses,id'],
            'seminar_date'      => ['required', 'date'],
            'date_label'        => ['nullable', 'string', 'max:120'],
            'time_label'        => ['required', 'string', 'max:120'],
            'venue'             => ['required', Rule::in(array_keys(Seminar::VENUES))],
            'registration_link' => ['nullable', 'url', 'max:255'],
            'live_link'         => ['nullable', 'url', 'max:255'],
            'status'            => ['required', Rule::in(array_keys(Seminar::STATUSES))],
            'sort_order'        => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order']  = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
