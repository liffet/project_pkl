    @extends('layouts.app')

    @section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Daftar Laporan Kerusakan</h2>
            </div>
            <div class="text-end">
                <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
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
                                Total Laporan
                            </div>
                            <div class="stats-value">{{ $totalReports ?? 0 }}</div>
                        </div>
                        <div class="stats-icon primary">
                            <i class="bi bi-file-text"></i>
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
                                Pending
                            </div>
                            <div class="stats-value text-warning">{{ $pendingReports ?? 0 }}</div>
                        </div>
                        <div class="stats-icon warning">
                            <i class="bi bi-clock-history"></i>
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
                                Diterima
                            </div>
                            <div class="stats-value text-success">{{ $acceptedReports ?? 0 }}</div>
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
                                <span class="stats-bullet danger"></span>
                                Ditolak
                            </div>
                            <div class="stats-value text-danger">{{ $rejectedReports ?? 0 }}</div>
                        </div>
                        <div class="stats-icon danger">
                            <i class="bi bi-x-circle"></i>
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
                            <th>No</th>
                            <th>User</th>
                            <th>Perangkat</th>
                            <th>Kategori</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $key => $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $report->user->name }}</td>
                                <td>{{ $report->device }}</td>
                                <td>{{ $report->category->name ?? '-' }}</td>
                                <td>{{ $report->reason }}</td>
                                <td>
                                    @if($report->status == 'accepted')
                                        <span class="badge-status success">Diterima</span>
                                    @elseif($report->status == 'rejected')
                                        <span class="badge-status danger">Ditolak</span>
                                    @else
                                        <span class="badge-status warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="accepted">
                                            <button class="btn-action success" {{ $report->status != 'pending' ? 'disabled' : '' }}>
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="btn-action danger" {{ $report->status != 'pending' ? 'disabled' : '' }}>
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Tidak ada data laporan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($reports, 'links'))
            <div class="table-footer d-flex justify-content-between align-items-center">
                <small class="pagination-info">
                    Menampilkan {{ $reports->firstItem() ?? 0 }} - {{ $reports->lastItem() ?? 0 }} dari {{ $reports->total() ?? 0 }} data
                </small>
                <nav>
                    <ul class="pagination">
                        <li class="page-item {{ $reports->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $reports->previousPageUrl() }}">Sebelumnya</a>
                        </li>
                        @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                            <li class="page-item {{ $reports->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ $reports->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $reports->nextPageUrl() }}">Berikutnya</a>
                        </li>
                    </ul>
                </nav>
            </div>
            @else
            <div class="table-footer">
                <small class="pagination-info">
                    Menampilkan {{ $reports->count() }} data
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
}
.custom-table tbody tr:hover {
    background: #fafbfc;
}

/* Badges */
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
}
.btn-action.success {
    background: #d1fae5;
    color: #065f46;
}
.btn-action.success:hover:not(:disabled) {
    background: #10b981;
    color: white;
}
.btn-action.danger {
    background: #fee2e2;
    color: #991b1b;
}
.btn-action.danger:hover:not(:disabled) {
    background: #ef4444;
    color: white;
}
.btn-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
}
.alert-success {
    background: #d1fae5;
    color: #065f46;
}
</style>
@endsection 