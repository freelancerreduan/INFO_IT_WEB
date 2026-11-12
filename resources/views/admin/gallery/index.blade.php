@extends('admin.layouts.app')

@section('title', 'Gallery List')
@section('subtitle', 'Browse gallery photos.')

@section('content')
<section class="page-sec show" id="page-gallery-list">
  <div class="page-header">
    <div><h2>Gallery List</h2><p>Photos from events, campus and activities.</p></div>
    <a class="btn btn-accent" href="{{ route('admin.gallery.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Gallery</a>
  </div>
  <div class="row g-3" id="galleryGrid">
    @forelse ($galleries as $g)
      <div class="col-6 col-md-4 col-xl-3">
        <div class="card gallery-card">
          <img src="{{ $g->image_url }}" alt="{{ $g->title }}">
          <div class="gallery-overlay">
            <div class="d-flex gap-2">
              <a class="act-btn" style="background:#fff" title="Edit" href="{{ route('admin.gallery.edit', $g) }}"><i class="bi bi-pencil"></i></a>
              <button class="act-btn js-confirm" style="background:#fff" title="Delete" data-type="photo" data-name="{{ $g->title }}" data-url="{{ route('admin.gallery.destroy', $g) }}"><i class="bi bi-trash"></i></button>
            </div>
          </div>
          <div class="card-body py-2 px-2">
            <div class="cell-name" style="font-size:12.5px">{{ $g->title }}</div>
            <div class="cell-sub">{{ $g->created_at?->format('Y-m-d') }}</div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="card"><div class="empty-state"><i class="bi bi-images"></i><p class="mt-2 mb-0">No gallery photos yet.</p></div></div>
      </div>
    @endforelse
  </div>
  @if ($galleries->hasPages())
    <div class="card mt-3">@include('admin.partials.pagination', ['paginator' => $galleries])</div>
  @endif
</section>
@endsection
