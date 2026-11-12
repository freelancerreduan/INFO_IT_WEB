<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Finance -> Add Payment
 *  1. Student search / list theke select
 *  2. Oi student er shob course order dropdown e ashe
 *  3. Course select korle Price / Paid / Due dekhay
 *  4. Amount dile due theke minus hoy (due er beshi dewa jay na)
 */
class PaymentController extends Controller
{
    public function __construct(private OrderService $orders)
    {
    }

    public function create(Request $request)
    {
        User::ensureStudentIds();
        // validation error hole old() theke, na hole ?user=ID
        $uid          = $request->old('user_id', $request->query('user'));
        $selectedUser = $uid ? User::students()->find($uid) : null;

        // ?order=ID diye asle student auto select
        if (! $selectedUser && $request->order) {
            $selectedUser = Enrollment::find($request->order)?->user;
        }

        return view('admin.payments.create', [
            'students'      => self::studentList(),
            'selectedUser'  => $selectedUser ? self::studentRowFor($selectedUser) : null,
            'selectedOrder' => $request->old('enrollment_id', $request->order),
        ]);
    }

    /** AJAX: student search (Student ID / name / email / phone / order no) */
    public function students(Request $request)
    {
        $q = trim((string) $request->q);

        $rows = $this->studentQuery()
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%$q%")
                ->when(User::ensureStudentIdColumn(), fn ($x) => $x->orWhere('student_id', 'like', "%$q%"))
                ->orWhere('email', 'like', "%$q%")
                ->orWhere('phone', 'like', "%$q%")
                ->orWhereHas('enrollments', fn ($e) => $e->where('order_no', 'like', "%$q%"))))
            ->take(30)->get()->map(fn ($u) => $this->studentRow($u));

        return response()->json($rows);
    }

    /** AJAX: ekjon student er shob order (course) + due */
    public function studentOrders(User $user)
    {
        abort_if($user->isAdmin(), 404);

        $orders = Enrollment::with('course')->withPaid()->withPending()
            ->where('user_id', $user->id)->notDeleted()
            ->latest('enrolled_at')->latest('id')->get()
            ->map(fn (Enrollment $o) => [
                'id'       => $o->id,
                'order_no' => $o->order_no,
                'course'   => $o->course?->title ?? '—',
                'status'   => $o->order_status_label,
                'open'     => $o->isOpen(),
                'fee'      => (float) $o->fee,
                'paid'     => $o->paidTotal(),
                'due'      => $o->due(),
                'pending'  => $o->pendingTotal(),
                'date'     => $o->enrolled_at?->format('Y-m-d'),
            ]);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'       => ['required', Rule::exists('users', 'id')->where('role', 'user')],
            'enrollment_id' => ['required', Rule::exists('enrollments', 'id')->where('user_id', $request->user_id)],
            'amount'        => ['required', 'numeric', 'min:1'],
            'method'        => ['required', Rule::in(Payment::METHODS)],
            'account_no'    => ['nullable', 'string', 'max:60'],
            'trx_id'        => ['nullable', 'string', 'max:60', 'unique:payments,trx_id'],
            'paid_at'       => ['required', 'date'],
            'note'          => ['nullable', 'string', 'max:500'],
        ], [
            'enrollment_id.required' => 'Please select a course (order).',
            'user_id.required'       => 'Please select a student.',
        ]);

        $order   = Enrollment::findOrFail($data['enrollment_id']);
        $payment = $this->orders->addPayment($order, $data + ['status' => 'paid']);
        $order->refresh();

        return redirect()->route('admin.payments.create', ['user' => $order->user_id, 'order' => $order->id])
            ->with('success', 'Payment ' . money($payment->amount) . " added to {$order->order_no}. Remaining due: " . money($order->due()) . '.');
    }

    /** Prothom 30 jon student (Add Payment / Add Transaction page er initial list). */
    public static function studentList()
    {
        $c = app(self::class);

        return $c->studentQuery()->take(30)->get()->map(fn ($u) => $c->studentRow($u));
    }

    /** Selected student er row (open order count soho). */
    public static function studentRowFor(User $u): array
    {
        $u->loadCount(['enrollments as open_orders' => fn ($q) => $q->open()]);

        return app(self::class)->studentRow($u);
    }

    private function studentQuery()
    {
        return User::students()
            ->withCount(['enrollments as open_orders' => fn ($q) => $q->open()])
            ->orderByDesc('open_orders')->latest('id');
    }

    private function studentRow(User $u): array
    {
        return [
            'id'     => $u->id,
            'sid'    => $u->student_id,
            'name'   => $u->name,
            'email'  => $u->email,
            'phone'  => $u->phone,
            'photo'  => $u->photo_url,
            'orders' => (int) ($u->open_orders ?? 0),
        ];
    }
}
