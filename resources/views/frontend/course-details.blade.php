@extends('layouts.app')

@section('title', $course->title . ' — ' . setting('institute_name'))

@section('content')
<!-- ================= COURSE DETAILS ================= -->
<section class="section-pad" style="padding-top:2.5rem;">
  <div class="container container-xl">
    <nav style="font-size:.82rem;color:var(--ink-soft);margin-bottom:1.4rem;">
      <a href="{{ route('home') }}" style="color:var(--ink-soft);">হোম</a> /
      <a href="{{ route('courses.index') }}" style="color:var(--ink-soft);">কোর্সসমূহ</a> /
      <span style="color:var(--ink);font-weight:600;">{{ $course->title }}</span>
    </nav>

    <div class="row g-5">
      <!-- Main content -->
      <div class="col-lg-8">
        <span class="badge-cat" style="position:static;display:inline-block;background:var(--navy-800);margin-bottom:1rem;">{{ $course->category }}</span>
        <h1 style="font-size:2rem;font-weight:700;margin-bottom:.8rem;">{{ $course->title }}</h1>
        <div class="d-flex flex-wrap align-items-center gap-3 mb-4" style="font-size:.88rem;color:var(--ink-soft);">
          <span class="rating" style="color:var(--gold);font-weight:700;"><i class="bi bi-star-fill"></i> {{ bn_num($course->rating) }}</span>
          <span><i class="bi bi-people"></i> {{ bn_num(number_format($course->enrollments_count)) }} জন শিক্ষার্থী</span>
          @if ($course->instructor)
            <span><i class="bi bi-person-badge"></i> {{ $course->instructor }}@if($course->instructor_role), {{ $course->instructor_role }}@endif</span>
          @endif
        </div>
        <div class="hero-media mb-4" style="box-shadow:0 20px 40px -20px rgba(10,26,61,.3);">
          <img src="{{ $course->thumb_url }}" alt="{{ $course->title }}" style="height:320px;">
        </div>

        @if ($course->long_desc)
          <div class="detail-block">
            <h2>কোর্স সম্পর্কে</h2>
            <p style="font-size:.95rem;color:var(--ink-soft);line-height:1.7;">{{ $course->long_desc }}</p>
          </div>
        @endif

        @if (! empty($course->outcomes))
          <div class="detail-block">
            <h2>যা যা শিখবেন</h2>
            <ul class="outcome-list list-unstyled">
              @foreach ($course->outcomes as $o)
                <li><i class="bi bi-check-circle-fill"></i> {{ $o }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if (! empty($course->curriculum))
          <div class="detail-block">
            <h2>কোর্স কারিকুলাম</h2>
            <ul class="curriculum-list list-unstyled">
              @foreach ($course->curriculum as $i => $m)
                <li><i class="bi bi-play-circle"></i> মডিউল {{ bn_num($i + 1) }}: {{ $m }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if ($course->instructor)
          <div class="detail-block">
            <h2>আপনার প্রশিক্ষক</h2>
            <div class="d-flex align-items-center gap-3">
              <div class="avatar-init" style="margin-bottom:0;">{{ mb_substr($course->instructor, 0, 1) }}</div>
              <div>
                <div style="font-weight:700;">{{ $course->instructor }}</div>
                <div style="font-size:.85rem;color:var(--mint-dark);font-weight:600;">{{ $course->instructor_role }}</div>
              </div>
            </div>
          </div>
        @endif

        @if ($courseSeminars->isNotEmpty())
          <div class="detail-block">
            <h2>এই কোর্সের ফ্রি সেমিনার</h2>
            @foreach ($courseSeminars as $s)
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 py-2" style="border-bottom:1px solid var(--line);">
                <div>
                  <div style="font-weight:600;">{{ $s->date_text }} · {{ $s->time_label }}</div>
                  <div style="font-size:.84rem;color:var(--ink-soft);">{{ $s->venue_label }} সেমিনার</div>
                </div>
                <a href="{{ $s->register_url }}" class="btn-ghost-navy btn-sm">রেজিস্ট্রেশন করুন</a>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Sticky sidebar -->
      <div class="col-lg-4">
        <div style="position:sticky;top:6.5rem;">
          <div class="sidebar-card">
            <div class="d-flex align-items-baseline gap-2 mb-1">
              <span style="font-size:1.7rem;font-weight:700;font-family:'Anek Bangla';">{{ bn_money($course->price) }}</span>
              @if ($course->old_price)
                <span style="font-size:.9rem;color:#b3ae9f;text-decoration:line-through;">{{ bn_money($course->old_price) }}</span>
              @endif
            </div>
            <div style="font-size:.8rem;color:var(--ink-soft);margin-bottom:1.2rem;">কিস্তিতে পেমেন্টের সুবিধা আছে</div>
            <a href="{{ route('apply', ['course' => $course->slug]) }}" class="btn-mint w-100 text-center d-block mb-3">এই কোর্সে ভর্তি হোন</a>
            @if ($course->duration)<div class="sidebar-row"><span>মেয়াদ</span><b>{{ $course->duration }}</b></div>@endif
            @if ($course->classes)<div class="sidebar-row"><span>ক্লাস সংখ্যা</span><b>{{ $course->classes }}</b></div>@endif
            @if ($course->mode)<div class="sidebar-row"><span>ক্লাসের ধরন</span><b>{{ $course->mode }}</b></div>@endif
            @if ($course->certificate)<div class="sidebar-row"><span>সার্টিফিকেট</span><b style="text-align:right;max-width:160px;">{{ $course->certificate }}</b></div>@endif
          </div>
          <div class="mt-3" style="font-size:.8rem;color:var(--ink-soft);text-align:center;">
            <i class="bi bi-telephone-fill"></i> কোনো প্রশ্ন? কল করুন {{ bn_num(setting('phone_display', '+880 9612 345 678')) }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= OTHER PROGRAMS ================= -->
@if ($related->isNotEmpty())
  <section class="section-pad" style="background:var(--cream);padding-top:0;">
    <div class="container container-xl">
      <div class="eyebrow">আরও দেখুন</div>
      <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1.6rem;">আপনার পছন্দ হতে পারে এমন আরও কোর্স</h2>
      <div class="row g-4">
        @foreach ($related as $c)
          @include('frontend.partials.course-card', ['c' => $c, 'hideFacts' => true])
        @endforeach
      </div>
    </div>
  </section>
@endif
@endsection
