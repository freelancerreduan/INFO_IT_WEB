<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Services\CsvExport;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Orders = enrollments table.
 *  - User (website) order dile -> Pending
 *  - Admin: Enroll / Reject / Cancel / Delete (Deleted order user panel e "Deleted" dekhay)
 *  - Admin manual order: user na thakle email diye user toiri (password = email) + received amount -> payment
 */
class OrderController extends Controller
{
    public function __construct(private OrderService $orders)
    {
    }

    public function index(Request $request)
    {
        $orders  = $this->filtered($request)->paginate(10)->withQueryString();
        $courses = Course::orderBy('title')->get(['id', 'title']);

        $counts = Enrollment::selectRaw('order_status, COUNT(*) as c')->groupBy('order_status')->pluck('c', 'order_status');

        return view('admin.orders.index', compact('orders', 'courses', 'counts'));
    }

    public function create()
    {
        return view('admin.orders.create', [
            'students' => User::students()->orderBy('name')->get(['id', 'name', 'email', 'phone', 'nid', 'parent_phone']),
            'courses'  => Course::orderBy('title')->get(['id', 'title', 'price', 'duration']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'    => ['required', 'exists:courses,id'],
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'phone'        => ['required', 'string', 'max:30'],
            'nid'          => ['nullable', 'string', 'max:40'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'fee'          => ['required', 'numeric', 'min:0'],
            'received'     => ['nullable', 'numeric', 'min:0', 'lte:fee'],
            'method'       => ['required', Rule::in(Payment::METHODS)],
            'account_no'   => ['nullable', 'string', 'max:60'],
            'order_date'   => ['required', 'date'],
            'order_status' => ['required', Rule::in(Enrollment::OPEN_STATUSES)],
            'note'         => ['nullable', 'string', 'max:500'],
        ], [
            'received.lte' => 'Received amount cannot be more than the course price.',
        ]);

        $result = $this->orders->createManualOrder($data);
        $order  = $result['order'];

        $msg = "Order {$order->order_no} created. Paid: " . money($order->paidTotal()) . ' · Due: ' . money($order->due()) . '.';
        if ($result['created']) {
            $msg .= " New student login — Email: {$result['user']->email} / Password: {$result['user']->email}";
        }

        return redirect()->route('admin.orders.show', $order)->with('success', $msg);
    }

    /** Order details + student er puro enrollment/payment history. */
    public function show(Enrollment $order)
    {
        $order->load(['user', 'course', 'payments' => fn ($q) => $q->latest('paid_at')->latest('id')]);

        $history = Enrollment::with('course')->withPaid()
            ->where('user_id', $order->user_id)
            ->latest('enrolled_at')->latest('id')->get();

        $payments = Payment::with('enrollment.course')
            ->where('user_id', $order->user_id)
            ->latest('paid_at')->latest('id')->get();

        return view('admin.orders.show', compact('order', 'history', 'payments'));
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->get()->map(fn (Enrollment $o) => [
            $o->order_no, $o->user?->name, $o->user?->email, $o->user?->phone, $o->course?->title, $o->fee,
            $o->paidTotal(), $o->due(), $o->payment_label, $o->order_status_label, $o->enrolled_at?->format('Y-m-d'),
        ]);

        return CsvExport::download('orders-' . now()->format('Y-m-d') . '.csv',
            ['Order ID', 'Student', 'Email', 'Phone', 'Course', 'Price', 'Paid', 'Due', 'Payment', 'Order Status', 'Date'], $rows);
    }

    /** Enroll / Reject / Cancel / Pending (restore) */
    public function status(Request $request, Enrollment $order)
    {
        $request->validate(['status' => ['required', Rule::in(array_keys(Enrollment::ORDER_STATUSES))]]);

        $this->orders->changeStatus($order, $request->status);

        return back()->with('success', "Order {$order->order_no} marked as {$order->order_status_label}.");
    }

    /**
     * 1st click  -> order "Deleted" (user er order history te Deleted dekhabe)
     * 2nd (Deleted order e) -> permanently delete
     */
    public function destroy(Enrollment $order)
    {
        if ($order->order_status === 'deleted') {
            $no = $order->order_no;
            $order->payments()->delete();
            $order->delete();

            return redirect()->route('admin.orders.index', ['status' => 'deleted'])->with('success', "Order {$no} permanently deleted.");
        }

        $this->orders->changeStatus($order, 'deleted');

        return back()->with('success', "Order {$order->order_no} deleted. (The student panel will show it as 'Deleted'. You can restore or permanently delete it from the 'Deleted' filter.)");
    }

    private function filtered(Request $request)
    {
        return Enrollment::with(['user', 'course'])->withPaid()
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('order_no', 'like', "%$v%")
                ->orWhereHas('user', fn ($s) => $s->where('name', 'like', "%$v%")
                    ->orWhere('email', 'like', "%$v%")->orWhere('phone', 'like', "%$v%"))))
            ->when($request->course, fn ($q, $v) => $q->where('course_id', $v))
            // Deleted order default e lukano thake, "Deleted" filter dile dekhay
            ->when($request->status, fn ($q, $v) => $q->where('order_status', $v), fn ($q) => $q->notDeleted())
            ->when($request->date, fn ($q, $v) => $q->whereDate('enrolled_at', $v))
            ->latest('enrolled_at')->latest('id');
    }
}
