@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Tambah Gedung</h2>

    <form action="{{ route('building.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Gedung</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Total Lantai</label>
            <input type="number" name="total_floors" min="1" class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('building.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
