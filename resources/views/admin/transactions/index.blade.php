@extends('admin.layouts.app')

@section('title', 'Transactions')
@section('subtitle', 'Track institute payments.')

@section('content')
<section class="page-sec show" id="page-transactions">
  <div class="page-header">
    <div><h2>Transactions</h2><p>Track every payment made to your institute.</p></div>
    <a class="btn btn-accent" href="{{ route('admin.transactions.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Transaction</a>
  </div>
  @if ($pendingCount > 0 && request('status') !== 'pending')
    <div class="flash mb-3" style="background:var(--warn-soft);color:var(--warn)">
      <i class="bi bi-hourglass-split"></i>
      <span><b>{{ $pendingCount }}</b> payment(s) waiting for approval.</span>
      <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" class="ms-auto fw-semibold" style="color:inherit">View pending <i class="bi bi-arrow-right"></i></a>
    </div>
  @endif
  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.transactions.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-3"><label class="form-label">Search TRX ID / Student ID</label><input class="form-control" name="q" value="{{ request('q') }}" placeholder="TRX ID, Student ID, name, phone"></div>
      <div class="col-md-2"><label class="form-label">Payment Method</label>
        <select class="form-select" name="method">
          <option value="">All</option>
          @foreach (\App\Models\Payment::METHODS as $m)
            <option @selected(request('method') === $m)>{{ $m }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option value="">All</option>
          @foreach (\App\Models\Payment::STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="{{ request('date') }}"></div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
    <div class="text-end mt-2">
      <a href="{{ route('admin.transactions.export', request()->query()) }}" class="btn btn-sm btn-outline-accent"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
    </div>
  </form>
  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Transaction ID</th><th>Payment Type</th><th>Account No.</th><th>Student</th><th>Course</th><th>Amount</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody id="txTableBody">
          @forelse ($transactions as $t)
            <tr>
              <td class="cell-sub">{{ $t->trx_id }}</td>
              <td>{{ $t->method }}</td>
              <td class="cell-sub">{{ $t->account_no ?: '—' }}</td>
              <td><div class="cell-name">{{ $t->user?->name ?? '—' }}</div>@if ($t->user?->student_id)<div class="cell-sub small">{{ $t->user->student_id }}</div>@endif</td>
              <td>{{ $t->enrollment?->course?->title ?? '—' }}</td>
              <td>{{ money($t->amount) }}</td>
              <td>{{ status_pill($t->status_label) }}</td>
              <td class="cell-sub">{{ $t->paid_at?->format('Y-m-d') }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="details" data-payload="{{ json_encode([
                    'title' => 'Transaction ' . $t->trx_id,
                    'rows' => [
                      ['Transaction ID', $t->trx_id], ['Payment Type', $t->method], ['Account No.', $t->account_no ?: '—'],
                      ['Student', $t->user?->name ?? '—'], ['Course', $t->enrollment?->course?->title ?? '—'], ['Amount', money($t->amount)],
                      ['Order', $t->enrollment?->order_no ?? '—'], ['Note', $t->note ?: '—'], ['Date', $t->paid_at?->format('Y-m-d')],
                    ],
                    'status' => $t->status_label,
                  ]) }}"><i class="bi bi-eye"></i></button>
                  @if ($t->status !== 'paid')
                  <button class="act-btn ok js-confirm" title="Approve" data-method="PATCH" data-status="paid"
                    data-title="Approve transaction?" data-text="Approving {{ $t->trx_id }} will reduce {{ $t->user?->name }}'s due by {{ money($t->amount) }}." data-label="Approve"
                    data-url="{{ route('admin.transactions.status', $t) }}"><i class="bi bi-check2"></i></button>
                  @endif
                  @if ($t->status !== 'rejected')
                  <button class="act-btn danger js-confirm" title="Reject" data-method="PATCH" data-status="rejected"
                    data-title="Reject transaction?" data-text="If you reject {{ $t->trx_id }}, the student will see it as Failed in their panel." data-label="Reject"
                    data-url="{{ route('admin.transactions.status', $t) }}"><i class="bi bi-x-lg"></i></button>
                  @endif
                  <button class="act-btn danger js-confirm" title="Delete" data-type="transaction" data-name="{{ $t->trx_id }}"
                    data-url="{{ route('admin.transactions.destroy', $t) }}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 9, 'text' => 'No transactions match your filters.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $transactions])
  </div>
</section>
@endsection
