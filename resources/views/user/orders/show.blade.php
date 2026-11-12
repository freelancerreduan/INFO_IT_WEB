@extends('layouts.user')
@section('title', 'Order ' . $order->order_no)
@section('subtitle', 'Order details and all payments of this course.')

@section('content')
<section class="page-sec show">
  <div class="page-header">
    <div><h2>Order {{ $order->order_no }}</h2><p>{{ $order->course?->title ?? '—' }}</p></div>
    <div class="d-flex gap-2">
      @if ($order->isOpen() && $order->due() > 0)
        <a href="{{ route('user.payments.create', ['order' => $order->id]) }}" class="btn btn-accent"><i class="bi bi-wallet2 me-1"></i>Add Payment</a>
      @endif
      <a href="{{ route('user.orders.index') }}" class="btn btn-outline-accent"><i class="bi bi-arrow-left me-1"></i>Back to orders</a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card h-100">
        <img src="{{ $order->course?->thumb_url }}" class="course-thumb" alt="">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="section-title">{{ $order->course?->title ?? '—' }}</div>
            {{ status_pill($order->order_status_label) }}
          </div>
          <div class="meta-row"><span>Order ID</span><span>{{ $order->order_no }}</span></div>
          <div class="meta-row"><span>Order Date</span><span>{{ $order->enrolled_at?->format('d M Y') }}</span></div>
          <div class="meta-row"><span>Duration</span><span>{{ $order->duration ?: ($order->course?->duration ?: '—') }}</span></div>
          <div class="meta-row"><span>Class Mode</span><span>{{ $order->course?->mode ?: '—' }}</span></div>
          <div class="meta-row"><span>Instructor</span><span>{{ $order->course?->instructor ?: '—' }}</span></div>
          @if ($order->course && $order->course->status === 'active')
            <a href="{{ route('courses.show', $order->course->slug) }}" class="btn btn-outline-accent w-100 mt-3"><i class="bi bi-box-arrow-up-right me-1"></i>View course</a>
          @endif
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="row g-3 mb-3">
        <div class="col-sm-4"><div class="card stat-card"><div class="stat-num">{{ money($order->fee) }}</div><div class="stat-label">Course Price</div></div></div>
        <div class="col-sm-4"><div class="card stat-card"><div class="stat-num paid-text">{{ money($order->paidTotal()) }}</div><div class="stat-label">Total Paid</div></div></div>
        <div class="col-sm-4"><div class="card stat-card"><div class="stat-num due-text">{{ money($order->due()) }}</div><div class="stat-label">Due</div></div></div>
      </div>

      <div class="card table-card">
        <div class="card-header"><div class="section-title">Payment History</div><div class="section-sub">All payments for this course ({{ $order->payments->count() }})</div></div>
        <div class="table-wrap">
          <table class="dtable" style="min-width:560px">
            <thead><tr><th>#</th><th>Date</th><th>TRX ID</th><th>Method</th><th>Account No.</th><th>Amount</th><th>Status</th><th>Note</th></tr></thead>
            <tbody>
              @forelse ($order->payments as $i => $p)
                <tr>
                  <td class="cell-sub">{{ $i + 1 }}</td>
                  <td>{{ $p->paid_at?->format('d M Y') }}</td>
                  <td class="cell-sub">{{ $p->trx_id }}</td>
                  <td>{{ $p->method }}</td>
                  <td class="cell-sub">{{ $p->account_no ?: '—' }}</td>
                  <td class="fw-semibold">{{ money($p->amount) }}</td>
                  <td>{{ status_pill($p->student_status_label) }}</td>
                  <td class="cell-sub">{{ $p->note ?: '—' }}</td>
                </tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 8, 'text' => 'No payment yet for this course.'])
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if ($order->order_status === 'pending')
        <div class="flash mt-3" style="background:var(--warn-soft);color:var(--warn)"><i class="bi bi-hourglass-split"></i>Your order is pending. Our admission team will confirm it soon.</div>
      @elseif (in_array($order->order_status, ['rejected', 'canceled', 'deleted']))
        <div class="flash flash-bad mt-3"><i class="bi bi-x-circle"></i>This order is {{ strtolower($order->order_status_label) }}. Contact us if you think this is a mistake.</div>
      @endif
    </div>
  </div>
</section>
@endsection
