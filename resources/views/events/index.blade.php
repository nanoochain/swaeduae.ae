@extends('layouts.app')
@section('title','Events | '.config('app.name'))
@section('page_header') <x-page-header :title="__('Events')" :subtitle="__('Browse upcoming events')" /> @endsection
@section('content')
  @include('partials.sort_bar')
  @if(isset($events) && $events->count())
    <ul class="list-unstyled">
      @foreach($events as $e)
        <li class="mb-2">
          <a href="{{ route('events.show', ['idOrSlug' => ($e->slug ?? $e->id)]) }}">
            {{ $e->title ?? ('Event #'.$e->id) }}
          </a>
        </li>
      @endforeach
    </ul>
    {{ $events->links() }}
  @else
    <div class="empty-state">{{ __('Events coming soon. Watch this space!') }}</div>
  @endif
@endsection
