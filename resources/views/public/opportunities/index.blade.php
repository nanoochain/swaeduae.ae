@extends('public.layout')
@php
  $title = 'Browse Volunteer Opportunities';
  $description = 'Search and filter volunteer opportunities across the UAE by emirate, city, and category.';
  $breadcrumbs = [
    ['name'=>'Home','url'=>url('/')],
    ['name'=>'Opportunities','url'=>route('opportunities.index')],
  ];
@endphp
@section('content')
  <h1>{{ $title }}</h1>
  <form class="filters" method="get">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Keyword" />
    @if($emirates->count()) 
      <select name="emirate">
        <option value="">All Emirates</option>
        @foreach($emirates as $em)
          <option value="{{ $em }}" @selected(request('emirate') === $em)>{{ $em }}</option>
        @endforeach
      </select>
    @endif
    @if($cities->count())
      <select name="city">
        <option value="">All Cities</option>
        @foreach($cities as $c)
          <option value="{{ $c }}" @selected(request('city') === $c)>{{ $c }}</option>
        @endforeach
      </select>
    @endif
    @if($categories->count())
      <select name="category_id">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" @selected((string)request('category_id')===(string)$cat->id)>{{ $cat->name }}</option>
        @endforeach
      </select>
    @endif
    <select name="sort">
      <option value="soon" @selected(request('sort','soon')==='soon')>Soonest</option>
      <option value="new"  @selected(request('sort')==='new')>Newest</option>
    </select>
    <button class="btn btn-primary" type="submit">Apply</button>
    <a class="btn" href="{{ route('opportunities.index') }}">Reset</a>
  </form>
  <div class="grid">
    @forelse($opps as $o)
      <article class="card">
        <h3>
          <a href="{{ url('/opportunities/'.$o->id.'-'.\Illuminate\Support\Str::slug($o->title ?? 'opportunity')) }}">
            {{ $o->title ?? 'Opportunity #'.$o->id }}
          </a>
        </h3>
        <p class="muted">
          @if(!empty($o->city)) {{ $o->city }} · @endif
          @if(!empty($o->emirate)) {{ $o->emirate }} @endif
        </p>
        @if(!empty($o->start_at))
          <p class="muted">Starts: {{ \Illuminate\Support\Carbon::parse($o->start_at)->toFormattedDateString() }}</p>
        @endif
        <a class="btn btn-sm" href="{{ url('/opportunities/'.$o->id.'-'.\Illuminate\Support\Str::slug($o->title ?? 'opportunity')) }}">View</a>
      </article>
    @empty
      <p class="muted">No results.</p>
    @endforelse
  </div>
  <div class="pagination">
    {{ $opps->links() }}
  </div>
@endsection
