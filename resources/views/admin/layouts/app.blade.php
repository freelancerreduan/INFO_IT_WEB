<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ setting('institute_name') }} · @yield('title', 'Admin')</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
@include('admin.layouts.partials.styles')
@stack('styles')
</head>
<body>

<div class="app-shell">

  <!-- ============ DESKTOP SIDEBAR ============ -->
  <aside class="sidebar">
    @include('admin.layouts.partials.brand')
    <nav class="side-scroll" id="sideNav">
      @include('admin.layouts.partials.sidebar-nav', ['navKey' => 'd'])
    </nav>
    <div class="side-foot">
      <div class="side-link" onclick="adminLogout()"><i class="bi bi-box-arrow-right side-ic"></i>Logout</div>
    </div>
  </aside>

  <div class="main-col">

    <!-- ============ TOPBAR ============ -->
    @include('admin.layouts.partials.topbar')

    <!-- ============ CONTENT ============ -->
    <main class="content" id="content">
      @include('admin.partials.flash')
      @yield('content')
    </main>
    <footer class="text-center text-muted small py-4">© {{ date('Y') }} {{ setting('institute_name') }} — Admin Panel</footer>
  </div>
</div>

<!-- ============ MOBILE OFFCANVAS SIDEBAR ============ -->
<div class="offcanvas offcanvas-start offcanvas-dark" id="mobileSidebar">
  <div class="offcanvas-header" style="border-bottom:1px solid var(--side-line)">
    @include('admin.layouts.partials.brand', ['extraClass' => 'py-0'])
    <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <nav class="side-scroll" id="sideNavMobile">
      @include('admin.layouts.partials.sidebar-nav', ['navKey' => 'm'])
    </nav>
    <div class="side-foot">
      <div class="side-link" onclick="adminLogout()"><i class="bi bi-box-arrow-right side-ic"></i>Logout</div>
    </div>
  </div>
</div>

<form id="logoutForm" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>

<!-- ============ MODALS ============ -->
@include('admin.layouts.partials.modals')

@include('admin.layouts.partials.scripts')
@stack('scripts')
</body>
</html>
