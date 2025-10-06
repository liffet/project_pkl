@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Hasil Pencarian: "{{ $query }}"</h4>

    @if($items->isEmpty())
        <div class="alert alert-warning">Tidak ada perangkat ditemukan.</div>
    @else
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Ruangan</th>
                    <th>Lantai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ $item->room }}</td>
                    <td>{{ $item->floor }}</td>
                    <td>
                        <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
