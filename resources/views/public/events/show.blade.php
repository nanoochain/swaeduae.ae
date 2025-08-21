@extends('layouts.app')
@section('content')
<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" @if(app()->getLocale()==='ar') dir="rtl" @endif>
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
@include('partials.meta')
<link href="{{ asset('css/site.css') }}" rel="stylesheet">
</head>
<body>
<header class="py-3 border-bottom bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="{{ url('/events') }}" class="text-decoration-none">&larr; Back</a>
    <strong>{{ $appSettings['site_name'] }}</strong>
    @include('partials.social')
  </div>
</header>

<main class="py-4">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-8">
        <h2 class="mb-1">{{ $ev->title }}</h2>
        <div class="text-muted mb-3">
          {{ $ev->city ?? '—' }} · {{ $ev->date ?? 'TBA' }}
          @if($ev->start_time || $ev->end_time) · {{ $ev->start_time ?? '' }} {{ $ev->end_time ? '– '.$ev->end_time : '' }} @endif
        </div>

        @if($ev->location)<p><strong>Location:</strong> {{ $ev->location }}</p>@endif
        @if($ev->category)<p><strong>Category:</strong> {{ $ev->category }}</p>@endif
        @if($ev->region)<p><strong>Region:</strong> {{ $ev->region }}</p>@endif
        @if($ev->capacity)<p><strong>Capacity:</strong> {{ $ev->capacity }}</p>@endif
        @if($ev->status)<p><strong>Status:</strong> {{ $ev->status }}</p>@endif
        @if($ev->target)<p><strong>Target:</strong> {{ $ev->target }}</p>@endif

        @if($ev->summary)<p class="lead">{{ $ev->summary }}</p>@endif
        @if($ev->description)<div class="mt-3">{!! nl2br(e($ev->description)) !!}</div>@endif
      </div>
      <div class="col-lg-4">
        @if($ev->poster_path)
          <img src="{{ asset('storage/'.$ev->poster_path) }}" class="img-fluid rounded" alt="Poster">
        @endif
      </div>
    </div>
  </div>
</main>

<footer class="py-4 border-top bg-light">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="small text-muted">© {{ date('Y') }} {{ $appSettings['site_name'] }}</div>
    @include('partials.social')
  </div>
</footer>
</body>
</html>

@endsection
