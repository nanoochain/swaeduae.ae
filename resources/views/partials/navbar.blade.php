@php
  use Illuminate\Support\Facades\Route as R;
  $locale = app()->getLocale();
  $isRtl = in_array(app()->getLocale(), ['ar','he','fa','ur']);
@endphp
<nav class="navbar navbar-expand-lg navbar-light navbar-soft sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-navy d-flex align-items-center gap-2" href="{{ url('/') }}">
      <i class="bi bi-heart-fill text-teal"></i>
      <span>SawaedUAE</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto gap-lg-2">
        <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('opportunities*') ? 'active' : '' }}" href="{{ url('/opportunities') }}">{{ __('Opportunities') }}</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}" href="{{ url('/categories') }}">{{ __('Categories') }}</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('partners*') ? 'active' : '' }}" href="{{ url('/partners') }}">{{ __('Partners') }}</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('events*') ? 'active' : '' }}" href="{{ url('/events') }}">{{ __('Events') }}</a></li>
      </ul>

      <ul class="navbar-nav {{ $isRtl ? 'me-auto' : 'ms-auto' }} align-items-lg-center gap-lg-2">
        {{-- Language toggle --}}
        <li class="nav-item">
          @if($locale === 'ar')
            <a class="nav-link" href="{{ url('/lang/en') }}">English</a>
          @else
            <a class="nav-link" href="{{ url('/lang/ar') }}">العربية</a>
          @endif
        </li>

        @guest
          {{-- Login (only if named route exists; else hide) --}}
          @if (Route::has('login'))
    @include('partials.auth_dropdown') {{-- auth dropdown --}}
          @endif

          {{-- Prefer user register if available, otherwise show organization register --}}
          @if (Route::has('register'))
            <li class="nav-item">
              <a class="btn btn-teal px-3" href="{{ route('register') }}">
                <i class="bi bi-person-plus me-1"></i>{{ __('Register') }}
              </a>
            </li>
          @elseif (Route::has('organizations.register'))
            <li class="nav-item">
              <a class="btn btn-teal px-3" href="{{ route('organizations.register') }}">
                <i class="bi bi-building-add me-1"></i>{{ __('Register Organization') }}
              </a>
            </li>
          @endif
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              @if (Route::has('dashboard'))
                <li><a class="dropdown-item" href="{{ route('profile') }}">{{ __('Dashboard') }}</a></li>
              @endif
              <li><a class="dropdown-item" href="{{ url('/profile') }}">{{ __('Profile') }}</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                @if (Route::has('logout'))
                  <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="dropdown-item">{{ __('Logout') }}</button>
                  </form>
                @else
                  <a class="dropdown-item" href="{{ url('/logout') }}">{{ __('Logout') }}</a>
                @endif
              </li>
            
    ('partials.auth_dropdown') {{-- auth dropdown --}}
  </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

@if(auth()->check() && !auth()->user()->hasVerifiedEmail())
  <div class="alert alert-warning text-center rounded-0 mb-0">
    {{ __('Please verify your email to access all features.') }}
    <form method="POST" class="d-inline ms-2" action="{{ route('verification.send') }}">@csrf
      <button class="btn btn-sm btn-teal">{{ __('Resend link') }}</button>
    </form>
  </div>
@endif
{{-- marker navbar.blade.php 04:04:24 --}}
