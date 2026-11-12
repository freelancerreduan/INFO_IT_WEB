@extends('layouts.app')

@section('title', 'সকল কোর্স — ' . setting('institute_name'))

@section('content')
<!-- ================= PAGE BANNER ================= -->
<header class="hero" style="padding:3.2rem 0 3rem;">
  <div class="container container-xl">
    <div class="eyebrow" style="color:var(--mint);">কোর্সসমূহ</div>
    <h1 style="font-size:2.1rem;font-weight:700;max-width:640px;line-height:1.2;">{{ setting('institute_name') }}-এর সকল প্রোগ্রাম</h1>
    <p style="color:rgba(255,255,255,.72);max-width:560px;margin-top:.8rem;font-size:.95rem;">প্রতিটি প্রোগ্রামে ক্যাপস্টোন প্রজেক্ট, ভেরিফায়েবল সার্টিফিকেট ও কিস্তিতে পেমেন্টের সুবিধা। ক্যাটাগরি অনুযায়ী ফিল্টার করুন অথবা যেকোনো কোর্স খুলে পূর্ণ সিলেবাস দেখুন।</p>
  </div>
</header>

<!-- ================= FILTER + GRID ================= -->
<section class="section-pad">
  <div class="container container-xl">
    <div id="categoryFilters" class="d-flex flex-wrap gap-2 mb-4">
      <a href="{{ route('courses.index') }}" class="btn-ghost-navy btn-sm filter-btn"
         @if (! request('category')) style="background:var(--navy-800);color:#fff;border-color:var(--navy-800);" @endif>সকল ক্যাটাগরি</a>
      @foreach ($categories as $cat)
        <a href="{{ route('courses.index', ['category' => $cat]) }}" class="btn-ghost-navy btn-sm filter-btn"
           @if (request('category') === $cat) style="background:var(--navy-800);color:#fff;border-color:var(--navy-800);" @endif>{{ $cat }}</a>
      @endforeach
    </div>

    <div id="courseGrid" class="row g-4">
      @forelse ($courses as $course)
        @include('frontend.partials.course-card', ['c' => $course])
      @empty
        <div class="col-12"><p class="text-center" style="color:var(--ink-soft);">এই ক্যাটাগরিতে কোনো কোর্স পাওয়া যায়নি।</p></div>
      @endforelse
    </div>

    @if ($courses->hasPages())
      <div class="mt-5 d-flex justify-content-center">{{ $courses->links() }}</div>
    @endif
  </div>
</section>
@endsection
