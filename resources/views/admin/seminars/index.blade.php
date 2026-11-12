@extends('admin.layouts.app')

@section('title', 'Seminar List')
@section('subtitle', 'Upcoming seminar schedule shown on the website.')

@section('content')
<section class="page-sec show" id="page-seminar-list">
  <div class="page-header">
    <div><h2>Seminar List</h2><p>These seminars appear in the “আপকামিং সেমিনার সময়সূচী” section of the website home page.</p></div>
    <a class="btn btn-accent" href="{{ route('admin.seminars.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Seminar</a>
  </div>

  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.seminars.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-4"><label class="form-label">Search</label><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Seminar / course name"></div>
      <div class="col-md-2"><label class="form-label">Venue</label>
        <select class="form-select" name="venue">
          <option value="">All</option>
          @foreach (\App\Models\Seminar::VENUES as $val => $label)
            <option value="{{ $val }}" @selected(request('venue') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option value="">All</option>
          @foreach (\App\Models\Seminar::STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="{{ route('admin.seminars.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
  </form>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Name / Course</th><th>Date</th><th>Time</th><th>Venue</th><th>Show</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse ($seminars as $s)
            <tr>
              <td class="cell-name">{{ $s->title }}<div class="cell-sub">{{ $s->course?->title ?? 'No course linked' }}</div></td>
              <td>{{ $s->date_text }}<div class="cell-sub">{{ $s->seminar_date?->format('Y-m-d') }}</div></td>
              <td>{{ $s->time_label }}</td>
              <td>{{ status_pill($s->venue === 'online' ? 'Online' : 'Offline') }}</td>
              <td class="cell-sub">{{ $s->is_featured ? 'Always' : 'View all only' }}</td>
              <td>{{ status_pill(\App\Models\Seminar::STATUSES[$s->status] ?? $s->status) }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="details" data-payload="{{ json_encode([
                    'title' => 'Seminar — ' . $s->title,
                    'rows' => [['Name / Course', $s->title], ['Linked course', $s->course?->title ?? '—'], ['Date', $s->date_text],
                               ['Time', $s->time_label], ['Venue', $s->venue === 'online' ? 'Online' : 'Offline'],
                               ['Registration link', $s->registration_link ?: 'Apply page'], ['Live link', $s->live_link ?: '—']],
                    'status' => \App\Models\Seminar::STATUSES[$s->status] ?? $s->status,
                  ]) }}"><i class="bi bi-eye"></i></button>
                  <a class="act-btn" title="Edit" href="{{ route('admin.seminars.edit', $s) }}"><i class="bi bi-pencil"></i></a>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="seminar" data-name="{{ $s->title }}"
                    data-url="{{ route('admin.seminars.destroy', $s) }}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 7, 'text' => 'No seminars yet.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $seminars])
  </div>
</section>
@endsection
