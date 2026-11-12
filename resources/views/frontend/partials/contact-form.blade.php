{{--
  যোগাযোগ ফর্ম — home page ও contact page দুই জায়গাতেই একই ফিল্ড।
  $prefix = ইউনিক id prefix (home / cp), $source = home | contact
--}}
@php $prefix = $prefix ?? 'home'; $source = $source ?? 'home'; @endphp

<form class="js-contact-form" method="POST" action="{{ route('contact.store') }}" novalidate>
  @csrf
  <input type="hidden" name="source" value="{{ $source }}">
  <input type="hidden" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">

  <div class="cf-alert" aria-live="polite">
    @if (session('contact_success') && session('contact_source') === $source)
      <div class="alert alert-success d-flex align-items-start gap-2 mb-4" role="status" style="border-radius:12px;">
        <i class="bi bi-check-circle-fill mt-1"></i>
        <div>{!! session('contact_success') !!}</div>
      </div>
    @endif
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <label class="cf-label" for="{{ $prefix }}Name">আপনার নাম<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-person"></i>
        <input class="cf-control @error('name') cf-invalid @enderror" id="{{ $prefix }}Name" name="name" type="text"
               value="{{ old('name') }}" placeholder="যেমন: রাকিব হাসান" autocomplete="name" required>
        <div class="cf-error">@error('name'){{ $message }}@else অনুগ্রহ করে আপনার নাম লিখুন।@enderror</div>
      </div>
    </div>
    <div class="col-md-6">
      <label class="cf-label" for="{{ $prefix }}Phone">মোবাইল নম্বর<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-phone"></i>
        <input class="cf-control @error('phone') cf-invalid @enderror" id="{{ $prefix }}Phone" name="phone" type="tel"
               inputmode="tel" value="{{ old('phone') }}" placeholder="০১XXXXXXXXX" autocomplete="tel" required>
        <div class="cf-error">@error('phone'){{ $message }}@else সঠিক মোবাইল নম্বর দিন (যেমন: ০১৭১২৩৪৫৬৭৮)।@enderror</div>
      </div>
    </div>
    <div class="col-12">
      <label class="cf-label" for="{{ $prefix }}Email">ইমেইল<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-envelope"></i>
        <input class="cf-control @error('email') cf-invalid @enderror" id="{{ $prefix }}Email" name="email" type="email"
               value="{{ old('email') }}" placeholder="example@gmail.com" autocomplete="email" required>
        <div class="cf-error">@error('email'){{ $message }}@else সঠিক ইমেইল ঠিকানা দিন।@enderror</div>
      </div>
    </div>
    <div class="col-12">
      <label class="cf-label" for="{{ $prefix }}Message">আপনার বার্তা<span class="req">*</span></label>
      <div class="cf-input-wrap">
        <i class="bi bi-chat-left-text"></i>
        <textarea class="cf-control @error('message') cf-invalid @enderror" id="{{ $prefix }}Message" name="message"
                  rows="{{ $rows ?? 5 }}" placeholder="কোন কোর্স নিয়ে জানতে চান, বা আপনার প্রশ্নটি লিখুন..." required>{{ old('message') }}</textarea>
        <div class="cf-error">@error('message'){{ $message }}@else অনুগ্রহ করে আপনার বার্তা লিখুন।@enderror</div>
      </div>
    </div>
  </div>
  <div class="cf-foot">
    <span class="cf-note"><i class="bi bi-shield-lock-fill"></i> আপনার তথ্য সম্পূর্ণ গোপন রাখা হবে।</span>
    <button type="submit" class="btn-mint cf-submit">বার্তা পাঠান <i class="bi bi-send-fill"></i></button>
  </div>
</form>
