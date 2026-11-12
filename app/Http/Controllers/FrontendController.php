<?php

namespace App\Http\Controllers;

use App\Models\ClassVideo;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\GalleryItem;
use App\Models\Seminar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Website (public) — shob content admin panel theke ashe.
 */
class FrontendController extends Controller
{
    public function home()
    {
        $courses = Course::withCount('enrollments')->where('status', 'active')->orderBy('id')->take(6)->get();

        $seminars = Seminar::with('course')->active()
            ->whereDate('seminar_date', '>=', now()->subDay())
            ->orderByDesc('is_featured')->orderBy('sort_order')->orderBy('seminar_date')
            ->get();

        // purano shob seminar shesh hoye gele o table ta khali rakha hoy na
        if ($seminars->isEmpty()) {
            $seminars = Seminar::with('course')->active()
                ->orderByDesc('is_featured')->orderBy('sort_order')->orderByDesc('seminar_date')
                ->take(5)->get();
        }

        $videos = ClassVideo::with('course')->active()
            ->orderBy('sort_order')->orderByDesc('class_date')->orderByDesc('id')
            ->get()->map(fn (ClassVideo $v) => $v->toCardArray())->values();

        $batches = Course::where('status', 'active')->where('show_batch', true)
            ->orderBy('id')->take(3)->get();

        $stats = [
            'students'     => max(User::students()->count(), (int) setting('stat_students', 10000)),
            'trainers'     => (int) setting('stat_trainers', 50),
            'courses'      => max(Course::where('status', 'active')->count(), (int) setting('stat_courses', 30)),
            'satisfaction' => (int) setting('stat_satisfaction', 95),
        ];

        return view('frontend.home', [
            'courses'          => $courses,
            'seminars'         => $seminars,
            'videos'           => $videos,
            'batches'          => $batches,
            'stats'            => $stats,
            'posterTopics'     => $seminars->pluck('title')->unique()->take(3)->values(),
            'liveSeminarLink'  => $seminars->firstWhere(fn ($s) => filled($s->live_link))?->live_link,
            'heroVideo'        => setting('hero_video') ? asset(setting('hero_video')) : asset('videos/intro.mp4'),
        ]);
    }

    public function courses(Request $request)
    {
        $courses = Course::withCount('enrollments')->where('status', 'active')
            ->when($request->category, fn ($q, $v) => $q->where('category', $v))
            ->when($request->search, fn ($q, $v) => $q->where('title', 'like', "%$v%"))
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        $categories = Course::where('status', 'active')->whereNotNull('category')
            ->distinct()->orderBy('category')->pluck('category');

        return view('frontend.courses', compact('courses', 'categories'));
    }

    public function courseDetails(Course $course)
    {
        abort_if($course->status !== 'active', 404);

        $course->loadCount('enrollments');

        $related = Course::withCount('enrollments')->where('status', 'active')
            ->where('id', '!=', $course->id)->orderBy('id')->take(3)->get();

        $courseSeminars = Seminar::active()->where('course_id', $course->id)
            ->whereDate('seminar_date', '>=', now()->subDay())
            ->orderBy('seminar_date')->get();

        return view('frontend.course-details', compact('course', 'related', 'courseSeminars'));
    }

