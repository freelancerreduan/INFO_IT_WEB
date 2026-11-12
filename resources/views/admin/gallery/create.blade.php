@extends('admin.layouts.app')

@php $editing = $gallery->exists; @endphp

@section('title', $editing ? 'Edit Gallery' : 'Add Gallery')
@section('subtitle', $editing ? 'Update gallery photo.' : 'Upload a new gallery photo.')

@section('content')
<section class="page-sec show" id="page-add-gallery">
  <div class="page-header"><div><h2>{{ $editing ? 'Edit Gallery' : 'Add Gallery' }}</h2><p>{{ $editing ? 'Update this photo in the institute gallery.' : 'Upload a new photo to the institute gallery.' }}</p></div></div>
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data"
                action="{{ $editing ? route('admin.gallery.update', $gallery) : route('admin.gallery.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            <label class="form-label">Gallery Title</label>
            <input class="form-control mb-3 @error('title') is-invalid @enderror" name="title" value="{{ old('title', $gallery->title) }}" placeholder="e.g. Graduation Ceremony 2026">
            @error('title')<div class="invalid-feedback mb-3" style="margin-top:-10px">{{ $message }}</div>@enderror
            <label class="form-label">Gallery Image</label>
            @include('admin.partials.upload', [
              'zone' => 'galleryPhotoZone', 'name' => 'image', 'icon' => 'bi-images',
              'text' => 'Click to upload image', 'hint' => 'PNG or JPG, up to 4MB',
              'existing' => $editing ? $gallery->image_url : null,
            ])
            <button type="submit" class="btn btn-accent px-4 mt-4"><i class="bi bi-check2 me-1"></i>{{ $editing ? 'Update' : 'Submit' }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
