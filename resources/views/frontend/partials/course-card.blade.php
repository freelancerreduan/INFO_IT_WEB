{{-- কোর্স কার্ড — হোম পেজ, কোর্স লিস্ট ও কোর্স ডিটেইলস পেজে একই ডিজাইন --}}
<div class="col-md-6 col-lg-4 course-item" data-category="{{ $c->category }}">
  <div class="course-card">
    <div class="course-thumb" style="background-image:url('{{ $c->thumb_url }}');">
      <span class="badge-cat">{{ $c->category }}</span>
    </div>
    <div class="course-body">
      <div class="course-meta-top">
        <span class="rating"><i class="bi bi-star-fill"></i> {{ bn_num($c->rating) }}</span>
        <span><i class="bi bi-people"></i> {{ bn_num(number_format($c->enrollments_count ?? $c->enrollments()->count())) }}</span>
      </div>
      <h3>{{ $c->title }}</h3>
      <p class="desc">{{ $c->short_desc }}</p>
      @unless ($hideFacts ?? false)
        <div class="course-facts">
          @if ($c->duration)<span><i class="bi bi-calendar3"></i> {{ $c->duration }}</span>@endif
          @if ($c->classes)<span><i class="bi bi-collection-play"></i> {{ $c->classes }}</span>@endif
          @if ($c->instructor)<span><i class="bi bi-person"></i> {{ $c->instructor }}</span>@endif
        </div>
      @endunless
      <div class="course-price-row">
        <div>
          <span class="price-now">{{ bn_money($c->price) }}</span>
          @if ($c->old_price)<span class="price-old">{{ bn_money($c->old_price) }}</span>@endif
        </div>
        <a href="{{ route('courses.show', $c->slug) }}" class="btn-ghost-navy btn-sm">বিস্তারিত</a>
      </div>
    </div>
  </div>
</div>
