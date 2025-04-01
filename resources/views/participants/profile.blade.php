@extends('templates.participant-base')

@section('page_title', 'Perfil')

@section('styles')
    <style>
        /* Estilos para los tabs */
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid #0d6efd;
        }

        /* Tarjetas de eventos */
        .event-status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
        }

        /* Avatar de usuario */
        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            color: #666;
            font-weight: bold;
        }
    </style>
@endsection

@section('participant-content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <!-- Panel de información del usuario -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="avatar mb-3"
                            style="width: 120px; height: 120px; background-color: #f0f0f0; border-radius: 50%; margin: 0 auto;">
                            <!-- Aquí puedes agregar la imagen del usuario si tienes -->
                            <span style="font-size: 48px; line-height: 120px; color: #666;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                            </span>
                        </div>
                        <h3 class="mb-1">{{ $user->name }} {{ $user->last_name }}</h3>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="badge bg-light text-dark">
                                Miembro desde {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Tabs de eventos -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="eventsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="confirmed-tab" data-bs-toggle="tab"
                                    data-bs-target="#confirmed" type="button" role="tab">
                                    Aceptados ({{ $confirmedEvents->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                                    type="button" role="tab">
                                    Pendientes ({{ $pendingEvents->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected"
                                    type="button" role="tab">
                                    Rechazados ({{ $rejectedEvents->count() }})
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="eventsTabContent">
                            <!-- Tab Aceptados -->
                            <div class="tab-pane fade show active" id="confirmed" role="tabpanel">
                                @if ($confirmedEvents->count() > 0)
                                    @foreach ($confirmedEvents as $participation)
                                        @include('participants._event_card', [
                                            'event' => $participation->event,
                                            'status' => 'confirmed',
                                        ])
                                    @endforeach
                                @else
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-check fa-2x mb-3"></i>
                                        <p class="mb-0">No tienes eventos aceptados</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab Pendientes -->
                            <div class="tab-pane fade" id="pending" role="tabpanel">
                                @if ($pendingEvents->count() > 0)
                                    @foreach ($pendingEvents as $participation)
                                        @include('participants._event_card', [
                                            'event' => $participation->event,
                                            'status' => 'pending',
                                        ])
                                    @endforeach
                                @else
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-clock fa-2x mb-3"></i>
                                        <p class="mb-0">No tienes eventos pendientes</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab Rechazados -->
                            <div class="tab-pane fade" id="rejected" role="tabpanel">
                                @if ($rejectedEvents->count() > 0)
                                    @foreach ($rejectedEvents as $participation)
                                        @include('participants._event_card', [
                                            'event' => $participation->event,
                                            'status' => 'rejected',
                                        ])
                                    @endforeach
                                @else
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-times-circle fa-2x mb-3"></i>
                                        <p class="mb-0">No tienes eventos rechazados</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
