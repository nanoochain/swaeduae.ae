<!doctype html><meta charset="utf-8"><title>Login</title>
<style>body{font-family:system-ui;margin:3rem}form{max-width:420px}label{display:block;margin:.6rem 0}</style>
<h2>Sign in</h2>
@if ($errors->any()) <div style="color:#c00">{{ $errors->first() }}</div> @endif
<form method="POST" action="{{ url('/login') }}">
  @csrf
  <label>Email <input type="email" name="email" required autofocus></label>
  <label>Password <input type="password" name="password" required></label>
  <button type="submit">Sign in</button>
@include('auth.partials.social-buttons')
<div class="text-center text-sm mt-2">@if(\Illuminate\Support\Facades\Route::has('password.request'))<a href="{{ route('password.request') }}">Forgot your password?</a>@else<a href="/forgot-password">Forgot your password?</a>@endif</div>
</form>
