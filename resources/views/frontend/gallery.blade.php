@extends('layouts.app')

@section('title', 'গ্যালারি — ' . setting('institute_name'))

@push('styles')
<style>
.gallery-item{
  border-radius:12px;overflow:hidden;cursor:pointer;position:relative;height:220px;
}
.gallery-item img{width:100%;height:100%;object-fit:cover;transition:.35s ease;}
.gallery-item:hover img{transform:scale(1.06);}
.gallery-item .cap{
  position:absolute;left:0;right:0;bottom:0;
  background:linear-gradient(to top, rgba(10,26,61,.85), transparent);
  color:#fff;font-size:.78rem;font-weight:600;
  padding:.7rem .8rem .5rem;
}
</style>
@endpush

@section('content')
<!-- ================= PAGE BANNER ================= -->
<header class="hero" style="padding:3rem 0 2.8rem;">
  <div class="container container-xl">
    <div class="eyebrow" style="color:var(--mint);">গ্যালারি</div>
    <h1 style="font-size:2rem;font-weight:700;">{{ setting('institute_name') }}</h1>
    <p style="color:rgba(255,255,255,.72);max-width:560px;margin-top:.6rem;font-size:.95rem;">আমাদের ক্যাম্পাস, ক্লাসরুম আর প্রতিদিন যারা শিখতে আসেন — তাদের কিছু মুহূর্ত।</p>
  </div>
</header>

<section class="section-pad">
  <div class="container container-xl">
    <div class="row g-3" id="galleryGrid">
      @forelse ($items as $item)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="gallery-item" data-img="{{ $item->image_url }}" data-cap="{{ $item->title }}">
            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" loading="lazy">
            @if ($item->title)<div class="cap">{{ $item->title }}</div>@endif
          </div>
        </div>
      @empty
        <div class="col-12"><p class="text-center" style="color:var(--ink-soft);">এখনও কোনো ছবি যুক্ত করা হয়নি।</p></div>
      @endforelse
    </div>

    @if ($items->hasPages())
      <div class="mt-5 d-flex justify-content-center">{{ $items->links() }}</div>
    @endif
  </div>
</section>

<!-- Lightbox modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="background:transparent;border:none;">
      <button type="button" class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal" style="opacity:.9;"></button>
      <img id="lightboxImg" src="" alt="" style="width:100%;border-radius:12px;">
      <p id="lightboxCaption" class="text-center text-white mt-3" style="font-size:.9rem;"></p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const grid = document.getElementById('galleryGrid');
const lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
grid.querySelectorAll('.gallery-item').forEach(item => {
  item.addEventListener('click', () => {
    document.getElementById('lightboxImg').src = item.dataset.img;
    document.getElementById('lightboxCaption').textContent = item.dataset.cap || '';
    lightboxModal.show();
  });
});
</script>
@endpush
