@extends('layouts.app')

@section('title', 'Daftar Ruangan')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Ruangan</h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Kelola ruangan di setiap lantai</p>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <!-- Tombol Export Excel -->
            <a href="{{ route('rooms.export.excel') }}"
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

            <!-- Tombol Tambah Ruangan -->
            <a href="{{ route('rooms.create') }}" class="btn-add">
                <i class="bi bi-plus-lg"></i>
                Tambah Ruangan
            </a>

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
                <input type="text" class="form-control" placeholder="Cari ruangan..." id="searchInput">
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10%">No</th>
                        <th>Nama Ruangan</th>
                        <th style="width: 25%">Lantai</th>
                        <th class="text-center" style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr class="room-row">
                        <td>
                            <span class="badge-code">{{ $loop->iteration }}</span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle">
                                    {{ strtoupper(substr($room->name, 0, 1)) }}
                                </div>
                                <span class="room-name fw-medium ms-3">{{ $room->name }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building me-2" style="color: #6b7280;"></i>
                                <span class="floor-badge">{{ $room->floor->name ?? '-' }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('rooms.edit', $room) }}" class="btn-action warning" title="Edit ruangan">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-action danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $room->id }}" title="Hapus ruangan">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $room->id }}" tabindex="-1">
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
                                            <p class="text-muted mb-2">Apakah Anda yakin ingin menghapus ruangan:</p>
                                            <p class="fw-bold mb-1" style="color: #2D4194;">{{ $room->name }}</p>
                                            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                                                <i class="bi bi-building me-1"></i>Lantai: {{ $room->floor->name ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline">
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
                        <td colspan="4" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-door-closed" style="font-size: 4rem; opacity: 0.2; color: #6b7280;"></i>
                                <h5 class="text-muted mt-3 mb-2">Belum ada ruangan</h5>
                                <p class="text-muted mb-3" style="font-size: 0.875rem;">Mulai dengan menambahkan ruangan pertama Anda</p>
                                <a href="{{ route('rooms.create') }}" class="btn-add">
                                    <i class="bi bi-plus-lg"></i>Tambah Ruangan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <!-- Pagination -->
        @if($rooms->isNotEmpty())
        @if(method_exists($rooms, 'links'))
        <div class="table-footer d-flex justify-content-between align-items-center">
            <small class="pagination-info">
                Menampilkan {{ $rooms->firstItem() ?? 0 }} - {{ $rooms->lastItem() ?? 0 }} dari {{ $rooms->total() ?? 0 }} data
            </small>
            <nav>
                <ul class="pagination">
                    <li class="page-item {{ $rooms->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $rooms->previousPageUrl() }}">Sebelumnya</a>
                    </li>
                    @foreach ($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)
                    <li class="page-item {{ $rooms->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ $rooms->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $rooms->nextPageUrl() }}">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        </div>
        @else
        <div class="table-footer">
            <small class="pagination-info">
                Menampilkan {{ $rooms->count() }} ruangan
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

    /* Floor Badge */
    .floor-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        background: #eff6ff;
        color: #1e40af;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 500;
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
        const rows = document.querySelectorAll('.room-row');

        rows.forEach(row => {
            const roomName = row.querySelector('.room-name').textContent.toLowerCase();
            const floorBadge = row.querySelector('.floor-badge').textContent.toLowerCase();
            const searchableText = roomName + ' ' + floorBadge;
            
            row.style.display = searchableText.includes(searchText) ? '' : 'none';
        });
    });
</script>
@endsection