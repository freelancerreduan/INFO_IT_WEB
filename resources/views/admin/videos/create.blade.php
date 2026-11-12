@extends('admin.layouts.app')

@php $editing = $video->exists; @endphp

@section('title', $editing ? 'Edit Video' : 'Add Video')
@section('subtitle', $editing ? 'Update class video.' : 'Upload a new class video.')

@section('content')
<section class="page-sec show" id="page-add-video">
  <div class="page-header"><div><h2>{{ $editing ? 'Edit Video' : 'Add Video' }}</h2><p>Shown in the “ক্লাস ভিডিও” section of the website home page.</p></div></div>

  <form method="POST" enctype="multipart/form-data"
        action="{{ $editing ? route('admin.videos.update', $video) : route('admin.videos.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif
    <div class="row g-3">
      <div class="col-lg-8">
        <div class="card"><div class="card-body">
          <div class="row g-3">
            <div class="col-md-7"><label class="form-label">Video Title</label>
              <input class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $video->title) }}" placeholder="যেমন: React দিয়ে প্রথম কম্পোনেন্ট তৈরি">
              @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5"><label class="form-label">Course</label>
              <select class="form-select" name="course_id">
                <option value="">— No course —</option>
                @foreach ($courses as $c)
                  <option value="{{ $c->id }}" @selected(old('course_id', $video->course_id) == $c->id)>{{ $c->title }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Course Label (if no course is selected)</label>
              <input class="form-control" name="course_label" value="{{ old('course_label', $video->course_label) }}" placeholder="যেমন: ফ্রিল্যান্সিং">
            </div>
            <div class="col-md-2"><label class="form-label">Batch</label>
              <input class="form-control" name="batch" value="{{ old('batch', $video->batch) }}" placeholder="ব্যাচ ২৪">
            </div>
            <div class="col-md-3"><label class="form-label">Class Date</label>
              <input type="date" class="form-control" name="class_date" value="{{ old('class_date', $video->class_date?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3"><label class="form-label">Duration</label>
              <input class="form-control" name="duration" value="{{ old('duration', $video->duration) }}" placeholder="১২:৪০">
            </div>
            <div class="col-md-4"><label class="form-label">Date Label (optional)</label>
              <input class="form-control" name="date_label" value="{{ old('date_label', $video->date_label) }}" placeholder="১৮ সেপ্টেম্বর ২০২৬">
            </div>
            <div class="col-md-4"><label class="form-label">Status</label>
              <select class="form-select" name="status">
                @foreach (\App\Models\ClassVideo::STATUSES as $val => $label)
                  <option value="{{ $val }}" @selected(old('status', $video->status) === $val)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Sort Order</label>
              <input type="number" min="0" class="form-control" name="sort_order" value="{{ old('sort_order', $video->sort_order ?? 0) }}">
            </div>
          </div>

          <hr class="my-4">

          <label class="form-label">Video Source</label>
          <div class="d-flex gap-4 mb-3">
            @foreach (\App\Models\ClassVideo::SOURCES as $val => $label)
              <div class="form-check">
                <input class="form-check-input js-source" type="radio" name="source" value="{{ $val }}" id="src-{{ $val }}" @checked(old('source', $video->source) === $val)>
                <label class="form-check-label" for="src-{{ $val }}">{{ $label }}</label>
              </div>
            @endforeach
          </div>

          <div id="uploadBox" class="row g-3">
            <div class="col-12"><label class="form-label">Video File (MP4 / WebM)</label>
              <input type="file" accept="video/*" class="form-control @error('video_file') is-invalid @enderror" name="video_file">
              @error('video_file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              <div class="form-text">এই সার্ভারে এক ফাইলে সর্বোচ্চ <b>{{ ini_get('upload_max_filesize') }}</b> আপলোড করা যাবে (php.ini → upload_max_filesize / post_max_size)। বড় ভিডিও হলে YouTube লিংক ব্যবহার করাই ভালো।</div>
              @if ($editing && $video->video_path && $video->source === 'upload')
                <div class="form-text">Current: <a href="{{ $video->video_url }}" target="_blank">{{ $video->video_path }}</a> — uploading a new file will replace the old one.</div>
              @endif
            </div>
            <div class="col-12"><label class="form-label">…অথবা সরাসরি ভিডিও লিংক (optional)</label>
              <input class="form-control @error('video_link') is-invalid @enderror" name="video_link" placeholder="https://example.com/class.mp4">
              @error('video_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div id="youtubeBox" class="row g-3">
            <div class="col-12"><label class="form-label">YouTube Link or Video ID</label>
              <input class="form-control" name="youtube_id" value="{{ old('youtube_id', $video->youtube_id) }}" placeholder="https://youtu.be/xxxxxxxxxxx">
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-accent px-4"><i class="bi bi-check2 me-1"></i>{{ $editing ? 'Update Video' : 'Add Video' }}</button>
            <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-accent px-4">Cancel</a>
          </div>
        </div></div>
      </div>

      <div class="col-lg-4">
        <div class="card">
          <div class="card-header"><div class="section-title">Video Thumbnail</div><div class="section-sub">Optional — if empty, the video's first frame is shown</div></div>
          <div class="card-body">
            @include('admin.partials.upload', [
              'zone' => 'videoThumbZone', 'name' => 'thumbnail', 'icon' => 'bi-image',
              'text' => 'Click to upload thumbnail', 'hint' => 'Recommended 800×450px',
              'existing' => $editing ? $video->thumb_url : null,
            ])
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection

@push('scripts')
<script>
function toggleVideoSource(){
  const yt = document.querySelector('.js-source:checked')?.value === 'youtube';
  document.getElementById('uploadBox').classList.toggle('d-none', yt);
  document.getElementById('youtubeBox').classList.toggle('d-none', !yt);
}
document.querySelectorAll('.js-source').forEach(r => r.addEventListener('change', toggleVideoSource));
toggleVideoSource();
</script>
@endpush
