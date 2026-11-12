@extends('layouts.user')
@section('title', 'My Dashboard')
@section('subtitle', 'Your courses, payments and dues at a glance.')

@section('content')
<section class="page-sec show">
  <div class="card welcome-card mb-3">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h2 class="h5 mb-1 font-display">Welcome back, {{ $user->name }} 👋</h2>
        <p class="mb-0 small">Track your enrolled courses, order status and payment history from here.</p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        @if ($stats['total_due'] > 0)
          <a href="{{ route('user.payments.create') }}" class="btn btn-warning fw-semibold"><i class="bi bi-wallet2 me-1"></i>Pay Due ({{ money($stats['total_due']) }})</a>
        @endif
        <a href="{{ route('courses.index') }}" class="btn btn-light fw-semibold"><i class="bi bi-bag-plus me-1"></i>Order a new course</a>
      </div>
    </div>
  </div>

  @php
    $cards = [
      ['label' => 'Enrolled Courses', 'value' => number_format($stats['courses']), 'icon' => 'bi-journal-bookmark-fill', 'color' => '#4C3AE3', 'soft' => '#EEEBFC',
       'note' => $stats['pending'] ? $stats['pending'] . ' order pending' : 'Only enrolled orders are counted'],
      ['label' => 'Total Course Price', 'value' => money_lakh($stats['total_fee']), 'icon' => 'bi-tags-fill', 'color' => '#0F8B8D', 'soft' => '#E1F5F5', 'note' => 'Of enrolled courses'],
      ['label' => 'Total Paid', 'value' => money_lakh($stats['total_paid']), 'icon' => 'bi-cash-stack', 'color' => '#178A4C', 'soft' => '#E3F7EC', 'note' => 'All completed payments'],
      ['label' => 'Total Due', 'value' => money_lakh($stats['total_due']), 'icon' => 'bi-exclamation-circle-fill', 'color' => '#B8650F', 'soft' => '#FCEEDD', 'note' => $stats['total_due'] > 0 ? 'Please clear your due' : 'No due — great!',
       'action' => $stats['total_due'] > 0 ? ['url' => route('user.payments.create'), 'label' => 'Add Payment'] : null],
    ];
  @endphp
  <div class="row g-3 mb-3">
    @foreach ($cards as $c)
      <div class="col-xl-3 col-sm-6">
        <div class="card stat-card h-100">
          <div class="stat-top">
            <div>
              <div class="stat-num">{{ $c['value'] }}</div>
              <div class="stat-label">{{ $c['label'] }}</div>
            </div>
            <div class="stat-ic" style="background:{{ $c['soft'] }};color:{{ $c['color'] }}"><i class="bi {{ $c['icon'] }}"></i></div>
          </div>
          <div class="cell-sub">{{ $c['note'] }}</div>
          @if (! empty($c['action']))
            <a href="{{ $c['action']['url'] }}" class="btn btn-sm btn-accent mt-2 align-self-start"><i class="bi bi-wallet2 me-1"></i>{{ $c['action']['label'] }}</a>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <div class="row g-3">
    <div class="col-xl-7">
      <div class="card table-card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div><div class="section-title">Recent Orders</div><div class="section-sub">Your latest course orders</div></div>
          <a href="{{ route('user.orders.index') }}" class="btn btn-sm btn-outline-accent">View all</a>
        </div>
        <div class="table-wrap">
          <table class="dtable" style="min-width:560px">
            <thead><tr><th>Course</th><th>Price</th><th>Due</th><th>Status</th><th>Date</th><th></th></tr></thead>
            <tbody>
              @forelse ($recentOrders as $o)
                <tr>
                  <td>
                    <div class="course-mini">
                      <img src="{{ $o->course?->thumb_url }}" alt="">
                      <div><div class="cell-name">{{ $o->course?->title ?? '—' }}</div><div class="cell-sub">{{ $o->order_no }}</div></div>
                    </div>
                  </td>
                  <td>{{ money($o->fee) }}</td>
                  <td class="{{ $o->due() > 0 ? 'due-text' : 'cell-sub' }}">{{ money($o->due()) }}</td>
                  <td>{{ status_pill($o->order_status_label) }}</td>
                  <td class="cell-sub">{{ $o->enrolled_at?->format('d M Y') }}</td>
                  <td>
                  @if ($o->isOpen() && $o->due() > 0)
                    <a class="act-btn ok" title="Add Payment" href="{{ route('user.payments.create', ['order' => $o->id]) }}"><i class="bi bi-wallet2"></i></a>
                  @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="6"><div class="empty-state"><i class="bi bi-bag"></i><p class="mt-2 mb-2">You haven't ordered any course yet.</p><a href="{{ route('courses.index') }}" class="btn btn-sm btn-accent">Browse courses</a></div></td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-xl-5">
      <div class="card table-card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div><div class="section-title">Recent Payments</div><div class="section-sub">Last 5 transactions</div></div>
          <a href="{{ route('user.payments.index') }}" class="btn btn-sm btn-outline-accent">View all</a>
        </div>
        <div class="table-wrap">
          <table class="dtable" style="min-width:420px">
            <thead><tr><th>Date</th><th>Course</th><th>Amount</th><th>Status</th></tr></thead>
            <tbody>
              @forelse ($recentPayments as $p)
                <tr>
                  <td class="cell-sub">{{ $p->paid_at?->format('d M Y') }}</td>
                  <td>{{ $p->enrollment?->course?->title ?? '—' }}</td>
                  <td class="fw-semibold">{{ money($p->amount) }}</td>
                  <td>{{ status_pill($p->student_status_label) }}</td>
                </tr>
              @empty
                @include('admin.partials.empty-row', ['cols' => 4, 'text' => 'No payments yet.'])
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
