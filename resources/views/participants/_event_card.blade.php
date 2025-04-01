<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4">
            @if ($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid rounded-start h-100"
                    style="object-fit: cover;" alt="{{ $event->title }}">
            @else
                <div class="bg-secondary h-100 d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-alt fa-3x text-white"></i>
                </div>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <span
                        class="badge bg-{{ $status === 'confirmed' ? 'success' : ($status === 'pending' ? 'warning text-dark' : 'danger') }}">
                        {{ $status === 'confirmed' ? 'Aceptado' : ($status === 'pending' ? 'Pendiente' : 'Rechazado') }}
                    </span>
                </div>

                <p class="card-text text-muted">
                    <small>
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ $event->start_date->format('d M Y, h:i A') }} -
                        {{ $event->end_date->format('d M Y, h:i A') }}
                    </small>
                </p>

                <p class="card-text">{{ Str::limit($event->description, 150) }}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-users me-1"></i> {{ $event->participants_count }}
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-comments me-1"></i> {{ $event->comments_count }}
                        </span>
                    </div>
                    <a href="{{ route('participant.event', $event) }}" class="btn btn-sm btn-outline-primary">
                        Ver evento
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
