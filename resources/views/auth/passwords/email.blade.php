@extends('layouts.app')
@section('title', __('Forgot Password'))
@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded shadow mt-16">
  <h1 class="text-2xl font-bold mb-6">{{ __('Forgot Your Password?') }}</h1>
  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-4">
      <label class="block mb-1">{{ __('Email') }}</label>
      <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
    </div>
    <button class="btn-primary w-full">{{ __('Send Reset Link') }}</button>
  </form>
</div>
@endsection
