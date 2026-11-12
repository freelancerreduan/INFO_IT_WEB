@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_no)
@section('subtitle', 'Order details and student history.')

@section('content')
<section class="page-sec show" id="page-order-details">
  <div class="page-header">
    <div><h2>Order {{ $order->order_no }}</h2><p>Order details, student profile and full enrollment history.</p></div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-accent"><i class="bi bi-arrow-left me-1"></i>Back to orders</a>
  </div>

  <div class="row g-3">
    {{-- Student profile --}}
    <div class="col-lg-4">
      <div class="card h-100">
        <div class="card-header"><div class="section-title">Student</div><div class="section-sub">Profile information</div></div>
        <div class="card-body">
          <div class="text-center mb-3">
            <img src="{{ $order->user?->photo_url }}" alt="" style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid var(--accent-soft)">
            <div class="fw-semibold mt-2">{{ $order->user?->name ?? '—' }}</div>
            <div class="cell-sub">{{ $order->user?->email }}</div>
          </div>
          <div class="meta-row"><span>Phone</span><span>{{ $order->user?->phone ?: '—' }}</span></div>
          <div class="meta-row"><span>Parent Number</span><span>{{ $order->user?->parent_phone ?: '—' }}</span></div>
          <div class="meta-row"><span>NID / Birth Reg.</span><span>{{ $order->user?->nid ?: '—' }}</span></div>
          <div class="meta-row"><span>Address</span><span style="max-width:60%;text-align:right">{{ $order->user?->address ?: '—' }}</span></div>
          <div class="meta-row"><span>Joined</span><span>{{ $order->user?->created_at?->format('Y-m-d') }}</span></div>
          <div class="meta-row"><span>Status</span><span>{{ status_pill($order->user?->status_label ?? '—') }}</span></div>
          @if ($order->user)
            <a href="{{ route('admin.students.edit', $order->user) }}" class="btn btn-outline-accent w-100 mt-3"><i class="bi bi-pencil me-1"></i>Edit student</a>
          @endif
        </div>
      </div>
    </div>

    {{-- Order info + actions --}}
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div><div class="section-title">Order Information</div><div class="section-sub">{{ $order->order_no }}</div></div>
          <span>{{ status_pill($order->order_status_label) }}</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="meta-row"><span>Course</span><span>{{ $order->course?->title ?? '—' }}</span></div>
              <div class="meta-row"><span>Duration</span><span>{{ $order->duration ?: '—' }}</span></div>
              <div class="meta-row"><span>Enrolled at</span><span>{{ $order->enrolled_at?->format('Y-m-d') }}</span></div>
            </div>
            <div class="col-md-6">
              <div class="meta-row"><span>Course Fee</span><span>{{ money($order->fee) }}</span></div>
              <div class="meta-row"><span>Paid</span><span>{{ money($order->paidTotal()) }}</span></div>
              <div class="meta-row"><span>Due</span><span>{{ money($order->due()) }}</span></div>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2 mt-4">
            @if ($order->order_status === 'deleted')
              <button class="btn btn-accent js-confirm" data-method="PATCH" data-status="pending"
                data-title="Restore order?" data-text="Order {{ $order->order_no }} will go back to Pending."
                data-label="Restore" data-url="{{ route('admin.orders.status', $order) }}"><i class="bi bi-arrow-counterclockwise me-1"></i>Restore</button>
              <button class="btn btn-soft-danger js-confirm" data-type="order" data-name="{{ $order->order_no }}"
                data-url="{{ route('admin.orders.destroy', $order) }}"><i class="bi bi-trash3-fill me-1"></i>Delete permanently</button>
            @else
              @if ($order->isOpen() && $order->due() > 0)
                <a class="btn btn-accent" href="{{ route('admin.payments.create', ['order' => $order->id]) }}"><i class="bi bi-plus-circle me-1"></i>Add Payment</a>
              @endif
              @if ($order->order_status !== 'completed')
                <button class="btn btn-outline-accent js-confirm" data-method="PATCH" data-status="completed"
                  data-title="Enroll student?" data-text="Order {{ $order->order_no }} will be Enrolled."
                  data-label="Enroll" data-url="{{ route('admin.orders.status', $order) }}"><i class="bi bi-check2-circle me-1"></i>Enroll</button>
              @endif
              @if ($order->order_status !== 'pending')
                <button class="btn btn-outline-accent js-confirm" data-method="PATCH" data-status="pending"
                  data-title="Mark as pending?" data-text="Order {{ $order->order_no }} will be marked as pending."
                  data-label="Pending" data-url="{{ route('admin.orders.status', $order) }}"><i class="bi bi-hourglass-split me-1"></i>Pending</button>
              @endif
              @if ($order->order_status !== 'rejected')
                <button class="btn btn-outline-accent js-confirm" data-method="PATCH" data-status="rejected"
                  data-title="Reject order?" data-text="Order {{ $order->order_no }} will be marked as rejected."
                  data-label="Reject" data-url="{{ route('admin.orders.status', $order) }}"><i class="bi bi-x-lg me-1"></i>Reject</button>
              @endif
              @if ($order->order_status !== 'canceled')
                <button class="btn btn-outline-accent js-confirm" data-method="PATCH" data-status="canceled"
                  data-title="Cancel order?" data-text="Order {{ $order->order_no }} will be marked as canceled."
                  data-label="Cancel order" data-url="{{ route('admin.orders.status', $order) }}"><i class="bi bi-slash-circle me-1"></i>Cancel</button>
              @endif
              <button class="btn btn-soft-danger js-confirm" data-method="DELETE"
                data-title="Delete order?" data-text="Order {{ $order->order_no }} will be marked Deleted — the student's order history will show 'Deleted'."
                data-label="Delete" data-url="{{ route('admin.orders.destroy', $order) }}"><i class="bi bi-trash me-1"></i>Delete</button>
            @endif
          </div>

          {{-- Ei order er payment --}}
          <div class="mt-4">
            <div class="section-title mb-2" style="font-size:14px">Payments of this order ({{ $order->payments->count() }})</div>
            <div class="table-wrap">
              <table class="dtable" style="min-width:520px">
                <thead><tr><th>Date</th><th>TRX ID</th><th>Method</th><th>Amount</th><th>Status</th><th>Note</th></tr></thead>
                <tbody>
                  @forelse ($order->payments as $p)
                    <tr>
                      <td class="cell-sub">{{ $p->paid_at?->format('Y-m-d') }}</td>
                      <td class="cell-sub">{{ $p->trx_id }}</td>
                      <td>{{ $p->method }}</td>
                      <td class="fw-semibold">{{ money($p->amount) }}</td>
                      <td>{{ status_pill($p->status_label) }}</td>
                      <td class="cell-sub">{{ $p->note ?: '—' }}</td>
                    </tr>
                  @empty
                    @include('admin.partials.empty-row', ['cols' => 6, 'text' => 'No payment on this order yet.'])
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Enrollment history --}}
    <div class="col-lg-7">
      <div class="card table-card h-100">
        <div class="card-header"><div class="section-title">Enrollment History</div><div class="section-sub">All orders of this student</div></div>
        <div class="table-wrap">
          <table class="dtable">
            <thead><tr><th>Order ID</th><th>Course</th><th>Fee</th><th>Paid</th><th>Due</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
              @forelse ($history as $h)
                <tr @class(['table-active' => $h->id === $order->id])>
                  <td class="cell-sub"><a href="{{ route('admin.orders.show', $h) }}" style="color:var(--accent)">{{ $h->order_no }}</a></td>
                  <td>{{ $h->course?->title ?? '—' }}</td>
                  <td>{{ money($h->fee) }}</td>
                  <td>{{ money($h->paidTotal()) }}</td>
                  <td>{{ money($h->due()) }}</td>
                  <td>{{ status_pill($h->order_status_label) }}</td>
                  <td class="cell-sub">{{ $h->enrolled_at?->format('Y-m-d') }}</td>
                </tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 7, 'text' => 'No enrollment history.'])
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Payment history --}}
    <div class="col-lg-5">
      <div class="card table-card h-100">
        <div class="card-header"><div class="section-title">Payment History</div><div class="section-sub">All transactions of this student</div></div>
        <div class="table-wrap">
          <table class="dtable">
            <thead><tr><th>TRX ID</th><th>Course</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
              @forelse ($payments as $p)
                <tr>
                  <td class="cell-sub">{{ $p->trx_id }}</td>
                  <td>{{ $p->enrollment?->course?->title ?? '—' }}</td>
                  <td>{{ money($p->amount) }}</td>
                  <td>{{ status_pill($p->status_label) }}</td>
                  <td class="cell-sub">{{ $p->paid_at?->format('Y-m-d') }}</td>
                </tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 5, 'text' => 'No payments yet.'])
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
