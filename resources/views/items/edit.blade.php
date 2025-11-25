@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Edit Perangkat</h2>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    Perbarui informasi perangkat: <strong>{{ $item->name }}</strong>
                    <span class="badge-code ms-2">{{ $item->code }}</span>
                </p>
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

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Left Column - Informasi Dasar -->
                    <div class="col-lg-6">
                        <div class="form-card">
                            <div class="form-card-header">
                                <i class="bi bi-info-circle"></i>
                                <span>Informasi Dasar</span>
                            </div>
                            <div class="form-card-body">
                                <!-- Kode Perangkat (Disabled) -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-upc-scan"></i>
                                        Kode Perangkat
                                    </label>
                                    <input type="text"
                                        class="form-control"
                                        value="{{ $item->code }}"
                                        disabled
                                        style="background-color: #f3f4f6; cursor: not-allowed;">
                                    <small class="form-text">
                                        <i class="bi bi-lock-fill"></i>
                                        Kode tidak dapat diubah
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
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
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
                                        value="{{ old('name', $item->name) }}"
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
                                    <select name="status" class="form-select" required>
                                        <option value="active" {{ old('status', $item->status) == 'active' ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="inactive" {{ old('status', $item->status) == 'inactive' ? 'selected' : '' }}>
                                            Tidak Aktif
                                        </option>
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
            <!-- Gedung -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-buildings"></i> Gedung <span class="text-danger">*</span>
                </label>
                <select name="building_id" id="building_id" class="form-select" required>
                    <option value="">-- Pilih Gedung --</option>
                    @foreach($buildings as $building)
                    <option value="{{ $building->id }}" {{ old('building_id', $item->building_id) == $building->id ? 'selected' : '' }}>
                        {{ $building->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Lantai -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-building"></i> Lantai <span class="text-danger">*</span>
                </label>
                <select name="floor_id" id="floor_id" class="form-select" required>
                    <option value="">-- Pilih Gedung Dulu --</option>
                    @foreach($floors as $floor)
                    <option value="{{ $floor->id }}" 
                            data-building="{{ $floor->building_id }}"
                            {{ old('floor_id', $item->floor_id) == $floor->id ? 'selected' : '' }}>
                        {{ $floor->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Ruangan -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-door-open"></i> Ruangan <span class="text-danger">*</span>
                </label>
                <select name="room_id" class="form-select" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id', $item->room_id) == $room->id ? 'selected' : '' }}>
                        {{ $room->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Pasang & Pergantian tetap sama -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-calendar-check"></i>
                    Tanggal Pasang
                    <span class="text-danger">*</span>
                </label>
                <input type="date"
                    name="install_date"
                    class="form-control"
                    value="{{ old('install_date', $item->install_date) }}"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-wrench"></i>
                    Tanggal Pergantian
                    <span class="text-danger">*</span>
                </label>
                <input type="date"
                    name="replacement_date"
                    class="form-control"
                    value="{{ old('replacement_date', $item->replacement_date) }}"
                    required>
                <small class="form-text">
                    <i class="bi bi-info-circle"></i>
                    Jadwal penggantian/maintenance berkala
                </small>
            </div>
        </div>
    </div>
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
                                        value="{{ old('install_date', $item->install_date) }}"
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
                                        value="{{ old('replacement_date', $item->replacement_date) }}"
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
                                                Upload Foto Baru
                                            </label>
                                            <input type="file"
                                                name="photo"
                                                class="form-control"
                                                onchange="previewImage(event)"
                                                accept="image/*">
                                            <small class="form-text">
                                                <i class="bi bi-info-circle"></i>
                                                Format: JPG, PNG, GIF (Max. 2MB) - Kosongkan jika tidak ingin mengubah foto
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preview-container">
                                            @if($item->photo)
                                            <div id="current-photo-container" class="image-preview">
                                                <p class="preview-label">Foto Saat Ini:</p>
                                                <img id="current-photo"
                                                    src="{{ asset('storage/'.$item->photo) }}"
                                                    alt="Foto Saat Ini">
                                            </div>
                                            @else
                                            <div id="no-photo" class="no-preview">
                                                <i class="bi bi-image"></i>
                                                <p>Belum ada foto</p>
                                            </div>
                                            @endif
                                            <div id="preview-container" class="image-preview" style="display:none;">
                                                <p class="preview-label text-success">
                                                    <i class="bi bi-check-circle me-1"></i>Preview Foto Baru:
                                                </p>
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
                            <a href="{{ route('items.show', $item->id) }}" class="btn-view">
                                <i class="bi bi-eye"></i>
                                Lihat Detail
                            </a>
                            <button type="submit" class="btn-submit warning">
                                <i class="bi bi-check-circle"></i>
                                Update Perangkat
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

    /* Badge Code in Header */
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
        text-align: center;
    }

    .image-preview .preview-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 250px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        transition: transform 0.2s;
    }

    .image-preview img:hover {
        transform: scale(1.02);
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

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.5rem;
        background: white;
        color: #3b82f6;
        border: 1px solid #3b82f6;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-view:hover {
        background: #eff6ff;
        color: #2563eb;
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

    .btn-submit.warning {
        background: #f59e0b;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-submit.warning:hover {
        background: #d97706;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
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
        .btn-view,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>

        // Dynamic Floor Filter berdasarkan Gedung
    document.addEventListener('DOMContentLoaded', function() {
        const buildingSelect = document.getElementById('building_id');
        const floorSelect = document.getElementById('floor_id');
        const allFloors = Array.from(floorSelect.querySelectorAll('option[data-building]'));

        function filterFloors() {
            const buildingId = buildingSelect.value;
            
            // Reset
            floorSelect.innerHTML = '<option value="">-- Pilih Lantai --</option>';
            
            if (buildingId) {
                // Filter lantai by building
                allFloors.forEach(option => {
                    if (option.dataset.building == buildingId) {
                        floorSelect.appendChild(option.cloneNode(true));
                    }
                });
                
                floorSelect.disabled = false;
            } else {
                floorSelect.disabled = true;
            }
        }

        buildingSelect.addEventListener('change', filterFloors);
        
        // Init on load
        if (buildingSelect.value) {
            filterFloors();
            // Restore selected floor
            setTimeout(() => {
                floorSelect.value = '{{ old('floor_id', $item->floor_id) }}';
            }, 100);
        }
    });
    
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function() {
                let output = document.getElementById('preview');
                let previewContainer = document.getElementById('preview-container');
                let currentContainer = document.getElementById('current-photo-container');
                let noPhoto = document.getElementById('no-photo');

                output.src = reader.result;
                previewContainer.style.display = 'block';

                // Sembunyikan foto lama dan placeholder
                if (currentContainer) currentContainer.style.display = 'none';
                if (noPhoto) noPhoto.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection