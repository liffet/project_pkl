@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-bold text-dark">Kategori</h2>
                    <p class="text-muted mb-0">Kelola kategori produk Anda</p>
                </div>
                <a href="{{ route('categories.create') }}" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Table Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-semibold">Daftar Kategori</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 bg-light" 
                               placeholder="Cari kategori..." id="searchInput">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold" style="width: 10%">ID</th>
                            <th class="py-3 text-muted fw-semibold">Nama Kategori</th>
                            <th class="py-3 text-muted fw-semibold text-center" style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr class="category-row">
                            <td class="px-4">
                                <span class="badge bg-light text-dark border">{{ $category->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-medium category-name">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <a href="{{ route('categories.edit', $category) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit kategori">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $category->id }}"
                                            title="Hapus kategori">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                                    Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-2">Apakah Anda yakin ingin menghapus kategori:</p>
                                                <p class="fw-bold text-primary mb-0">{{ $category->name }}</p>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Batal
                                                </button>
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada kategori</h5>
                                    <p class="text-muted mb-3">Mulai dengan menambahkan kategori pertama Anda</p>
                                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($categories->isNotEmpty())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $categories->count() }} kategori
                </small>
                @if(method_exists($categories, 'links'))
                    {{ $categories->links() }}
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

.btn-group .btn {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.empty-state {
    padding: 2rem 0;
}

.card {
    overflow: hidden;
}

.input-group-text {
    background-color: #f8f9fa !important;
}

.input-group .form-control:focus {
    border-color: #dee2e6;
    box-shadow: none;
}

.modal-content {
    border-radius: 1rem;
}

.badge {
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.25rem !important;
        margin-bottom: 0.25rem;
    }
}
</style>

<script>
// Search functionality
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const rows = document.querySelectorAll('.category-row');
    
    rows.forEach(row => {
        const categoryName = row.querySelector('.category-name').textContent.toLowerCase();
        row.style.display = categoryName.includes(searchText) ? '' : 'none';
    });
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection