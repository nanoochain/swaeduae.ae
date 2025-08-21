@extends('public.layout')
@php
  $breadcrumbs = [
    ['name'=>'Home','url'=>url('/')],
    ['name'=>'Opportunities','url'=>route('opportunities.index')],
    ['name'=>($row->title ?? ('Opportunity #'.$row->id)),'url'=>url()->current()],
  ];
@endphp
@section('content')
  <article class="detail">
    <h1>{{ $row->title ?? ('Opportunity #'.$row->id) }}</h1>
    <div class="meta muted">
      @if(!empty($row->organization_id))
        <span>Organization #{{ $row->organization_id }}</span>
      @endif
      @if(!empty($row->city) || !empty($row->emirate))
        <span> · {{ trim(($row->city ?? '').' '.($row->emirate ?? '')) }}</span>
      @endif
      @if(!empty($row->start_at))
        <span> · Starts: {{ \Illuminate\Support\Carbon::parse($row->start_at)->toDayDateTimeString() }}</span>
      @endif
    </div>
    @if(!empty($row->description))
      <div class="prose">{!! $row->description !!}</div>
    @endif
    <div class="actions">
      <a class="btn btn-primary" href="{{ route('login') }}">Apply / Sign in</a>
      <a class="btn" href="{{ route('opportunities.index') }}">Back to list</a>
    </div>
  </article>
@endsection
