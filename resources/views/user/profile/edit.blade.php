@extends('layouts.user')
@section('title', 'My Profile')
@section('subtitle', 'Update your personal information and password.')

@section('content')
<section class="page-sec show">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header"><div class="section-title">Profile Information</div></div>
        <div class="card-body">
          <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="d-flex align-items-center gap-3 mb-4">
              <img src="{{ $user->photo_url }}" alt="" style="width:76px;height:76px;border-radius:50%;object-fit:cover;border:3px solid var(--accent-soft)">
              <div class="flex-fill">
                <label class="form-label">Profile Photo</label>
                <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror">
              </div>
              <div class="col-md-6"><label class="form-label">Parent / Guardian Number</label>
                <input type="text" value="{{ $user->parent_phone ?: '—' }}" class="form-control" disabled>
              </div>
              <div class="col-md-6"><label class="form-label">NID / Birth Reg.</label>
                <input type="text" value="{{ $user->nid ?: '—' }}" class="form-control" disabled>
              </div>
              <div class="col-md-6"><label class="form-label">Joined</label>
                <input type="text" value="{{ $user->created_at?->format('d M Y') }}" class="form-control" disabled>
              </div>
              <div class="col-12"><label class="form-label">Address</label>
                <textarea name="address" rows="2" class="form-control">{{ old('address', $user->address) }}</textarea>
              </div>
            </div>

            <hr class="my-4">
            <div class="section-title mb-1">Change Password</div>
            <p class="cell-sub">Leave empty to keep your current password. (If the admin created your account, your first password is your email — please change it.)</p>
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6"><label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control">
              </div>
            </div>
            <button class="btn btn-accent px-4 mt-4"><i class="bi bi-check2 me-1"></i>Update Profile</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
