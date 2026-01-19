@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Dashboard Maintenance</h2>
        </div>
        <div class="text-end">
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
        </div>
    </div>

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
                            Masih Aman
                        </div>
                        <div class="stats-value text-success">{{ $safeItems ?? 0 }}</div>
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
                            Hampir Maintenance
                        </div>
                        <div class="stats-value text-warning">{{ $soonMaintenance ?? 0 }}</div>
                    </div>
                    <div class="stats-icon warning">
                        <i class="bi bi-clock"></i>
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
                            Harus Maintenance
                        </div>
                        <div class="stats-value text-danger">{{ $maintenanceNow ?? 0 }}</div>
                    </div>
                    <div class="stats-icon danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card with Tabs -->
    <div class="table-card">
        <!-- Helper PHP untuk append tab ke URL -->
        @php
            function appendTab($url, $tab) {
                if (!$url) return '#';
                $separator = strpos($url, '?') !== false ? '&' : '?';
                return $url . $separator . 'tab=' . $tab;
            }
        @endphp

        <!-- Tabs -->
        <ul class="nav custom-tabs" id="maintenanceTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'all' ? 'active' : '' }}" 
                        id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                    Lainnya
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'safe' ? 'active' : '' }}" 
                        id="safe-tab" data-bs-toggle="tab" data-bs-target="#safe" type="button" role="tab">
                    Masih Aman
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'soon' ? 'active' : '' }}" 
                        id="soon-tab" data-bs-toggle="tab" data-bs-target="#soon" type="button" role="tab">
                    Hampir Maintenance
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'maintenance' ? 'active' : '' }}" 
                        id="urgent-tab" data-bs-toggle="tab" data-bs-target="#urgent" type="button" role="tab">
                    Harus Maintenance
                </button>
            </li>
        </ul>

        <div class="tab-content">

            <!-- All Tab -->
            <div class="tab-pane fade {{ $activeTab === 'all' ? 'show active' : '' }}" id="all" role="tabpanel">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Gedung</th>
                                <th>Lantai</th>
                                <th>Ruang</th>
                                <th>Tanggal Pasang</th>
                                <th>Tanggal Maintenance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allItems as $item)
                                @php
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->replacement_date), false);
                                    if ($daysLeft < 0) {
                                        $statusClass = 'danger';
                                        $statusText = 'Harus Maintenance';
                                    } elseif ($daysLeft <= 7) {
                                        $statusClass = 'warning';
                                        $statusText = 'Hampir Maintenance';
                                    } else {
                                        $statusClass = 'success';
                                        $statusText = 'Masih Aman';
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>{{ $item->building->name }}</td>
                                    <td>{{ $item->floor->name ?? '-' }}</td>
                                    <td>{{ $item->room->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->replacement_date)->format('d-m-Y') }}</td>
                                    <td><span class="badge-status {{ $statusClass }}">{{ $statusText }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $allItems->firstItem() ?? 0 }} - {{ $allItems->lastItem() ?? 0 }} dari {{ $allItems->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $allItems->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($allItems->previousPageUrl(), 'all') }}">Sebelumnya</a>
                            </li>
                            @foreach ($allItems->getUrlRange(1, $allItems->lastPage()) as $page => $url)
                                <li class="page-item {{ $allItems->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ appendTab($url, 'all') }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item {{ $allItems->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($allItems->nextPageUrl(), 'all') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Safe Tab -->
            <div class="tab-pane fade {{ $activeTab === 'safe' ? 'show active' : '' }}" id="safe" role="tabpanel">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Gedung</th>
                                <th>Lantai</th>
                                <th>Ruang</th>
                                <th>Tanggal Pasang</th>
                                <th>Tanggal Maintenance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($safeList as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>{{ $item->building->name }}</td>
                                    <td>{{ $item->floor->name ?? '-' }}</td>
                                    <td>{{ $item->room->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at ?? now())->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->replacement_date)->format('d-m-Y') }}</td>
                                    <td><span class="badge-status success">Masih Aman</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $safeList->firstItem() ?? 0 }} - {{ $safeList->lastItem() ?? 0 }} dari {{ $safeList->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $safeList->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($safeList->previousPageUrl(), 'safe') }}">Sebelumnya</a>
                            </li>
                            @foreach ($safeList->getUrlRange(1, $safeList->lastPage()) as $page => $url)
                                <li class="page-item {{ $safeList->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ appendTab($url, 'safe') }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item {{ $safeList->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($safeList->nextPageUrl(), 'safe') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Soon Tab -->
            <div class="tab-pane fade {{ $activeTab === 'soon' ? 'show active' : '' }}" id="soon" role="tabpanel">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Gedung</th>
                                <th>Lantai</th>
                                <th>Ruang</th>
                                <th>Tanggal Pasang</th>
                                <th>Tanggal Maintenance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($soonList as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>{{ $item->building->name }}</td>
                                    <td>{{ $item->floor->name ?? '-' }}</td>
                                    <td>{{ $item->room->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at ?? now())->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->replacement_date)->format('d-m-Y') }}</td>
                                    <td><span class="badge-status warning">Hampir Maintenance</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $soonList->firstItem() ?? 0 }} - {{ $soonList->lastItem() ?? 0 }} dari {{ $soonList->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $soonList->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($soonList->previousPageUrl(), 'soon') }}">Sebelumnya</a>
                            </li>
                            @foreach ($soonList->getUrlRange(1, $soonList->lastPage()) as $page => $url)
                                <li class="page-item {{ $soonList->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ appendTab($url, 'soon') }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item {{ $soonList->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($soonList->nextPageUrl(), 'soon') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Urgent Tab -->
            <div class="tab-pane fade {{ $activeTab === 'maintenance' ? 'show active' : '' }}" id="urgent" role="tabpanel">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Gedung</th>
                                <th>Lantai</th>
                                <th>Ruang</th>
                                <th>Tanggal Pasang</th>
                                <th>Tanggal Maintenance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($maintenanceList as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>{{ $item->building->name }}</td>
                                    <td>{{ $item->floor->name ?? '-' }}</td>
                                    <td>{{ $item->room->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at ?? now())->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->replacement_date)->format('d-m-Y') }}</td>
                                    <td><span class="badge-status danger">Harus Maintenance</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $maintenanceList->firstItem() ?? 0 }} - {{ $maintenanceList->lastItem() ?? 0 }} dari {{ $maintenanceList->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $maintenanceList->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($maintenanceList->previousPageUrl(), 'maintenance') }}">Sebelumnya</a>
                            </li>
                            @foreach ($maintenanceList->getUrlRange(1, $maintenanceList->lastPage()) as $page => $url)
                                <li class="page-item {{ $maintenanceList->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ appendTab($url, 'maintenance') }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item {{ $maintenanceList->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($maintenanceList->nextPageUrl(), 'maintenance') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
 


<style>

   
#safe-tab.active {
    color: #10b981; 
    border-bottom-color: #10b981;
}

#soon-tab.active {
    color: #f59e0b; 
    border-bottom-color: #f59e0b;
}

#urgent-tab.active {
    color: #ef4444;
    border-bottom-color: #ef4444;
}

#all-tab.active {
    color: #2D4194; 
    border-bottom-color: #2D4194;
}


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

.table-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}


.custom-tabs {
    display: flex;
    border-bottom: 1px solid #e5e7eb;
    padding: 0 1.5rem;
    background: #fafbfc;
    margin-bottom: 0;
}
.custom-tab {
    padding: 1rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
}
.custom-tab:hover {
    color: #2D4194;
}
.custom-tab.active {
    color: #2D4194;
    border-bottom-color: #2D4194;
}


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


.badge-code {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background: #f3f4f6;
    color: #374151;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 500;
    font-family: 'Monaco', monospace;
}
.badge-category {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f3f4f6;
    color: #6b7280;
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
</style>
@endsection