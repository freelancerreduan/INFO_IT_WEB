<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Order + Payment er shob hishab ekhane (easy + ek jaygay).
 *
 *  - createManualOrder() : Admin panel "Add Order" (user na thakle email diye user toiri,
 *                          password = email) + received amount -> payment history
 *  - addPayment()        : Admin panel "Add Payment" — due er beshi neya jabe na
 *  - changeStatus()      : Enroll / Reject / Cancel / Delete / Pending
 */
class OrderService
{
    /**
     * @param  array  $data  course_id, name, email, phone, nid, parent_phone, fee?, received?, method?, account_no?, order_date?, order_status?, note?
     * @return array{order: Enrollment, user: User, created: bool}
     */
    public function createManualOrder(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $course = Course::findOrFail($data['course_id']);
            $email  = strtolower(trim($data['email']));

            $user    = User::where('email', $email)->first();
            $created = false;

            if ($user && $user->isAdmin()) {
                throw ValidationException::withMessages(['email' => 'This email belongs to an admin account. Please use the student\'s email.']);
            }

            if (! $user) {
                // Email + Password dutoi = email (student next time login korte parbe)
                $user = User::create([
                    'name'         => $data['name'],
                    'email'        => $email,
                    'password'     => $email,              // User model e 'hashed' cast ache
                    'phone'        => $data['phone'] ?? null,
                    'nid'          => $data['nid'] ?? null,
                    'parent_phone' => $data['parent_phone'] ?? null,
                    'role'         => 'user',
                    'status'       => 'active',
                ]);
                $created = true;
            } else {
                // purano student — khali field gulo fill kore dei, name/phone update
                $user->update(array_filter([
                    'name'         => $data['name'] ?? null,
                    'phone'        => $data['phone'] ?? null,
                    'nid'          => $data['nid'] ?? null,
                    'parent_phone' => $data['parent_phone'] ?? null,
                ], fn ($v) => filled($v)));
            }

            $this->ensureNoDuplicate($user->id, $course->id, 'course_id');

            $fee      = isset($data['fee']) && $data['fee'] !== '' ? (float) $data['fee'] : (float) $course->price;
            $received = (float) ($data['received'] ?? 0);

            if ($received > $fee) {
                throw ValidationException::withMessages(['received' => 'Received amount cannot be more than the course price (' . money($fee) . ').']);
            }

            $status = $data['order_status'] ?? 'completed';

            $order = Enrollment::create([
                'user_id'      => $user->id,
                'course_id'    => $course->id,
                'fee'          => $fee,
                'duration'     => $course->duration,
                'status'       => $status === 'completed' ? 'active' : 'inactive',
                'order_status' => $status,
                'enrolled_at'  => $data['order_date'] ?? now(),
            ]);

            if ($received > 0) {
                Payment::create([
                    'user_id'       => $user->id,
                    'enrollment_id' => $order->id,
                    'amount'        => $received,
                    'method'        => $data['method'] ?? 'Cash',
                    'account_no'    => $data['account_no'] ?? null,
                    'status'        => 'paid',
                    'paid_at'       => $data['order_date'] ?? now(),
                    'note'          => $data['note'] ?? 'Admission payment (manual order)',
                ]);
            }

            return ['order' => $order, 'user' => $user, 'created' => $created];
        });
    }

    /**
     * Order er against e payment add. Amount due er beshi hole error.
     */
    public function addPayment(Enrollment $order, array $data): Payment
    {
        return DB::transaction(function () use ($order, $data) {
            $order = Enrollment::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! $order->isOpen()) {
                throw ValidationException::withMessages(['enrollment_id' => "Order {$order->order_no} {$order->order_status_label} — payments cannot be added to this order."]);
            }

            $amount = round((float) $data['amount'], 2);
            $due    = $order->due();
            $status = $data['status'] ?? 'paid';

            if ($status === 'paid' && $amount > $due) {
                throw ValidationException::withMessages(['amount' => $due <= 0
                    ? 'This course has no due amount (fully paid).'
                    : 'Amount cannot be more than the due (' . money($due) . ').']);
            }

            return Payment::create([
                'user_id'       => $order->user_id,
                'enrollment_id' => $order->id,
                'amount'        => $amount,
                'method'        => $data['method'] ?? 'Cash',
                'account_no'    => $data['account_no'] ?? null,
                'trx_id'        => ! empty($data['trx_id']) ? strtoupper($data['trx_id']) : null,
                'status'        => $status,
                'paid_at'       => $data['paid_at'] ?? now(),
                'note'          => $data['note'] ?? null,
            ]);
        });
    }

    /**
     * Student panel "Add Payment" — payment Pending e thake, admin approve korle due kome.
     * Amount (due - age theke pending thaka payment) er beshi hote parbe na.
     */
    public function submitStudentPayment(Enrollment $order, array $data): Payment
    {
        return DB::transaction(function () use ($order, $data) {
            $order = Enrollment::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! $order->isOpen()) {
                throw ValidationException::withMessages(['enrollment_id' => "Order {$order->order_no} {$order->order_status_label} — payments cannot be made for this course."]);
            }

            $amount  = round((float) $data['amount'], 2);
            $payable = $order->payable();

            if ($amount > $payable) {
                throw ValidationException::withMessages(['amount' => $payable <= 0
                    ? 'This course has no payable due right now (or a payment is waiting for approval).'
                    : 'Amount cannot be more than ' . money($payable) . '.']);
            }

            return Payment::create([
                'user_id'       => $order->user_id,
                'enrollment_id' => $order->id,
                'amount'        => $amount,
                'method'        => $data['method'],
                'account_no'    => $data['account_no'] ?? null,
                'trx_id'        => strtoupper(trim($data['trx_id'])),
                'status'        => 'pending',
                'paid_at'       => now(),
                'note'          => $data['note'] ?? 'Student panel payment',
            ]);
        });
    }

    /**
     * Admin Approve (paid) / Reject — approve korle due er beshi hole error.
     */
    public function changePaymentStatus(Payment $payment, string $status): Payment
    {
        return DB::transaction(function () use ($payment, $status) {
            $payment = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();

            if ($status === 'paid' && $payment->status !== 'paid' && $payment->enrollment_id) {
                $order = Enrollment::whereKey($payment->enrollment_id)->lockForUpdate()->firstOrFail();
                $due   = $order->due();
                if ((float) $payment->amount > $due) {
                    throw ValidationException::withMessages(['status' => "Transaction {$payment->trx_id} cannot be approved — the order only has " . money($due) . ' due.']);
                }
            }

            $payment->update(['status' => $status]);

            return $payment;
        });
    }

    /** Enroll(completed) / Reject / Cancel / Delete / Pending */
    public function changeStatus(Enrollment $order, string $status): Enrollment
    {
        if (in_array($status, Enrollment::OPEN_STATUSES, true) && ! $order->isOpen()) {
            // restore korle same course e arekta open order thakle duplicate hobe
            $this->ensureNoDuplicate($order->user_id, $order->course_id, 'status', $order->id);
        }

        $order->update([
            'order_status' => $status,
            'status'       => $status === 'completed' ? 'active' : 'inactive',
        ]);

        return $order;
    }

    /** Same student + same course e already Pending / Enrolled order thakle abar order hobe na. */
    public function ensureNoDuplicate(int $userId, int $courseId, string $field = 'course_id', ?int $ignoreId = null): void
    {
        $exists = Enrollment::where('user_id', $userId)->where('course_id', $courseId)->open()
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->first();

        if ($exists) {
            throw ValidationException::withMessages([
                $field => "This student already has an order for this course ({$exists->order_no} — {$exists->order_status_label}).",
            ]);
        }
    }
}
