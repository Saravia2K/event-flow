@extends('templates.participant-base')

@section('page_title')
    Eventos | {{ $event->title }}
@endsection

@section('styles')
    <style>
        .event-image {
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .participant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .status-badge {
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('participant-content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <!-- Contenido principal -->
        <div class="col-lg-8">
            <!-- Imagen del evento -->
            @if ($event->image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid event-image w-100 shadow"
                        alt="{{ $event->title }}">
                </div>
            @endif

            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">{{ $event->title }}</h1>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary">{{ $event->status }}</span>
                </div>
            </div>

            <!-- Fechas -->
            <div class="d-flex gap-4 mb-4">
                <div>
                    <i class="fas fa-calendar-day me-2"></i>
                    <strong>Inicio:</strong> {{ $event->start_date->format('d M Y, h:i A') }}
                </div>
                <div>
                    <i class="fas fa-calendar-check me-2"></i>
                    <strong>Fin:</strong> {{ $event->end_date->format('d M Y, h:i A') }}
                </div>
            </div>

            <!-- Descripción -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Descripción</h3>
                    <div class="card-text">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Participantes -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h3 class="mb-0">Participantes</h3>
                </div>
                <div class="card-body">
                    @if ($event->participants->count() > 0)
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($event->participants as $participant)
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $participant->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . $participant->user->name }}"
                                        class="participant-avatar">
                                    <div>
                                        <div class="fw-medium">{{ $participant->user->name }}</div>
                                        <span
                                            class="badge 
                                @if ($participant->participation_status == 'confirmed') bg-success
                                @elseif($participant->participation_status == 'pending') bg-warning text-dark
                                @else bg-danger @endif status-badge">
                                            {{ $participant->participation_status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-users-slash fa-2x mb-2"></i>
                            <p class="mb-0">Aún no hay participantes</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sección de comentarios -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h3 class="mb-0">Comentarios ({{ $event->comments->count() }})</h3>
                </div>

                <div class="card-body">
                    @if ($canComment)
                        <!-- Formulario visible solo para participantes confirmados -->
                        <form action="{{ route('participant.event.comment', $event) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <textarea name="comment" class="form-control" rows="3" placeholder="Escribe tu comentario..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Enviar comentario
                            </button>
                        </form>
                    @elseif(auth()->check())
                        <!-- Mensaje para usuarios logueados pero no aceptados -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Solo los participantes confirmados pueden comentar en este evento.
                        </div>
                    @else
                        <!-- Mensaje para usuarios no logueados -->
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <a href="{{ route('login') }}" class="alert-link">Inicia sesión</a> y participa en el evento
                            para poder comentar.
                        </div>
                    @endif

                    <!-- Listado de comentarios (visible para todos) -->
                    @forelse($event->comments as $comment)
                        <div class="d-flex gap-3 mb-4">
                            <img src="{{ $comment->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . $comment->user->name }}"
                                class="comment-avatar">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                    <small class="text-muted">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="mb-2">{{ $comment->comment }}</div>
                                @if (auth()->id() == $comment->user_id)
                                    <form action="{{ route('comment.destroy', $comment) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-comment-slash fa-2x mb-3"></i>
                            <p class="mb-0">No hay comentarios aún</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <!-- Botón de participación -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body text-center">
                        @if ($userParticipation)
                            @if ($userParticipation->participation_status == 'confirmed')
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle me-2"></i>
                                    ¡Ya estás participando!
                                </div>
                            @elseif($userParticipation->participation_status == 'pending')
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    Solicitud pendiente
                                </div>
                            @else
                                <div class="alert alert-danger mb-0">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Participación rechazada
                                </div>
                            @endif
                        @else
                            <form action="{{ route('participant.event.participate', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                    <i class="fas fa-user-plus me-2"></i> Participar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Detalles rápidos -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">Detalles</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span><i class="fas fa-users me-2"></i> Participantes</span>
                                <span class="badge bg-primary rounded-pill">{{ $event->participants->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span><i class="fas fa-comments me-2"></i> Comentarios</span>
                                <span class="badge bg-primary rounded-pill">{{ $event->comments->count() }}</span>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>Estado: <strong>{{ ucfirst($event->status) }}</strong></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
