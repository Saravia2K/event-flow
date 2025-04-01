@extends('templates.organizer-base')

@section('page_title', 'Eventos')

@section('styles')
    <style>
        /* Minimal custom CSS para ajustes específicos */
        .status-badge {
            font-size: 0.8rem;
            padding: 0.35rem 0.7rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.03);
        }

        .action-icons a {
            transition: all 0.2s ease;
        }

        .action-icons a:hover {
            transform: scale(1.1);
        }

        .action-icons .edit:hover {
            color: #0d6efd !important;
        }

        .action-icons .delete:hover {
            color: #dc3545 !important;
        }
    </style>
@endsection

@section('dashboard-content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <x-dashboard-pages-title text="Eventos" />
        <a href="{{ route('organizer.events.create-form') }}"
            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Agregar evento</a>

    </div>
    <div class="container-fluid py-4">
        @if (session('alert'))
            @if (session('alert')['success'])
                <!-- Alertas -->
                <div id="successAlert" class="alert alert-success" role="alert">
                    <span id="successMessage">{{ session('alert')['message'] }}</span>
                </div>
            @endif

            @if (!session('alert')['success'])
                <div id="errorAlert" class="alert alert-danger" role="alert">
                    <span id="errorMessage">{{ session('alert')['message'] }}</span>
                </div>
            @endif
        @endif

        <!-- Tarjeta contenedora -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-semibold">Gestión de Eventos</h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary rounded-pill me-2" id="eventCount">{{ count($events) }}</span>
                    <span>Eventos registrados</span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Título</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="eventsTableBody">
                            @foreach ($events as $event)
                                @php
                                    $statusClass = '';
                                    $statusText = '';

                                    switch ($event->status) {
                                        case 'active':
                                            $statusClass = 'bg-success';
                                            $statusText = 'Activo';
                                            break;
                                        case 'inactive':
                                            $statusClass = 'bg-secondary';
                                            $statusText = 'Inactivo';
                                            break;
                                        case 'finished':
                                            $statusClass = 'bg-warning text-dark';
                                            $statusText = 'Finalizado';
                                            break;
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $event->id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $event->title }}</span>
                                            <small class="text-muted">{{ Str::limit($event->description) }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $event->start_date->format('d/m/Y, h:i A') }}</td>
                                    <td>{{ $event->end_date->format('d/m/Y, h:i A') }}</td>
                                    <td><span class="badge {{ $statusClass }} status-badge">{{ $statusText }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="action-icons">
                                            <a href="{{ route('organizer.events.edit-form', ['id' => $event->id]) }}"
                                                class="text-primary edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="text-danger delete"
                                                onclick="confirmDelete({{ $event->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <a href="{{ route('organizer.events.details', $event) }}"
                                                class="text-success watch">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmDelete(eventId) {
            Swal.fire({
                title: 'Confirmar eliminación',
                text: "¿Estás seguro que deseas eliminar este evento? Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const idInput = document.createElement("input");
                    idInput.id = "id";
                    idInput.name = "id";
                    idInput.value = eventId;

                    const tokenInput = document.createElement("input");
                    tokenInput.name = "_token";
                    tokenInput.value = '{{ csrf_token() }}';

                    const methodInput = document.createElement("input");
                    methodInput.name = "_method";
                    methodInput.value = "DELETE";

                    const form = document.createElement("form");
                    form.method = 'POST';
                    form.action = '{{ route('organizer.events.delete') }}'
                    form.appendChild(tokenInput);
                    form.appendChild(methodInput);
                    form.appendChild(idInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
