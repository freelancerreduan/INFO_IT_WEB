@extends('admin.layouts.app')

@section('title', 'Student List')
@section('subtitle', 'Manage enrolled students.')

@section('content')
<section class="page-sec show" id="page-student-list">
  <div class="page-header">
    <div><h2>Student List</h2><p>All enrolled students in your institute.</p></div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-accent" href="{{ route('admin.students.print', request()->query()) }}" target="_blank" rel="noopener"><i class="bi bi-printer me-1"></i>Print</a>
      <a class="btn btn-accent" href="{{ route('admin.students.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Student</a>
    </div>
  </div>

  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.students.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-3"><label class="form-label">Search Student</label><input class="form-control" placeholder="Student ID, name, email or phone" id="stuSearch" name="q" value="{{ request('q') }}"></div>
      <div class="col-md-2"><label class="form-label">Course</label>
        <select class="form-select" id="stuCourseFilter" name="course">
          <option value="">All courses</option>
          @foreach ($courses as $c)
            <option value="{{ $c->id }}" @selected(request('course') == $c->id)>{{ $c->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Status</label>
        <select class="form-select" id="stuStatusFilter" name="status">
          <option value="">All status</option>
          @foreach (\App\Models\User::STUDENT_STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><label class="form-label">Date</label><input type="date" class="form-control" id="stuDateFilter" name="date" value="{{ request('date') }}"></div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
  </form>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>SL</th><th>Student ID</th><th>Student</th><th>Email</th><th>Phone</th><th>Course</th><th>Enrolled</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody id="studentTableBody">
          @forelse ($students as $s)
            @php
              $enrolled = $s->first_enrolled_at ? \Illuminate\Support\Carbon::parse($s->first_enrolled_at)->format('Y-m-d') : $s->created_at?->format('Y-m-d');
              // Course filter dile oi course er naam-i dekhabe
              $courseName = (request('course') ? $courses->firstWhere('id', (int) request('course'))?->title : null)
                  ?? $s->latestEnrollment?->course?->title ?? '—';
            @endphp
            <tr>
              <td class="cell-sub">{{ $students->firstItem() + $loop->index }}</td>
              <td class="cell-sub text-nowrap fw-semibold">{{ $s->student_id ?: '—' }}</td>
              <td><div class="d-flex align-items-center gap-2"><img src="{{ $s->photo_url }}" class="row-photo"><span class="cell-name">{{ $s->name }}</span></div></td>
              <td class="cell-sub">{{ $s->email }}</td>
              <td class="cell-sub">{{ $s->phone ?: '—' }}</td>
              <td>{{ $courseName }}</td>
              <td class="cell-sub">{{ $enrolled }}</td>
              <td>{{ status_pill($s->status ?? 'active') }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="student" data-payload="{{ json_encode([
                    'sid' => $s->student_id ?: '—', 'name' => $s->name, 'email' => $s->email, 'phone' => $s->phone ?: '—', 'parent' => $s->parent_phone ?: '—',
                    'nid' => $s->nid ?: '—', 'course' => $courseName, 'date' => $enrolled,
                    'status' => $s->status_label, 'photo' => $s->photo_url,
                  ]) }}"><i class="bi bi-eye"></i></button>
                  <a class="act-btn" title="Edit" href="{{ route('admin.students.edit', $s) }}"><i class="bi bi-pencil"></i></a>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="student" data-name="{{ $s->name }}" data-url="{{ route('admin.students.destroy', $s) }}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 9, 'text' => 'No students match your filters.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $students])
  </div>
</section>
@endsection
