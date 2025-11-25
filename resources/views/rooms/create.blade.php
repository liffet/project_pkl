@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Tambah Ruangan Baru</h2>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">Tambahkan ruangan baru ke dalam sistem</p>
            </div>

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.25rem;"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">Perhatian!</h6>
                            <p class="mb-0">{{ session('warning') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

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

            <!-- Form Card -->
            <form action="{{ route('rooms.store') }}" method="POST" id="roomForm">
                @csrf
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-door-open"></i>
                        <span>Informasi Ruangan</span>
                    </div>
                    <div class="form-card-body">
                        <!-- Nama Ruangan -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="bi bi-card-heading"></i>
                                Nama Ruangan
                                <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama ruangan (contoh: Lab Komputer, Ruang Meeting)"
                                required
                                autofocus>
                            @error('name')
                                <div class="invalid-feedback d-block mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="form-text">
                                    <i class="bi bi-info-circle"></i> Pastikan nama ruangan belum digunakan di lantai yang sama.
                                </small>
                            @enderror
                        </div>

                        <!-- Gedung -->
                        <div class="form-group">
                            <label for="building_id" class="form-label">
                                <i class="bi bi-buildings"></i>
                                Gedung
                                <span class="text-danger">*</span>
                            </label>
                            <select 
                                name="building_id" 
                                id="building_id"
                                class="form-select @error('building_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Gedung --</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('building_id')
                                <div class="invalid-feedback d-block mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="form-text">
                                    <i class="bi bi-info-circle"></i> Pilih gedung terlebih dahulu untuk memilih lantai.
                                </small>
                            @enderror
                        </div>

                        <!-- Lantai -->
                        <div class="form-group">
                            <label for="floor_id" class="form-label">
                                <i class="bi bi-building"></i>
                                Lantai
                                <span class="text-danger">*</span>
                            </label>
                            <select 
                                name="floor_id" 
                                id="floor_id"
                                class="form-select @error('floor_id') is-invalid @enderror"
                                required
                                disabled>
                                <option value="">-- Pilih Gedung Dulu --</option>
                            </select>
                            @error('floor_id')
                                <div class="invalid-feedback d-block mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="form-text">
                                    <i class="bi bi-info-circle"></i> Pilih lantai dimana ruangan ini berada.
                                </small>
                            @enderror
                        </div>

                        <!-- Preview -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-eye"></i>
                                Preview
                            </label>
                            <div class="preview-box">
                                <div class="preview-icon text-white me-3" id="previewAvatar">?</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold" id="previewName">Nama Ruangan</h6>
                                    <small class="text-muted" id="previewLocation">Pilih gedung dan lantai terlebih dahulu</small>
                                </div>
                            </div>
                        </div>

                        <!-- Character Counter -->
                        <div class="text-end mt-2">
                            <small class="text-muted">
                                <span id="charCount">0</span>/255 karakter
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions mt-4">
                    <a href="{{ route('rooms.index') }}" class="btn-cancel">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle"></i> Simpan Ruangan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tips -->
            <div class="form-card mt-4" style="background: #f0f9ff;">
                <div class="form-card-header" style="background: #2D4194; color: white;">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>Tips Penamaan Ruangan</span>
                </div>
                <div class="form-card-body">
                    <ul class="mb-0 ps-3 small text-muted">
                        <li>Gunakan nama yang jelas dan mudah dipahami</li>
                        <li>Sertakan fungsi atau tipe ruangan jika perlu (Lab, Ruang Meeting, dll)</li>
                        <li>Pastikan nama belum digunakan di lantai yang sama</li>
                        <li>Gunakan huruf kapital di awal kata untuk konsistensi</li>
                        <li>Pilih gedung terlebih dahulu sebelum memilih lantai</li>
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
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
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

.form-select:disabled {
    background-color: #f3f4f6;
    cursor: not-allowed;
}

.preview-box {
    display: flex;
    align-items: center;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
}

.preview-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    background-color: #2D4194;
    color: #ffffff;
    text-transform: uppercase;
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

.btn-outline-warning {
    padding: 0.625rem 1.5rem;
    border: 1px solid #f59e0b;
    color: #f59e0b;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-outline-warning:hover {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #f59e0b;
}

.invalid-feedback {
    color: #dc2626;
    font-size: 0.8125rem;
}

.form-text {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.alert {
    border-radius: 12px;
    padding: 1rem 1.25rem;
}

.alert-danger {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    color: #991b1b;
}

.alert-warning {
    background: #fffbeb;
    border: 1px solid #fef3c7;
    color: #92400e;
}

.alert-warning .bi-exclamation-triangle-fill {
    color: #f59e0b;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const buildingSelect = document.getElementById('building_id');
    const floorSelect = document.getElementById('floor_id');
    const charCount = document.getElementById('charCount');
    const previewName = document.getElementById('previewName');
    const previewLocation = document.getElementById('previewLocation');
    const previewAvatar = document.getElementById('previewAvatar');
    const form = document.getElementById('roomForm');

    // Dynamic floor loading berdasarkan gedung
    buildingSelect.addEventListener('change', function() {
        const buildingId = this.value;
        
        floorSelect.innerHTML = '<option value="">-- Memuat lantai... --</option>';
        floorSelect.disabled = true;

        if (buildingId) {
            fetch(`/api/floors/by-building/${buildingId}`)
                .then(response => response.json())
                .then(data => {
                    floorSelect.innerHTML = '<option value="">-- Pilih Lantai --</option>';
                    
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
                    
                    updatePreview();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data lantai');
                    floorSelect.innerHTML = '<option value="">-- Error memuat lantai --</option>';
                });
        } else {
            floorSelect.innerHTML = '<option value="">-- Pilih Gedung Dulu --</option>';
            updatePreview();
        }
    });

    function updatePreview() {
        const nameValue = nameInput.value.trim();
        const buildingText = buildingSelect.options[buildingSelect.selectedIndex]?.text || '';
        const floorText = floorSelect.options[floorSelect.selectedIndex]?.text || '';
        
        charCount.textContent = nameValue.length;

        // Update nama
        if (nameValue) {
            previewName.textContent = nameValue;
            previewAvatar.textContent = nameValue.charAt(0).toUpperCase();
        } else {
            previewName.textContent = 'Nama Ruangan';
            previewAvatar.textContent = '?';
        }

        // Update lokasi
        if (buildingSelect.value && floorSelect.value) {
            previewLocation.textContent = `${buildingText} - ${floorText}`;
        } else if (buildingSelect.value) {
            previewLocation.textContent = `${buildingText} - Pilih lantai`;
        } else {
            previewLocation.textContent = 'Pilih gedung dan lantai terlebih dahulu';
        }
    }

    nameInput.addEventListener('input', updatePreview);
    floorSelect.addEventListener('change', updatePreview);

    form.addEventListener('submit', function() {
        const btn = form.querySelector('.btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });

    form.addEventListener('reset', function() {
        setTimeout(() => {
            nameInput.value = '';
            buildingSelect.selectedIndex = 0;
            floorSelect.selectedIndex = 0;
            floorSelect.disabled = true;
            floorSelect.innerHTML = '<option value="">-- Pilih Gedung Dulu --</option>';
            charCount.textContent = '0';
            previewName.textContent = 'Nama Ruangan';
            previewLocation.textContent = 'Pilih gedung dan lantai terlebih dahulu';
            previewAvatar.textContent = '?';
        }, 0);
    });

    // Restore old values jika ada error validation
    @if(old('building_id'))
        buildingSelect.value = '{{ old('building_id') }}';
        buildingSelect.dispatchEvent(new Event('change'));
        
        setTimeout(() => {
            @if(old('floor_id'))
                floorSelect.value = '{{ old('floor_id') }}';
                updatePreview();
            @endif
        }, 500);
    @endif

    // Inisialisasi awal
    updatePreview();
});
</script>
@endsection