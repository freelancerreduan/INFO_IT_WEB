<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Student = users table (role = user). Course assign = enrollments (Order).
 */
class StudentController extends Controller
{
    public function index(Request $request)
    {
        User::ensureStudentIds();   // ID nei emon student thakle ekhanei ID pay
        if (! User::ensureStudentIdColumn()) {
            session()->now('error', 'Could not create the Student ID column in the database (the database user may not have ALTER permission). Check storage/logs/laravel.log.');
        }
        $students = $this->filtered($request)->paginate(10)->withQueryString();
        $courses  = Course::orderBy('title')->get(['id', 'title']);

        return view('admin.students.index', compact('students', 'courses'));
    }

    /** Print — Student List er current filter (search / course / status / date) er shob student, ek page e. */
    public function print(Request $request)
    {
        User::ensureStudentIds();
        $students = $this->filtered($request)->get();
        $course   = $request->course ? Course::find($request->course) : null;

        return view('admin.students.print', compact('students', 'course'));
    }

    /** Student List + Print — same filter. Search: Student ID / name / email / phone */
    private function filtered(Request $request)
    {
        return User::students()
            ->with(['latestEnrollment.course'])
            ->withMin('enrollments as first_enrolled_at', 'enrolled_at')
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', "%$v%")->orWhere('email', 'like', "%$v%")->orWhere('phone', 'like', "%$v%")
                ->when(User::ensureStudentIdColumn(), fn ($x) => $x->orWhere('student_id', 'like', "%$v%"))))
            ->when($request->course, fn ($q, $v) => $q->whereHas('enrollments', fn ($e) => $e->where('course_id', $v)))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->date, fn ($q, $v) => $q->whereDate('created_at', $v))
            ->orderByDesc('id');   // newest student first, oldest last
    }

    public function create()
    {
        return view('admin.students.create', [
            'student' => new User(),
            'currentCourseId' => null,
            'courses' => $this->courseCards(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        // Admin theke toiri student: Email + Password = email (Add Order er moto)
        $data['email'] = strtolower($data['email']);
        $password      = $data['email'];

        DB::transaction(function () use ($request, $data, $password) {
            $student = User::create([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'],
                'parent_phone' => $data['parent_phone'] ?? null,
                'nid'          => $data['nid'] ?? null,
                'photo'        => $request->file('photo')?->store('avatars', 'public'),
                'status'       => 'active',
                'role'         => 'user',
                'password'     => $password,     // hashed by cast
            ]);

            $course = Course::findOrFail($data['course_id']);
            Enrollment::create([
                'user_id'      => $student->id,
                'course_id'    => $course->id,
                'fee'          => $course->selling_price,
                'duration'     => $course->duration,
                'status'       => 'active',
                'order_status' => 'pending',
                'enrolled_at'  => now(),
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', "Student added successfully. Login: {$data['email']} / Password: {$password}");
    }

    public function edit(User $student)
    {
        abort_if($student->isAdmin(), 404);

        return view('admin.students.create', [
            'student' => $student,
            'currentCourseId' => $student->latestEnrollment?->course_id,
            'courses' => $this->courseCards(),
        ]);
    }

    public function update(Request $request, User $student)
    {
        abort_if($student->isAdmin(), 404);
        $data = $this->validated($request, $student);

        DB::transaction(function () use ($request, $data, $student) {
            $update = collect($data)->only(['name', 'email', 'phone', 'parent_phone', 'nid', 'status'])->all();

            if ($request->hasFile('photo')) {
                $this->deleteFile($student->photo);
                $update['photo'] = $request->file('photo')->store('avatars', 'public');
            }
            $student->update($update);

            // Course changed?
            $latest = $student->latestEnrollment;
            if ((int) $data['course_id'] !== (int) $latest?->course_id) {
                $course = Course::findOrFail($data['course_id']);
                if ($latest && $latest->paidTotal() == 0) {
                    // no money received yet -> just fix the course on the same order
                    $latest->update(['course_id' => $course->id, 'fee' => $course->selling_price, 'duration' => $course->duration]);
                } else {
                    Enrollment::create([
                        'user_id' => $student->id, 'course_id' => $course->id, 'fee' => $course->selling_price,
                        'duration' => $course->duration, 'status' => 'active', 'order_status' => 'pending', 'enrolled_at' => now(),
                    ]);
                }
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        abort_if($student->isAdmin(), 404);
        $this->deleteFile($student->photo);
        $student->delete();   // enrollments + payments cascade

        return back()->with('success', 'Student deleted successfully.');
    }

    /* ------------------------------------------------------------ */

    private function validated(Request $request, ?User $student = null): array
    {
        return $request->validate([
            'course_id'    => ['required', 'exists:courses,id'],
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student?->id)],
            'phone'        => ['required', 'string', 'max:30'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'nid'          => ['nullable', 'string', 'max:40'],
            'photo'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'status'       => [$student ? 'required' : 'nullable', Rule::in(array_keys(User::STUDENT_STATUSES))],
        ]);
    }

    private function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }

    /** Course info card data on the Add Student page. */
    private function courseCards()
    {
        return Course::with('module')->orderBy('title')->get()->map(fn (Course $c) => [
            'id'         => (string) $c->id,
            'name'       => $c->title,
            'category'   => $c->category ?: '—',
            'status'     => $c->status_label,
            'desc'       => $c->short_desc,
            'price'      => $c->regular_price,
            'discount'   => $c->selling_price,
            'duration'   => $c->duration ?: '—',
            'modules'    => $c->total_modules ?: '—',
            'classes'    => $c->total_classes ?: ($c->classes ?: '—'),
            'instructor' => $c->instructor ?: '—',
            'rating'     => $c->rating ?: '—',
            'thumb'      => $c->thumb_url,
        ]);
    }
}
