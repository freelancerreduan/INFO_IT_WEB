@if (session('success'))
  <div class="flash flash-ok"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
@endif
@if (session('error'))
  <div class="flash flash-bad"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
@endif
@if ($errors->any())
  <div class="flash flash-bad"><i class="bi bi-exclamation-circle-fill"></i>Please fix the highlighted fields and try again.</div>
@endif
