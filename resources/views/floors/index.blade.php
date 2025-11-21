@extends('layouts.app')

@section('title', 'Daftar Lantai')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #1f2937; font-size: 1.5rem;">Lantai</h2>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Daftar lantai berdasarkan gedung (readonly)</p>
        </div>

        <!-- Tanggal -->
        <small class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</small>
    </div>

    <!-- PILIH GEDUNG -->
    <form method="GET" action="{{ route('floors.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label class="fw-medium mb-2">Pilih Gedung</label>
                <select name="building_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Pilih Gedung --</option>

                    @foreach($buildings as $building)
                        <option value="{{ $building->id }}"
                            {{ request('building_id') == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                    @endforeach

                </select>
            </div>
        </div>
    </form>

    <!-- Table Card -->
    <div class="table-card">

        <!-- Search + Export -->
        <div class="d-flex justify-content-between align-items-center mb-3">

            <!-- Tombol Export -->
            @if(request('building_id'))
            <a href="{{ route('floors.export.excel', ['building_id' => request('building_id')]) }}" 
   class="btn btn-success">
    <i class="bi bi-file-earmark-excel"></i> Download Excel
</a>

            @endif

            <!-- Search Bar -->
            <div class="search-bar w-50">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Cari lantai..." id="searchInput">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 10%">No</th>
                        <th>Lantai</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($floors as $floor)
                    <tr class="floor-row">
                        <td>
                            <span class="badge-code">{{ $loop->iteration }}</span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <span class="floor-name fw-medium ms-3">{{ $floor->name }}</span>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-building" style="font-size: 4rem; opacity: 0.2; color: #6b7280;"></i>
                                <h5 class="text-muted mt-3 mb-2">Tidak ada data lantai</h5>
                                <p class="text-muted mb-3" style="font-size: 0.875rem;">
                                    Pilih gedung untuk melihat lantai.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #e5e7eb;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 600;
}

.custom-table th {
    background-color: #f3f4f6;
    color: #374151;
    font-weight: 600;
}

.table-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.badge-code {
    background-color: #e5e7eb;
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 0.75rem;
    font-weight: 600;
}
</style>

<script>
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const rows = document.querySelectorAll('.floor-row');

    rows.forEach(row => {
        const floorName = row.querySelector('.floor-name').textContent.toLowerCase();
        row.style.display = floorName.includes(searchText) ? '' : 'none';
    });
});
</script>

@endsection
