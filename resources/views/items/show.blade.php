@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">{{ $item->name }}</h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-upc-scan me-1"></i>Kode: <strong>{{ $item->code }}</strong>
                    </p>
                </div>
                <div>
                    @if($item->status == 'active')
                        <span class="badge bg-success px-3 py-2 fs-6">
                            <i class="bi bi-check-circle me-1"></i> Aktif
                        </span>
                    @else
                        <span class="badge bg-secondary px-3 py-2 fs-6">
                            <i class="bi bi-x-circle me-1"></i> Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>

            <div class="row g-4">
                <!-- Photo Section -->
                @if($item->photo)
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-0">
                            <img src="{{ asset('storage/'.$item->photo) }}" 
                                 alt="Foto {{ $item->name }}" 
                                 class="img-fluid w-100 rounded"
                                 style="object-fit: cover; max-height: 400px;">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Information Section -->
                <div class="col-lg-{{ $item->photo ? '7' : '12' }}">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-4 text-primary">
                                <i class="bi bi-info-circle me-2"></i>Informasi Perangkat
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-tag me-1"></i>Kategori
                                        </small>
                                        <strong class="text-dark">{{ $item->category->name }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 rounded {{ $item->status == 'active' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 border {{ $item->status == 'active' ? 'border-success' : 'border-secondary' }}">
                                        <small class="{{ $item->status == 'active' ? 'text-success' : 'text-secondary' }} d-block mb-1">
                                            <i class="bi bi-toggle-on me-1"></i>Status Perangkat
                                        </small>
                                        <strong class="text-dark">
                                            {{ $item->status == 'active' ? '🟢 Aktif' : '🔴 Tidak Aktif' }}
                                        </strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-door-open me-1"></i>Ruangan
                                        </small>
                                        <strong class="text-dark">{{ $item->room ?? '-' }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-building me-1"></i>Lantai
                                        </small>
                                        <strong class="text-dark">{{ $item->floor ?? '-' }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-calendar-check me-1"></i>Tanggal Pasang
                                        </small>
                                        <strong class="text-dark">{{ \Carbon\Carbon::parse($item->install_date)->format('d M Y') }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item p-3 bg-warning bg-opacity-10 rounded border border-warning">
                                        <small class="text-warning d-block mb-1">
                                            <i class="bi bi-wrench me-1"></i>Jadwal Penggantian
                                        </small>
                                        <strong class="text-dark">{{ \Carbon\Carbon::parse($item->replacement_date)->format('d M Y') }}</strong>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-2 text-muted small">
                                <div class="col-md-6">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Dibuat: {{ $item->created_at->format('d M Y, H:i') }}
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    Diupdate: {{ $item->updated_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning px-4">
                            <i class="bi bi-pencil-square me-2"></i>Edit Perangkat
                        </a>
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus perangkat ini?')" 
                                    class="btn btn-danger px-4">
                                <i class="bi bi-trash me-2"></i>Hapus
                            </button>
                        </form>
                        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary px-4 ms-auto">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .info-item {
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .card {
        transition: all 0.3s ease;
    }

    .btn {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .badge {
        border-radius: 0.5rem;
    }
</style>
@endsection