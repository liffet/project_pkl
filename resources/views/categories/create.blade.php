@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Page Header -->
            <div class="mb-4">
                <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Tambah Kategori Baru</h2>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">Isi formulir di bawah untuk menambahkan kategori baru</p>
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
            <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                @csrf
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Informasi Kategori</span>
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
                                value="{{ old('name') }}"
                                placeholder="Contoh: Elektronik, Alat Kantor, Furniture"
                                required
                                autofocus>
                            @error('name')
                                <div class="invalid-feedback d-block mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="form-text">
                                    <i class="bi bi-info-circle"></i> Nama kategori harus unik dan maksimal 255 karakter.
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
                                <div class="preview-icon me-3" id="previewAvatar">?</div>
                                <div>
                                    <h6 class="mb-0 fw-semibold" id="previewName">Nama Kategori</h6>
                                    <small class="text-muted">Preview tampilan kategori</small>
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
                    <a href="{{ route('categories.index') }}" class="btn-cancel">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle"></i> Simpan Kategori
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tips -->
            <div class="form-card mt-4" style="background: #f0f9ff;">
                <div class="form-card-header" style="background: #2D4194; color: white;">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>Tips Penamaan Kategori</span>
                </div>
                <div class="form-card-body">
                    <ul class="mb-0 ps-3 small text-muted">
                        <li>Gunakan nama yang jelas dan mudah dipahami</li>
                        <li>Hindari karakter khusus yang berlebihan</li>
                        <li>Pastikan nama belum digunakan sebelumnya</li>
                        <li>Gunakan huruf kapital di awal kata untuk konsistensi</li>
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
    background-color: #2D4194;  /* 💙 warna utama */
    color: #ffffff;             /* teks putih agar kontras */
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

    nameInput.addEventListener('input', function() {
        const value = this.value;
        const length = value.length;
        charCount.textContent = length;

        if (value.trim()) {
            previewName.textContent = value;
            previewAvatar.textContent = value.charAt(0).toUpperCase();
        } else {
            previewName.textContent = 'Nama Kategori';
            previewAvatar.textContent = '?';
        }

        if (length > 240) {
            charCount.classList.add('text-warning');
        } else if (length > 255) {
            charCount.classList.remove('text-warning');
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-warning', 'text-danger');
        }
    });

    form.addEventListener('submit', function(e) {
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
});
</script>
@endsection
