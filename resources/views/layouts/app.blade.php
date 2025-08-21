<!DOCTYPE html>
  <html lang="{{ str_replace("_","-", app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'rtl' : 'ltr' }}">
<head>
    @include('components.seo')

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'SawaedUAE')</title>

  <!-- Argon Dashboard CSS (prebuilt, no npm) -->
  <link rel="stylesheet" href="{{ asset('vendor/argon/assets/css/nucleo-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/argon/assets/css/nucleo-svg.css') }}">
  <link id="pagestyle" rel="stylesheet" href="{{ asset('vendor/argon/assets/css/argon-dashboard.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/brand.css') }}">

  @stack('head')
    <link rel="stylesheet" href="{{ asset('css/a11y.css') }}">
</head>
@include('partials.seo')
<body class="bg-gray-100 {{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'rtl' : '' }}">
    <a class="skip-link" href="#main">{{ __( 'Skip to main content' ) }}</a>
  @include('argon_front._navbar')

  <main class="container-fluid py-4 mt-4 mt-4">
    @include('argon_front._flash')
    <main id="main" tabindex="-1">@yield('content')</main>
    @include('argon_front/_footer')
  </main>

  <!-- Argon Dashboard JS -->
  
{{-- vendor plugins that Argon expects as globals --}}
<script src="{{ asset('vendor/argon/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
{{-- core / framework --}}
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
{{-- Argon core (pick ONE: minified OR unminified) --}}
<script src="{{ asset('js/argon-dashboard.min.js') }}" defer></script>
{{-- your site init (only once) --}}
<script src="{{ asset('js/argon-init.js') }}" defer></script>

@stack('scripts')
</body>
</html>
