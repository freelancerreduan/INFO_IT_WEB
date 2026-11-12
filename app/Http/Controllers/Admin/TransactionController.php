<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Services\CsvExport;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Transactions = payments table.
 */
class TransactionController extends Controller
{
    public function index(Request $request)
    {
        User::ensureStudentIds();
        $transactions = $this->filtered($request)->paginate(10)->withQueryString();
        $pendingCount = Payment::where('status', 'pending')->count();

        return view('admin.transactions.index', compact('transactions', 'pendingCount'));
    }

    /**
     * Add Transaction — student search kore select -> tar course (order) dropdown -> due -> amount.
     * Student search / order list AJAX: PaymentController::students() + studentOrders()
     */
    public function create(Request $request)
    {
        User::ensureStudentIds();
        $uid          = $request->old('user_id', $request->query('user'));
        $selectedUser = $uid ? User::students()->find($uid) : null;

        // ?order=ID diye asle student auto select
        if (! $selectedUser && $request->order) {
            $selectedUser = Enrollment::find($request->order)?->user;
        }

        return view('admin.transactions.create', [
            'students'      => PaymentController::studentList(),
            'selectedUser'  => $selectedUser ? PaymentController::studentRowFor($selectedUser) : null,
            'selectedOrder' => $request->old('enrollment_id', $request->order),
        ]);
    }

    public function store(Request $request, OrderService $orders)
    {
        $data = $request->validate([
            'user_id'       => ['required', Rule::exists('users', 'id')->where('role', 'user')],
            'enrollment_id' => ['required', Rule::exists('enrollments', 'id')->where('user_id', $request->user_id)],
            'amount'        => ['required', 'numeric', 'min:1'],
            'method'        => ['required', Rule::in(Payment::METHODS)],
            'account_no'    => ['nullable', 'string', 'max:60'],
            'trx_id'        => ['nullable', 'string', 'max:60', 'unique:payments,trx_id'],
            'status'        => ['required', Rule::in(array_keys(Payment::STATUSES))],
            'paid_at'       => ['required', 'date'],
            'note'          => ['nullable', 'string', 'max:500'],
        ], [
            'user_id.required'       => 'Please select a student.',
            'enrollment_id.required' => 'Please select a course (order).',
        ]);

        // due er beshi paid amount neya jabe na (OrderService e check hoy)
        $order = Enrollment::findOrFail($data['enrollment_id']);
        $orders->addPayment($order, $data);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaction recorded successfully.');
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->get()->map(fn (Payment $t) => [
            $t->trx_id, $t->method, $t->account_no, $t->user?->name, $t->enrollment?->course?->title,
            $t->amount, $t->status_label, $t->paid_at?->format('Y-m-d'),
        ]);

        return CsvExport::download('transactions-' . now()->format('Y-m-d') . '.csv',
            ['Transaction ID', 'Payment Type', 'Account No.', 'Student', 'Course', 'Amount', 'Status', 'Date'], $rows);
    }

    /** Approve (Complete) / Reject — approve korle student er due kome, reject korle student "Failed" dekhe */
    public function status(Request $request, Payment $transaction, OrderService $orders)
    {
        $request->validate(['status' => ['required', Rule::in(array_keys(Payment::STATUSES))]]);

        try {
            $transaction = $orders->changePaymentStatus($transaction, $request->status);
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', "Transaction {$transaction->trx_id} marked as {$transaction->status_label}.");
    }

    public function destroy(Payment $transaction)
    {
        $transaction->delete();

        return back()->with('success', 'Transaction deleted successfully.');
    }

    private function filtered(Request $request)
    {
        return Payment::with(['user', 'enrollment.course'])
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('trx_id', 'like', "%$v%")
                ->orWhere('account_no', 'like', "%$v%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$v%")->orWhere('phone', 'like', "%$v%")
                    ->when(User::ensureStudentIdColumn(), fn ($w) => $w->orWhere('student_id', 'like', "%$v%")))))
            ->when($request->method, fn ($q, $v) => $q->where('method', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->date, fn ($q, $v) => $q->whereDate('paid_at', $v))
            ->latest('paid_at')->latest('id');
    }
}
