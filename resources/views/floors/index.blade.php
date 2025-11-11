@extends('layouts.app')

@section('title', 'Daftar Lantai')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div>
        <a href="{{ route('floors.export.excel') }}" class="btn btn-success mb-3">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Lantai</h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Kelola lantai gedung Anda</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('floors.create') }}" class="btn-add">
                <i class="bi bi-plus-lg"></i>
                Tambah Lantai
            </a>
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
                <input type="text" class="form-control" placeholder="Cari lantai..." id="searchInput">
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10%">No</th>
                        <th>Lantai</th>
                        <th class="text-center" style="width: 20%">Aksi</th>
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
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('floors.edit', $floor) }}" class="btn-action warning" title="Edit lantai">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-action danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $floor->id }}" title="Hapus lantai">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $floor->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title d-flex align-items-center">
                                                <div class="modal-icon warning me-3">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                </div>
                                                Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body pt-2">
                                            <p class="text-muted mb-2">Apakah Anda yakin ingin menghapus lantai:</p>
                                            <p class="fw-bold mb-0" style="color: #2D4194;">{{ $floor->name }}</p>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                            <form action="{{ route('floors.destroy', $floor) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger-custom">
                                                    <i class="bi bi-trash me-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-building" style="font-size: 4rem; opacity: 0.2; color: #6b7280;"></i>
                                <h5 class="text-muted mt-3 mb-2">Belum ada lantai</h5>
                                <p class="text-muted mb-3" style="font-size: 0.875rem;">Mulai dengan menambahkan lantai pertama Anda</p>
                                <a href="{{ route('floors.create') }}" class="btn-add">
                                    <i class="bi bi-plus-lg"></i>Tambah Lantai
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <!-- Pagination -->
        @if($floors->isNotEmpty())
        @if(method_exists($floors, 'links'))
        <div class="table-footer d-flex justify-content-between align-items-center">
            <small class="pagination-info">
                Menampilkan {{ $floors->firstItem() ?? 0 }} - {{ $floors->lastItem() ?? 0 }} dari {{ $floors->total() ?? 0 }} data
            </small>
            <nav>
                <ul class="pagination">
                    <li class="page-item {{ $floors->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $floors->previousPageUrl() }}">Sebelumnya</a>
                    </li>
                    @foreach ($floors->getUrlRange(1, $floors->lastPage()) as $page => $url)
                    <li class="page-item {{ $floors->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ $floors->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $floors->nextPageUrl() }}">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        </div>
        @else
        <div class="table-footer">
            <small class="pagination-info">
                Menampilkan {{ $floors->count() }} lantai
            </small>
        </div>
        @endif
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

    .btn-action.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-action.warning:hover {
        background: #f59e0b;
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

    /* Modal */
    .modal-content {
        border-radius: 12px;
    }

    .modal-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .modal-icon.warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .btn-secondary-custom {
        padding: 0.5rem 1.25rem;
        background: #f3f4f6;
        color: #374151;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-secondary-custom:hover {
        background: #e5e7eb;
    }

    .btn-danger-custom {
        padding: 0.5rem 1.25rem;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-danger-custom:hover {
        background: #dc2626;
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
        const rows = document.querySelectorAll('.floor-row');

        rows.forEach(row => {
            const floorName = row.querySelector('.floor-name').textContent.toLowerCase();
            row.style.display = floorName.includes(searchText) ? '' : 'none';
        });
    });
</script>
@endsection