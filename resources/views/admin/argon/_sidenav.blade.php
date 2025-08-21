@php
  $rtl = app()->getLocale()==='ar';
  function nav_active($patterns){ foreach((array)$patterns as $p){ if(request()->routeIs($p)) return 'active'; } return ''; }
@endphp
<aside
  class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 {{ $rtl ? 'fixed-end me-3' : 'fixed-start ms-3' }} bg-white"
  id="sidenav-main">
  <div class="sidenav-header">
    <a class="navbar-brand m-0 d-flex align-items-center" href="{{ url('/admin') }}">
      <img src="{{ asset('vendor/argon/assets/img/logo-ct.png') }}" class="navbar-brand-img h-100 me-2" alt="logo">
      <span class="ms-1 font-weight-bold">SawaedUAE Admin</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0 mb-2">
  <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{ nav_active(['admin.dashboard','admin.index']) }}" href="{{ route('admin.dashboard') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('Dashboard')</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ nav_active(['admin.opportunities.*']) }}" href="{{ route('admin.opportunities.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-collection text-info text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('Opportunities')</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ nav_active(['admin.organizations.*']) }}" href="{{ route('admin.organizations.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-building text-success text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('Organizations')</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ nav_active(['admin.events.*']) }}" href="{{ route('admin.events.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('Events')</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ nav_active(['admin.users.*']) }}" href="{{ route('admin.users.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('Users')</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link {{ nav_active(['admin.settings*']) }}" href="{{ route('admin.settings.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-settings-gear-65 text-danger text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('Settings')</span>
        </a>
      </li>

      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">@lang('Account')</h6>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}" target="_blank" rel="noopener" target="_blank">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-world text-secondary text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('View Site')</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-user-run text-danger text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">@lang('Sign out')</span>
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
      </li>
    </ul>
  </div>
</aside>

{{-- Safe admin logout (POST) --}}
<form method="POST" action="{{ route('admin.logout') }}" class="px-3 mt-3">
  @csrf
  <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
    {{ __('Sign out') }}
  </button>
</form>
