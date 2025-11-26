@extends('layouts.app')

@section('title', 'Daftar Gedung')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Gedung</h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Kelola data gedung dan lantainya</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
     

            <!-- Tanggal -->
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Table Card -->
    <div class="table-card">
        <!-- Search Bar -->
        <div class="search-bar">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari gedung..." id="searchInput">
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10%">No</th>
                        <th>Nama Gedung</th>
                        <th style="width: 20%" class="text-center">Total Lantai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buildings as $building)
                    <tr class="building-row">
                        <td>
                            <span class="badge-code">{{ $loop->iteration }}</span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($building->name, 0, 1)) }}
                                </div>
                                <span class="building-name fw-medium ms-3">{{ $building->name }}</span>
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="badge-floor">
                                <i class="bi bi-layers me-1"></i>
                                {{ $building->total_floors }} Lantai
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-building" style="font-size: 4rem; opacity: 0.2; color: #6b7280;"></i>
                                <h5 class="text-muted mt-3 mb-2">Belum ada gedung</h5>
                                <p class="text-muted mb-3" style="font-size: 0.875rem;">Data gedung belum tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        @if($buildings->isNotEmpty())
        <div class="table-footer">
            <small class="pagination-info">
                Menampilkan {{ $buildings->count() }} gedung
            </small>
        </div>
        @endif
    </div>
</div>

<style>
    /* Add Button */
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: #2D4194;
        color: white;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-add:hover {
        background: #1e2f6f;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 65, 148, 0.3);
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    /* Search Bar */
    .search-bar {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: #fafbfc;
    }

    .search-bar .input-group-text {
        background: white;
        border-right: none;
        color: #6b7280;
    }

    .search-bar .form-control {
        border-left: none;
        background: white;
    }

    .search-bar .form-control:focus {
        box-shadow: none;
        border-color: #e5e7eb;
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

    /* Avatar Circle */
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2D4194 0%, #1e2f6f 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        flex-shrink: 0;
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

    .badge-floor {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        background: #dbeafe;
        color: #1e40af;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        padding: 2rem 0;
    }

    /* Table Footer */
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

    /* Alert */
    .alert {
        border-radius: 12px;
        border: 1px solid #d1fae5;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
    }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('.building-row');

        rows.forEach(row => {
            const buildingName = row.querySelector('.building-name').textContent.toLowerCase();
            row.style.display = buildingName.includes(searchText) ? '' : 'none';
        });
    });
</script>
@endsection