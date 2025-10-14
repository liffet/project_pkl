@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Tambah Perangkat Baru</h2>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">Lengkapi formulir di bawah untuk menambahkan perangkat baru</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.25rem;"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-2 fw-semibold">Terdapat Kesalahan Input!</h6>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <!-- Left Column - Informasi Dasar -->
                    <div class="col-lg-6">
                        <div class="form-card">
                            <div class="form-card-header">
                                <i class="bi bi-info-circle"></i>
                                <span>Informasi Dasar</span>
                            </div>
                            <div class="form-card-body">
                                <!-- Kode Perangkat -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-upc-scan"></i>
                                        Kode Perangkat
                                    </label>
                                    <input type="text" 
                                           name="code" 
                                           class="form-control" 
                                           value="{{ old('code') }}" 
                                           placeholder="Kosongkan untuk generate otomatis">
                                    <small class="form-text">
                                        <i class="bi bi-info-circle"></i>
                                        Biarkan kosong untuk generate otomatis
                                    </small>
                                </div>

                                <!-- Kategori -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                        Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nama Perangkat -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-laptop"></i>
                                        Nama Perangkat
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control" 
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama perangkat" 
                                           required>
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-circle-fill"></i>
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                   <select name="status" class="form-select" required id="status-select">
    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
</select>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Lokasi & Jadwal -->
                    <div class="col-lg-6">
                        <div class="form-card">
                            <div class="form-card-header">
                                <i class="bi bi-geo-alt"></i>
                                <span>Lokasi & Jadwal</span>
                            </div>
                            <div class="form-card-body">
                                <!-- Ruangan -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-building"></i>
                                        Ruangan
                                    </label>
                                    <input type="text" 
                                           name="room" 
                                           class="form-control" 
                                           value="{{ old('room') }}" 
                                           placeholder="Contoh: Ruang Pusdatin">
                                    <small class="form-text">
                                        <i class="bi bi-info-circle"></i>
                                        Biarkan kosong untuk generate otomatis
                                    </small>
                                </div>

                                <!-- Lantai -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-building"></i>
                                        Lantai
                                    </label>
<select name="floor" class="form-select">
    <option value="">-- Pilih Lantai --</option>
    <?php for($i = 1; $i <= 25; $i++): ?>
        <option value="Lantai <?= $i ?>" {{ old('floor') == "Lantai $i" ? 'selected' : '' }}>
            Lantai <?= $i ?>
        </option>
    <?php endfor; ?>
</select>

                                </div>

                                <!-- Tanggal Pasang -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-check"></i>
                                        Tanggal Pasang
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="install_date" 
                                           class="form-control" 
                                           value="{{ old('install_date') }}" 
                                           required>
                                </div>

                                <!-- Tanggal Pergantian -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-wrench"></i>
                                        Tanggal Pergantian
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="replacement_date" 
                                           class="form-control" 
                                           value="{{ old('replacement_date') }}" 
                                           required>
                                    <small class="form-text">
                                        <i class="bi bi-info-circle"></i>
                                        Jadwal penggantian/maintenance berkala
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width - Foto Perangkat -->
                    <div class="col-12">
                        <div class="form-card">
                            <div class="form-card-header">
                                <i class="bi bi-image"></i>
                                <span>Foto Perangkat</span>
                            </div>
                            <div class="form-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">
                                                <i class="bi bi-camera"></i>
                                                Upload Foto
                                            </label>
                                            <input type="file" 
                                                   name="photo" 
                                                   class="form-control" 
                                                   onchange="previewImage(event)" 
                                                   accept="image/*">
                                            <small class="form-text">
                                                <i class="bi bi-info-circle"></i>
                                                Format: JPG, PNG, GIF (Max. 2MB)
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preview-container">
                                            <div id="no-preview" class="no-preview">
                                                <i class="bi bi-image"></i>
                                                <p>Belum ada foto dipilih</p>
                                            </div>
                                            <div id="preview-container" class="image-preview" style="display:none;">
                                                <img id="preview" src="#" alt="Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12">
                        <div class="form-actions">
                            <a href="{{ route('items.index') }}" class="btn-cancel">
                                <i class="bi bi-x-circle"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-check-circle"></i>
                                Simpan Perangkat
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Form Card */
.form-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.form-card-header {
    background: #2D4194;
    color: white;
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 0.9375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-card-header i {
    font-size: 1.125rem;
}

.form-card-body {
    padding: 1.5rem;
}

/* Form Group */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-label i {
    font-size: 1rem;
    color: #6b7280;
}

.form-label .text-danger {
    margin-left: auto;
}

/* Form Controls */
.form-control,
.form-select {
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    transition: all 0.2s;
}

.form-control:focus,
.form-select:focus {
    border-color: #2D4194;
    box-shadow: 0 0 0 3px rgba(45, 65, 148, 0.1);
    outline: none;
}

.form-control::placeholder {
    color: #9ca3af;
}

.form-text {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.375rem;
}

.form-text i {
    font-size: 0.875rem;
}

/* Preview Container */
.preview-container {
    height: 100%;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-preview {
    text-align: center;
    color: #9ca3af;
}

.no-preview i {
    font-size: 3rem;
    opacity: 0.3;
    display: block;
    margin-bottom: 0.5rem;
}

.no-preview p {
    font-size: 0.875rem;
    margin: 0;
}

.image-preview {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-preview img {
    max-width: 100%;
    max-height: 250px;
    border-radius: 8px;
    border: 2px solid #e5e7eb;
}

/* Action Buttons */
.form-actions {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.5rem;
    background: white;
    color: #ef4444;
    border: 1px solid #ef4444;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-cancel:hover {
    background: #fef2f2;
    color: #dc2626;
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.5rem;
    background: #10b981;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Alert */
.alert {
    border-radius: 12px;
    border: 1px solid #fecaca;
    padding: 1rem 1.25rem;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

.alert h6 {
    font-size: 0.875rem;
    margin: 0;
}

.alert ul {
    font-size: 0.8125rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .btn-cancel,
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function(){
            let output = document.getElementById('preview');
            let container = document.getElementById('preview-container');
            let noPreview = document.getElementById('no-preview');
            
            output.src = reader.result;
            container.style.display = 'flex';
            noPreview.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection