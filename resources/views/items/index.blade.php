@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">
                Manajemen Perangkat
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Kelola data perangkat yang tersedia, termasuk informasi, status, dan ketersediaannya.
            </p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <!-- Tombol Export Excel -->
            <a href="{{ route('damage-reports.export.excel') }}"
               class="btn shadow-sm d-flex align-items-center px-3 py-2 fw-semibold"
               style="
                   background-color: #2D4194;
                   color: white;
                   border: none;
                   border-radius: 8px;
                   font-size: 14px;
                   font-weight: 600;
                   transition: all 0.2s ease;
               "
               onmouseover="this.style.backgroundColor='#3E52B0'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 5px 12px rgba(45,65,148,0.35)';"
               onmouseout="this.style.backgroundColor='#2D4194'; this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 6px rgba(0,0,0,0.1)';">
                <i class="bi bi-file-earmark-excel me-2" style="font-size: 1rem;"></i>
                Export Excel
            </a>

            <!-- Tombol Tambah Perangkat -->
            <a href="{{ route('items.create') }}" class="btn-add">
                <i class="bi bi-plus-lg"></i>
                Tambah Perangkat
            </a>

            <!-- Tanggal -->
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
        </div>
    </div>



    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stats-label">
                            <span class="stats-bullet primary"></span>
                            Total Perangkat
                        </div>
                        <div class="stats-value">{{ $totalItems ?? 0 }}</div>
                    </div>
                    <div class="stats-icon primary">
                        <i class="bi bi-phone"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stats-label">
                            <span class="stats-bullet success"></span>
                            Aktif
                        </div>
                        <div class="stats-value text-success">{{ $activeItems ?? 0 }}</div>
                    </div>
                    <div class="stats-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stats-label">
                            <span class="stats-bullet warning"></span>
                            Tidak Aktif
                        </div>
                        <div class="stats-value text-warning">{{ $inactiveItems ?? 0 }}</div>
                    </div>
                    <div class="stats-icon warning">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stats-label">
                            <span class="stats-bullet danger"></span>
                            Perlu Maintenance
                        </div>
                        <div class="stats-value text-danger">{{ $needMaintenance ?? 0 }}</div>
                    </div>
                    <div class="stats-icon danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Button Section -->
    <div class="mb-3">
        <div class="d-flex gap-2 align-items-center">
            <button type="button" class="btn-filter" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-funnel"></i>
                Filter Data
                @if(request()->hasAny(['item_name', 'category_id', 'status', 'room_id', 'floor_id']))
                    <span class="filter-badge">{{ count(array_filter(request()->only(['item_name', 'category_id', 'status', 'room_id', 'floor_id']))) }}</span>
                @endif
            </button>
            
            @if(request()->hasAny(['item_name', 'category_id', 'status', 'room_id', 'floor_id']))
                <a href="{{ route('items.index') }}" class="btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset Filter
                </a>
            @endif
        </div>
        
        @if(request()->hasAny(['item_name', 'category_id', 'status', 'room_id', 'floor_id']))
            <div class="active-filters mt-3">
                <small class="text-muted d-block mb-2"><i class="bi bi-info-circle me-1"></i>Filter Aktif:</small>
                <div class="d-flex flex-wrap gap-2">
                    @if(request('item_name'))
                        <span class="filter-chip">
                            <i class="bi bi-search"></i> Nama: {{ request('item_name') }}
                        </span>
                    @endif
                    @if(request('category_id'))
                        <span class="filter-chip">
                            <i class="bi bi-tags"></i> Kategori: {{ $categories->find(request('category_id'))->name ?? '-' }}
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="filter-chip">
                            <i class="bi bi-toggle-on"></i> Status: {{ request('status') == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    @endif
                    @if(request('room_id'))
                        <span class="filter-chip">
                            <i class="bi bi-door-open"></i> Ruangan: {{ $rooms->find(request('room_id'))->name ?? '-' }}
                        </span>
                    @endif
                    @if(request('floor_id'))
                        <span class="filter-chip">
                            <i class="bi bi-building"></i> Lantai: {{ $floors->find(request('floor_id'))->name ?? '-' }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="bi bi-funnel me-2"></i>Filter Data Perangkat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('items.index') }}">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Filter Nama Perangkat -->
                            <div class="col-md-6">
                                <label class="form-label-filter">
                                    <i class="bi bi-search me-1"></i>Nama Perangkat
                                </label>
                                <input type="text" 
                                       name="item_name" 
                                       class="form-control" 
                                       placeholder="Cari nama perangkat..."
                                       value="{{ request('item_name') }}">
                            </div>

                            <!-- Filter Kategori -->
                            <div class="col-md-6">
                                <label class="form-label-filter">
                                    <i class="bi bi-tags me-1"></i>Kategori
                                </label>
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Status -->
                            <div class="col-md-4">
                                <label class="form-label-filter">
                                    <i class="bi bi-toggle-on me-1"></i>Status
                                </label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>

                            <!-- Filter Ruangan -->
                            <div class="col-md-4">
                                <label class="form-label-filter">
                                    <i class="bi bi-door-open me-1"></i>Ruangan
                                </label>
                                <select name="room_id" class="form-select">
                                    <option value="">Semua Ruangan</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Lantai -->
                            <div class="col-md-4">
                                <label class="form-label-filter">
                                    <i class="bi bi-building me-1"></i>Lantai
                                </label>
                                <select name="floor_id" class="form-select">
                                    <option value="">Semua Lantai</option>
                                    @foreach($floors as $floor)
                                        <option value="{{ $floor->id }}" {{ request('floor_id') == $floor->id ? 'selected' : '' }}>
                                            {{ $floor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Ruangan</th>
                        <th>Lantai</th>
                        <th>Tgl Pasang</th>
                        <th>Tgl Maintenance</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <span class="badge-code">{{ $item->code }}</span>
                            </td>
                            <td class="fw-medium">{{ $item->name }}</td>
                            <td>
                                <span class="badge-category">{{ $item->category->name }}</span>
                            </td>
                            <td>
                                @if($item->status == 'active')
                                    <span class="badge-status success">Aktif</span>
                                @else
                                    <span class="badge-status warning">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>{{$item->room->name }}</td>
                            <td>{{$item->floor->name}}</td>
                            <td>{{ \Carbon\Carbon::parse($item->install_date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->replacement_date)->format('d-m-Y') }}</td>
                            <td>
                                @if($item->photo)
                                    <img src="{{ asset('storage/'.$item->photo) }}" class="item-thumbnail" alt="{{ $item->name }}">
                                @else
                                    <div class="no-image">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('items.show', $item->id) }}" class="btn-action info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn-action warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action danger" title="Hapus" onclick="return confirm('Yakin hapus perangkat ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">
                                    @if(request()->hasAny(['item_name', 'category_id', 'status', 'room_id', 'floor_id']))
                                        Tidak ada perangkat yang sesuai dengan filter
                                    @else
                                        Tidak ada data perangkat
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($items, 'links'))
        <div class="table-footer d-flex justify-content-between align-items-center">
            <small class="pagination-info">
                Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() ?? 0 }} data
            </small>
            <nav>
                <ul class="pagination">
                    <li class="page-item {{ $items->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $items->previousPageUrl() }}">Sebelumnya</a>
                    </li>
                    @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                        <li class="page-item {{ $items->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $items->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $items->nextPageUrl() }}">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        </div>
        @else
        <div class="table-footer">
            <small class="pagination-info">
                Menampilkan {{ $items->count() }} data
            </small>
        </div>
        @endif
    </div>
