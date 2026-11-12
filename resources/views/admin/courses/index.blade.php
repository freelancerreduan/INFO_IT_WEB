@extends('admin.layouts.app')

@section('title', 'Course List')
@section('subtitle', 'Manage all courses.')

@section('content')
<section class="page-sec show" id="page-course-list">
  <div class="page-header">
    <div><h2>Course List</h2><p>Manage all courses offered by your institute.</p></div>
    <a class="btn btn-accent" href="{{ route('admin.courses.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Course</a>
  </div>
  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.courses.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-4"><label class="form-label">Search Course</label><input class="form-control" placeholder="Course name" id="courseSearch" name="q" value="{{ request('q') }}" oninput="autoSubmit(this.form, 500)"></div>
      <div class="col-md-3"><label class="form-label">Category</label>
        <select class="form-select" id="courseListCatFilter" name="category" onchange="this.form.submit()">
          <option value="">All categories</option>
          @foreach ($categories as $cat)
            <option @selected(request('category') === $cat)>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3"><label class="form-label">Status</label>
        <select class="form-select" id="courseListStatusFilter" name="status" onchange="this.form.submit()">
          <option value="">All status</option>
          @foreach (\App\Models\Course::STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2"><a href="{{ route('admin.courses.index') }}" class="btn btn-outline-accent w-100">Reset</a></div>
    </div>
  </form>
  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Course</th><th>Category</th><th>Description</th><th>Price</th><th>Students</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody id="courseTableBody">
          @forelse ($courses as $c)
            <tr>
              <td><div class="d-flex align-items-center gap-2"><img src="{{ $c->thumb_url }}" class="row-photo"><span class="cell-name">{{ $c->title }}</span></div></td>
              <td>{{ $c->category ?: '—' }}</td>
              <td class="cell-sub" style="max-width:220px;white-space:normal">{{ \Illuminate\Support\Str::limit($c->short_desc, 60, '…') }}</td>
              <td>{{ money($c->selling_price) }}</td>
              <td><a href="{{ route('admin.students.index', ['course' => $c->id]) }}" class="fw-semibold" title="View students of this course">{{ $c->enrollments_count }}</a></td>
              <td>{{ status_pill($c->status) }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="act-btn" title="View" data-view="course" data-payload="{{ json_encode([
                    'name' => $c->title, 'category' => $c->category ?: '—', 'desc' => $c->short_desc ?: '—',
                    'discount' => $c->selling_price, 'duration' => $c->duration ?: '—', 'modules' => $c->total_modules ?: '—',
                    'classes' => $c->total_classes ?: ($c->classes ?: '—'), 'students' => $c->enrollments_count, 'status' => $c->status_label, 'thumb' => $c->thumb_url,
                  ]) }}"><i class="bi bi-eye"></i></button>
                  <a class="act-btn" title="Students" href="{{ route('admin.students.index', ['course' => $c->id]) }}"><i class="bi bi-people"></i></a>
                  <a class="act-btn" title="Edit" href="{{ route('admin.courses.edit', $c) }}"><i class="bi bi-pencil"></i></a>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="course" data-name="{{ $c->title }}" data-url="{{ route('admin.courses.destroy', $c) }}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 7, 'text' => 'No courses match your filters.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $courses])
  </div>
</section>
@endsection

@push('scripts')
<script>
  (function(){ const i = document.getElementById('courseSearch'); if(i.value){ i.focus(); i.setSelectionRange(i.value.length, i.value.length); } })();
</script>
@endpush
