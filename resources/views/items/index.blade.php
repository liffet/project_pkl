@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Manajemen Perangkat</h2>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('items.create') }}" class="btn-add">
                <i class="bi bi-plus-lg"></i>
                Tambah Perangkat
            </a>
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
                            <td>{{ $item->room ?? '-' }}</td>
                            <td>{{ $item->floor ?? '-' }}</td>
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
                                <p class="mt-2 mb-0">Tidak ada data perangkat</p>
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