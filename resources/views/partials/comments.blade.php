@if ($comments->count() > 0)
    <div class="comments-section mt-4">
        <h4 class="mb-3">Comentarios ({{ $comments->count() }})</h4>

        <div class="comments-list">
            @foreach ($comments as $comment)
                <div class="comment-item mb-4 pb-3 border-bottom" id="comment-{{ $comment->id }}">
                    <div class="d-flex gap-3">
                        <!-- Avatar del usuario -->
                        <div class="flex-shrink-0">
                            <img src="{{ $comment->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . $comment->user->name . '&background=random' }}"
                                alt="{{ $comment->user->name }}" class="rounded-circle" width="48" height="48">
                        </div>

                        <!-- Contenido del comentario -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $comment->user->name }}</h6>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>

                                <!-- Botón de eliminar (solo para autor o admin) -->
                                @if (auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->is_admin))
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Estás seguro de eliminar este comentario?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="comment-content">
                                {{ $comment->comment }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="alert alert-info mt-4">
        <i class="fas fa-comment-slash me-2"></i> No hay comentarios aún
    </div>
@endif

<!-- Formulario para nuevo comentario (si está habilitado) -->
@if (isset($showForm) && $showForm)
    <div class="add-comment mt-5">
        <h5 class="mb-3">Agregar comentario</h5>
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <div class="mb-3">
                <textarea name="comment" class="form-control" rows="3" placeholder="Escribe tu comentario..." required></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Enviar comentario
                </button>
            </div>
        </form>
    </div>
@endif
