@php $rtl = app()->getLocale()==='ar'; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Organization Console')</title>

  <!-- Argon Dashboard CSS -->
  <link rel="stylesheet" href="{{ asset('vendor/argon/assets/css/nucleo-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/argon/assets/css/nucleo-svg.css') }}">
  <link id="pagestyle" rel="stylesheet" href="{{ asset('vendor/argon/assets/css/argon-dashboard.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/brand.css') }}">

  @stack('head')
  @include('org.partials.branding_styles')
</head>
<body class="g-sidenav-show bg-gray-100 {{ $rtl ? 'rtl' : '' }}">
  @include('org.argon._sidenav')

  <main class="main-content position-relative border-radius-lg {{ $rtl ? 'me-3' : 'ms-3' }}">
    @include('admin.argon._navbar')

    <div class="container-fluid py-4">
      @if(session('status'))
        <div class="alert alert-success shadow-sm">{{ session('status') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger shadow-sm">
          <ul class="m-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      @include(\x27org.partials.menu\x27)
@yield(\x27content\x27)

      @include('admin.argon._footer')
    </div>
  </main>

  <!-- Argon Dashboard JS -->
  <script src="{{ asset('vendor/argon/assets/js/core/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/argon/assets/js/argon-dashboard.min.js') }}"></script>
  <script src="{{ asset('js/argon-init.js') }}" defer></script>
  @stack('scripts')
</body>
</html>
