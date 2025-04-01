@extends('templates.organizer-base')

@section('page_title', 'Reportes')

@section('dashboard-content')
    <div class="row">
        @foreach ($reports as $report)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <i class="fas fa-file-pdf pdf-icon"></i>
                            <span class="badge bg-light text-dark">
                                {{ round(Storage::disk('public')->size($report->file_path) / 1024, 2) }} KB
                            </span>
                        </div>
                        <h6 class="mt-2">{{ basename($report->file_path) }}</h6>
                        <p class="small text-muted mb-2">
                            Generado por: {{ $report->user->name }}<br>
                            {{ $report->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ Storage::url($report->file_path) }}" class="btn btn-sm btn-primary w-100" download>
                            <i class="fas fa-download me-1"></i> Descargar
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
