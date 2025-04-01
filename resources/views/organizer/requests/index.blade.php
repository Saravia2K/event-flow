@extends('templates.organizer-base')

@section('page_title', 'Solicitudes')

@section('styles')
    <style>
        .request-user-icon {
            width: 32px !important;
            height: 32px !important;
        }
    </style>
@endsection

@section('dashboard-content')
    <div class="container py-5">
        <h1 class="mb-4">Administración de Participantes</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabla de Solicitudes Pendientes -->
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Solicitudes Pendientes</h2>
            </div>
            <div class="card-body">
                @if ($pendingParticipants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Evento</th>
                                    <th>Solicitado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingParticipants as $participant)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . $participant->user->name }}"
                                                    class="request-user-icon rounded-circle me-2">
                                                {{ $participant->user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $participant->event->title }}</td>
                                        <td>{{ $participant->created_at->diffForHumans() }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('organizer.requests.participants.update-status', $participant) }}"
                                                class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-sm btn-success me-2">
                                                    <i class="fas fa-check"></i> Aprobar
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('organizer.requests.participants.update-status', $participant) }}"
                                                class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <p class="mb-0">No hay solicitudes pendientes</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabla de Solicitudes Procesadas -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h2 class="h5 mb-0">Solicitudes Procesadas</h2>
            </div>
            <div class="card-body">
                @if ($processedParticipants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Evento</th>
                                    <th>Estado</th>
                                    <th>Procesado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($processedParticipants as $participant)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img class="request-user-icon rounded-circle me-2 "
                                                    src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . $participant->user->name }}">
                                                {{ $participant->user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $participant->event->title }}</td>
                                        <td>
                                            <span
                                                class="badge 
                                    @if ($participant->participation_status == 'confirmed') bg-success
                                    @else bg-danger @endif">
                                                {{ $participant->participation_status == 'confirmed' ? 'Aprobado' : 'Rechazado' }}
                                            </span>
                                        </td>
                                        <td>{{ $participant->updated_at->diffForHumans() }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('organizer.requests.participants.update-status', $participant) }}">
                                                @csrf
                                                <input type="hidden" name="status"
                                                    value="{{ $participant->participation_status == 'confirmed' ? 'rejected' : 'confirmed' }}">
                                                <button type="submit"
                                                    class="btn btn-sm 
                                        @if ($participant->participation_status == 'confirmed') btn-danger
                                        @else btn-success @endif">
                                                    @if ($participant->participation_status == 'confirmed')
                                                        <i class="fas fa-times"></i> Rechazar
                                                    @else
                                                        <i class="fas fa-check"></i> Aprobar
                                                    @endif
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-list fa-2x mb-3"></i>
                        <p class="mb-0">No hay solicitudes procesadas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
