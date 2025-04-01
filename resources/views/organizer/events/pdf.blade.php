<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Detalles del Evento: {{ $event->title }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #2c3e50;
        }

        .event-info {
            margin-bottom: 30px;
        }

        .section-title {
            background-color: #3498db;
            color: white;
            padding: 8px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-confirmed {
            background-color: #2ecc71;
            color: white;
        }

        .badge-pending {
            background-color: #f39c12;
            color: black;
        }

        .badge-rejected {
            background-color: #e74c3c;
            color: white;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Detalles del Evento</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="event-info">
        <h2>{{ $event->title }}</h2>
        <p><strong>Descripción:</strong> {{ $event->description }}</p>
        <p><strong>Fecha Inicio:</strong> {{ $event->start_date->format('d/m/Y H:i') }}</p>
        <p><strong>Fecha Fin:</strong> {{ $event->end_date->format('d/m/Y H:i') }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($event->status) }}</p>
    </div>

    <div class="section-title">Participantes ({{ $event->participants->count() }})</div>

    @if ($event->participants->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->participants as $participant)
                    <tr>
                        <td>{{ $participant->user->name }}</td>
                        <td>{{ $participant->user->email }}</td>
                        <td>
                            <span class="badge badge-{{ $participant->participation_status }}">
                                {{ ucfirst($participant->participation_status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay participantes registrados para este evento.</p>
    @endif

    <div class="footer">
        <p>Sistema de Gestión de Eventos - {{ config('app.name') }}</p>
    </div>
</body>

</html>
