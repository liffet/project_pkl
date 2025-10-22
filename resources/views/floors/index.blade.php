@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manajemen Lantai</h2>
        <a href="{{ route('floors.create') }}" class="btn btn-primary">+ Tambah Lantai</a>
    </div>

    <div class="card">
        <div class="card-header">Daftar Lantai</div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>Nama Lantai</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($floors as $index => $floor)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $floor->name }}</td>
                            <td>
                                <a href="{{ route('floors.edit', $floor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('floors.destroy', $floor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus lantai ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data lantai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
