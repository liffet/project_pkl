@extends('layouts.app')

@section('title', 'Daftar Lantai')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Lantai</h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Daftar lantai berdasarkan gedung</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <!-- Tombol Export Excel -->
            @if(request('building_id'))
            <a href="{{ route('floors.export.excel', ['building_id' => request('building_id')]) }}"
                class="btn shadow-sm d-flex align-items-center px-3 py-2"
                style="
               background-color: #2D4194;
               color: white;
               border: none;
               border-radius: 8px;
               font-size: 14px;
               font-weight: 600;
               transition: all 0.2s ease;
               user-select: none;
           "
                onmouseover="this.style.backgroundColor='#3E52B0'"
                onmouseout="this.style.backgroundColor='#2D4194'"
                onmousedown="this.style.backgroundColor='black'; this.style.color='white';"
                onmouseup="this.style.backgroundColor='#3E52B0'; this.style.color='white';"
                onfocus="this.style.boxShadow='none'; this.style.outline='none';">
                <i class="bi bi-file-earmark-excel me-2" style="font-size: 1rem;"></i>
                Export Excel
            </a>
            @endif

            <!-- Tanggal -->
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
        </div>
    </div>

    <!-- Filter Gedung -->
    <div class="mb-4">
        <div class="filter-card">
            <div class="filter-card-header">
                <i class="bi bi-funnel"></i>
                <span>Filter Berdasarkan Gedung</span>
            </div>
            <div class="filter-card-body">
                <form method="GET" action="{{ route('floors.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">
                                <i class="bi bi-building"></i>
                                Pilih Gedung
                            </label>
                            <select name="building_id" class="form-select-custom" id="buildingSelect">
                                <option value="">-- Semua Gedung --</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}"
                                        {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-search"></i>
                                Tampilkan
                            </button>
                            @if(request('building_id'))
                            <a href="{{ route('floors.index') }}" class="btn-reset" title="Reset Filter">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                            @endif
                        </div>
                        @if(request('building_id'))
                        <div class="col-md-4">
                            <div class="selected-info">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>Menampilkan lantai dari: <strong>{{ $buildings->find(request('building_id'))->name ?? 'Gedung' }}</strong></span>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <!-- Search Bar -->
        <div class="search-bar">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari lantai..." id="searchInput">
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10%">No</th>
                        <th>Nama Lantai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($floors as $floor)
                    <tr class="floor-row">
                        <td>
                            <span class="badge-code">{{ $loop->iteration }}</span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($floor->name, 0, 1)) }}
                                </div>
                                <span class="floor-name fw-medium ms-3">{{ $floor->name }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-building" style="font-size: 4rem; opacity: 0.2; color: #6b7280;"></i>
                                <h5 class="text-muted mt-3 mb-2">Tidak ada data lantai</h5>
                                <p class="text-muted mb-3" style="font-size: 0.875rem;">
                                    @if(!request('building_id'))
                                        Pilih gedung untuk melihat daftar lantai
                                    @else
                                        Belum ada lantai pada gedung ini
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        @if(!empty($floors))
        <div class="table-footer">
            <small class="pagination-info">
                Menampilkan {{ $floors->count() }} lantai
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

    /* Empty State */
    .empty-state {
        padding: 2rem 0;
    }

    /* Pagination */
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

    .pagination {
        display: flex;
        gap: 0.25rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .page-item {
        display: flex;
    }

    .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: #6b7280;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: #f9fafb;
        color: #2D4194;
    }

    .page-item.active .page-link {
        background: #2D4194;
        color: white;
        border-color: #2D4194;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .filter-card-header {
        background: linear-gradient(135deg, #2D4194 0%, #1e2f6f 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-card-body {
        padding: 1.5rem;
        background: #fafbfc;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-select-custom {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        color: #374151;
        transition: all 0.2s;
        cursor: pointer;
    }

    .form-select-custom:hover {
        border-color: #2D4194;
    }

    .form-select-custom:focus {
        border-color: #2D4194;
        box-shadow: 0 0 0 4px rgba(45, 65, 148, 0.1);
        outline: none;
    }

    .btn-filter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: #2D4194;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: #1e2f6f;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 65, 148, 0.3);
    }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        background: #f3f4f6;
        color: #6b7280;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        margin-left: 0.5rem;
    }

    .btn-reset:hover {
        background: #e5e7eb;
        color: #374151;
    }

    .selected-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        font-size: 0.8125rem;
        color: #166534;
    }

    .selected-info i {
        font-size: 1rem;
    }

    .selected-info strong {
        color: #15803d;
    }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('.floor-row');

        rows.forEach(row => {
            const floorName = row.querySelector('.floor-name').textContent.toLowerCase();
            row.style.display = floorName.includes(searchText) ? '' : 'none';
        });
    });

    // Auto submit on building select (opsional, bisa dihapus jika ingin pakai tombol)
    // document.getElementById('buildingSelect')?.addEventListener('change', function() {
    //     if(this.value) {
    //         document.getElementById('filterForm').submit();
    //     }
    // });
</script>
@endsection