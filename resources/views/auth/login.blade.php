@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="container py-5" style="max-width:460px;">
  <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
    <h3 class="font-display fw-bold mb-1">Welcome back</h3>
    <p class="text-muted small mb-4">Login to your Info-IT account.</p>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label small" for="remember">Remember me</label>
      </div>
      <button class="btn btn-brand w-100 py-2">Login</button>
    </form>
    <p class="text-center small text-muted mt-4 mb-0">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
    <div class="alert alert-light border small mt-4 mb-0">
      Demo admin: <b>admin@vertex.test</b> / password<br>
      Demo student: <b>student@vertex.test</b> / password
    </div>
  </div>
</div>
@endsection
