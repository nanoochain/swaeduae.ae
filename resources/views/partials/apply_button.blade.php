@php
  // Required variables:
  // $type: 'opportunity' or 'event'
  // $recordId: numeric ID
  $applyRoute = null;
  if ($type === 'opportunity' && \Illuminate\Support\Facades\Route::has('opportunities.apply')) {
      $applyRoute = route('opportunities.apply', $recordId);
  } elseif ($type === 'event' && \Illuminate\Support\Facades\Route::has('events.apply')) {
      $applyRoute = route('events.apply', $recordId);
  } else {
      // Fallback URLs if named routes differ
      $applyRoute = $type === 'event'
        ? url('/events/'.$recordId.'/apply')
        : url('/opportunities/'.$recordId.'/apply');
  }
@endphp

@guest
  <a href="{{ route('login') }}" class="btn btn-primary w-100">
    {{ __('Sign in to Apply') }}
  </a>
@else
  <form method="POST" action="{{ $applyRoute }}">
    @csrf
    <button type="submit" class="btn btn-primary w-100">{{ __('Apply Now') }}</button>
  </form>
@endguest