</div>

<style>
/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}
.stats-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.stats-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.75rem;
}
.stats-bullet {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
.stats-bullet.primary { background: #2D4194; }
.stats-bullet.success { background: #10b981; }
.stats-bullet.warning { background: #f59e0b; }
.stats-bullet.danger { background: #ef4444; }

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}
.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.stats-icon.primary {
    background: #eff6ff;
    color: #2D4194;
}
.stats-icon.success {
    background: #d1fae5;
    color: #10b981;
}
.stats-icon.warning {
    background: #fef3c7;
    color: #f59e0b;
}
.stats-icon.danger {
    background: #fee2e2;
    color: #ef4444;
}

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

/* Filter Button */
.btn-filter {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: white;
    color: #2D4194;
    border: 2px solid #2D4194;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
    position: relative;
}
.btn-filter:hover {
    background: #2D4194;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45, 65, 148, 0.2);
}

/* Filter Badge Counter */
.filter-badge {
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    margin-left: 0.25rem;
}

/* Reset Button */
.btn-reset {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fecaca;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-reset:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Active Filters Display */
.active-filters {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.filter-chip i {
    color: #2D4194;
    font-size: 0.875rem;
}

/* Modal Styling */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}

.btn-success {
    background-color: #16a34a;
    border-color: #16a34a;
    color: white;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.2s;
}

.btn-success:hover {
    background-color: #15803d;
}

.modal-header {
    background: linear-gradient(135deg, #2D4194 0%, #1e2f6f 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 1.25rem 1.5rem;
    border-bottom: none;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
}

/* Form Labels in Modal */
.form-label-filter {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

/* Form Controls */
.form-control, .form-select {
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #2D4194;
    box-shadow: 0 0 0 3px rgba(45, 65, 148, 0.1);
    outline: none;
}

/* Modal Buttons */
.modal-footer .btn {
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-footer .btn-primary {
    background: #2D4194;
    border-color: #2D4194;
}

.modal-footer .btn-primary:hover {
    background: #1e2f6f;
    border-color: #1e2f6f;
}

.modal-footer .btn-secondary {
    background: #6b7280;
    border-color: #6b7280;
}

.modal-footer .btn-secondary:hover {
    background: #4b5563;
    border-color: #4b5563;
}

/* Table Card */
.table-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
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
.badge-status.danger {
    background: #fee2e2;
    color: #991b1b;
}

/* Image Thumbnail */
.item-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e5e7eb;
}
.no-image {
    width: 60px;
    height: 60px;
    background: #f3f4f6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 1.5rem;
}

/* Action Buttons */
.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}
.btn-action.info {
    background: #dbeafe;
    color: #1e40af;
}
.btn-action.info:hover {
    background: #3b82f6;
    color: white;
}
.btn-action.warning {
    background: #fef3c7;
    color: #92400e;
}
.btn-action.warning:hover {
    background: #f59e0b;
    color: white;
}
.btn-action.success {
    background: #d1fae5;
    color: #065f46;
}
.btn-action.success:hover {
    background: #10b981;
    color: white;
}
.btn-action.danger {
    background: #fee2e2;
    color: #991b1b;
}
.btn-action.danger:hover {
    background: #ef4444;
    color: white;
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
@endsection