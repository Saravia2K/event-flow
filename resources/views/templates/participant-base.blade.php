@extends('templates.base')

@section('body-class', 'bg-light')

@section('content')
    <x-participant-navbar></x-participant-navbar>

    <div class="container py-5 z-1">
        @yield('participant-content')
    </div>
@endsection
