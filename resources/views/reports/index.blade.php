@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">
                Daftar Laporan Kerusakan Perangkat
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Kelola dan pantau laporan kerusakan perangkat yang diajukan pengguna
            </p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- Export Excel dengan Filter -->
            <a href="{{ route('damage-reports.export.excel', request()->all()) }}"
               class="btn shadow-sm d-flex align-items-center px-3 py-2 fw-semibold"
               style="background-color:#2D4194;color:white;border:none;border-radius:8px;">
                <i class="bi bi-file-earmark-excel me-2"></i>
                Export Excel
            </a>
            <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        @php
        $stats = [
            ['label' => 'Total Laporan', 'value' => $totalReports ?? 0, 'icon' => 'bi-file-text', 'color' => 'secondary'],
            ['label' => 'Pending', 'value' => $pendingReports ?? 0, 'icon' => 'bi-clock-history', 'color' => 'warning'],
            ['label' => 'Diterima', 'value' => $acceptedReports ?? 0, 'icon' => 'bi-check-circle', 'color' => 'success'],
            ['label' => 'Dalam Proses', 'value' => $inProgressReports ?? 0, 'icon' => 'bi-tools', 'color' => 'info'],
            ['label' => 'Selesai', 'value' => $completedReports ?? 0, 'icon' => 'bi-clipboard-check', 'color' => 'primary'],
            ['label' => 'Ditolak', 'value' => $rejectedReports ?? 0, 'icon' => 'bi-x-circle', 'color' => 'danger'],
        ];
        @endphp

        @foreach ($stats as $s)
        <div class="col-lg-2 col-md-4 col-sm-6">
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

    <!-- Filter Section -->
    <div class="mb-3">
        <div class="d-flex gap-2">
            <button class="btn-filter" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-funnel"></i> Filter Data

                @php
                    $filterCount = count(array_filter(request()->only([
                        'item_name','category_id','status','building_id','floor_id','room_id','user_id'
                    ])));
                @endphp

                @if($filterCount > 0)
                    <span class="filter-badge">{{ $filterCount }}</span>
                @endif
            </button>

            @if($filterCount > 0)
                <a href="{{ route('damage-reports.index') }}" class="btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                </a>
            @endif
        </div>

        @if($filterCount > 0)
            <div class="active-filters mt-3">
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Filter Aktif:</small>

                <div class="d-flex flex-wrap gap-2 mt-2">
                    @if(request('item_name'))
                        <span class="filter-chip">
                            <i class="bi bi-search"></i> Nama Item: {{ request('item_name') }}
                        </span>
                    @endif

                    @if(request('category_id'))
                        <span class="filter-chip">
                            <i class="bi bi-tags"></i>
                            Kategori: {{ optional($categories->firstWhere('id', request('category_id')))->name ?? '-' }}
                        </span>
                    @endif

                    @if(request('status'))
                        <span class="filter-chip">
                            <i class="bi bi-info-circle"></i>
                            Status: 
                            @switch(request('status'))
                                @case('pending') Pending @break
                                @case('accepted') Diterima @break
                                @case('in_progress') Dalam Proses @break
                                @case('completed') Selesai @break
                                @case('rejected') Ditolak @break
                            @endswitch
                        </span>
                    @endif

                    @if(request('building_id'))
                        <span class="filter-chip">
                            <i class="bi bi-building"></i>
                            Gedung: {{ optional($buildings->firstWhere('id', request('building_id')))->name ?? '-' }}
                        </span>
                    @endif

                    @if(request('floor_id'))
                        <span class="filter-chip">
                            <i class="bi bi-building"></i>
                            Lantai: {{ optional($floors->firstWhere('id', request('floor_id')))->name ?? '-' }}
                        </span>
                    @endif

                    @if(request('room_id'))
                        <span class="filter-chip">
                            <i class="bi bi-door-open"></i>
                            Ruangan: {{ optional($rooms->firstWhere('id', request('room_id')))->name ?? '-' }}
                        </span>
                    @endif

                    @if(request('user_id'))
                        <span class="filter-chip">
                            <i class="bi bi-person"></i>
                            Pelapor: {{ optional($users->firstWhere('id', request('user_id')))->name ?? '-' }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="GET" action="{{ route('damage-reports.index') }}">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-funnel me-2"></i>Filter Laporan Kerusakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-filter">Nama Item</label>
                                <input type="text" name="item_name" class="form-control"
                                       placeholder="Cari nama item..."
                                       value="{{ request('item_name') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-filter">Kategori</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-filter">Status Laporan</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                                    <option value="accepted" {{ request('status')=='accepted'?'selected':'' }}>Diterima</option>
                                    <option value="in_progress" {{ request('status')=='in_progress'?'selected':'' }}>Dalam Proses</option>
                                    <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Selesai</option>
                                    <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-filter">Gedung</label>
                                <select name="building_id" class="form-select">
                                    <option value="">Semua Gedung</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}" {{ request('building_id')==$building->id?'selected':'' }}>
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label-filter">Lantai</label>
                                <select name="floor_id" class="form-select">
                                    <option value="">Semua Lantai</option>
                                    @foreach($floors as $floor)
                                        <option value="{{ $floor->id }}" {{ request('floor_id')==$floor->id?'selected':'' }}>
                                            {{ $floor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-filter">Ruangan</label>
                                <select name="room_id" class="form-select">
                                    <option value="">Semua Ruangan</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ request('room_id')==$room->id?'selected':'' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-filter">Pelapor</label>
                                <select name="user_id" class="form-select">
                                    <option value="">Semua Pelapor</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id')==$user->id?'selected':'' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
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
                        <th>No</th>
                        <th>Kode Item</th>
                        <th>Nama Item</th>
                        <th>Kategori</th>
                        <th>Gedung</th>
                        <th>Lantai</th>
                        <th>Ruangan</th>
                        <th>Pelapor</th>
                        <th>Alasan</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $index => $report)
                    <tr>
                        <td>{{ $reports->firstItem() + $index }}</td>
                        <td><span class="badge-code">{{ $report->item->code ?? '-' }}</span></td>
                        <td class="fw-medium">{{ $report->item->name ?? '-' }}</td>
                        <td><span class="badge-category">{{ $report->item->category->name ?? '-' }}</span></td>
                        <td>{{ $report->item->building->name ?? '-' }}</td>
                        <td>{{ $report->item->floor->name ?? '-' }}</td>
                        <td>{{ $report->item->room->name ?? '-' }}</td>
                        <td>{{ $report->user->name ?? '-' }}</td>
                        <td>{{ Str::limit($report->reason, 40) }}</td>
                        <td>
                            @if($report->photo)
                            <img src="{{ asset('storage/' . $report->photo) }}"
                                alt="Foto Kerusakan"
                                class="damage-photo-thumbnail"
                                data-bs-toggle="modal"
                                data-bs-target="#photoModal{{ $report->id }}">
                            @else
                            <div class="no-image"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td>
                            @switch($report->status)
                                @case('pending')
                                    <span class="badge-status warning">Pending</span>
                                    @break
                                @case('accepted')
                                    <span class="badge-status success">Diterima</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge-status info">Dalam Proses</span>
                                    @break
                                @case('completed')
                                    <span class="badge-status primary">Selesai</span>
                                    @break
                                @case('rejected')
                                    <span class="badge-status danger">Ditolak</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Pending: Accept/Reject -->
                                @if($report->status === 'pending')
                                    <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="accepted">
                                        <button class="btn-action success" onclick="return confirm('Terima laporan ini?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn-action danger" onclick="return confirm('Tolak laporan ini?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Accepted: Start Progress -->
                                @if($report->status === 'accepted')
                                    <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button class="btn-action info" onclick="return confirm('Mulai proses perbaikan?')">
                                            <i class="bi bi-tools"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- In Progress: Mark Complete -->
                                @if($report->status === 'in_progress')
                                    <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button class="btn-action primary" onclick="return confirm('Tandai sebagai selesai?')">
                                            <i class="bi bi-clipboard-check"></i>
                                        </button>
                                    </form>
                                @endif
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
                        <td colspan="12" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size:3rem;opacity:0.3;"></i>
                            <p class="mt-2">
                                {{ $filterCount>0 ? 'Tidak ada laporan sesuai filter' : 'Tidak ada laporan kerusakan' }}
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($reports,'links'))
            <div class="table-footer d-flex justify-content-between align-items-center">
                <small>Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} data</small>
                {{ $reports->appends(request()->all())->links() }}
            </div>
        @endif
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

    .stats-bullet.primary { background: #3b82f6; }
    .stats-bullet.secondary { background: #6b7280; }
    .stats-bullet.success { background: #10b981; }
    .stats-bullet.warning { background: #f59e0b; }
    .stats-bullet.danger { background: #ef4444; }
    .stats-bullet.info { background: #0ea5e9; }

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

    .stats-icon.primary { background: #dbeafe; color: #3b82f6; }
    .stats-icon.secondary { background: #f3f4f6; color: #6b7280; }
    .stats-icon.success { background: #d1fae5; color: #10b981; }
    .stats-icon.warning { background: #fef3c7; color: #f59e0b; }
    .stats-icon.danger { background: #fee2e2; color: #ef4444; }
    .stats-icon.info { background: #e0f2fe; color: #0ea5e9; }

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
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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

    .modal-body img {
        border: 1px solid #e5e7eb;
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

    .badge-status.primary { background: #dbeafe; color: #1e40af; }
    .badge-status.success { background: #d1fae5; color: #065f46; }
    .badge-status.warning { background: #fef3c7; color: #92400e; }
    .badge-status.danger { background: #fee2e2; color: #991b1b; }
    .badge-status.info { background: #e0f2fe; color: #075985; }

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

    .btn-action.info {
        background: #e0f2fe;
        color: #075985;
    }

    .btn-action.info:hover {
        background: #0ea5e9;
        color: white;
    }

    .btn-action.primary {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-action.primary:hover {
        background: #3b82f6;
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
        border: 1px solid;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }
</style>
@endsection