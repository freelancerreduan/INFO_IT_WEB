@extends('admin.layouts.app')

@section('title', 'Video List')
@section('subtitle', 'Class videos shown on the website home page.')

@section('content')
<section class="page-sec show" id="page-video-list">
  <div class="page-header">
    <div><h2>Video List</h2><p>These videos appear in the “ক্লাস ভিডিও” section of the website home page (9 per page).</p></div>
    <a class="btn btn-accent" href="{{ route('admin.videos.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Video</a>
  </div>

  <form class="filter-bar mb-3" method="GET" action="{{ route('admin.videos.index') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-4"><label class="form-label">Search</label><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Video title"></div>
      <div class="col-md-3"><label class="form-label">Course</label>
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
          @foreach (\App\Models\ClassVideo::STATUSES as $val => $label)
            <option value="{{ $val }}" @selected(request('status') === $val)>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-accent flex-fill"><i class="bi bi-search me-1"></i>Search</button>
        <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-accent flex-fill">Reset</a>
      </div>
    </div>
  </form>

  <div class="card table-card">
    <div class="table-wrap">
      <table class="dtable">
        <thead><tr><th>Video</th><th>Course</th><th>Batch</th><th>Date</th><th>Duration</th><th>Source</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse ($videos as $v)
            <tr>
              <td class="cell-name">{{ $v->title }}</td>
              <td>{{ $v->course_text ?: '—' }}</td>
              <td class="cell-sub">{{ $v->batch ?: '—' }}</td>
              <td class="cell-sub">{{ $v->date_text ?: '—' }}</td>
              <td class="cell-sub">{{ $v->duration ?: '—' }}</td>
              <td>{{ $v->source === 'youtube' ? 'YouTube' : 'Uploaded' }}</td>
              <td>{{ status_pill(\App\Models\ClassVideo::STATUSES[$v->status] ?? $v->status) }}</td>
              <td>
                <div class="d-flex gap-1">
                  @if ($v->source === 'youtube' && $v->youtube_id)
                    <a class="act-btn" title="Open" target="_blank" href="https://www.youtube.com/watch?v={{ $v->youtube_id }}"><i class="bi bi-play-circle"></i></a>
                  @elseif ($v->video_url)
                    <a class="act-btn" title="Open" target="_blank" href="{{ $v->video_url }}"><i class="bi bi-play-circle"></i></a>
                  @endif
                  <a class="act-btn" title="Edit" href="{{ route('admin.videos.edit', $v) }}"><i class="bi bi-pencil"></i></a>
                  <button class="act-btn danger js-confirm" title="Delete" data-type="video" data-name="{{ $v->title }}"
                    data-url="{{ route('admin.videos.destroy', $v) }}"><i class="bi bi-trash"></i></button>
                </div>
              </td>
            </tr>
          @empty
            @include('admin.partials.empty-row', ['cols' => 8, 'text' => 'No videos yet.'])
          @endforelse
        </tbody>
      </table>
    </div>
    @include('admin.partials.pagination', ['paginator' => $videos])
  </div>
</section>
@endsection
