@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('categories.index') }}" class="text-decoration-none">
                    <i class="bi bi-house-door"></i> Kategori
                </a>
            </li>
            <li class="breadcrumb-item active">Tambah Kategori</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-plus-circle fs-3"></i>
                </div>
                <div>
                    <h2 class="mb-1 fw-bold text-dark">Tambah Kategori Baru</h2>
                    <p class="text-muted mb-0">Isi form di bawah untuk menambahkan kategori baru</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Form Card -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Informasi Kategori
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                        @csrf
                        
                        <!-- Nama Kategori -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-tag text-muted"></i>
                                </span>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name"
                                    class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}"
                                    placeholder="Contoh: Elektronik, Makanan, Fashion"
                                    required
                                    autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @else
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Nama kategori harus unik dan maksimal 255 karakter
                                </small>
                            @enderror
                            
                            <!-- Character Counter -->
                            <div class="mt-2">
                                <small class="text-muted">
                                    <span id="charCount">0</span>/255 karakter
                                </small>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Preview</label>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="preview-avatar bg-primary bg-opacity-10 text-primary me-3" id="previewAvatar">
                                            ?
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold" id="previewName">Nama Kategori</h6>
                                            <small class="text-muted">Preview kategori yang akan dibuat</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-warning">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Kategori
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card border-0 bg-info bg-opacity-10 mt-3">
                <div class="card-body">
                    <h6 class="text-info mb-2">
                        <i class="bi bi-lightbulb-fill me-2"></i>Tips Penamaan Kategori
                    </h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Gunakan nama yang jelas dan mudah dipahami</li>
                        <li>Hindari penggunaan karakter khusus yang berlebihan</li>
                        <li>Pastikan nama kategori belum digunakan sebelumnya</li>
                        <li>Gunakan huruf kapital di awal kata untuk konsistensi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.2s ease;
}

.input-group-text {
    background-color: #f8f9fa !important;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-control.border-start-0:focus {
    border-color: #dee2e6;
    box-shadow: none;
}

.input-group:focus-within .input-group-text {
    border-color: #667eea;
}

.input-group:focus-within .border-end-0 {
    border-right-color: transparent !important;
}

.preview-avatar {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item a {
    color: #6c757d;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #667eea;
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 500;
}

.btn {
    padding: 0.5rem 1.25rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    transform: translateY(-1px);
}

.btn-outline-warning:hover {
    transform: translateY(-1px);
}

.invalid-feedback {
    animation: shake 0.3s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Responsive */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
        width: 100%;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
    
    .justify-content-between {
        flex-direction: column !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const charCount = document.getElementById('charCount');
    const previewName = document.getElementById('previewName');
    const previewAvatar = document.getElementById('previewAvatar');
    const form = document.getElementById('categoryForm');

    // Character counter and live preview
    nameInput.addEventListener('input', function() {
        const value = this.value;
        const length = value.length;
        
        // Update character count
        charCount.textContent = length;
        
        // Update preview
        if (value.trim()) {
            previewName.textContent = value;
            previewAvatar.textContent = value.charAt(0).toUpperCase();
        } else {
            previewName.textContent = 'Nama Kategori';
            previewAvatar.textContent = '?';
        }
        
        // Color indication for character limit
        if (length > 240) {
            charCount.classList.add('text-warning');
        } else if (length > 255) {
            charCount.classList.remove('text-warning');
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-warning', 'text-danger');
        }
    });

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Disable submit button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });

    // Reset button functionality
    form.addEventListener('reset', function() {
        setTimeout(() => {
            charCount.textContent = '0';
            previewName.textContent = 'Nama Kategori';
            previewAvatar.textContent = '?';
            nameInput.focus();
        }, 0);
    });

    // Auto-focus on name input
    if (nameInput) {
        nameInput.focus();
    }
});
</script>
@endsection