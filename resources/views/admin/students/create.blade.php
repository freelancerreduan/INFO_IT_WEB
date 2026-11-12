@extends('admin.layouts.app')

@php $editing = $student->exists; @endphp

@section('title', $editing ? 'Edit Student' : 'Add Student')
@section('subtitle', $editing ? 'Update student information.' : 'Register a new student.')

@section('content')
<section class="page-sec show" id="page-add-student">
  <div class="page-header">
    <div>
      <h2>{{ $editing ? 'Edit Student' : 'Add Student' }}</h2>
      <p>{{ $editing ? 'Update student details and assigned course.' : 'Register a new student and assign a course.' }}</p>
    </div>
  </div>
  <div class="row g-3">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header"><div class="section-title">Student Information</div></div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data"
                action="{{ $editing ? route('admin.students.update', $student) : route('admin.students.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Select Course</label>
                <select class="form-select @error('course_id') is-invalid @enderror" id="courseSelect" name="course_id" onchange="renderCourseInfo(this.value)">
                  <option value="">Choose a course…</option>
                  @foreach ($courses as $c)
                    <option value="{{ $c['id'] }}" @selected(old('course_id', $currentCourseId) == $c['id'])>{{ $c['name'] }}</option>
                  @endforeach
                </select>
                @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Student Name</label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Farhan Rahman">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $student->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="student@email.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="01XXXXXXXXX">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Parent / Guardian Number</label>
                <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" class="form-control @error('parent_phone') is-invalid @enderror" placeholder="01XXXXXXXXX">
                @error('parent_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="{{ $editing ? 'col-md-6' : 'col-12' }}">
                <label class="form-label">NID / Birth Registration Number</label>
                <input type="text" name="nid" value="{{ old('nid', $student->nid) }}" class="form-control @error('nid') is-invalid @enderror" placeholder="Enter ID number">
                @error('nid')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              @if ($editing)
                <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="status">
                    @foreach (\App\Models\User::STUDENT_STATUSES as $val => $label)
                      <option value="{{ $val }}" @selected(old('status', $student->status ?? 'active') === $val)>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
              @endif
              <div class="col-12">
                <label class="form-label">Passport Size Photo</label>
                @include('admin.partials.upload', [
                  'zone' => 'studentPhotoZone', 'name' => 'photo', 'icon' => 'bi-cloud-arrow-up',
                  'text' => 'Click to upload photo', 'hint' => 'PNG or JPG, up to 2MB',
                  'existing' => $editing && $student->photo ? $student->photo_url : null,
                ])
              </div>
            </div>
            @unless ($editing)
              <div class="cell-sub mt-3"><i class="bi bi-info-circle me-1"></i>A login password will be generated and shown after submit — the student can log in to the student dashboard with it.</div>
            @endunless
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-accent px-4"><i class="bi bi-check2 me-1"></i>{{ $editing ? 'Update Student' : 'Submit Student' }}</button>
              <a href="{{ route('admin.students.index') }}" class="btn btn-outline-accent px-4">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card" id="courseInfoCard">
        <div class="empty-state">
          <i class="bi bi-mortarboard"></i>
          <p class="mt-3 mb-0">Select a course to view course information.</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
const COURSES = @json($courses);

function renderCourseInfo(courseId){
  const card = document.getElementById('courseInfoCard');
  if(!courseId){
    card.innerHTML = `<div class="empty-state"><i class="bi bi-mortarboard"></i><p class="mt-3 mb-0">Select a course to view course information.</p></div>`;
    return;
  }
  const c = COURSES.find(x=>x.id===String(courseId));
  if(!c) return;
  card.innerHTML = `
    <img src="${esc(c.thumb)}" class="course-thumb" alt="${esc(c.name)}">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="fw-bold" style="font-family:'Sora';font-size:16px">${esc(c.name)}</div>
          <span class="badge rounded-pill" style="background:var(--accent-soft);color:var(--accent-ink);font-weight:600">${esc(c.category)}</span>
        </div>
        ${statusPill(c.status==='Active'?'active':'inactive')}
      </div>
      <p class="text-muted small mt-2 mb-3">${esc(c.desc)}</p>
      <div class="meta-row"><span>Course Price</span><span>${money(c.discount)} ${c.discount < c.price ? `<span class="text-muted text-decoration-line-through fw-normal">${money(c.price)}</span>` : ''}</span></div>
      <div class="meta-row"><span>Duration</span><span>${esc(c.duration)}</span></div>
      <div class="meta-row"><span>Total Modules</span><span>${esc(c.modules)}</span></div>
      <div class="meta-row"><span>Total Classes</span><span>${esc(c.classes)}</span></div>
      <div class="meta-row"><span>Instructor</span><span>${esc(c.instructor)}</span></div>
      <div class="meta-row"><span>Rating</span><span><i class="bi bi-star-fill text-warning"></i> ${esc(c.rating)}</span></div>
      <div class="meta-row"><span>Admission Status</span><span>${c.status==='Active'?'Open':'Closed'}</span></div>
    </div>`;
}
renderCourseInfo(document.getElementById('courseSelect').value);
</script>
@endpush
