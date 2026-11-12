@extends('layouts.user')
@section('title', 'Payment History')
@section('subtitle', 'When you paid and how much.')

@section('content')
<section class="page-sec show">
  <div class="page-header">
    <div><h2>Payment History</h2><p><b>Pending</b> = waiting for admin approval · <b>Paid</b> = deducted from your due · <b>Failed</b> = rejected by admin.</p></div>
    <a class="btn btn-accent" href="{{ route('user.payments.create') }}"><i class="bi bi-wallet2 me-1"></i>Add Payment</a>
  </div>
  @if ($summary['pending'] > 0)
    <div class="flash mb-3" style="background:var(--warn-soft);color:var(--warn)"><i class="bi bi-hourglass-split"></i>{{ money($summary['pending']) }} in payments is waiting for admin approval.</div>
  @endif
  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card stat-card h-100">
        <div class="stat-top">
          <div><div class="stat-num paid-text">{{ money_lakh($summary['paid']) }}</div><div class="stat-label">Total Paid</div></div>
          <div class="stat-ic" style="background:var(--ok-soft);color:var(--ok)"><i class="bi bi-cash-stack"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card h-100">
        <div class="stat-top">
          <div><div class="stat-num">{{ $summary['count'] }}</div><div class="stat-label">Payments made</div></div>
          <div class="stat-ic" style="background:var(--accent-soft);color:var(--accent)"><i class="bi bi-receipt"></i></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card h-100">
        <div class="stat-top">
          <div>
            <div class="stat-num" style="font-size:19px">{{ $summary['last'] ? \Illuminate\Support\Carbon::parse($summary['last'])->format('d M Y') : '—' }}</div>
            <div class="stat-label">Last payment</div>
          </div>
          <div class="stat-ic" style="background:#E1F5F5;color:#0F8B8D"><i class="bi bi-calendar-check"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card table-card">
    <div class="card-header"><div class="section-title">All Payments</div><div class="section-sub">Every payment you have made</div></div>
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Date</th><th>TRX ID</th><th>Course</th><th>Order ID</th><th>Amount</th><th>Method</th><th>Account No.</th><th>Status</th><th>Note</th></tr></thead>
        <tbody>
          @forelse ($payments as $p)
            <tr>
              <td>{{ $p->paid_at?->format('d M Y') }}</td>
              <td class="cell-sub">{{ $p->trx_id }}</td>
              <td class="cell-name">{{ $p->enrollment?->course?->title ?? '—' }}</td>
              <td class="cell-sub">
                @if ($p->enrollment)
                  <a href="{{ route('user.orders.show', $p->enrollment) }}" style="color:var(--accent)">{{ $p->enrollment->order_no }}</a>
                @else — @endif
              </td>
              <td class="fw-semibold">{{ money($p->amount) }}</td>
              <td>{{ $p->method }}</td>
              <td class="cell-sub">{{ $p->account_no ?: '—' }}</td>
              <td>{{ status_pill($p->student_status_label) }}</td>
              <td class="cell-sub">{{ $p->note ?: '—' }}</td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 9, 'text' => 'No payment history yet.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $payments])
  </div>
</section>
@endsection
