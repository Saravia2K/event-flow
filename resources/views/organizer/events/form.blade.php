@extends('templates.organizer-base')

@section('page_title', 'Nuevo evento')

@php
    $eventComes = isset($event);
    $status = $eventComes ? $event->status : '';
@endphp

@section('dashboard-content')
    <div class="form-container">
        <h1>Formulario de Eventos</h1>
        <form id="eventForm" action="{{ route($eventComes ? 'organizer.events.update' : 'organizer.events') }}" method="POST">
            @csrf

            @if ($eventComes)
                @method('PATCH')
                <input type="hidden" name="id" value="{{ $event->id }}">
            @endif

            <!-- Título del Evento -->
            <div class="form-group">
                <label for="title">Título del Evento*</label>
                <input type="text" id="title" name="title" required maxlength="255"
                    placeholder="Ej: Conferencia de Tecnología 2023" value="{{ $eventComes ? $event->title : '' }}">
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="form-group">
                <label for="description">Descripción*</label>
                <textarea id="description" name="description" required placeholder="Descripción detallada del evento...">{{ $eventComes ? $event->description : '' }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Fechas -->
            <div class="form-row">
                <div class="form-group">
                    <label for="start_date">Fecha y Hora de Inicio*</label>
                    <input type="datetime-local" id="start_date" name="start_date" required
                        value="{{ $eventComes ? $event->start_date : '' }}">
                    @error('start_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">Fecha y Hora de Finalización*</label>
                    <input type="datetime-local" id="end_date" name="end_date" required
                        value="{{ $eventComes ? $event->end_date : '' }}">
                    @error('end_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Estado -->
            <div class="form-group">
                <label for="status">Estado del Evento*</label>
                <select id="status" name="status" required>
                    <option disabled @selected($status == '')>----- Seleccione un estado -----</option>
                    <option value="active" @selected($status == 'active')>Activo</option>
                    <option value="inactive" @selected($status == 'inactive')>Inactivo</option>
                    <option value="finished" @selected($status == 'finished')>Finalizado</option>
                </select>
                @error('status')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">{{ $eventComes ? 'Actualizar' : 'Guardar' }} evento</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const startDateInput = document.getElementById("start_date");
            const endDateInput = document.getElementById("end_date");

            // Configurar fecha mínima para start_date (mañana a las 00:00)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);

            // Formatear para input datetime-local
            const minDate = tomorrow.toISOString().slice(0, 16);
            startDateInput.min = minDate;

            // Actualizar end_date cuando cambia start_date
            startDateInput.addEventListener("change", function() {
                const startDate = new Date(this.value);

                // Establecer fecha mínima para end_date
                endDateInput.min = this.value;

                // Si end_date es anterior a la nueva start_date, resetear
                const endDate = new Date(endDateInput.value);
                if (endDate < startDate) {
                    endDateInput.value = "";
                }
            });
        });
    </script>
@endsection
