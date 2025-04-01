@extends('templates.organizer-base')

@section('page_title')
    Eventos | {{ $event->title }}
@endsection

@section('styles')
    <style>
        /* En tu archivo CSS principal */
        .btn-pdf {
            background-color: #e74c3c;
            color: white;
            transition: all 0.3s;
        }

        .btn-pdf:hover {
            background-color: #c0392b;
            color: white;
        }

        .btn-details {
            transition: all 0.3s;
        }

        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('dashboard-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Detalles del Evento: {{ $event->title }}</h1>
            <a href="{{ route('organizer.events.report', $event) }}" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Información del Evento</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Título:</strong> {{ $event->title }}</p>
                        <p><strong>Descripción:</strong> {{ $event->description }}</p>
                        <p><strong>Fecha Inicio:</strong> {{ $event->start_date->format('d/m/Y H:i') }}</p>
                        <p><strong>Fecha Fin:</strong> {{ $event->end_date->format('d/m/Y H:i') }}</p>
                        <p><strong>Estado:</strong>
                            <span class="badge bg-{{ $event->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Participantes ({{ $event->participants->count() }})</h3>
                    </div>
                    <div class="card-body">
                        @if ($event->participants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($event->participants as $participant)
                                            <tr>
                                                <td>{{ $participant->user->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge 
                                            @if ($participant->participation_status == 'confirmed') bg-success
                                            @elseif($participant->participation_status == 'pending') bg-warning text-dark
                                            @else bg-danger @endif">
                                                        {{ ucfirst($participant->participation_status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                No hay participantes registrados
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Comentarios (opcional) -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Comentarios ({{ $event->comments->count() }})</h3>
            </div>
            <div class="card-body">
                @include('partials.comments', ['comments' => $event->comments])
            </div>
        </div>
    </div>
@endsection
