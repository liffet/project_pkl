@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Maintenance</h2>
            <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name ?? 'User' }} 👋</p>
        </div>
        <div class="text-end">
            <small class="text-muted d-block">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</small>
            <small class="text-muted">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</small>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">Total Perangkat</p>
                            <h2 class="fw-bold mb-0">{{ $totalItems ?? 0 }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">Harus Maintenance</p>
                            <h2 class="fw-bold mb-0 text-danger">{{ $maintenanceNow ?? 0 }}</h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-danger" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 bg-danger" style="height: 3px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">Hampir Maintenance</p>
                            <h2 class="fw-bold mb-0 text-warning">{{ $soonMaintenance ?? 0 }}</h2>
                            <small class="text-muted">≤7 hari</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 bg-warning" style="height: 3px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">Masih Aman</p>
                            <h2 class="fw-bold mb-0 text-success">{{ $safeItems ?? 0 }}</h2>
                            <small class="text-muted">>7 hari</small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="row g-4">
        <!-- Harus Maintenance -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-2 me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-danger" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Harus Maintenance Segera</h5>
                            <small class="text-muted">Perangkat yang sudah melewati tanggal maintenance</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0 text-muted small fw-semibold">#</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Kode</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Nama Perangkat</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Kategori</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Ruangan</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Lantai</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Tanggal Maintenance</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenanceList as $item)
                                    <tr>
                                        <td class="px-4 align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle"><span class="badge bg-light text-dark border">{{ $item->code }}</span></td>
                                        <td class="align-middle fw-medium">{{ $item->name }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border-0">
                                                {{ $item->category->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">{{ $item->room ?? '-' }}</td>
                                        <td class="align-middle">{{ $item->floor ?? '-' }}</td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->replacement_date)->isoFormat('D MMM YYYY') }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-circle me-1"></i>Urgent
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="mb-3 opacity-25" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                                </svg>
                                                <p class="mb-0">Tidak ada perangkat yang harus maintenance</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hampir Maintenance -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Hampir Maintenance</h5>
                            <small class="text-muted">Perangkat yang akan maintenance dalam 7 hari ke depan</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0 text-muted small fw-semibold">#</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Kode</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Nama Perangkat</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Kategori</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Ruangan</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Lantai</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Tanggal Maintenance</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Sisa Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($soonList as $item)
                                    @php
                                        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->replacement_date), false);
                                    @endphp
                                    <tr>
                                        <td class="px-4 align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle"><span class="badge bg-light text-dark border">{{ $item->code }}</span></td>
                                        <td class="align-middle fw-medium">{{ $item->name }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border-0">
                                                {{ $item->category->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">{{ $item->room ?? '-' }}</td>
                                        <td class="align-middle">{{ $item->floor ?? '-' }}</td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->replacement_date)->isoFormat('D MMM YYYY') }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>{{ $daysLeft }} hari lagi
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="mb-3 opacity-25" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                                </svg>
                                                <p class="mb-0">Tidak ada perangkat yang hampir maintenance</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Masih Aman -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-3 p-2 me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-success" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Masih Aman</h5>
                            <small class="text-muted">Perangkat dengan tanggal maintenance lebih dari 7 hari</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0 text-muted small fw-semibold">#</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Kode</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Nama Perangkat</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Kategori</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Ruangan</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Lantai</th>
                                    <th class="py-3 border-0 text-muted small fw-semibold">Tanggal Maintenance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($safeList as $item)
                                    <tr>
                                        <td class="px-4 align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle"><span class="badge bg-light text-dark border">{{ $item->code }}</span></td>
                                        <td class="align-middle fw-medium">{{ $item->name }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border-0">
                                                {{ $item->category->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">{{ $item->room ?? '-' }}</td>
                                        <td class="align-middle">{{ $item->floor ?? '-' }}</td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->replacement_date)->isoFormat('D MMM YYYY') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="mb-3 opacity-25" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                                </svg>
                                                <p class="mb-0">Semua perangkat sudah terjadwal dengan baik</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
