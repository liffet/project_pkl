@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Hasil Pencarian</h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Menampilkan hasil untuk: <span class="fw-medium" style="color: #2D4194;">"{{ $query }}"</span>
            </p>
        </div>
        <div class="text-end">
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        @if($items->isEmpty())
        <div class="empty-result">
            <i class="bi bi-search" style="font-size: 4rem; opacity: 0.2; color: #6b7280;"></i>
            <h5 class="text-muted mt-3 mb-2">Tidak ada perangkat ditemukan</h5>
            <p class="text-muted mb-3" style="font-size: 0.875rem;">
                Coba gunakan kata kunci yang berbeda atau periksa ejaan Anda
            </p>
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
        @else
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Ruangan</th>
                        <th>Lantai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>
                            <span class="badge-code">{{ $item->code }}</span>
                        </td>
                        <td class="fw-medium">{{ $item->name }}</td>
                        <td>
                            <span class="badge-category">{{ $item->category->name }}</span>
                        </td>
                        <td>{{ $item->room->name ?? '-' }}</td>
                        <td>{{ $item->floor->name ?? '-' }}</td>
                        <td>
                            @if($item->status == 'active')
                            <span class="badge-status success">Aktif</span>
                            @else
                            <span class="badge-status warning">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer with result count -->
        <div class="table-footer">
            <small class="pagination-info">
                Ditemukan {{ $items->count() }} perangkat
            </small>
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
        @endif
    </div>
</div>

<style>
    /* Table Card */
    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    /* Empty Result */
    .empty-result {
        padding: 4rem 2rem;
        text-align: center;
    }

    /* Table */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead th {
        background: #fafbfc;
        padding: 0.875rem 1.5rem;
        text-align: left;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border-bottom: 1px solid #e5e7eb;
    }

    .custom-table tbody td {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background: #fafbfc;
    }

    /* Badges */
    .badge-code {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        background: #f3f4f6;
        color: #374151;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .badge-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #eff6ff;
        color: #2D4194;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .badge-status.success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-status.warning {
        background: #fef3c7;
        color: #92400e;
    }

    /* Footer */
    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        background: #fafbfc;
    }

    .pagination-info {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Back Button */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        color: #374151;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-back:hover {
        background: #e5e7eb;
        color: #1f2937;
    }
</style>
@endsection