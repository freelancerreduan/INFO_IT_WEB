<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with('module')->withCount('enrollments')
            ->when($request->q, fn ($q, $v) => $q->where('title', 'like', "%$v%"))
            ->when($request->category, fn ($q, $v) => $q->where('category', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate(10)->withQueryString();

        $categories = Course::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function create()
    {
        return view('admin.courses.create', ['course' => new Course(['status' => 'active']), 'categories' => $this->categories()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $request->file('thumbnail')?->store('courses', 'public');
        $module = $this->pullModule($data);

        $course = Course::create($data);
        $course->module()->updateOrCreate([], $module);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.create', ['course' => $course->load('module'), 'categories' => $this->categories()]);
    }

    public function update(Request $request, Course $course)
    {
        $data = $this->validated($request);
        $module = $this->pullModule($data);

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($course->image);
            $data['image'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($data);
        $course->module()->updateOrCreate([], $module);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $this->deleteFile($course->image);
        $course->delete();

        return back()->with('success', 'Course deleted successfully.');
    }

    /** total_modules / total_classes -> course_modules table */
    private function pullModule(array &$data): array
    {
        $module = ['total_modules' => $data['total_modules'], 'total_classes' => $data['total_classes']];
        unset($data['total_modules'], $data['total_classes']);

        return $module;
    }

    private function categories()
    {
        return collect(Course::CATEGORIES)
            ->merge(Course::whereNotNull('category')->distinct()->pluck('category'))
            ->unique(fn ($c) => mb_strtolower($c))->values();
    }

    /**
     * Design form: Price + Discount Price.
     * DB: price = student pays (discount if given), old_price = crossed price.
     */
    private function validated(Request $request): array
    {
        $v = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'category'        => ['required', 'string', 'max:120'],
            'short_desc'      => ['nullable', 'string', 'max:500'],
            'regular_price'   => ['required', 'numeric', 'min:0'],
            'discount_price'  => ['nullable', 'numeric', 'min:0', 'lte:regular_price'],
            'duration'        => ['nullable', 'string', 'max:60'],
            'status'          => ['required', Rule::in(array_keys(Course::STATUSES))],
            'total_modules'   => ['nullable', 'integer', 'min:0'],
            'total_classes'   => ['nullable', 'integer', 'min:0'],
            'thumbnail'       => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            // website (frontend) details
            'long_desc'       => ['nullable', 'string'],
            'instructor'      => ['nullable', 'string', 'max:120'],
            'instructor_role' => ['nullable', 'string', 'max:120'],
            'mode'            => ['nullable', 'string', 'max:120'],
            'certificate'     => ['nullable', 'string', 'max:255'],
            'rating'          => ['nullable', 'numeric', 'min:0', 'max:5'],
            'classes'         => ['nullable', 'string', 'max:60'],
            'curriculum'      => ['nullable', 'string'],
            'outcomes'        => ['nullable', 'string'],
            // website "আসন্ন ব্যাচ" section
            'batch_no'        => ['nullable', 'string', 'max:60'],
            'batch_start'     => ['nullable', 'string', 'max:120'],
            'batch_schedule'  => ['nullable', 'string', 'max:200'],
            'batch_deadline'  => ['nullable', 'string', 'max:120'],
            'total_seats'     => ['nullable', 'integer', 'min:0'],
            'seats_left'      => ['nullable', 'integer', 'min:0', 'lte:total_seats'],
        ]);

        $hasDiscount = $v['discount_price'] !== null && $v['discount_price'] !== '' && (float) $v['discount_price'] < (float) $v['regular_price'];
        $modules = (int) ($v['total_modules'] ?? 0);
        $classes = (int) ($v['total_classes'] ?? 0);

        return [
            'title'           => $v['title'],
            'category'        => $v['category'],
            'short_desc'      => $v['short_desc'] ?? null,
            'price'           => $hasDiscount ? $v['discount_price'] : $v['regular_price'],
            'old_price'       => $hasDiscount ? $v['regular_price'] : null,
            'duration'        => $v['duration'] ?? null,
            'status'          => $v['status'],
            'total_modules'   => $modules,
            'total_classes'   => $classes,
            'classes'         => ($v['classes'] ?? null) ?: ($classes ? $classes . ' classes' : null),
            'long_desc'       => $v['long_desc'] ?? null,
            'instructor'      => $v['instructor'] ?? null,
            'instructor_role' => $v['instructor_role'] ?? null,
            'mode'            => $v['mode'] ?? null,
            'certificate'     => $v['certificate'] ?? null,
            'rating'          => $v['rating'] ?? 4.8,
            'curriculum'      => $this->lines($v['curriculum'] ?? null),
            'outcomes'        => $this->lines($v['outcomes'] ?? null),
            'batch_no'        => $v['batch_no'] ?? null,
            'batch_start'     => $v['batch_start'] ?? null,
            'batch_schedule'  => $v['batch_schedule'] ?? null,
            'batch_deadline'  => $v['batch_deadline'] ?? null,
            'total_seats'     => $v['total_seats'] ?? null,
            'seats_left'      => $v['seats_left'] ?? null,
            'show_batch'      => $request->boolean('show_batch'),
        ];
    }

    private function lines(?string $text): array
    {
        return collect(preg_split('/\r?\n/', (string) $text))->map(fn ($l) => trim($l))->filter()->values()->all();
    }

    private function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
