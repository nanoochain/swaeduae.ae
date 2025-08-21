@extends('layouts.app')
@section('content')@include('partials.page_header', ['title'=>__('Events')])
@include('partials.page_header', ['title'=>__('Events')])

<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" @if(app()->getLocale()==='ar') dir="rtl" @endif>
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
@include('partials.meta')
<link href="{{ asset('css/site.css') }}" rel="stylesheet">
</head>
<body>
@include('partials.header')

<main class="py-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Events</h3>
      <form class="d-flex gap-2">
        <input class="form-control" name="q" value="{{ $q }}" placeholder="Search">
        <input class="form-control" name="city" value="{{ $city }}" placeholder="City">
        <button class="btn btn-primary">Filter</button>
      </form>
    </div>

    <div class="row g-3">
      @forelse($items as $ev)
        <div class="col-md-6 col-lg-4">
          <a class="card h-100 text-decoration-none text-dark" href="{{ route('public.events.show',$ev->id) }}">
            <div class="card-body">
              <div class="small text-muted mb-1">{{ $ev->city ?? '—' }} · {{ $ev->date ?? 'TBA' }}</div>
              <h5 class="card-title">{{ $ev->title }}</h5>
              <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($ev->summary ?? $ev->description, 120) }}</p>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center text-muted py-5">No events found.</div>
      @endforelse
    </div>

    <div class="mt-3">{{ $items->links() }}</div>
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
