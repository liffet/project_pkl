@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937;">Daftar Laporan Kerusakan Perangkat</h2>
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Tombol Export Excel -->
            <a href="{{ route('damage-reports.export.excel') }}" class="btn btn-sm btn-primary" style="background-color:#2D4194;border:none;">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        @php
        $stats = [
            ['label' => 'Total Laporan', 'value' => $totalReports ?? 0, 'icon' => 'bi-file-text', 'color' => 'primary'],
            ['label' => 'Pending', 'value' => $pendingReports ?? 0, 'icon' => 'bi-clock-history', 'color' => 'warning'],
            ['label' => 'Diterima', 'value' => $acceptedReports ?? 0, 'icon' => 'bi-check-circle', 'color' => 'success'],
            ['label' => 'Ditolak', 'value' => $rejectedReports ?? 0, 'icon' => 'bi-x-circle', 'color' => 'danger'],
        ];
        @endphp

        @foreach ($stats as $s)
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stats-label">
                            <span class="stats-bullet {{ $s['color'] }}"></span>
                            {{ $s['label'] }}
                        </div>
                        <div class="stats-value text-{{ $s['color'] }}">{{ $s['value'] }}</div>
                    </div>
                    <div class="stats-icon {{ $s['color'] }}">
                        <i class="bi {{ $s['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
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
        <ul class="nav custom-tabs" id="reportsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'all' ? 'active' : '' }}" 
                        id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                    Semua Laporan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'pending' ? 'active' : '' }}" 
                        id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                    Pending
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'accepted' ? 'active' : '' }}" 
                        id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted" type="button" role="tab">
                    Diterima
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="custom-tab {{ $activeTab === 'rejected' ? 'active' : '' }}" 
                        id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
                    Ditolak
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
                                <th>No</th>
                                <th>User</th>
                                <th>Perangkat</th>
                                <th>Kode Perangkat</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Lantai</th>
                                <th>Alasan Kerusakan</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Tanggal Laporan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allReports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                              
                                <td>{{ $report->user->name ?? '-' }}</td>
                                <td>{{ $report->item->name ?? '-' }}</td>
                                <td>{{ $report->item->code ?? '-' }}</td>
                                <td>{{ $report->item->category->name ?? '-' }}</td>
                                <td>{{ $report->item->room->name ?? '-' }}</td>
                                <td>{{ $report->item->floor->name ?? '-' }}</td>
                                <td>{{ $report->reason }}</td>
                                  <td>
                                    @if($report->photo)
                                        <img src="{{ asset('storage/' . $report->photo) }}" 
                                             alt="Foto Kerusakan" 
                                             class="damage-photo-thumbnail"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModal{{ $report->id }}">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->status == 'accepted')
                                    <span class="badge-status success">Diterima</span>
                                    @elseif($report->status == 'rejected')
                                    <span class="badge-status danger">Ditolak</span>
                                    @else
                                    <span class="badge-status warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>
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

                            <!-- Modal untuk foto -->
                            @if($report->photo)
                            <div class="modal fade" id="photoModal{{ $report->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Foto Kerusakan - {{ $report->item->name ?? 'Perangkat' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $report->photo) }}" 
                                                 alt="Foto Kerusakan" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 70vh;">
                                            <div class="mt-3 text-start">
                                                <p class="mb-1"><strong>Alasan:</strong> {{ $report->reason }}</p>
                                                <p class="mb-0"><strong>Dilaporkan oleh:</strong> {{ $report->user->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-5 text-muted">Tidak ada laporan kerusakan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $allReports->firstItem() ?? 0 }} - {{ $allReports->lastItem() ?? 0 }} dari {{ $allReports->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $allReports->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($allReports->previousPageUrl(), 'all') }}">Sebelumnya</a>
                            </li>
                            @foreach ($allReports->getUrlRange(1, $allReports->lastPage()) as $page => $url)
                            <li class="page-item {{ $allReports->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ appendTab($url, 'all') }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{ $allReports->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($allReports->nextPageUrl(), 'all') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Pending Tab -->
            <div class="tab-pane fade {{ $activeTab === 'pending' ? 'show active' : '' }}" id="pending" role="tabpanel">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Perangkat</th>
                                <th>Kode Perangkat</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Lantai</th>
                                <th>Alasan Kerusakan</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Tanggal Laporan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingList as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                               
                                <td>{{ $report->user->name ?? '-' }}</td>
                                <td>{{ $report->item->name ?? '-' }}</td>
                                <td>{{ $report->item->code ?? '-' }}</td>
                                <td>{{ $report->item->category->name ?? '-' }}</td>
                                <td>{{ $report->item->room->name ?? '-' }}</td>
                                <td>{{ $report->item->floor->name ?? '-' }}</td>
                                <td>{{ $report->reason }}</td>
                                 <td>
                                    @if($report->photo)
                                        <img src="{{ asset('storage/' . $report->photo) }}" 
                                             alt="Foto Kerusakan" 
                                             class="damage-photo-thumbnail"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModalPending{{ $report->id }}">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td><span class="badge-status warning">Pending</span></td>
                                <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="accepted">
                                            <button class="btn-action success">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="btn-action danger">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal untuk foto -->
                            @if($report->photo)
                            <div class="modal fade" id="photoModalPending{{ $report->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Foto Kerusakan - {{ $report->item->name ?? 'Perangkat' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $report->photo) }}" 
                                                 alt="Foto Kerusakan" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 70vh;">
                                            <div class="mt-3 text-start">
                                                <p class="mb-1"><strong>Alasan:</strong> {{ $report->reason }}</p>
                                                <p class="mb-0"><strong>Dilaporkan oleh:</strong> {{ $report->user->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-5 text-muted">Tidak ada laporan pending</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $pendingList->firstItem() ?? 0 }} - {{ $pendingList->lastItem() ?? 0 }} dari {{ $pendingList->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $pendingList->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($pendingList->previousPageUrl(), 'pending') }}">Sebelumnya</a>
                            </li>
                            @foreach ($pendingList->getUrlRange(1, $pendingList->lastPage()) as $page => $url)
                            <li class="page-item {{ $pendingList->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ appendTab($url, 'pending') }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{ $pendingList->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($pendingList->nextPageUrl(), 'pending') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Accepted Tab -->
            <div class="tab-pane fade {{ $activeTab === 'accepted' ? 'show active' : '' }}" id="accepted" role="tabpanel">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Perangkat</th>
                                <th>Kode Perangkat</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Lantai</th>
                                <th>Alasan Kerusakan</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Tanggal Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($acceptedList as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                
                                <td>{{ $report->user->name ?? '-' }}</td>
                                <td>{{ $report->item->name ?? '-' }}</td>
                                <td>{{ $report->item->code ?? '-' }}</td>
                                <td>{{ $report->item->category->name ?? '-' }}</td>
                                <td>{{ $report->item->room->name ?? '-' }}</td>
                                <td>{{ $report->item->floor->name ?? '-' }}</td>
                                <td>{{ $report->reason }}</td>
                                <td>
                                    @if($report->photo)
                                        <img src="{{ asset('storage/' . $report->photo) }}" 
                                             alt="Foto Kerusakan" 
                                             class="damage-photo-thumbnail"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModalAccepted{{ $report->id }}">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td><span class="badge-status success">Diterima</span></td>
                                <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>
                            </tr>

                            <!-- Modal untuk foto -->
                            @if($report->photo)
                            <div class="modal fade" id="photoModalAccepted{{ $report->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Foto Kerusakan - {{ $report->item->name ?? 'Perangkat' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $report->photo) }}" 
                                                 alt="Foto Kerusakan" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 70vh;">
                                            <div class="mt-3 text-start">
                                                <p class="mb-1"><strong>Alasan:</strong> {{ $report->reason }}</p>
                                                <p class="mb-0"><strong>Dilaporkan oleh:</strong> {{ $report->user->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">Tidak ada laporan yang diterima</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $acceptedList->firstItem() ?? 0 }} - {{ $acceptedList->lastItem() ?? 0 }} dari {{ $acceptedList->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $acceptedList->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($acceptedList->previousPageUrl(), 'accepted') }}">Sebelumnya</a>
                            </li>
                            @foreach ($acceptedList->getUrlRange(1, $acceptedList->lastPage()) as $page => $url)
                            <li class="page-item {{ $acceptedList->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ appendTab($url, 'accepted') }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{ $acceptedList->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($acceptedList->nextPageUrl(), 'accepted') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Rejected Tab -->
            <div class="tab-pane fade {{ $activeTab === 'rejected' ? 'show active' : '' }}" id="rejected" role="tabpanel">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Perangkat</th>
                                <th>Kode Perangkat</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Lantai</th>
                                <th>Alasan Kerusakan</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Tanggal Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rejectedList as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                              
                                <td>{{ $report->user->name ?? '-' }}</td>
                                <td>{{ $report->item->name ?? '-' }}</td>
                                <td>{{ $report->item->code ?? '-' }}</td>
                                <td>{{ $report->item->category->name ?? '-' }}</td>
                                <td>{{ $report->item->room->name ?? '-' }}</td>
                                <td>{{ $report->item->floor->name ?? '-' }}</td>
                                <td>{{ $report->reason }}</td>
                                  <td>
                                    @if($report->photo)
                                        <img src="{{ asset('storage/' . $report->photo) }}" 
                                             alt="Foto Kerusakan" 
                                             class="damage-photo-thumbnail"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModalRejected{{ $report->id }}">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td><span class="badge-status danger">Ditolak</span></td>
                                <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>
                            </tr>

                            <!-- Modal untuk foto -->
                            @if($report->photo)
                            <div class="modal fade" id="photoModalRejected{{ $report->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Foto Kerusakan - {{ $report->item->name ?? 'Perangkat' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $report->photo) }}" 
                                                 alt="Foto Kerusakan" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 70vh;">
                                            <div class="mt-3 text-start">
                                                <p class="mb-1"><strong>Alasan:</strong> {{ $report->reason }}</p>
                                                <p class="mb-0"><strong>Dilaporkan oleh:</strong> {{ $report->user->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">Tidak ada laporan yang ditolak</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer d-flex justify-content-between align-items-center">
                    <small class="pagination-info">
                        Menampilkan {{ $rejectedList->firstItem() ?? 0 }} - {{ $rejectedList->lastItem() ?? 0 }} dari {{ $rejectedList->total() ?? 0 }} data
                    </small>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item {{ $rejectedList->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ appendTab($rejectedList->previousPageUrl(), 'rejected') }}">Sebelumnya</a>
                            </li>
                            @foreach ($rejectedList->getUrlRange(1, $rejectedList->lastPage()) as $page => $url)
                            <li class="page-item {{ $rejectedList->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ appendTab($url, 'rejected') }}">{{ $page }}</a>
                            </li>
                            @endforeach
                            <li class="page-item {{ $rejectedList->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ appendTab($rejectedList->nextPageUrl(), 'rejected') }}">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Photo Thumbnail */
    .damage-photo-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.2s;
    }

    .damage-photo-thumbnail:hover {
        transform: scale(1.1);
        border-color: #2D4194;
        box-shadow: 0 4px 12px rgba(45, 65, 148, 0.2);
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        border-radius: 12px 12px 0 0;
    }

    .modal-body img {
        border: 1px solid #e5e7eb;
    }

    /* Warna Khusus Tiap Tab */
    #all-tab.active {
        color: #2D4194;
        border-bottom-color: #2D4194;
    }

    #pending-tab.active {
        color: #f59e0b;
        border-bottom-color: #f59e0b;
    }

    #accepted-tab.active {
        color: #10b981;
        border-bottom-color: #10b981;
    }

    #rejected-tab.active {
        color: #ef4444;
        border-bottom-color: #ef4444;
    }

    /* Stats Cards */
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .stats-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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

    .stats-bullet.primary {
        background: #2D4194;
    }

    .stats-bullet.success {
        background: #10b981;
    }

    .stats-bullet.warning {
        background: #f59e0b;
    }

    .stats-bullet.danger {
        background: #ef4444;
    }

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

    /* Tabs */
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