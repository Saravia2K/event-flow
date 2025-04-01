@extends('templates.participant-base')

@section('page_title', 'Eventos')

@section('styles')
    <style>
        .event-card {
            transition: all 0.3s ease;
            height: 100%;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .event-img {
            height: 200px;
            object-fit: cover;
        }

        .badge-date {
            background-color: #4361ee;
        }

        .search-box {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
@endsection

@section('participant-content')
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold">Catálogo de Eventos</h1>
            <p class="lead text-muted">Descubre los mejores eventos activos</p>
        </div>
    </div>

    <!-- Barra de búsqueda y filtros -->
    <div class="row mb-4">
        <div class="col-md-12 mb-3 mb-md-0">
            <div class="search-box input-group">
                <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Buscar eventos...">
                <button class="btn btn-primary" type="button" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Filtros adicionales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label for="dateFilter" class="form-label">Fecha</label>
                        <select id="dateFilter" class="form-select">
                            <option value="">Todas las fechas</option>
                            <option value="today">Hoy</option>
                            <option value="week">Esta semana</option>
                            <option value="month">Este mes</option>
                            <option value="upcoming">Próximos</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de eventos -->
    <div class="row" id="eventsContainer">
        @forelse($events as $event)
            <div class="col-lg-4 col-md-6 mb-4 event-item" data-date="{{ $event->start_date->format('Y-m-d') }}"
                data-price="{{ $event->is_free ? 'free' : 'paid' }}">
                <div class="card event-card h-100">
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top event-img"
                            alt="{{ $event->title }}">
                    @else
                        <div class="event-img bg-secondary d-flex align-items-center justify-content-center">
                            <i class="fas fa-calendar-alt fa-4x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge badge-date text-white">
                                {{ $event->start_date->format('d M') }}
                            </span>
                        </div>
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('participant.event', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No hay eventos activos en este momento
                </div>
            </div>
        @endforelse
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const dateFilter = document.getElementById('dateFilter');
            const eventItems = document.querySelectorAll('.event-item');

            // Función para filtrar eventos
            function filterEvents() {
                const searchTerm = searchInput.value.toLowerCase();
                const dateFilterValue = dateFilter.value;
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                eventItems.forEach(item => {
                    const eventText = item.textContent.toLowerCase();
                    const eventDate = new Date(item.dataset.date);

                    // Verificar coincidencia con búsqueda
                    const matchesSearch = searchTerm === '' || eventText.includes(searchTerm);

                    // Verificar fecha
                    let matchesDate = true;
                    if (dateFilterValue !== '') {
                        const eventDay = new Date(eventDate);
                        eventDay.setHours(0, 0, 0, 0);

                        if (dateFilterValue === 'today') {
                            matchesDate = eventDay.getTime() === today.getTime();
                        } else if (dateFilterValue === 'week') {
                            const endOfWeek = new Date(today);
                            endOfWeek.setDate(today.getDate() + (6 - today.getDay()));
                            matchesDate = eventDay >= today && eventDay <= endOfWeek;
                        } else if (dateFilterValue === 'month') {
                            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                            matchesDate = eventDay >= today && eventDay <= endOfMonth;
                        } else if (dateFilterValue === 'upcoming') {
                            matchesDate = eventDay >= today;
                        }
                    }

                    // Mostrar u ocultar según coincidencia
                    if (matchesSearch && matchesDate) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            // Event Listeners
            searchInput.addEventListener('input', filterEvents);
            searchButton.addEventListener('click', filterEvents);
            dateFilter.addEventListener('change', filterEvents);

            // Buscar al presionar Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    filterEvents();
                }
            });
        });
    </script>
@endsection
