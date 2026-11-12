<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Enrollment::with('course')->withPaid()
            ->where('user_id', $user->id)
            ->latest('enrolled_at')->latest('id')->get();

        $enrolled = $orders->where('order_status', 'completed');   // shudhu Enrolled (complete) order

        $stats = [
            'courses'    => $enrolled->count(),                                   // koto gulo course kinse
            'total_fee'  => (float) $enrolled->sum('fee'),                        // mot course price
            'total_paid' => (float) $user->payments()->where('status', 'paid')->sum('amount'), // mot koto tk diyeche
            'total_due'  => (float) $enrolled->sum(fn ($e) => $e->due()),         // mot due
            'pending'    => $orders->where('order_status', 'pending')->count(),
        ];

        $recentOrders   = $orders->take(5);
        $recentPayments = $user->payments()->with('enrollment.course')->latest('paid_at')->latest('id')->take(5)->get();

        return view('user.dashboard', compact('user', 'stats', 'recentOrders', 'recentPayments'));
    }
}
