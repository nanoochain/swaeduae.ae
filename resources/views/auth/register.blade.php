@php($type = $type ?? (request("type") ?? "volunteer"))
@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width:520px">
  <h2 class="mb-3">
    {{ $type === 'org' ? __('Create Organization Account') : ($type === 'volunteer' ? __('Create Volunteer Account') : __('Register')) }}
  </h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ url('/register') }}">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}"/>

    <div class="mb-3">
      <label class="form-label">{{ __('Full name') }}</label>
      <input class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus>
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Email') }}</label>
      <input class="form-control" type="email" name="email" value="{{ old('email') }}" required dir="ltr">
    </div>

    <div class="mb-3">
      <label class="form-label">{{ __('Password') }}</label>
      <input class="form-control" type="password" name="password" required>
    </div>

    <button class="btn btn-primary w-100" type="submit">{{ __('Create account') }}</button>
  @include('auth.partials.social-buttons')
<div class="text-center text-sm mt-2">@if(\Illuminate\Support\Facades\Route::has('password.request'))<a href="{{ route('password.request') }}">Forgot your password?</a>@else<a href="/forgot-password">Forgot your password?</a>@endif</div>
    @include("components.honeypot")
</form>

  <div class="mt-3 text-center">
    <a href="{{ route('login', ['type'=>$type]) }}">{{ __('Already have an account? Sign in') }}</a>
  </div>
</div>
@includeIf('auth.partials.vol-social')
@endsection
