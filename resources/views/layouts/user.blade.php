<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'My Dashboard') · {{ setting('institute_name') }}</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
{{-- Admin panel er same design (CSS) --}}
@include('admin.layouts.partials.styles')
<style>
/* ---------- Student panel extra ---------- */
.course-mini{display:flex;align-items:center;gap:10px;}
.course-mini img{width:46px;height:34px;border-radius:7px;object-fit:cover;flex-shrink:0;border:1px solid var(--line);}
.due-text{color:var(--warn);font-weight:600;}
.paid-text{color:var(--ok);font-weight:600;}
.progress-thin{height:6px;border-radius:99px;background:#EEEEF3;overflow:hidden;min-width:80px;}
.progress-thin > div{height:100%;background:var(--ok);border-radius:99px;}
.side-link.new-order{background:rgba(76,58,227,.18);color:#fff;}
.side-link.new-order:hover{background:var(--accent);}
.welcome-card{background:linear-gradient(120deg,var(--accent),#7A6CF0);color:#fff;border:none;}
.welcome-card p{color:rgba(255,255,255,.8);}
</style>
@stack('styles')
</head>
<body>
@php $me = auth()->user(); @endphp

<div class="app-shell">

  <!-- ============ DESKTOP SIDEBAR ============ -->
  <aside class="sidebar">
    @include('user.partials.brand')
    <nav class="side-scroll">
      @include('user.partials.sidebar-nav')
    </nav>
    <div class="side-foot">
      <div class="side-link" onclick="document.getElementById('logoutForm').submit()"><i class="bi bi-box-arrow-right side-ic"></i>Logout</div>
    </div>
  </aside>

  <div class="main-col">

    <!-- ============ TOPBAR ============ -->
    <header class="topbar">
      <button class="icon-btn d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"><i class="bi bi-list"></i></button>
      <div>
        <h1 class="page-title">@yield('title', 'My Dashboard')</h1>
        <div class="page-title-sub">@yield('subtitle')</div>
      </div>
      <div class="ms-auto d-flex align-items-center gap-2">
        <a href="{{ route('courses.index') }}" class="btn btn-accent d-none d-md-inline-flex align-items-center"><i class="bi bi-bag-plus me-1"></i>New Order</a>
        <div class="dropdown">
          <button class="profile-btn dropdown-toggle" data-bs-toggle="dropdown">
            <img src="{{ $me->photo_url }}" class="avatar" style="object-fit:cover" alt="">
            <div class="d-none d-sm-block text-start">
              <div class="profile-name">{{ $me->name }}</div>
              <div class="profile-role">Student</div>
            </div>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2">
            <li><a class="dropdown-item" href="{{ route('user.profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>Website</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="#" onclick="document.getElementById('logoutForm').submit();return false;"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <!-- ============ CONTENT ============ -->
    <main class="content">
      @if (session('status'))
        <div class="flash flash-ok"><i class="bi bi-check-circle-fill"></i>{{ session('status') }}</div>
      @endif
      @include('admin.partials.flash')
      @yield('content')
    </main>
    <footer class="text-center text-muted small py-4">© {{ date('Y') }} {{ setting('institute_name') }} — Student Panel</footer>
  </div>
</div>

<!-- ============ MOBILE OFFCANVAS SIDEBAR ============ -->
<div class="offcanvas offcanvas-start offcanvas-dark" id="mobileSidebar">
  <div class="offcanvas-header" style="border-bottom:1px solid var(--side-line)">
    @include('user.partials.brand', ['extraClass' => 'py-0'])
    <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <nav class="side-scroll">
      @include('user.partials.sidebar-nav')
    </nav>
    <div class="side-foot">
      <div class="side-link" onclick="document.getElementById('logoutForm').submit()"><i class="bi bi-box-arrow-right side-ic"></i>Logout</div>
    </div>
  </div>
</div>

<form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

{{-- Order er payment history modal --}}
<div class="modal fade" id="payHistoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title font-display" id="payHistoryTitle">Payment History</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body" id="payHistoryBody"></div>
      <div class="modal-footer"><button class="btn btn-outline-accent" data-bs-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
const CURRENCY = @json(currency_symbol());
function money(n){ return CURRENCY + Number(n || 0).toLocaleString('en-US'); }
function esc(v){ return String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
function statusPill(s){ return `<span class="status-pill status-${esc(String(s).toLowerCase())}">${esc(s)}</span>`; }

/* data-pay-history='{"title":"","course":"","fee":0,"paid":0,"due":0,"payments":[...]}' */
document.addEventListener('click', e=>{
  const b = e.target.closest('[data-pay-history]');
  if(!b) return;
  e.preventDefault();
  const d = JSON.parse(b.dataset.payHistory);
  document.getElementById('payHistoryTitle').textContent = d.title;
  const rows = d.payments.length ? d.payments.map((p,i)=>`
      <tr>
        <td class="cell-sub">${i+1}</td>
        <td>${esc(p.date)}</td>
        <td class="cell-sub">${esc(p.trx)}</td>
        <td>${esc(p.method)}</td>
        <td class="fw-semibold">${money(p.amount)}</td>
        <td>${statusPill(p.status)}</td>
      </tr>`).join('')
    : `<tr><td colspan="6"><div class="empty-state py-4"><i class="bi bi-inbox"></i><p class="mt-2 mb-0">No payment yet for this course.</p></div></td></tr>`;
  document.getElementById('payHistoryBody').innerHTML = `
    <div class="row g-2 mb-3">
      <div class="col-12"><div class="meta-row"><span>Course</span><span>${esc(d.course)}</span></div></div>
      <div class="col-4"><div class="card p-3 text-center"><div class="stat-label mt-0">Course Price</div><div class="fw-bold font-display">${money(d.fee)}</div></div></div>
      <div class="col-4"><div class="card p-3 text-center"><div class="stat-label mt-0">Paid</div><div class="fw-bold font-display paid-text">${money(d.paid)}</div></div></div>
      <div class="col-4"><div class="card p-3 text-center"><div class="stat-label mt-0">Due</div><div class="fw-bold font-display due-text">${money(d.due)}</div></div></div>
    </div>
    <div class="table-wrap"><table class="dtable" style="min-width:560px">
      <thead><tr><th>#</th><th>Date</th><th>TRX ID</th><th>Method</th><th>Amount</th><th>Status</th></tr></thead>
      <tbody>${rows}</tbody>
    </table></div>`;
  bootstrap.Modal.getOrCreateInstance(document.getElementById('payHistoryModal')).show();
});
</script>
@stack('scripts')
</body>
</html>
