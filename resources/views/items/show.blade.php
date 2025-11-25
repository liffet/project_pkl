@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1">{{ $item->name }}</h2>
                    <p class="text-secondary mb-0">
                        <i class="bi bi-upc-scan me-1"></i>Kode: <strong>{{ $item->code }}</strong>
                    </p>
                </div>

                <div>
                    @if($item->status == 'active')
                    <span class="badge fs-6 px-3 py-2 rounded-pill text-white"
                        style="background: linear-gradient(135deg, #28a745, #5cd65c); box-shadow: 0 4px 10px rgba(40,167,69,0.3);">
                        <i class="bi bi-check-circle me-1"></i> Aktif
                    </span>
                    @else
                    <span class="badge fs-6 px-3 py-2 rounded-pill text-white"
                        style="background: linear-gradient(135deg, #ff0000ff, #c94747ff);">
                        <i class="bi bi-x-circle me-1"></i> Tidak Aktif
                    </span>
                    @endif

                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- FOTO -->
                @if($item->photo)
                <div class="col-lg-5">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <img src="{{ asset('storage/'.$item->photo) }}"
                            alt="Foto {{ $item->name }}"
                            class="img-fluid w-100"
                            style="object-fit: cover; height: 400px;">
                    </div>
                </div>
                @endif

                <!-- INFORMASI -->
                <div class="col-lg-{{ $item->photo ? '7' : '12' }}">
                    <div class="card border-0 shadow-lg rounded-4 glass-card p-4">
                        <h5 class="fw-semibold text-primary mb-4">
                            <i class="bi bi-info-circle me-2"></i>Informasi Perangkat
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <small class="text-muted"><i class="bi bi-tag me-1"></i>Kategori</small>
                                    <strong class="d-block mt-1">{{ $item->category->name }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box border-start border-3 {{ $item->status == 'active' ? 'border-success' : 'border-secondary' }}">
                                    <small class="text-muted"><i class="bi bi-toggle-on me-1"></i>Status</small>
                                    <strong class="d-block mt-1">
                                        {{ $item->status == 'active' ? '🟢 Aktif' : '🔴 Tidak Aktif' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
    <div class="info-box">
        <small class="text-muted"><i class="bi bi-buildings me-1"></i>Gedung</small>
        <strong class="d-block mt-1">{{ $item->building->name ?? '-' }}</strong>
    </div>
</div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <small class="text-muted"><i class="bi bi-door-open me-1"></i>Ruangan</small>
                                    <strong class="d-block mt-1">{{ $item->room->name }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <small class="text-muted"><i class="bi bi-building me-1"></i>Lantai</small>
                                    <strong class="d-block mt-1">{{ $item->floor?->name }}</strong>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="info-box">
                                    <small class="text-muted"><i class="bi bi-calendar-check me-1"></i>Tanggal Pasang</small>
                                    <strong class="d-block mt-1">{{ \Carbon\Carbon::parse($item->install_date)->format('d M Y') }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box border-start border-3 border-warning bg-warning bg-opacity-10">
                                    <small class="text-warning"><i class="bi bi-wrench me-1"></i>Jadwal Penggantian</small>
                                    <strong class="d-block mt-1">{{ \Carbon\Carbon::parse($item->replacement_date)->format('d M Y') }}</strong>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row text-muted small">
                            <div class="col-md-6">
                                <i class="bi bi-clock-history me-1"></i>Dibuat: {{ $item->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="col-md-6">
                                <i class="bi bi-arrow-clockwise me-1"></i>Diupdate: {{ $item->updated_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AKSI -->
            <div class="card border-0 shadow-lg mt-4 rounded-4">
                <div class="card-body p-4 d-flex flex-wrap gap-3 justify-content-between align-items-center">

                    <div class="d-flex gap-2 flex-wrap">
                        <!-- Edit -->
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-primary-gradient">
                            <i class="bi bi-pencil-square me-2"></i>Edit Perangkat
                        </a>

                        <!-- Hapus -->
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus perangkat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger-gradient">
                                <i class="bi bi-trash me-2"></i>Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Kembali -->
                    <a href="{{ route('items.index') }}" class="btn btn-outline-primary px-4 fw-semibold">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- STYLE -->
<style>
    :root {
        --primary-color: #2D4194;
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, #2D4194, #3B5DDC);
        color: #fff !important;
        border: none;
        padding: 10px 22px;
        border-radius: 0.6rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(45, 65, 148, 0.3);
    }

    .btn-danger-gradient {
        background: linear-gradient(135deg, #d62828, #ff4d4d);
        color: #fff !important;
        border: none;
        padding: 10px 22px;
        border-radius: 0.6rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-danger-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(214, 40, 40, 0.3);
    }

    .btn-outline-primary {
        color: var(--primary-color) !important;
        border: 2px solid var(--primary-color);
        transition: all 0.3s ease;
        border-radius: 0.6rem;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(45, 65, 148, 0.3);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(45, 65, 148, 0.15);
    }

    .info-box {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(45, 65, 148, 0.1);
    }

    .card {
        transition: all 0.3s ease;
        border-radius: 1rem !important;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 768px) {
        .card-body {
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endsection