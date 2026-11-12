<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

/** My Courses — shudhu admin "Enroll" kora (completed) course gulo. */
class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = Enrollment::with('course')->withPaid()
            ->where('user_id', $request->user()->id)
            ->enrolled()
            ->latest('enrolled_at')->latest('id')
            ->paginate(10);

        return view('user.enrollments.index', compact('enrollments'));
    }
}
