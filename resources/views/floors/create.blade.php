@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Tambah Lantai</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('floors.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lantai</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lantai..." required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('floors.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
