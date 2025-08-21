@extends('layouts.app')

@section('title', __('Register Organization'))

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
          <h4 class="mb-0">{{ __('Register your Organization') }}</h4>
          <small class="text-muted">{{ __('Provide details to create your organization account') }}</small>
        </div>
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" enctype="multipart/form-data" action="{{ route('org.register.submit') }}" enctype="multipart/form-data" enctype="multipart/form-data" class="row g-3">
            @csrf

            <h5 class="mt-2">{{ __('Account Credentials') }}</h5>
            <div class="col-md-6">
              <label class="form-label">{{ __('Email') }} *</label>
  @include('org.auth._logo_field') {{-- Logo upload --}}
              <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">{{ __('Password') }} *</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">{{ __('Confirm Password') }} *</label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <hr class="mt-4"/>

            <h5>{{ __('Organization Details') }}</h5>
            <div class="col-md-6">
              <label class="form-label">{{ __('Organization Name (English)') }} *</label>
              <input type="text" name="name_en" class="form-control" required value="{{ old('name_en') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('Organization Name (Arabic)') }}</label>
              <input type="text" name="name_ar" class="form-control" dir="rtl" value="{{ old('name_ar') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ __('Emirate') }} *</label>
              <select name="emirate" class="form-select" required>
                @php $ems = ['Abu Dhabi','Dubai','Sharjah','Ajman','Umm Al Quwain','Ras Al Khaimah','Fujairah']; @endphp
                <option value="">{{ __('Choose...') }}</option>
                @foreach($ems as $e)<option @selected(old('emirate')===$e)>{{ $e }}</option>@endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ __('Type / Nature of Work') }} *</label>
              <select name="org_type" class="form-select" required>
                @php $types = ['Educational','Cultural','Charitable','Sports','Professional/Specialized','Environmental','Health','Community']; @endphp
                <option value="">{{ __('Choose...') }}</option>
                @foreach($types as $t)<option @selected(old('org_type')===$t)>{{ $t }}</option>@endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ __('Organization Logo') }}</label>
              <input type="file" name="logo" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">{{ __('Mobile Number') }} *</label>
              <input type="text" name="mobile" class="form-control" required value="{{ old('mobile') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ __('Website') }}</label>
              <input type="url" name="website" class="form-control" value="{{ old('website') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ __('Public Email (displayed)') }}</label>
              <input type="email" name="public_email" class="form-control" value="{{ old('public_email') }}">
            </div>

            <div class="col-md-12">
              <label class="form-label">{{ __('Physical Address') }}</label>
              <input type="text" name="address" class="form-control" value="{{ old('address') }}">
            </div>

            <div class="col-md-12">
              <label class="form-label">{{ __('Describe your entity and intended volunteer programs/activities') }}</label>
              <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-12">
              <label class="form-label">{{ __('Volunteer Programs / Activities (optional)') }}</label>
              <textarea name="volunteer_programs" class="form-control" rows="2">{{ old('volunteer_programs') }}</textarea>
            </div>

            <hr class="mt-4"/>

            <h5>{{ __('Primary Contact Person') }}</h5>
            <div class="col-md-4">
              <label class="form-label">{{ __('Name') }} *</label>
              <input type="text" name="contact_person_name" class="form-control" required value="{{ old('contact_person_name') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ __('Email') }} *</label>
              <input type="email" name="contact_person_email" class="form-control" required value="{{ old('contact_person_email') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label">{{ __('Phone') }} *</label>
              <input type="text" name="contact_person_phone" class="form-control" required value="{{ old('contact_person_phone') }}">
            </div>

            <div class="col-md-12 form-check mt-3">
              <input class="form-check-input" type="checkbox" value="1" id="wants_license" name="wants_license" @checked(old('wants_license'))>
              <label class="form-check-label" for="wants_license">
                {{ __('Request Volunteer Licensing Application (e.g., for Abu Dhabi DCD compliance)') }}
              </label>
            </div>

            <div class="col-md-12 form-check mt-2">
              <input class="form-check-input" type="checkbox" value="1" id="accept_tos" name="accept_tos" required>
              <label class="form-check-label" for="accept_tos">
                {{ __('I agree to the Terms of Service') }}
              </label>
            </div>
            <div class="col-md-12 form-check">
              <input class="form-check-input" type="checkbox" value="1" id="accept_policy" name="accept_policy" required>
              <label class="form-check-label" for="accept_policy">
                {{ __('I agree to applicable volunteering policies (e.g., Abu Dhabi)') }}
              </label>
            </div>

            <div class="col-12 mt-3">
              <button class="btn btn-primary px-4" type="submit">{{ __('Create Organization Account') }}</button>
              <a href="{{ route('org.login') }}" class="btn btn-link">{{ __('Already registered? Sign in') }}</a>
            </div>
          <div class="mb-3">
    <label for="license_file" class="form-label">Upload Organization License (PDF/JPG/PNG)</label>
    <input type="file" class="form-control" id="license_file" name="license_file" accept=".pdf,.jpg,.jpeg,.png" required>
    <div class="form-text">Attach your valid organization license document.</div>
</div>

@include('auth.partials.social-buttons')
<div class="text-center text-sm mt-2">@if(\Illuminate\Support\Facades\Route::has('password.request'))<a href="{{ route('password.request') }}">Forgot your password?</a>@else<a href="/forgot-password">Forgot your password?</a>@endif</div>
</form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
{{-- marker: register.blade.php 04:25:07 --}}
