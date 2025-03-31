@extends('templates.organizer-base')

@section('page_title', 'Eventos')

@section('dashboard-content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <x-dashboard-pages-title text="Eventos" />
        <a href="{{ route('organizer.events.create-form') }}"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Agregar evento</a>
    </div>
@endsection
