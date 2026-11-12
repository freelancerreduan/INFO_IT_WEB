@extends('layouts.user')
@section('title', 'Order History')
@section('subtitle', 'All of your course orders and their status.')

@section('content')
<section class="page-sec show">
  <div class="page-header">
    <div><h2>Order History</h2><p>Every course you have ordered — status, price, paid and due.</p></div>
    <a class="btn btn-accent" href="{{ route('courses.index') }}"><i class="bi bi-bag-plus me-1"></i>New Order</a>
  </div>

  <form class="filter-bar mb-3" method="GET" action="{{ route('user.orders.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-4"><label class="form-label">Status</label>
        <select class="form-select" name="status" onchange="this.form.submit()">
          <option value="">All orders</option>
          @foreach (\App\Models\Enrollment::ORDER_STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-8 cell-sub">
        <i class="bi bi-info-circle me-1"></i><b>Pending</b> = waiting for admin confirmation · <b>Enrolled</b> = admission confirmed · <b>Rejected / Canceled / Deleted</b> = order is not active.
      </div>
    </div>
  </form>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable" style="min-width:1050px">
        <thead><tr>
          <th>Order ID</th><th>Order Date</th><th>Course</th><th>Duration</th><th>Course Price</th>
          <th>Paid</th><th>Due</th><th>Progress</th><th>Status</th><th>Payment</th>
        </tr></thead>
        <tbody>
          @forelse ($orders as $o)
            @php
              $paid = $o->paidTotal();
              $pct  = $o->fee > 0 ? min(100, round($paid / $o->fee * 100)) : 100;
            @endphp
            <tr>
              <td class="cell-sub"><a href="{{ route('user.orders.show', $o) }}" style="color:var(--accent)">{{ $o->order_no }}</a></td>
              <td class="cell-sub">{{ $o->enrolled_at?->format('d M Y') }}</td>
              <td>
                <div class="course-mini">
                  <img src="{{ $o->course?->thumb_url }}" alt="">
                  <div>
                    <div class="cell-name">{{ $o->course?->title ?? '—' }}</div>
                    <div class="cell-sub">{{ $o->course?->category }}{{ $o->course?->mode ? ' · ' . $o->course->mode : '' }}</div>
                  </div>
                </div>
              </td>
              <td>{{ $o->duration ?: ($o->course?->duration ?: '—') }}</td>
              <td class="fw-semibold">{{ money($o->fee) }}</td>
              <td class="paid-text">{{ money($paid) }}</td>
              <td class="{{ $o->due() > 0 ? 'due-text' : 'cell-sub' }}">{{ money($o->due()) }}</td>
              <td>
                <div class="progress-thin"><div style="width:{{ $pct }}%"></div></div>
                <div class="cell-sub mt-1">{{ $pct }}% paid</div>
              </td>
              <td>{{ status_pill($o->order_status_label) }}</td>
              <td>
                <div class="d-flex gap-1">
                  @include('user.partials.pay-btn', ['o' => $o, 'full' => true])
                  @if ($o->isOpen() && $o->due() > 0)
                    <a class="act-btn ok" title="Add Payment" href="{{ route('user.payments.create', ['order' => $o->id]) }}"><i class="bi bi-wallet2"></i></a>
                  @endif
                  <a class="act-btn" title="Order details" href="{{ route('user.orders.show', $o) }}"><i class="bi bi-eye"></i></a>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="10"><div class="empty-state"><i class="bi bi-receipt"></i><p class="mt-2 mb-2">No orders found.</p><a href="{{ route('courses.index') }}" class="btn btn-sm btn-accent">Browse courses</a></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $orders])
  </div>
</section>
@endsection
