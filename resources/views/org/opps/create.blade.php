@extends('layouts.app')
@section('title', __('Create Opportunity').' | '.config('app.name'))

@section('page_header')
  <x-page-header :title="__('Create Opportunity')" />
@endsection

@section('content')
  @include('partials.flash')

  <form method="POST" action="{{ route('org.opps.store') }}">
    @include('org.opps._form')
  </form>
@endsection
