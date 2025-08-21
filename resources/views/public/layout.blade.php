<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('components.seo')
  <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body class="site">
<header class="site-header">
  <div class="wrap">
    <a class="brand" href="{{ url('/') }}">{{ config('app.name','Swaed UAE') }}</a>
    <nav class="nav">
      <a href="{{ url('/') }}">{{ __('app.nav.home',[],app()->getLocale()) }}</a>
      <a href="{{ route('opportunities.index') }}">Opportunities</a>
      <a href="{{ url('/about') }}">About</a>
      <a href="{{ url('/contact') }}">Contact</a>
    </nav>
    <div class="auth">
      <a class="btn" href="{{ route('login') }}">{{ __('app.auth.signin',[],app()->getLocale()) }}</a>
      <a class="btn btn-primary" href="{{ route('register') }}">{{ __('app.auth.register',[],app()->getLocale()) }}</a>
    </div>
    <div class="lang">
      <a href="{{ request()->fullUrlWithQuery(['lang'=>'en']) }}">EN</a> |
      <a href="{{ request()->fullUrlWithQuery(['lang'=>'ar']) }}">AR</a>
    </div>
  </div>
</header>

<main class="content wrap">
  @yield('content')
</main>

<footer class="site-footer">
  <div class="wrap small">
    <p>&copy; {{ date('Y') }} {{ config('app.name','Swaed UAE') }} · <a href="{{ url('/sitemap.xml') }}">Sitemap</a></p>
  </div>
</footer>
</body>
</html>
