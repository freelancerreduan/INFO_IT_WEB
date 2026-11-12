<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $payments = $user->payments()->with('enrollment.course')
            ->latest('paid_at')->latest('id')
            ->paginate(10);

        $summary = [
            'paid'    => (float) $user->payments()->where('status', 'paid')->sum('amount'),
            'count'   => $user->payments()->where('status', 'paid')->count(),
            'pending' => (float) $user->payments()->whereIn('status', ['pending', 'due'])->sum('amount'),
            'last'    => $user->payments()->where('status', 'paid')->latest('paid_at')->value('paid_at'),
        ];

        return view('user.payments.index', compact('payments', 'summary'));
    }

    /**
     * Add Payment — course search / select -> oi course er due -> amount (due porjonto)
     * -> payment type + transaction number -> Pending (admin approve korle due kombe).
     */
    public function create(Request $request)
    {
        $courses = Enrollment::with('course')->withPaid()->withPending()
            ->where('user_id', $request->user()->id)
            ->open()
            ->latest('enrolled_at')->latest('id')->get()
            ->map(fn (Enrollment $o) => [
                'id'       => $o->id,
                'order_no' => $o->order_no,
                'course'   => $o->course?->title ?? '—',
                'thumb'    => $o->course?->thumb_url,
                'status'   => $o->order_status_label,
                'fee'      => (float) $o->fee,
                'paid'     => $o->paidTotal(),
                'due'      => $o->due(),
                'pending'  => $o->pendingTotal(),
                'payable'  => $o->payable(),
            ])->values();

        return view('user.payments.create', [
            'courses'  => $courses,
            'selected' => (string) $request->old('enrollment_id', $request->query('order', '')),
        ]);
    }

    public function store(Request $request, OrderService $orders)
    {
        $user = $request->user();

        $data = $request->validate([
            'enrollment_id' => ['required', Rule::exists('enrollments', 'id')->where('user_id', $user->id)],
            'amount'        => ['required', 'numeric', 'min:1'],
            'method'        => ['required', Rule::in(Payment::STUDENT_METHODS)],
            'trx_id'        => ['required', 'string', 'max:60', 'unique:payments,trx_id'],
            'account_no'    => ['nullable', 'string', 'max:30'],
            'note'          => ['nullable', 'string', 'max:300'],
        ], [
            'enrollment_id.required' => 'Please select a course.',
            'enrollment_id.exists'   => 'This course is not in your orders.',
            'trx_id.required'        => 'Transaction number din.',
            'trx_id.unique'          => 'A payment with this transaction number has already been submitted.',
        ]);

        $order   = Enrollment::findOrFail($data['enrollment_id']);
        $payment = $orders->submitStudentPayment($order, $data);

        return redirect()->route('user.payments.index')
            ->with('success', 'Payment ' . money($payment->amount) . " submitted (TRX: {$payment->trx_id}). Your due will go down once the admin approves it.");
    }
}
