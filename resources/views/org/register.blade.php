@extends('layouts.app')
@section('title', __('Organization Registration'))

@section('content')
<div class="container py-5">
  <h1 class="mb-4 text-center">{{ __('Register Your Organization') }}</h1>

  <form action="{{ route('org.register.submit') }}" method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width:900px">
    @csrf

    <div class="mb-3">
      <label class="form-label">{{ __('Organization Name (English)') }}</label>
      <input type="text" name="name_en" class="form-control" required value="{{ old('name_en') }}">
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Organization Name (Arabic)') }}</label>
      <input type="text" name="name_ar" class="form-control" dir="rtl" value="{{ old('name_ar') }}">
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Business Email') }}</label>
      <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Organization Logo') }}</label>
      <input type="file" name="logo" accept="image/*" class="form-control">
    <div class="mb-3">
      <label class="form-label">Trade License (PDF/JPG/PNG)</label>
      <input type="file" name="license" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
    </div>
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Password') }}</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Confirm Password') }}</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('License Number') }}</label>
      <input type="text" name="license_number" class="form-control" required value="{{ old('license_number') }}">
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Upload Trade License') }}</label>
      <input type="file" name="license_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Phone') }}</label>
      <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Website') }}</label>
      <input type="url" name="website" class="form-control" value="{{ old('website') }}">
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Address') }}</label>
      <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary">{{ __('Submit Application') }}</button>
    </div>
  </form>
</div>
@endsection
