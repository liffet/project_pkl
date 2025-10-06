@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Edit Perangkat
                </h2>
                <p class="text-muted mb-0">Perbarui informasi perangkat: <strong>{{ $item->name }}</strong> ({{ $item->code }})</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2">Terdapat Kesalahan Input!</h6>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Left Column - Basic Info -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-4 text-primary">
                                    <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-upc-scan me-1 text-muted"></i>Kode Perangkat
                                    </label>
                                    <input type="text" class="form-control form-control-lg" 
                                           value="{{ $item->code }}" 
                                           disabled
                                           style="background-color: #f8f9fa;">
                                    <small class="text-muted">
                                        <i class="bi bi-lock me-1"></i>Kode tidak dapat diubah
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-tag me-1 text-muted"></i>Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" class="form-select form-select-lg" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-laptop me-1 text-muted"></i>Nama Perangkat
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control form-control-lg" 
                                           value="{{ old('name', $item->name) }}" 
                                           placeholder="Masukkan nama perangkat" 
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-toggle-on me-1 text-muted"></i>Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" class="form-select form-select-lg" required>
                                        <option value="active" {{ old('status', $item->status) == 'active' ? 'selected' : '' }}>
                                            🟢 Aktif
                                        </option>
                                        <option value="inactive" {{ old('status', $item->status) == 'inactive' ? 'selected' : '' }}>
                                            🔴 Tidak Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Location & Dates -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-4 text-primary">
                                    <i class="bi bi-geo-alt me-2"></i>Lokasi & Jadwal
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-door-open me-1 text-muted"></i>Ruangan
                                    </label>
                                    <input type="text" name="room" class="form-control form-control-lg" 
                                           value="{{ old('room', $item->room) }}" 
                                           placeholder="Contoh: Lab Komputer 1">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-building me-1 text-muted"></i>Lantai
                                    </label>
                                    <input type="text" name="floor" class="form-control form-control-lg" 
                                           value="{{ old('floor', $item->floor) }}" 
                                           placeholder="Contoh: Lantai 2">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-calendar-check me-1 text-muted"></i>Tanggal Pasang
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="install_date" class="form-control form-control-lg" 
                                           value="{{ old('install_date', $item->install_date) }}" 
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-wrench me-1 text-warning"></i>Tanggal Penggantian
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="replacement_date" class="form-control form-control-lg" 
                                           value="{{ old('replacement_date', $item->replacement_date) }}" 
                                           required>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Jadwal penggantian/maintenance berkala
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width - Photo Upload -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-4 text-primary">
                                    <i class="bi bi-image me-2"></i>Foto Perangkat
                                </h5>

                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-camera me-1 text-muted"></i>Upload Foto Baru
                                        </label>
                                        <input type="file" name="photo" class="form-control form-control-lg" 
                                               onchange="previewImage(event)" 
                                               accept="image/*">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>Format: JPG, PNG, GIF (Max. 2MB) - Kosongkan jika tidak ingin mengubah foto
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        @if($item->photo)
                                            <div id="current-photo-container">
                                                <p class="text-muted small mb-2">Foto Saat Ini:</p>
                                                <img id="current-photo" 
                                                     src="{{ asset('storage/'.$item->photo) }}" 
                                                     alt="Foto Saat Ini" 
                                                     class="img-fluid rounded shadow-sm" 
                                                     style="max-height: 200px;">
                                            </div>
                                        @endif
                                        <div id="preview-container" style="display:none;">
                                            <p class="text-success small mb-2">
                                                <i class="bi bi-check-circle me-1"></i>Preview Foto Baru:
                                            </p>
                                            <img id="preview" src="#" alt="Preview Foto" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 style="max-height: 200px;">
                                        </div>
                                        @if(!$item->photo)
                                            <div id="no-photo" class="text-muted py-4">
                                                <i class="bi bi-image fs-1 d-block mb-2 opacity-25"></i>
                                                <small>Belum ada foto</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap gap-2 justify-content-end">
                                    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="bi bi-x-circle me-2"></i>Batal
                                    </a>
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-info btn-lg px-4">
                                        <i class="bi bi-eye me-2"></i>Lihat Detail
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg px-5">
                                        <i class="bi bi-check-circle me-2"></i>Update Perangkat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .form-control-lg, .form-select-lg {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
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

    .alert {
        border-radius: 0.5rem;
    }

    label {
        margin-bottom: 0.5rem;
    }

    .text-danger {
        font-weight: bold;
    }

    #preview, #current-photo {
        transition: all 0.3s ease;
    }

    #preview:hover, #current-photo:hover {
        transform: scale(1.05);
    }
</style>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(){
                let output = document.getElementById('preview');
                let previewContainer = document.getElementById('preview-container');
                let currentContainer = document.getElementById('current-photo-container');
                let noPhoto = document.getElementById('no-photo');
                
                output.src = reader.result;
                previewContainer.style.display = 'block';
                
                // Sembunyikan foto lama dan placeholder
                if(currentContainer) currentContainer.style.display = 'none';
                if(noPhoto) noPhoto.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection