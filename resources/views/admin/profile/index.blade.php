@extends('admin.layouts.app')

@section('title', 'Profile')
@section('subtitle', 'Your admin account.')

@section('content')
<section class="page-sec show" id="page-profile">
  <div class="page-header"><div><h2>Profile</h2><p>Update your name, email and password.</p></div></div>
  <form method="POST" action="{{ route('admin.profile.update') }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-lg-7">
        <div class="card">
          <div class="card-header"><div class="section-title">Account Information</div></div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Name</label>
                <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card">
          <div class="card-header"><div class="section-title">Change Password</div></div>
          <div class="card-body">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-control mb-1 @error('current_password') is-invalid @enderror" name="current_password">
            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <label class="form-label mt-2">New Password</label>
            <input type="password" class="form-control mb-1 @error('password') is-invalid @enderror" name="password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <label class="form-label mt-2">Confirm New Password</label>
            <input type="password" class="form-control" name="password_confirmation">
          </div>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-accent mt-3 px-4"><i class="bi bi-check2 me-1"></i>Save Changes</button>
  </form>
</section>
@endsection
