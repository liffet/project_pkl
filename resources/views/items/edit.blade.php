@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
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

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="itemForm">
                @csrf
                @method('PUT')

                <!-- Informasi Dasar -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-info-circle"></i>
                        <span>Informasi Dasar</span>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-upc-scan"></i>
                                    Kode Perangkat
                                </label>
                                <input type="text" class="form-control" 
                                       value="{{ $item->code }}" 
                                       disabled
                                       style="background-color: #f3f4f6; cursor: not-allowed;">
                                <small class="form-text">
                                    <i class="bi bi-lock-fill"></i> Kode tidak dapat diubah
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-tag"></i>
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">
                                    <i class="bi bi-box"></i>
                                    Nama Perangkat <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $item->name) }}" 
                                       placeholder="Contoh: Komputer HP EliteDesk" required>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-toggle-on"></i>
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" 
                                               id="statusActive" value="active" 
                                               {{ old('status', $item->status) == 'active' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="statusActive">
                                            <i class="bi bi-check-circle text-success"></i> Aktif
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" 
                                               id="statusInactive" value="inactive" 
                                               {{ old('status', $item->status) == 'inactive' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusInactive">
                                            <i class="bi bi-x-circle text-danger"></i> Tidak Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi & Jadwal -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-geo-alt"></i>
                        <span>Lokasi & Jadwal</span>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <!-- Gedung -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-building"></i>
                                    Gedung <span class="text-danger">*</span>
                                </label>
                                <select name="building_id" id="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Gedung --</option>
                                    @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id', $item->building_id) == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('building_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Lantai -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-layers"></i>
                                    Lantai <span class="text-danger">*</span>
                                </label>
                                <select name="floor_id" id="floor_id" class="form-select @error('floor_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Gedung Dulu --</option>
                                    @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}" 
                                            data-building="{{ $floor->building_id }}"
                                            {{ old('floor_id', $item->floor_id) == $floor->id ? 'selected' : '' }}>
                                        {{ $floor->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('floor_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Ruangan -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-door-open"></i>
                                    Ruangan <span class="text-danger">*</span>
                                </label>
                                <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Lantai Dulu --</option>
                                    @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                            data-floor="{{ $room->floor_id }}"
                                            {{ old('room_id', $item->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-calendar-check"></i>
                                    Tanggal Pasang <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="install_date" class="form-control @error('install_date') is-invalid @enderror" 
                                       value="{{ old('install_date', $item->install_date) }}" required>
                                @error('install_date')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-calendar-event"></i>
                                    Tanggal Pergantian <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="replacement_date" class="form-control @error('replacement_date') is-invalid @enderror" 
                                       value="{{ old('replacement_date', $item->replacement_date) }}" required>
                                @error('replacement_date')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @else
                                    <small class="form-text">
                                        <i class="bi bi-info-circle"></i> Jadwal penggantian/maintenance berkala
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Foto Perangkat -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-camera"></i>
                        <span>Foto Perangkat</span>
                    </div>
                    <div class="form-card-body">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-image"></i>
                                Upload Foto Baru
                            </label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/gif" id="photoInput">
                            @error('photo')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="form-text">
                                    <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF (Max. 2MB) - Kosongkan jika tidak ingin mengubah foto
                                </small>
                            @enderror
                        </div>
                        <div class="preview-box text-center">
                            @if($item->photo)
                            <div id="currentPhoto">
                                <p class="text-muted mb-2 small">
                                    <i class="bi bi-image-fill text-primary"></i> Foto Saat Ini:
                                </p>
                                <img src="{{ asset('storage/'.$item->photo) }}" alt="Foto Saat Ini" 
                                     class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                            </div>
                            @else
                            <div id="noPhoto">
                                <i class="bi bi-image" style="font-size: 3rem; color: #6b7280; opacity: 0.5;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada foto</p>
                            </div>
                            @endif
                            <div id="newPhotoPreview" style="display: none;">
                                <p class="text-success mb-2 small fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i> Preview Foto Baru:
                                </p>
                                <img id="photoPreview" src="#" alt="Preview" 
                                     class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <a href="{{ route('items.index') }}" class="btn-cancel">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('items.show', $item->id) }}" class="btn-view">
                            <i class="bi bi-eye me-1"></i> Lihat Detail
                        </a>
                        <button type="submit" class="btn-submit warning">
                            <i class="bi bi-check-circle"></i> Update Perangkat
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tips -->
            <div class="form-card" style="background: #f0f9ff;">
                <div class="form-card-header" style="background: #2D4194; color: white;">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>Tips Mengubah Data Perangkat</span>
                </div>
                <div class="form-card-body">
                    <ul class="mb-0 ps-3 small text-muted">
                        <li>Kode perangkat tidak dapat diubah setelah dibuat</li>
                        <li>Pastikan semua field yang bertanda <span class="text-danger">*</span> diisi dengan benar</li>
                        <li>Gedung, lantai, dan ruangan harus dipilih secara berurutan</li>
                        <li>Upload foto baru hanya jika ingin mengganti foto yang ada</li>
                        <li>Perubahan status akan mempengaruhi visibilitas perangkat dalam sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    margin-bottom: 1.5rem;
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

.form-card-body {
    padding: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #2D4194;
    box-shadow: 0 0 0 3px rgba(45, 65, 148, 0.1);
}

.form-text {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.invalid-feedback {
    color: #dc2626;
    font-size: 0.8125rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.25rem;
}

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

.preview-box {
    background: #f9fafb;
    border: 1px dashed #e5e7eb;
    border-radius: 8px;
    padding: 2rem;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-actions {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
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
    background: #10b981;
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-submit.warning:hover {
    background: #059669;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.form-check-label {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.875rem;
    cursor: pointer;
}

.alert {
    border-radius: 12px;
    border: 1px solid #fee2e2;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('building_id');
    const floorSelect = document.getElementById('floor_id');
    const roomSelect = document.getElementById('room_id');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const newPhotoPreview = document.getElementById('newPhotoPreview');
    const currentPhoto = document.getElementById('currentPhoto');
    const noPhoto = document.getElementById('noPhoto');
    const form = document.getElementById('itemForm');

    // Store all floors and rooms
    const allFloors = Array.from(floorSelect.querySelectorAll('option[data-building]'));
    const allRooms = Array.from(roomSelect.querySelectorAll('option[data-floor]'));

    // Handle foto preview
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    newPhotoPreview.style.display = 'block';
                    if (currentPhoto) currentPhoto.style.display = 'none';
                    if (noPhoto) noPhoto.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Filter floors by building
    function filterFloors() {
        const buildingId = buildingSelect.value;
        
        floorSelect.innerHTML = '<option value="">-- Pilih Lantai --</option>';
        roomSelect.innerHTML = '<option value="">-- Pilih Lantai Dulu --</option>';
        roomSelect.disabled = true;

        if (buildingId) {
            floorSelect.disabled = false;
            allFloors.forEach(option => {
                if (option.dataset.building == buildingId) {
                    floorSelect.appendChild(option.cloneNode(true));
                }
            });
        } else {
            floorSelect.disabled = true;
        }
    }

    // Filter rooms by floor
    function filterRooms() {
        const floorId = floorSelect.value;
        
        roomSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
        roomSelect.disabled = true;

        if (floorId) {
            roomSelect.disabled = false;
            allRooms.forEach(option => {
                if (option.dataset.floor == floorId) {
                    roomSelect.appendChild(option.cloneNode(true));
                }
            });
        }
    }

    buildingSelect.addEventListener('change', filterFloors);
    floorSelect.addEventListener('change', filterRooms);

    // Initialize on load
    if (buildingSelect.value) {
        filterFloors();
        setTimeout(() => {
            floorSelect.value = '{{ old('floor_id', $item->floor_id) }}';
            filterRooms();
            setTimeout(() => {
                roomSelect.value = '{{ old('room_id', $item->room_id) }}';
            }, 100);
        }, 100);
    }

    // Handle form submit
    form.addEventListener('submit', function(e) {
        const btn = form.querySelector('.btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengupdate...';
    });
});
</script>
@endsection