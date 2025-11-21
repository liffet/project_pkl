@extends('layouts.app')

@section('title', 'Daftar Lantai')

@section('content')
<div class="container-fluid px-4 py-4">

    <h2 class="fw-bold mb-1">Lantai – {{ $building->name }}</h2>
    <p class="text-muted mb-3">Daftar lantai dalam gedung ini</p>

    <a href="{{ route('floors.index') }}" class="btn btn-secondary mb-3">
        Kembali ke daftar gedung
    </a>

    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lantai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($floors as $floor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $floor->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
