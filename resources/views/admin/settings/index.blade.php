@extends('admin.layouts.app')

@section('title', 'Settings')
@section('subtitle', 'Institute configuration.')

@section('content')
<section class="page-sec show" id="page-settings">
  <div class="page-header"><div><h2>Settings</h2><p>Institute preferences and configuration.</p></div></div>
  <div class="card"><div class="card-body">
    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf
      @method('PUT')
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Institute Name</label>
          <input class="form-control @error('institute_name') is-invalid @enderror" name="institute_name" value="{{ old('institute_name', $settings['institute_name']) }}">
          @error('institute_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6"><label class="form-label">Support Email</label>
          <input class="form-control @error('support_email') is-invalid @enderror" name="support_email" value="{{ old('support_email', $settings['support_email']) }}">
          @error('support_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6"><label class="form-label">Currency</label>
          <select class="form-select" name="currency">
            @foreach ($currencies as $code => $label)
              <option value="{{ $code }}" @selected(old('currency', $settings['currency']) === $code)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6"><label class="form-label">Timezone</label>
          <select class="form-select" name="timezone">
            @foreach ($timezones as $tz => $label)
              <option value="{{ $tz }}" @selected(old('timezone', $settings['timezone']) === $tz)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <hr class="my-4">
      <div class="section-title mb-1">Website Information</div>
      <div class="section-sub mb-3">This information is shown in the website navbar, footer, contact page and seminar poster.</div>
      <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Phone (link)</label>
          <input class="form-control" name="phone" value="{{ old('phone', $settings['phone'] ?? '+8801723296130') }}" placeholder="+8801723296130"></div>
        <div class="col-md-4"><label class="form-label">Phone (shown on the website)</label>
          <input class="form-control" name="phone_display" value="{{ old('phone_display', $settings['phone_display'] ?? '+880 9612 345 678') }}"></div>
        <div class="col-md-4"><label class="form-label">Phone (seminar poster)</label>
          <input class="form-control" name="phone_short" value="{{ old('phone_short', $settings['phone_short'] ?? '০৯৬১২-৩৪৫৬৭৮') }}"></div>
        <div class="col-md-4"><label class="form-label">WhatsApp Number</label>
          <input class="form-control" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '8801712345678') }}" placeholder="8801712345678"></div>
        <div class="col-md-8"><label class="form-label">Address</label>
          <input class="form-control" name="address" value="{{ old('address', $settings['address'] ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Office Hours</label>
          <input class="form-control" name="office_hours" value="{{ old('office_hours', $settings['office_hours'] ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Office Note</label>
          <input class="form-control" name="office_note" value="{{ old('office_note', $settings['office_note'] ?? '') }}" placeholder="শুক্রবার বন্ধ (সেমিনারের দিন ছাড়া)"></div>
        <div class="col-md-6"><label class="form-label">Website (shown on the poster)</label>
          <input class="form-control" name="website" value="{{ old('website', $settings['website'] ?? 'www.infoittraining.com') }}"></div>
        <div class="col-md-6"><label class="form-label">Google Map Location</label>
          <input class="form-control" name="map_query" value="{{ old('map_query', $settings['map_query'] ?? 'Banani, Dhaka 1213') }}"></div>
        <div class="col-md-3"><label class="form-label">Facebook</label>
          <input class="form-control" name="facebook" value="{{ old('facebook', $settings['facebook'] ?? '#') }}"></div>
        <div class="col-md-3"><label class="form-label">Instagram</label>
          <input class="form-control" name="instagram" value="{{ old('instagram', $settings['instagram'] ?? '#') }}"></div>
        <div class="col-md-3"><label class="form-label">LinkedIn</label>
          <input class="form-control" name="linkedin" value="{{ old('linkedin', $settings['linkedin'] ?? '#') }}"></div>
        <div class="col-md-3"><label class="form-label">YouTube</label>
          <input class="form-control" name="youtube" value="{{ old('youtube', $settings['youtube'] ?? '#') }}"></div>
      </div>

      <hr class="my-4">
      <div class="section-title mb-1">Home Page Counter</div>
      <div class="section-sub mb-3">The 4 numbers in the hero section.</div>
      <div class="row g-3">
        <div class="col-md-3"><label class="form-label">Trained Students</label>
          <input type="number" class="form-control" name="stat_students" value="{{ old('stat_students', $settings['stat_students'] ?? 10000) }}"></div>
        <div class="col-md-3"><label class="form-label">Trainers</label>
          <input type="number" class="form-control" name="stat_trainers" value="{{ old('stat_trainers', $settings['stat_trainers'] ?? 50) }}"></div>
        <div class="col-md-3"><label class="form-label">Courses</label>
          <input type="number" class="form-control" name="stat_courses" value="{{ old('stat_courses', $settings['stat_courses'] ?? 30) }}"></div>
        <div class="col-md-3"><label class="form-label">Satisfaction (%)</label>
          <input type="number" class="form-control" name="stat_satisfaction" value="{{ old('stat_satisfaction', $settings['stat_satisfaction'] ?? 95) }}"></div>
      </div>

      <button type="submit" class="btn btn-accent mt-4">Save Changes</button>
    </form>
  </div></div>
</section>
@endsection
