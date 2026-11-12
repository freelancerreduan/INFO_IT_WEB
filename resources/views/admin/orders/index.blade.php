@extends('admin.layouts.app')

@section('title', 'Orders')
@section('subtitle', 'Manage course orders.')

@section('content')
<section class="page-sec show" id="page-orders">
  <div class="page-header">
    <div><h2>Orders</h2><p>Orders from the website stay <b>Pending</b> — Enroll / Reject / Cancel / Delete them.</p></div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-accent" href="{{ route('admin.payments.create') }}"><i class="bi bi-cash-coin me-1"></i>Add Payment</a>
      <a class="btn btn-accent" href="{{ route('admin.orders.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Order</a>
    </div>
  </div>

  {{-- status tabs --}}
  <div class="seg mb-3 flex-wrap">
    <button type="button" class="{{ request('status') ? '' : 'active' }}" onclick="location.href='{{ route('admin.orders.index', request()->except('status', 'page')) }}'">All <span class="cell-sub">({{ $counts->except('deleted')->sum() }})</span></button>
    @foreach (\App\Models\Enrollment::ORDER_STATUSES as $val => $label)
      <button type="button" class="{{ request('status') === $val ? 'active' : '' }}" onclick="location.href='{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => $val])) }}'">{{ $label }} <span class="cell-sub">({{ $counts[$val] ?? 0 }})</span></button>
    @endforeach
  </div>

  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.orders.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-3"><label class="form-label">Search Order</label><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Order ID, name, email, phone"></div>
      <div class="col-md-2"><label class="form-label">Course</label>
        <select class="form-select" id="orderCourseFilter" name="course">
          <option value="">All courses</option>
          @foreach ($courses as $c)
            <option value="{{ $c->id }}" @selected(request('course') == $c->id)>{{ $c->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option value="">All (except deleted)</option>
          @foreach (\App\Models\Enrollment::ORDER_STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="{{ request('date') }}"></div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
    <div class="text-end mt-2">
      <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-sm btn-outline-accent"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
    </div>
  </form>
  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable" style="min-width:1100px">
        <thead><tr><th>Order ID</th><th>Student</th><th>Course</th><th>Price</th><th>Paid</th><th>Due</th><th>Payment</th><th>Order Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody id="orderTableBody">
          @forelse ($orders as $o)
            <tr>
              <td class="cell-sub"><a href="{{ route('admin.orders.show', $o) }}" style="color:var(--accent)">{{ $o->order_no }}</a></td>
              <td><div class="cell-name">{{ $o->user?->name ?? '—' }}</div><div class="cell-sub">{{ $o->user?->phone ?: $o->user?->email }}</div></td>
              <td>{{ $o->course?->title ?? '—' }}</td>
              <td>{{ money($o->fee) }}</td>
              <td style="color:var(--ok);font-weight:600">{{ money($o->paidTotal()) }}</td>
              <td style="{{ $o->due() > 0 ? 'color:var(--warn);font-weight:600' : '' }}">{{ money($o->due()) }}</td>
              <td>{{ status_pill($o->payment_label) }}</td>
              <td>{{ status_pill($o->order_status_label) }}</td>
              <td class="cell-sub">{{ $o->enrolled_at?->format('Y-m-d') }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="details" data-payload="{{ json_encode([
                    'title' => 'Order ' . $o->order_no,
                    'rows' => [
                      ['Order ID', $o->order_no], ['Student', $o->user?->name ?? '—'], ['Email', $o->user?->email ?? '—'],
                      ['Phone', $o->user?->phone ?: '—'], ['Course', $o->course?->title ?? '—'], ['Price', money($o->fee)],
                      ['Paid', money($o->paidTotal())], ['Due', money($o->due())],
                      ['Payment', $o->payment_label], ['Date', $o->enrolled_at?->format('Y-m-d')],
                    ],
                    'status' => $o->order_status_label,
                  ]) }}"><i class="bi bi-eye"></i></button>
                  <a class="act-btn" title="Details & history" href="{{ route('admin.orders.show', $o) }}"><i class="bi bi-clock-history"></i></a>
                  @include('admin.orders._actions', ['o' => $o])
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 10, 'text' => 'No orders match your filters.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $orders])
  </div>
</section>
@endsection
