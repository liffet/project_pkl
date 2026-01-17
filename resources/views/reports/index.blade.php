@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Laporan Kerusakan</h4>

        {{-- Statistik --}}
        <div class="d-flex gap-2">
            <span class="badge bg-secondary">Total: {{ $totalReports }}</span>
            <span class="badge bg-warning text-dark">Pending: {{ $pendingReports }}</span>
            <span class="badge bg-success">Accepted: {{ $acceptedReports }}</span>
            <span class="badge bg-info">In Progress: {{ $inProgressReports }}</span>
            <span class="badge bg-primary">Completed: {{ $completedReports }}</span>
            <span class="badge bg-danger">Rejected: {{ $rejectedReports }}</span>
        </div>
    </div>

    {{-- ================= ALERT ================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ================= TABLE ================= --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
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
                        <th width="160">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($reports as $index => $report)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $report->item->code ?? '-' }}</td>
                        <td>{{ $report->item->name ?? '-' }}</td>
                        <td>{{ $report->item->category->name ?? '-' }}</td>
                        <td>{{ $report->item->building->name ?? '-' }}</td>
                        <td>{{ $report->item->floor->name ?? '-' }}</td>
                        <td>{{ $report->item->room->name ?? '-' }}</td>
                        <td>{{ $report->user->name ?? '-' }}</td>
                        <td>{{ Str::limit($report->reason, 40) }}</td>

                        {{-- FOTO --}}
                        <td class="text-center">
                            @if($report->photo)
                                <a href="{{ asset('storage/'.$report->photo) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$report->photo) }}"
                                         class="img-thumbnail"
                                         width="60">
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">
                            @switch($report->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @break
                                @case('accepted')
                                    <span class="badge bg-success">Accepted</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-info">In Progress</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-primary">Completed</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    @break
                            @endswitch
                        </td>

                        {{-- AKSI --}}
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                {{-- Pending --}}
                                @if($report->status === 'pending')
                                    <form method="POST" action="{{ route('damage-reports.update', $report->id) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="accepted">
                                        <button class="btn btn-sm btn-success"
                                            onclick="return confirm('Terima laporan ini?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('damage-reports.update', $report->id) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tolak laporan ini?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Accepted --}}
                                @if($report->status === 'accepted')
                                    <form method="POST" action="{{ route('damage-reports.update', $report->id) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button class="btn btn-sm btn-warning"
                                            onclick="return confirm('Mulai proses perbaikan?')">
                                            <i class="bi bi-tools"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- In Progress --}}
                                @if($report->status === 'in_progress')
                                    <form method="POST" action="{{ route('damage-reports.update', $report->id) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button class="btn btn-sm btn-primary"
                                            onclick="return confirm('Tandai sebagai selesai?')">
                                            <i class="bi bi-clipboard-check"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted">
                            Tidak ada laporan kerusakan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection