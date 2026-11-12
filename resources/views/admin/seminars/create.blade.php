@extends('admin.layouts.app')

@php $editing = $seminar->exists; @endphp

@section('title', $editing ? 'Edit Seminar' : 'Add Seminar')
@section('subtitle', $editing ? 'Update seminar schedule.' : 'Add a seminar to the website schedule.')

@section('content')
<section class="page-sec show" id="page-add-seminar">
  <div class="page-header"><div><h2>{{ $editing ? 'Edit Seminar' : 'Add Seminar' }}</h2><p>Shown in the “আপকামিং সেমিনার সময়সূচী” table on the website home page.</p></div></div>

  <form method="POST" action="{{ $editing ? route('admin.seminars.update', $seminar) : route('admin.seminars.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif
    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card"><div class="card-body">
          <div class="row g-3">
            <div class="col-md-7"><label class="form-label">Name / Course (this text is shown on the website)</label>
              <input class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $seminar->title) }}" placeholder="যেমন: গ্রাফিক ডিজাইন">
              @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5"><label class="form-label">Linked Course (optional)</label>
              <select class="form-select" name="course_id">
                <option value="">— No course —</option>
                @foreach ($courses as $c)
                  <option value="{{ $c->id }}" @selected(old('course_id', $seminar->course_id) == $c->id)>{{ $c->title }}</option>
                @endforeach
              </select>
              <div class="form-text">The Registration button opens this course's apply page.</div>
            </div>
            <div class="col-md-4"><label class="form-label">Seminar Date</label>
              <input type="date" class="form-control @error('seminar_date') is-invalid @enderror" name="seminar_date" value="{{ old('seminar_date', $seminar->seminar_date?->format('Y-m-d')) }}">
              @error('seminar_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4"><label class="form-label">Date Label (optional)</label>
              <input class="form-control" name="date_label" value="{{ old('date_label', $seminar->date_label) }}" placeholder="শুক্রবার, ২৫ সেপ্টেম্বর">
              <div class="form-text">If empty, the Bangla date is generated automatically.</div>
            </div>
            <div class="col-md-4"><label class="form-label">Time</label>
              <input class="form-control @error('time_label') is-invalid @enderror" name="time_label" value="{{ old('time_label', $seminar->time_label) }}" placeholder="বিকাল ৪:০০ টা">
              @error('time_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4"><label class="form-label">Venue</label>
              <select class="form-select" name="venue">
                @foreach (\App\Models\Seminar::VENUES as $val => $label)
                  <option value="{{ $val }}" @selected(old('venue', $seminar->venue) === $val)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Status</label>
              <select class="form-select" name="status">
                @foreach (\App\Models\Seminar::STATUSES as $val => $label)
                  <option value="{{ $val }}" @selected(old('status', $seminar->status) === $val)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Sort Order</label>
              <input type="number" min="0" class="form-control" name="sort_order" value="{{ old('sort_order', $seminar->sort_order ?? 0) }}">
            </div>
            <div class="col-md-6"><label class="form-label">Registration Link (optional)</label>
              <input class="form-control @error('registration_link') is-invalid @enderror" name="registration_link" value="{{ old('registration_link', $seminar->registration_link) }}" placeholder="https://forms.gle/...">
              @error('registration_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <div class="form-text">If empty, it goes to the website's ভর্তি (apply) page.</div>
            </div>
            <div class="col-md-6"><label class="form-label">Live Seminar Link (Zoom / Facebook Live)</label>
              <input class="form-control @error('live_link') is-invalid @enderror" name="live_link" value="{{ old('live_link', $seminar->live_link) }}" placeholder="https://zoom.us/j/...">
              @error('live_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <div class="form-text">This link is used on the red “আজকের লাইভ সেমিনারে জয়েন করুন” button on the home page.</div>
            </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="is_featured" id="isFeatured" @checked(old('is_featured', $seminar->is_featured ?? true))>
                <label class="form-check-label" for="isFeatured">Show at the top of the table (if unchecked, it only shows under “সকল সেমিনার দেখুন”)</label>
              </div>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-accent px-4"><i class="bi bi-check2 me-1"></i>{{ $editing ? 'Update Seminar' : 'Create Seminar' }}</button>
            <a href="{{ route('admin.seminars.index') }}" class="btn btn-outline-accent px-4">Cancel</a>
          </div>
        </div></div>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-header"><div class="section-title">Website Preview</div><div class="section-sub">Home page table row</div></div>
          <div class="card-body">
            <div class="meta-row"><span>Name / Course</span><span>{{ $seminar->title ?: '—' }}</span></div>
            <div class="meta-row"><span>Date</span><span>{{ $seminar->exists ? $seminar->date_text : '—' }}</span></div>
            <div class="meta-row"><span>Time</span><span>{{ $seminar->time_label ?: '—' }}</span></div>
            <div class="meta-row"><span>Venue</span><span>{{ $seminar->exists ? $seminar->venue_label : '—' }}</span></div>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection
