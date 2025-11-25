@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body bg-gradient-primary text-white rounded">
                    <h4 class="mb-1">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Perangkat Baru
                    </h4>
                    <p class="mb-0 opacity-90">Lengkapi formulir di bawah untuk menambahkan perangkat baru</p>
                </div>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="alert-heading mb-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>Terdapat Kesalahan Input!
                </h6>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Informasi Dasar -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Dasar
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Perangkat</label>
                                <input type="text" name="code" class="form-control" 
                                       value="{{ old('code') }}" 
                                       placeholder="Contoh: ITM-001">
                                <small class="text-muted">Biarkan kosong untuk generate otomatis</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                       value="{{ old('name') }}" 
                                       placeholder="Contoh: Komputer HP EliteDesk" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
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
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>Lokasi & Jadwal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Gedung -->
                            <div class="col-md-4">
                                <label class="form-label">Gedung <span class="text-danger">*</span></label>
                                <select name="building_id" id="building_id" class="form-select" required>
                                    <option value="">-- Pilih Gedung --</option>
                                    @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Lantai -->
                            <div class="col-md-4">
                                <label class="form-label">Lantai <span class="text-danger">*</span></label>
                                <select name="floor_id" id="floor_id" class="form-select" required disabled>
                                    <option value="">-- Pilih Gedung Dulu --</option>
                                </select>
                            </div>

                            <!-- Ruangan -->
                            <div class="col-md-4">
                                <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                                <select name="room_id" id="room_id" class="form-select" required disabled>
                                    <option value="">-- Pilih Lantai Dulu --</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pasang <span class="text-danger">*</span></label>
                                <input type="date" name="install_date" class="form-control" 
                                       value="{{ old('install_date') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pergantian <span class="text-danger">*</span></label>
                                <input type="date" name="replacement_date" class="form-control" 
                                       value="{{ old('replacement_date') }}" required>
                                <small class="text-muted">Jadwal penggantian/maintenance berkala</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Foto Perangkat -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-camera me-2 text-primary"></i>Foto Perangkat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Foto</label>
                            <input type="file" name="photo" class="form-control" 
                                   accept="image/jpeg,image/png,image/gif" id="photoInput">
                            <small class="text-muted">Format: JPG, PNG, GIF (Max. 2MB)</small>
                        </div>
                        <div class="text-center">
                            <img id="photoPreview" src="#" alt="Preview" 
                                 style="max-width: 300px; max-height: 300px; display: none;" 
                                 class="img-thumbnail">
                            <p id="photoPlaceholder" class="text-muted">
                                <i class="bi bi-image fs-1 d-block mb-2"></i>
                                Belum ada foto dipilih
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perangkat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('building_id');
    const floorSelect = document.getElementById('floor_id');
    const roomSelect = document.getElementById('room_id');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const photoPlaceholder = document.getElementById('photoPlaceholder');

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
@endpush
@endsection