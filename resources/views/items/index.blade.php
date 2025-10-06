@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Manajemen Perangkat</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tombol Tambah Item -->
    <div class="mb-3">
        <a href="{{ route('items.create') }}" class="btn btn-primary">+ Tambah Perangkat</a>
    </div>

    <!-- List Item -->
    <div class="card">
        <div class="card-header">Daftar Perangkat</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Ruangan</th>
                        <th>Lantai</th>
                        <th>Tgl Pasang</th>
                        <th>Tgl Maintenance</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>
                            @if($item->status == 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>{{ $item->room }}</td>
                        <td>{{ $item->floor }}</td>
                        <td>{{ $item->install_date }}</td>
                        <td>{{ $item->replacement_date }}</td>
                        <td>
                            @if($item->photo)
                                <img src="{{ asset('storage/'.$item->photo) }}" width="60">
                            @endif
                        </td>
                        <td>
                            <!-- Tombol Detail -->
                            <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-info mb-1">Detail</a>

                            <!-- Tombol Edit -->
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning mb-1">Edit</a>

                            <!-- Form Hapus -->
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus item ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection