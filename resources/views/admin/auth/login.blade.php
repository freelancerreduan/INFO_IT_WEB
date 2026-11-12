<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login · {{ setting('institute_name') }}</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
@include('admin.layouts.partials.styles')
<style>
  body{background:#fff;}
  .auth{display:flex;min-height:100vh;}

  /* ---------- left showcase ---------- */
  .auth-side{
    position:relative;overflow:hidden;flex:1 1 56%;
    background:linear-gradient(160deg,#14151F 0%,#1C1E2C 55%,#231F4A 100%);
    color:#fff;padding:44px 56px;display:flex;flex-direction:column;
  }
  .auth-side::before{ /* dotted grid */
    content:"";position:absolute;inset:0;
    background-image:radial-gradient(rgba(255,255,255,.07) 1px,transparent 1px);
    background-size:22px 22px;mask-image:linear-gradient(180deg,#000 30%,transparent 95%);
  }
  .blob{position:absolute;border-radius:50%;filter:blur(70px);opacity:.55;pointer-events:none;}
  .blob.b1{width:380px;height:380px;background:#4C3AE3;top:-120px;right:-80px;animation:float 14s ease-in-out infinite;}
  .blob.b2{width:300px;height:300px;background:#0F8B8D;bottom:-110px;left:-60px;opacity:.35;animation:float 18s ease-in-out infinite reverse;}
  @keyframes float{0%,100%{transform:translate(0,0);}50%{transform:translate(-30px,25px);}}
  .auth-side > *:not(.blob){position:relative;z-index:1;}
  .auth-brand{display:flex;align-items:center;gap:12px;}
  .auth-brand .brand-mark{width:42px;height:42px;border-radius:11px;font-size:16px;box-shadow:0 8px 24px -8px rgba(76,58,227,.8);}
  .auth-brand .bt{font-family:'Sora';font-weight:600;font-size:16px;line-height:1.15;}
  .auth-brand .bt small{display:block;font-family:'Inter';font-weight:400;font-size:12px;color:var(--side-text);}
  .auth-hero{margin-top:auto;margin-bottom:auto;max-width:520px;padding:40px 0;}
  .auth-chip{display:inline-flex;align-items:center;gap:7px;padding:6px 12px;border-radius:99px;font-size:12px;font-weight:600;
    background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:#D9D7FF;}
  .auth-chip .dot{width:7px;height:7px;border-radius:50%;background:#3DDC97;box-shadow:0 0 0 4px rgba(61,220,151,.18);}
  .auth-hero h1{font-size:40px;font-weight:700;line-height:1.15;letter-spacing:-.02em;margin:18px 0 14px;}
  .auth-hero h1 span{background:linear-gradient(90deg,#A99BFF,#6FE3E1);-webkit-background-clip:text;background-clip:text;color:transparent;}
  .auth-hero p{color:var(--side-text);font-size:15px;line-height:1.6;margin:0 0 30px;}
  .feat{display:flex;gap:14px;align-items:flex-start;margin-bottom:16px;}
  .feat .fi{width:38px;height:38px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:#B9AEFF;font-size:16px;}
  .feat b{display:block;font-size:14px;font-weight:600;}
  .feat span{font-size:12.8px;color:var(--side-text);}

  /* mini dashboard illustration */
  .mini{margin-top:26px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:16px 18px;
    backdrop-filter:blur(6px);max-width:420px;}
  .mini-top{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:var(--side-text);margin-bottom:12px;}
  .mini-top b{color:#fff;font-family:'Sora';font-size:13px;}
  .bars{display:flex;align-items:flex-end;gap:8px;height:74px;}
  .bars i{flex:1;border-radius:5px 5px 2px 2px;background:linear-gradient(180deg,#7B6BFF,#4C3AE3);opacity:.9;
    transform-origin:bottom;animation:grow .9s cubic-bezier(.2,.8,.2,1) both;}
  .bars i:nth-child(even){background:linear-gradient(180deg,#5FD4D1,#0F8B8D);}
  @keyframes grow{from{transform:scaleY(.1);opacity:0;}to{transform:scaleY(1);opacity:.9;}}
  .auth-foot{font-size:12px;color:#6F7290;}

  /* ---------- right form ---------- */
  .auth-main{flex:1 1 44%;display:flex;align-items:center;justify-content:center;padding:40px 24px;background:
    radial-gradient(600px 300px at 100% 0%,rgba(76,58,227,.06),transparent 70%),#fff;}
  .auth-card{width:100%;max-width:400px;animation:fadein .4s ease;}
  .auth-card .mobile-brand{display:none;align-items:center;gap:10px;margin-bottom:28px;}
  .auth-card .mobile-brand .bt{font-family:'Sora';font-weight:600;font-size:15px;color:var(--ink);}
  .auth-card h2{font-size:26px;font-weight:700;letter-spacing:-.01em;margin:0 0 6px;}
  .auth-card .lead-sub{color:var(--muted);font-size:14px;margin-bottom:28px;}
  .field{position:relative;}
  .field > i.lead-ic{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9A9DB5;font-size:16px;pointer-events:none;transition:color .15s;}
  .field .form-control{height:48px;padding-left:42px;border-radius:11px;font-size:14px;background:#FAFAFD;}
  .field .form-control:focus{background:#fff;}
  .field .form-control:focus ~ i.lead-ic, .field:focus-within > i.lead-ic{color:var(--accent);}
  .field .toggle-pw{position:absolute;right:6px;top:50%;transform:translateY(-50%);border:none;background:transparent;
    width:38px;height:38px;border-radius:9px;color:#9A9DB5;}
  .field .toggle-pw:hover{background:#F0F0F7;color:var(--ink);}
  .field .form-control.has-toggle{padding-right:46px;}
  .form-check-input:checked{background-color:var(--accent);border-color:var(--accent);}
  .form-check-input:focus{box-shadow:0 0 0 3px var(--accent-soft);border-color:var(--accent);}
  .btn-login{height:48px;border-radius:11px;font-size:14.5px;display:flex;align-items:center;justify-content:center;gap:8px;
    box-shadow:0 10px 24px -12px rgba(76,58,227,.8);transition:transform .15s,box-shadow .15s,background .15s;}
  .btn-login:hover{transform:translateY(-1px);box-shadow:0 14px 28px -12px rgba(76,58,227,.9);}
  .btn-login .spinner-border{width:16px;height:16px;border-width:2px;display:none;}
  .btn-login.loading .spinner-border{display:inline-block;}
  .btn-login.loading .bi{display:none;}
  .auth-alert{border-radius:11px;padding:12px 14px;font-size:13px;display:flex;gap:10px;align-items:flex-start;margin-bottom:20px;}
  .auth-alert.ok{background:var(--ok-soft);color:var(--ok);}
  .auth-alert.bad{background:var(--bad-soft);color:var(--bad);}
  .secure{display:flex;align-items:center;justify-content:center;gap:6px;font-size:12px;color:var(--muted);margin-top:26px;}
  .back-link{font-size:13px;font-weight:600;color:var(--accent);}
  .back-link:hover{color:var(--accent-ink);}

  @media (max-width:991.98px){
    .auth-side{display:none;}
    .auth-card .mobile-brand{display:flex;}
  }
</style>
</head>
<body>
<div class="auth">

  {{-- ============ Showcase ============ --}}
  <aside class="auth-side">
    <span class="blob b1"></span><span class="blob b2"></span>

    <div class="auth-brand">
      <div class="brand-mark">{{ initials(setting('institute_name')) }}</div>
      <div class="bt">{{ setting('institute_name') }}<small>Admin Console</small></div>
    </div>

    <div class="auth-hero">
      <span class="auth-chip"><span class="dot"></span>Secure admin area</span>
      <h1>Run your whole institute <span>from one place.</span></h1>
      <p>Students, course orders, payments, gallery and financial reports — everything your team needs, always up to date.</p>

      <div class="feat">
        <div class="fi"><i class="bi bi-people"></i></div>
        <div><b>Students &amp; enrollments</b><span>Register students, assign courses and track every order.</span></div>
      </div>
      <div class="feat">
        <div class="fi"><i class="bi bi-cash-coin"></i></div>
        <div><b>Payments &amp; costing</b><span>bKash, Nagad, card or cash — earnings and profit calculated live.</span></div>
      </div>
      <div class="feat">
        <div class="fi"><i class="bi bi-bar-chart-line"></i></div>
        <div><b>Reports &amp; exports</b><span>Monthly and yearly reports, exported to Excel in one click.</span></div>
      </div>

      <div class="mini" aria-hidden="true">
        <div class="mini-top"><b>Financial Overview</b><span>Monthly</span></div>
        <div class="bars">
          @foreach ([38, 52, 46, 64, 58, 72, 68, 84, 76, 92, 88, 100] as $i => $h)
            <i style="height:{{ $h }}%;animation-delay:{{ $i * 60 }}ms"></i>
          @endforeach
        </div>
      </div>
    </div>

    <div class="auth-foot">© {{ date('Y') }} {{ setting('institute_name') }}. All rights reserved.</div>
  </aside>

  {{-- ============ Form ============ --}}
  <main class="auth-main">
    <div class="auth-card">
      <div class="mobile-brand">
        <div class="brand-mark">{{ initials(setting('institute_name')) }}</div>
        <div class="bt">{{ setting('institute_name') }}</div>
      </div>

      <h2>Sign in to Admin</h2>
      <div class="lead-sub">Welcome back! Please enter your admin credentials.</div>

      @if (session('logged_out'))
        <div class="auth-alert ok"><i class="bi bi-check-circle-fill"></i><div>You have been logged out successfully.</div></div>
      @endif
      @if ($errors->any())
        <div class="auth-alert bad"><i class="bi bi-exclamation-circle-fill"></i><div>{{ $errors->first() }}</div></div>
      @endif

      <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm" novalidate>
        @csrf
        <label class="form-label" for="email">Email address</label>
        <div class="field mb-3">
          <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus
                 class="form-control @error('email') is-invalid @enderror" placeholder="admin@example.com">
          <i class="bi bi-envelope lead-ic"></i>
        </div>

        <label class="form-label" for="password">Password</label>
        <div class="field mb-3">
          <input type="password" id="password" name="password" autocomplete="current-password" required
                 class="form-control has-toggle @error('password') is-invalid @enderror" placeholder="Enter your password">
          <i class="bi bi-lock lead-ic"></i>
          <button type="button" class="toggle-pw" onclick="togglePw()" aria-label="Show password"><i class="bi bi-eye" id="pwIcon"></i></button>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
          <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
            <label class="form-check-label small" for="remember">Remember me</label>
          </div>
          <a href="{{ url('/') }}" class="back-link"><i class="bi bi-arrow-left me-1"></i>Website</a>
        </div>

        <button type="submit" class="btn btn-accent w-100 btn-login" id="loginBtn">
          <span class="spinner-border" role="status"></span>
          <span>Sign in</span><i class="bi bi-arrow-right"></i>
        </button>
      </form>

      <div class="secure"><i class="bi bi-shield-lock"></i>Protected area — authorized staff only</div>
    </div>
  </main>
</div>

<script>
function togglePw(){
  const i = document.getElementById('password'), ic = document.getElementById('pwIcon');
  const show = i.type === 'password';
  i.type = show ? 'text' : 'password';
  ic.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
}
document.getElementById('loginForm').addEventListener('submit', function(){
  const b = document.getElementById('loginBtn'); b.classList.add('loading'); b.disabled = true;
});
</script>
</body>
</html>
