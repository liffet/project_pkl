@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Edit Kategori</h2>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">Perbarui data kategori yang dipilih</p>
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

            <!-- Form Card -->
            <form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryForm">
                @csrf
                @method('PUT')
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-pencil-square"></i>
                        <span>Ubah Informasi Kategori</span>
                    </div>
                    <div class="form-card-body">
                        <!-- Nama Kategori -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="bi bi-tag"></i>
                                Nama Kategori
                                <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name) }}"
                                placeholder="Masukkan nama kategori"
                                required
                                autofocus>
                            @error('name')
                                <div class="invalid-feedback d-block mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="form-text">
                                    <i class="bi bi-info-circle"></i> Pastikan nama kategori belum digunakan.
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
                                <div>
                                    <h6 class="mb-0 fw-semibold" id="previewName">{{ old('name', $category->name) ?: 'Nama Kategori' }}</h6>
                                    <small class="text-muted">Preview tampilan kategori</small>
                                </div>
                            </div>
                        </div>

                        <!-- Character Counter -->
                        <div class="text-end mt-2">
                            <small class="text-muted">
                                <span id="charCount">{{ strlen(old('name', $category->name)) }}</span>/255 karakter
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions mt-4">
                    <a href="{{ route('categories.index') }}" class="btn-cancel">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const charCount = document.getElementById('charCount');
    const previewName = document.getElementById('previewName');
    const previewAvatar = document.getElementById('previewAvatar');
    const form = document.getElementById('categoryForm');

    function updatePreview() {
        const value = nameInput.value.trim();
        charCount.textContent = value.length;

        if (value) {
            previewName.textContent = value;
            previewAvatar.textContent = value.charAt(0).toUpperCase();
        } else {
            previewName.textContent = 'Nama Kategori';
            previewAvatar.textContent = '?';
        }
    }

    nameInput.addEventListener('input', updatePreview);

    form.addEventListener('submit', function() {
        const btn = form.querySelector('.btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });

    form.addEventListener('reset', function() {
        setTimeout(() => {
            nameInput.value = '';
            charCount.textContent = '0';
            previewName.textContent = 'Nama Kategori';
            previewAvatar.textContent = '?';
        }, 0);
    });

    // Inisialisasi awal
    updatePreview();
});
</script>
@endsection