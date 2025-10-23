@extends('layouts.app')

@section('title', 'Edit Ruangan')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Edit Ruangan</h2>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">Perbarui informasi ruangan yang sudah ada</p>
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
            <form action="{{ route('rooms.update', $room->id) }}" method="POST" id="roomForm">
                @csrf
                @method('PUT')
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
                                value="{{ old('name', $room->name) }}"
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
                                required>
                                <option value="">-- Pilih Lantai --</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}" 
                                        {{ old('floor_id', $room->floor_id) == $floor->id ? 'selected' : '' }}>
                                        {{ $floor->name }}
                                    </option>
                                @endforeach
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
                                <div class="preview-icon text-white me-3" id="previewAvatar">
                                    {{ substr($room->name, 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold" id="previewName">{{ $room->name }}</h6>
                                    <small class="text-muted" id="previewFloor">{{ $room->floor->name }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Character Counter -->
                        <div class="text-end mt-2">
                            <small class="text-muted">
                                <span id="charCount">{{ strlen($room->name) }}</span>/255 karakter
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
                            <i class="bi bi-check-circle"></i> Perbarui Ruangan
                        </button>
                    </div>
                </div>
            </form>
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
    const floorSelect = document.getElementById('floor_id');
    const charCount = document.getElementById('charCount');
    const previewName = document.getElementById('previewName');
    const previewFloor = document.getElementById('previewFloor');
    const previewAvatar = document.getElementById('previewAvatar');
    const form = document.getElementById('roomForm');
    const originalName = '{{ $room->name }}';
    const originalFloorId = '{{ $room->floor_id }}';

    function updatePreview() {
        const nameValue = nameInput.value.trim();
        const floorText = floorSelect.options[floorSelect.selectedIndex].text;
        
        charCount.textContent = nameValue.length;

        // Update nama
        if (nameValue) {
            previewName.textContent = nameValue;
            previewAvatar.textContent = nameValue.charAt(0).toUpperCase();
        } else {
            previewName.textContent = 'Nama Ruangan';
            previewAvatar.textContent = '?';
        }

        // Update lantai
        if (floorSelect.value) {
            previewFloor.textContent = floorText;
        } else {
            previewFloor.textContent = 'Pilih lantai terlebih dahulu';
        }
    }

    nameInput.addEventListener('input', updatePreview);
    floorSelect.addEventListener('change', updatePreview);

    form.addEventListener('submit', function() {
        const btn = form.querySelector('.btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memperbarui...';
    });

    form.addEventListener('reset', function() {
        setTimeout(() => {
            nameInput.value = originalName;
            floorSelect.value = originalFloorId;
            updatePreview();
        }, 0);
    });

    // Inisialisasi awal
    updatePreview();
});
</script>
@endsection