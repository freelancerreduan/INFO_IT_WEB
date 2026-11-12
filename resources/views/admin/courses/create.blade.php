@extends('admin.layouts.app')

@php
  $editing  = $course->exists;
  $regular  = $editing ? (float) $course->regular_price : '';
  $discount = $editing && $course->old_price ? (float) $course->price : '';
  $cats     = collect($categories);
  if ($course->category && ! $cats->contains($course->category)) { $cats->prepend($course->category); }
@endphp

@section('title', $editing ? 'Edit Course' : 'Add Course')
@section('subtitle', $editing ? 'Update course details.' : 'Create a new course.')

@section('content')
<section class="page-sec show" id="page-add-course">
  <div class="page-header"><div><h2>{{ $editing ? 'Edit Course' : 'Add Course' }}</h2><p>{{ $editing ? 'Update this course offering.' : 'Create a new course offering.' }}</p></div></div>
  <form method="POST" enctype="multipart/form-data"
        action="{{ $editing ? route('admin.courses.update', $course) : route('admin.courses.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif
    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-7"><label class="form-label">Course Name</label>
                <input class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $course->title) }}" placeholder="e.g. Advanced Digital Marketing">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-5"><label class="form-label">Category</label>
                <select class="form-select @error('category') is-invalid @enderror" name="category">
                  @foreach ($cats as $cat)
                    <option @selected(old('category', $course->category) === $cat)>{{ $cat }}</option>
                  @endforeach
                </select>
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12"><label class="form-label">Description</label>
                <textarea class="form-control @error('short_desc') is-invalid @enderror" name="short_desc" rows="3" placeholder="Short course description">{{ old('short_desc', $course->short_desc) }}</textarea>
                @error('short_desc')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-3"><label class="form-label">Price ({{ currency_symbol() }})</label>
                <input type="number" step="0.01" class="form-control @error('regular_price') is-invalid @enderror" name="regular_price" value="{{ old('regular_price', $regular) }}" placeholder="12000">
                @error('regular_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-3"><label class="form-label">Discount Price ({{ currency_symbol() }})</label>
                <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" name="discount_price" value="{{ old('discount_price', $discount) }}" placeholder="9500">
                @error('discount_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-3"><label class="form-label">Duration</label>
                <input class="form-control @error('duration') is-invalid @enderror" name="duration" value="{{ old('duration', $course->duration) }}" placeholder="e.g. 3 Months">
                @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-3"><label class="form-label">Status</label>
                <select class="form-select" name="status">
                  @foreach (\App\Models\Course::STATUSES as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $course->status) === $val)>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6"><label class="form-label">Total Modules</label>
                <input type="number" class="form-control @error('total_modules') is-invalid @enderror" name="total_modules" value="{{ old('total_modules', $course->total_modules ?: '') }}" placeholder="8">
                @error('total_modules')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Total Classes</label>
                <input type="number" class="form-control @error('total_classes') is-invalid @enderror" name="total_classes" value="{{ old('total_classes', $course->total_classes ?: '') }}" placeholder="24">
                @error('total_classes')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-accent px-4"><i class="bi bi-check2 me-1"></i>{{ $editing ? 'Update Course' : 'Create Course' }}</button>
              <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-accent px-4">Cancel</a>
            </div>
          </div>
        </div>

        {{-- Website (frontend course details page) info --}}
        <div class="card mt-3">
          <div class="card-header d-flex align-items-center justify-content-between" role="button" data-bs-toggle="collapse" data-bs-target="#webDetails">
            <div>
              <div class="section-title">Website Details</div>
              <div class="section-sub">Shown on the public course details page (optional)</div>
            </div>
            <i class="bi bi-chevron-down"></i>
          </div>
          <div class="collapse {{ $errors->hasAny(['long_desc','instructor','instructor_role','mode','certificate','rating','classes','curriculum','outcomes']) ? 'show' : '' }}" id="webDetails">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Instructor</label>
                  <input class="form-control" name="instructor" value="{{ old('instructor', $course->instructor) }}" placeholder="e.g. Ashiqur Rahman"></div>
                <div class="col-md-6"><label class="form-label">Instructor Role</label>
                  <input class="form-control" name="instructor_role" value="{{ old('instructor_role', $course->instructor_role) }}" placeholder="e.g. Senior Instructor"></div>
                <div class="col-md-4"><label class="form-label">Class Mode</label>
                  <input class="form-control" name="mode" value="{{ old('mode', $course->mode) }}" placeholder="Hybrid (Campus + Live Online)"></div>
                <div class="col-md-4"><label class="form-label">Classes Label</label>
                  <input class="form-control" name="classes" value="{{ old('classes', $course->classes) }}" placeholder="e.g. 40+ classes"></div>
                <div class="col-md-4"><label class="form-label">Rating</label>
                  <input type="number" step="0.1" min="0" max="5" class="form-control @error('rating') is-invalid @enderror" name="rating" value="{{ old('rating', $course->rating ?? 4.8) }}">
                  @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label class="form-label">Certificate</label>
                  <input class="form-control" name="certificate" value="{{ old('certificate', $course->certificate) }}" placeholder="Verifiable certificate + capstone project"></div>
                <div class="col-12"><label class="form-label">Long Description</label>
                  <textarea class="form-control" name="long_desc" rows="3">{{ old('long_desc', $course->long_desc) }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Curriculum (one per line)</label>
                  <textarea class="form-control" name="curriculum" rows="4">{{ old('curriculum', implode("\n", $course->curriculum ?? [])) }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Outcomes (one per line)</label>
                  <textarea class="form-control" name="outcomes" rows="4">{{ old('outcomes', implode("\n", $course->outcomes ?? [])) }}</textarea></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        {{-- Website er "আসন্ন ব্যাচ" section --}}
        <div class="card mb-3">
          <div class="card-header">
            <div class="section-title">Upcoming Batch</div>
            <div class="section-sub">The “আসন্ন ব্যাচ” section of the website home page</div>
          </div>
          <div class="card-body">
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="1" name="show_batch" id="showBatch" @checked(old('show_batch', $course->show_batch))>
              <label class="form-check-label" for="showBatch">Show this batch on the website</label>
            </div>
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Batch No</label>
                <input class="form-control" name="batch_no" value="{{ old('batch_no', $course->batch_no) }}" placeholder="ব্যাচ #২৪"></div>
              <div class="col-md-6"><label class="form-label">Start Date</label>
                <input class="form-control" name="batch_start" value="{{ old('batch_start', $course->batch_start) }}" placeholder="১০ অক্টোবর ২০২৬"></div>
              <div class="col-12"><label class="form-label">Class Schedule</label>
                <input class="form-control" name="batch_schedule" value="{{ old('batch_schedule', $course->batch_schedule) }}" placeholder="শনি / সোম / বুধ · রাত ৮:০০ – ১০:০০"></div>
              <div class="col-12"><label class="form-label">Application Deadline</label>
                <input class="form-control" name="batch_deadline" value="{{ old('batch_deadline', $course->batch_deadline) }}" placeholder="০৫ অক্টোবর ২০২৬"></div>
              <div class="col-md-6"><label class="form-label">Total Seats</label>
                <input type="number" min="0" class="form-control @error('total_seats') is-invalid @enderror" name="total_seats" value="{{ old('total_seats', $course->total_seats) }}" placeholder="35">
                @error('total_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
              <div class="col-md-6"><label class="form-label">Seats Left</label>
                <input type="number" min="0" class="form-control @error('seats_left') is-invalid @enderror" name="seats_left" value="{{ old('seats_left', $course->seats_left) }}" placeholder="12">
                @error('seats_left')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><div class="section-title">Course Thumbnail</div></div>
          <div class="card-body">
            @include('admin.partials.upload', [
              'zone' => 'coursePhotoZone', 'name' => 'thumbnail', 'icon' => 'bi-image',
              'text' => 'Drag &amp; drop or click to upload', 'hint' => 'Recommended 800×450px',
              'existing' => $editing && $course->image ? $course->thumb_url : null,
            ])
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection
