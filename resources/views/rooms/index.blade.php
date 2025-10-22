@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manajemen Ruangan</h2>
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">+ Tambah Ruangan</a>
    </div>

    <div class="card">
        <div class="card-header">Daftar Ruangan</div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>Nama Ruangan</th>
                        <th>Lantai</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $index => $room)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->floor->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus ruangan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data ruangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
