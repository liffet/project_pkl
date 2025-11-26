@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
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

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="itemForm">
                @csrf

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
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code') }}" 
                                       placeholder="Contoh: ITM-001">
                                @error('code')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @else
                                    <small class="form-text">
                                        <i class="bi bi-info-circle"></i> Biarkan kosong untuk generate otomatis
                                    </small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-tag"></i>
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                       value="{{ old('name') }}" 
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
                                               {{ old('status', 'active') == 'active' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="statusActive">
                                            <i class="bi bi-check-circle text-success"></i> Aktif
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" 
                                               id="statusInactive" value="inactive" 
                                               {{ old('status') == 'inactive' ? 'checked' : '' }}>
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
                                    <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
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
                                <select name="floor_id" id="floor_id" class="form-select @error('floor_id') is-invalid @enderror" required disabled>
                                    <option value="">-- Pilih Gedung Dulu --</option>
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
                                <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required disabled>
                                    <option value="">-- Pilih Lantai Dulu --</option>
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
                                       value="{{ old('install_date') }}" required>
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
                                       value="{{ old('replacement_date') }}" required>
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
                                Upload Foto
                            </label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/gif" id="photoInput">
                            @error('photo')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="form-text">
                                    <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF (Max. 2MB)
                                </small>
                            @enderror
                        </div>
                        <div class="preview-box text-center">
                            <img id="photoPreview" src="#" alt="Preview" 
                                 style="max-width: 300px; max-height: 300px; display: none;" 
                                 class="img-thumbnail">
                            <div id="photoPlaceholder">
                                <i class="bi bi-image" style="font-size: 3rem; color: #6b7280; opacity: 0.5;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada foto dipilih</p>
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
                        <button type="reset" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle"></i> Simpan Perangkat
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tips -->
            <div class="form-card" style="background: #f0f9ff;">
                <div class="form-card-header" style="background: #2D4194; color: white;">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>Tips Pengisian Form</span>
                </div>
                <div class="form-card-body">
                    <ul class="mb-0 ps-3 small text-muted">
                        <li>Pastikan semua field yang bertanda <span class="text-danger">*</span> diisi dengan benar</li>
                        <li>Kode perangkat akan digenerate otomatis jika dikosongkan</li>
                        <li>Pilih gedung terlebih dahulu untuk mengaktifkan pilihan lantai dan ruangan</li>
                        <li>Format foto yang didukung: JPG, PNG, GIF dengan ukuran maksimal 2MB</li>
                        <li>Tanggal pergantian sebaiknya diisi untuk memudahkan jadwal maintenance</li>
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
    const photoPlaceholder = document.getElementById('photoPlaceholder');
    const form = document.getElementById('itemForm');

    // Handle foto preview
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                    photoPlaceholder.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Handle gedung change - load lantai
    buildingSelect.addEventListener('change', function() {
        const buildingId = this.value;
        
        // Reset floor dan room
        floorSelect.innerHTML = '<option value="">-- Pilih Lantai --</option>';
        floorSelect.disabled = true;
        roomSelect.innerHTML = '<option value="">-- Pilih Lantai Dulu --</option>';
        roomSelect.disabled = true;

        if (buildingId) {
            // Fetch floors berdasarkan gedung
            fetch(`/api/floors/by-building/${buildingId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.floors.length > 0) {
                        floorSelect.disabled = false;
                        data.floors.forEach(floor => {
                            const option = document.createElement('option');
                            option.value = floor.id;
                            option.textContent = floor.name;
                            floorSelect.appendChild(option);
                        });
                    } else {
                        floorSelect.innerHTML = '<option value="">-- Tidak ada lantai --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data lantai');
                });
        }
    });

    // Handle lantai change - load ruangan
    floorSelect.addEventListener('change', function() {
        const floorId = this.value;
        
        // Reset room
        roomSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
        roomSelect.disabled = true;

        if (floorId) {
            // Fetch rooms berdasarkan lantai
            fetch(`/api/rooms/by-floor/${floorId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.rooms.length > 0) {
                        roomSelect.disabled = false;
                        data.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = room.name;
                            roomSelect.appendChild(option);
                        });
                    } else {
                        roomSelect.innerHTML = '<option value="">-- Tidak ada ruangan --</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data ruangan');
                });
        }
    });

    // Handle form submit
    form.addEventListener('submit', function(e) {
        const btn = form.querySelector('.btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });

    // Handle form reset
    form.addEventListener('reset', function() {
        setTimeout(() => {
            photoPreview.style.display = 'none';
            photoPlaceholder.style.display = 'block';
            floorSelect.disabled = true;
            roomSelect.disabled = true;
            floorSelect.innerHTML = '<option value="">-- Pilih Gedung Dulu --</option>';
            roomSelect.innerHTML = '<option value="">-- Pilih Lantai Dulu --</option>';
        }, 0);
    });

    // Restore old values jika ada error validation
    @if(old('building_id'))
        buildingSelect.value = '{{ old('building_id') }}';
        buildingSelect.dispatchEvent(new Event('change'));
        
        setTimeout(() => {
            @if(old('floor_id'))
                floorSelect.value = '{{ old('floor_id') }}';
                floorSelect.dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    @if(old('room_id'))
                        roomSelect.value = '{{ old('room_id') }}';
                    @endif
                }, 500);
            @endif
        }, 500);
    @endif
});
</script>
@endsection