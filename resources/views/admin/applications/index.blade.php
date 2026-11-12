@extends('admin.layouts.app')

@section('title', 'Applications')
@section('subtitle', 'Leads submitted through the website apply form.')

@section('content')
<section class="page-sec show" id="page-applications">
  <div class="page-header">
    <div><h2>Applications</h2><p>Students who applied from the website. Contact them and add as student.</p></div>
    <a class="btn btn-accent" href="{{ route('admin.students.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Student</a>
  </div>

  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.applications.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-3"><label class="form-label">Search</label><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Name, email or phone"></div>
      <div class="col-md-2"><label class="form-label">Course</label>
        <select class="form-select" name="course">
          <option value="">All courses</option>
          @foreach ($courses as $c)
            <option value="{{ $c->id }}" @selected(request('course') == $c->id)>{{ $c->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option value="">All</option>
          @foreach (\App\Models\Application::STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" name="date" value="{{ request('date') }}"></div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
  </form>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse ($applications as $a)
            <tr>
              <td class="cell-name">{{ $a->name }}</td>
              <td class="cell-sub">{{ $a->email }}</td>
              <td class="cell-sub">{{ $a->phone ?: '—' }}</td>
              <td>{{ $a->course?->title ?? '—' }}</td>
              <td>{{ status_pill(\App\Models\Application::STATUSES[$a->status] ?? $a->status) }}</td>
              <td class="cell-sub">{{ $a->created_at?->format('Y-m-d') }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="details" data-payload="{{ json_encode([
                    'title' => 'Application — ' . $a->name,
                    'rows' => [['Name', $a->name], ['Email', $a->email], ['Phone', $a->phone ?: '—'], ['Course', $a->course?->title ?? '—'],
                               ['Message', $a->message ?: '—'], ['Date', $a->created_at?->format('Y-m-d H:i')]],
                    'status' => \App\Models\Application::STATUSES[$a->status] ?? $a->status,
                  ]) }}"><i class="bi bi-eye"></i></button>
                  <button class="act-btn js-confirm" title="Mark contacted" data-method="PATCH" data-status="contacted"
                    data-title="Mark as contacted?" data-text="{{ $a->name }} will be marked as contacted." data-label="Contacted"
                    data-url="{{ route('admin.applications.update', $a) }}"><i class="bi bi-telephone"></i></button>
                  <button class="act-btn ok js-confirm" title="Mark enrolled" data-method="PATCH" data-status="enrolled"
                    data-title="Mark as enrolled?" data-text="{{ $a->name }} will be marked as enrolled." data-label="Enrolled"
                    data-url="{{ route('admin.applications.update', $a) }}"><i class="bi bi-check2"></i></button>
                  <button class="act-btn danger js-confirm" title="Reject" data-method="PATCH" data-status="rejected"
                    data-title="Reject application?" data-text="{{ $a->name }} will be marked as rejected." data-label="Reject"
                    data-url="{{ route('admin.applications.update', $a) }}"><i class="bi bi-x-lg"></i></button>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="application" data-name="{{ $a->name }}"
                    data-url="{{ route('admin.applications.destroy', $a) }}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 7, 'text' => 'No applications yet.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $applications])
  </div>
</section>
@endsection
