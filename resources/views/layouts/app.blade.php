<!DOCTYPE html>
<html lang="bn">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('institute_name', 'INFO IT TRAINING CENTER'))</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
      href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    @stack('page-styles')
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @stack('styles')
  </head>

  <body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-vertex sticky-top">
      <div class="container container-xl">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
          <img src="{{ asset('img/logo.jpg') }}" alt="{{ setting('institute_name') }}" class="img-fluid" style="max-height: 60px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="মেনু খুলুন">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
          <div class="nav-links d-flex flex-column flex-lg-row mt-3 mt-lg-0">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
            <a href="{{ route('courses.index') }}" @class(['active' => request()->routeIs('courses.*')]) @if(request()->routeIs('courses.*')) aria-current="page" @endif> Courses </a>
            <a href="{{ route('home') }}#seminar">Seminer</a>
            <a href="{{ route('home') }}#videos">Class Video</a>
            <a href="{{ route('gallery') }}" @class(['active' => request()->routeIs('gallery')]) @if(request()->routeIs('gallery')) aria-current="page" @endif>Gallary</a>
            <a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')]) @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
          </div>
          <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 ms-lg-4">
            <!-- <a href="tel:{{ setting('phone', '+8801723296130') }}" class="nav-phone d-none d-xl-flex"><i class="bi bi-telephone-fill"></i> {{ bn_num(setting('phone_display', '+880 9612 345 678')) }}</a> -->
            @auth
              {{-- login thakle direct dashboard e dhukar button --}}
              <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="btn btn-navy btn-sm"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
              <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
              </form>
            @else
              <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-person me-1"></i>Login</a>
              <a href="{{ route('apply') }}" class="btn btn-navy btn-sm">Admission</a>
            @endauth
          </div>
        </div>
      </div>
    </nav>

    @yield('content')

    <!-- ================= FOOTER ================= -->
    <footer class="site-footer" id="footer">
      <div class="container container-xl">
        <div class="row g-4">
          <div class="col-lg-4">
            <a href="{{ route('home') }}" class="footer-logo"><img src="{{ asset('img/logo.jpg') }}" alt="{{ setting('institute_name') }}"></a>
            <p>ওয়েব ডেভেলপমেন্ট, ডিজাইন, ডিজিটাল মার্কেটিং ও আইটি বিষয়ে ক্যারিয়ারমুখী প্রশিক্ষণ — ইন্ডাস্ট্রিতে কাজ করা অভিজ্ঞ প্রশিক্ষকদের হাতে, বাস্তব প্রজেক্টের মাধ্যমে।</p>
          </div>
          <div class="col-6 col-lg-3">
            <h5>কোর্সসমূহ</h5>
            @foreach ($footerCourses as $fc)
              <a href="{{ route('courses.show', $fc->slug) }}">{{ $fc->title }}</a>
            @endforeach
          </div>
          <div class="col-6 col-lg-2">
            <h5>প্রতিষ্ঠান</h5>
            <a href="{{ route('home') }}#seminar">ফ্রি সেমিনার</a>
            <a href="{{ route('home') }}#videos">ক্লাস ভিডিও</a>
            <a href="{{ route('gallery') }}">গ্যালারি</a>
            <a href="{{ route('apply') }}">ভর্তি হোন</a>
            <a href="{{ route('contact') }}">যোগাযোগ</a>
            <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard')) : route('login') }}">{{ auth()->check() ? 'ড্যাশবোর্ড' : 'লগইন' }}</a>
          </div>
          <div class="col-lg-3">
            <h5>আমাদের ঠিকানা</h5>
            <p class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i>{{ setting('address', 'লেভেল ৩, বাড়ি ২৭, রোড ১১, বনানী, ঢাকা ১২১৩') }}</p>
            <p class="mb-2"><i class="bi bi-telephone-fill me-2"></i>{{ bn_num(setting('phone_display', '+880 9612 345 678')) }}</p>
            <p class="mb-2"><i class="bi bi-envelope-fill me-2"></i>{{ setting('support_email', 'info@infoittraining.com') }}</p>
            <p class="mb-0"><i class="bi bi-clock-fill me-2"></i>{{ setting('office_hours', 'শনি–বৃহস্পতি, সকাল ৯টা – রাত ৯টা') }}</p>
          </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2">
          <span>&copy; {{ bn_num(date('Y')) }} {{ setting('institute_name') }}। সর্বস্বত্ব সংরক্ষিত।</span>
          <div class="d-flex gap-3">
            <a href="{{ setting('facebook', '#') }}" style="margin:0;" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="{{ setting('instagram', '#') }}" style="margin:0;" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="{{ setting('linkedin', '#') }}" style="margin:0;" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="{{ setting('youtube', '#') }}" style="margin:0;" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    @stack('scripts')
  </body>

</html>
