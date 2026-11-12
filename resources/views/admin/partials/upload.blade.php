{{--
  @include('admin.partials.upload', [
     'zone' => 'studentPhotoZone', 'name' => 'photo', 'icon' => 'bi-cloud-arrow-up',
     'text' => 'Click to upload photo', 'hint' => 'PNG or JPG, up to 2MB', 'existing' => $url|null ])
--}}
<div id="{{ $zone }}">
  <label class="upload-box d-block {{ !empty($existing) ? 'd-none' : '' }}" for="{{ $zone }}Input" data-upload-label>
    <i class="bi {{ $icon }} fs-3" style="color:var(--accent)"></i>
    <div class="fw-semibold mt-2 small">{!! $text !!}</div>
    <div class="text-muted small">{{ $hint }}</div>
  </label>
  <div class="upload-preview {{ empty($existing) ? 'd-none' : '' }}" data-upload-preview>
    <img src="{{ $existing ?? '' }}" alt="preview">
    <button type="button" class="upload-remove" onclick="resetUpload('{{ $zone }}')"><i class="bi bi-x-lg"></i></button>
  </div>
  <input type="file" name="{{ $name }}" accept="image/*" id="{{ $zone }}Input" class="d-none" onchange="handlePhoto(event,'{{ $zone }}')">
</div>
@error($name)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
