@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 text-secondary">📊 Import History</h2>
            <div>
                <a href="{{ route('import.history') }}" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
                <a href="{{ route('import.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> New Upload
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="bg-light">
                        <tr>
                            <th scope="col" class="ps-4">File name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Details</th>
                            <th scope="col">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($imports as $import)
                            @php
                                $statusClass = match($import->status) {
                                    'completed' => 'success',
                                    'processing' => 'primary',
                                    'failed' => 'danger',
                                    default => 'secondary',
                                };

                                $statusLabel = match($import->status) {
                                    'completed' => 'Completed',
                                    'processing' => 'Processing...',
                                    'failed' => 'Error',
                                    'pending' => 'Pending',
                                    default => $import->status,
                                };
                            @endphp

                            <tr>
                                <td class="ps-4 fw-bold text-dark">
                                    <i class="bi bi-file-earmark-spreadsheet text-success me-2"></i>
                                    {{ basename($import->path) }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }} bg-opacity-10 text-{{ $statusClass }} px-3 py-2 rounded-pill">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <div class="d-flex gap-3">
                                            <span class="text-secondary" title="Total">
                                                <i class="bi bi-layers-fill"></i> {{ $import->total_rows ?? '-' }} Rows
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted small">
                                    {{ $import->created_at->format('d.m.Y H:i') }}
                                    <br>
                                    <span class="fw-light">{{ $import->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No imports
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
