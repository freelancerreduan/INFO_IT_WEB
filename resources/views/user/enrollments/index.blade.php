@extends('layouts.user')
@section('title', 'My Courses')
@section('subtitle', 'Courses you are enrolled in.')

@section('content')
<section class="page-sec show">
  <div class="page-header">
    <div><h2>My Courses</h2><p>Only enrolled (confirmed) courses are shown here. See <a href="{{ route('user.orders.index') }}" style="color:var(--accent)">Order History</a> for all orders.</p></div>
    <a class="btn btn-accent" href="{{ route('courses.index') }}"><i class="bi bi-bag-plus me-1"></i>New Order</a>
  </div>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Course</th><th>Duration</th><th>Enrolled On</th><th>Course Price</th><th>Paid</th><th>Due</th><th>Status</th><th></th></tr></thead>
        <tbody>
          @forelse ($enrollments as $e)
            <tr>
              <td>
                <div class="course-mini">
                  <img src="{{ $e->course?->thumb_url }}" alt="">
                  <div><div class="cell-name">{{ $e->course?->title ?? '—' }}</div><div class="cell-sub">{{ $e->order_no }}</div></div>
                </div>
              </td>
              <td>{{ $e->duration ?: '—' }}</td>
              <td class="cell-sub">{{ $e->enrolled_at?->format('d M Y') }}</td>
              <td>{{ money($e->fee) }}</td>
              <td class="paid-text">{{ money($e->paidTotal()) }}</td>
              <td class="{{ $e->due() > 0 ? 'due-text' : 'cell-sub' }}">{{ money($e->due()) }}</td>
              <td>{{ status_pill($e->order_status_label) }}</td>
              <td>
                <div class="d-flex gap-1">
                  @if ($e->isOpen() && $e->due() > 0)
                    <a class="act-btn ok" title="Add Payment" href="{{ route('user.payments.create', ['order' => $e->id]) }}"><i class="bi bi-wallet2"></i></a>
                  @endif
                  <a class="act-btn" title="Details" href="{{ route('user.orders.show', $e) }}"><i class="bi bi-eye"></i></a>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="8"><div class="empty-state"><i class="bi bi-journal-bookmark"></i><p class="mt-2 mb-2">You are not enrolled in any course yet.</p><a href="{{ route('courses.index') }}" class="btn btn-sm btn-accent">Browse courses</a></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $enrollments])
  </div>
</section>
@endsection