    public function gallery()
    {
        $items = GalleryItem::latest('id')->paginate(12);

        return view('frontend.gallery', compact('items'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    /** Home page + contact page er "বার্তা পাঠান" form. */
    public function contactStore(Request $request)
    {
        // simple spam trap (hidden field)
        if ($request->filled('website')) {
            return back();
        }

        // ০১৭… (Bangla digit) likhleo jeno kaj kore
        $request->merge(['phone' => self::enDigits((string) $request->input('phone'))]);

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['required', 'string', 'max:30', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'source'  => ['nullable', Rule::in(['home', 'contact'])],
        ], [
            'phone.regex' => 'সঠিক মোবাইল নম্বর দিন (যেমন: ০১৭১২৩৪৫৬৭৮)।',
        ]);

        $data['source'] = $data['source'] ?? 'home';
        $data['status'] = 'new';

        ContactMessage::create($data);

        return back()
            ->with('contact_success', '<b>ধন্যবাদ, ' . e($data['name']) . '!</b> আপনার বার্তা আমরা পেয়েছি। আমাদের টিম এক কর্মদিবসের মধ্যে আপনার সাথে যোগাযোগ করবে।')
            ->with('contact_source', $data['source'])
            ->withFragment($data['source'] === 'home' ? 'contact' : 'footer');
    }

    public function apply(Request $request)
    {
        $courses = Course::where('status', 'active')->orderBy('title')->get();
        $selectedCourse = $request->course ? $courses->firstWhere('slug', $request->course) : null;

        return view('frontend.apply', compact('courses', 'selectedCourse'));
    }

    /**
     * Apply form -> student account + admin panel er "Orders" e ekta pending enrollment.
     */
    public function applyStore(Request $request)
    {
        $request->merge([
            'phone'        => self::enDigits((string) $request->input('phone')),
            'parent_phone' => self::enDigits((string) $request->input('parent_phone')),
            'nid'          => self::enDigits((string) $request->input('nid')),
        ]);

        $data = $request->validate([
            'course_id'    => ['required', 'exists:courses,id'],
            'name'         => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:30', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'parent_phone' => ['required', 'string', 'max:30', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'nid'          => ['nullable', 'string', 'max:40'],   // NID optional
            'address'      => ['nullable', 'string', 'max:500'],
            'email'        => ['nullable', 'email', 'max:255'],
            'photo'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'agree'        => ['accepted'],
        ], [
            'phone.regex'        => 'সঠিক মোবাইল নম্বর দিন (যেমন: ০১৭১২৩৪৫৬৭৮)।',
            'parent_phone.regex' => 'অভিভাবকের সঠিক মোবাইল নম্বর দিন।',
            'agree.accepted'     => 'আবেদন জমা দিতে শর্তে সম্মতি দিন।',
        ]);

        $phone       = $data['phone'];
        $parentPhone = $data['parent_phone'];
        $course      = Course::findOrFail($data['course_id']);

        // login kora student hole take-i use kori; na hole email/phone diye khuji
        $auth    = $request->user();
        $student = $auth && ! $auth->isAdmin() ? $auth : User::where('role', 'user')
            ->when($data['email'] ?? null, fn ($q, $v) => $q->where('email', strtolower($v)))
            ->when(! ($data['email'] ?? null), fn ($q) => $q->where('phone', $phone))
            ->first();

        $fields = [
            'name'         => $data['name'],
            'phone'        => $phone,
            'parent_phone' => $parentPhone,
            'address'      => $data['address'] ?? null,
        ];
        // NID optional — dile save hobe, faka rakhle ager NID (thakle) muche jabe na
        if (! empty($data['nid'])) {
            $fields['nid'] = $data['nid'];
        }

        if ($request->hasFile('photo')) {
            $fields['photo'] = $request->file('photo')->store('students', 'public');
        }

        // same course e already Pending / Enrolled order thakle notun order hobe na
        if ($student) {
            $existing = Enrollment::where('user_id', $student->id)->where('course_id', $course->id)->open()->first();
            if ($existing) {
                return back()->withInput()->withErrors([
                    'course_id' => 'এই কোর্সে আপনার একটি অর্ডার আগে থেকেই আছে (' . bn_num($existing->order_no) . ' — ' . $existing->order_status_label . ')।',
                ]);
            }
        }

        $newLogin = null;
        if ($student) {
            $student->update($fields);
        } else {
            // Login: email (na dile phone@student.local) — password = oi email
            $email    = strtolower($data['email'] ?? '') ?: $phone . '@student.local';
            if (User::where('email', $email)->exists()) {
                return back()->withInput()->withErrors(['email' => 'এই ইমেইলটি অন্য একটি অ্যাকাউন্টে ব্যবহৃত হচ্ছে। অন্য ইমেইল দিন অথবা লগইন করুন।']);
            }
            $student  = User::create($fields + [
                'email'    => $email,
                'password' => $email,        // hashed cast
                'role'     => 'user',
                'status'   => 'active',
            ]);
            $newLogin = $email;
        }

        $order = Enrollment::create([
            'user_id'      => $student->id,
            'course_id'    => $course->id,
            'fee'          => $course->price,
            'duration'     => $course->duration,
            'status'       => 'inactive',      // admin "Enroll" korle active hobe
            'order_status' => 'pending',
            'enrolled_at'  => now(),
        ]);

        $msg = '<b>আপনার আবেদন গ্রহণ করা হয়েছে।</b> ' . e($course->title) . '-এর জন্য আপনার রেফারেন্স নম্বর <b>' . bn_num($order->order_no) . '</b>। '
            . 'আমাদের অ্যাডমিশন টিম এক কর্মদিবসের মধ্যে আপনাকে কল করবে।';
        if ($newLogin) {
            $msg .= '<br>ড্যাশবোর্ডে লগইন — ইমেইল: <b>' . e($newLogin) . '</b> · পাসওয়ার্ড: <b>' . e($newLogin) . '</b>';
        } elseif ($auth) {
            $msg .= ' <a href="' . route('user.orders.index') . '">অর্ডার হিস্টোরি দেখুন</a>';
        }

        return back()->with('apply_success', $msg);
    }

    /** ০১৭১২… -> 01712… */
    private static function enDigits(string $value): string
    {
        return trim(strtr($value, ['০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4', '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9']));
    }
}
