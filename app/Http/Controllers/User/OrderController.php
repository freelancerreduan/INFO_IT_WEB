<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

/** Order History — user er shob order (Pending / Enrolled / Rejected / Canceled / Deleted). */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Enrollment::with(['course', 'payments' => fn ($q) => $q->latest('paid_at')->latest('id')])
            ->withPaid()
            ->where('user_id', $request->user()->id)
            ->when($request->status, fn ($q, $v) => $q->where('order_status', $v))
            ->latest('enrolled_at')->latest('id')
            ->paginate(10)->withQueryString();

        return view('user.orders.index', compact('orders'));
    }

    /** Ekta order er details + oi course er shob payment. */
    public function show(Request $request, Enrollment $order)
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        $order->load(['course', 'payments' => fn ($q) => $q->latest('paid_at')->latest('id')]);

        return view('user.orders.show', compact('order'));
    }
}
