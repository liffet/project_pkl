@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Lantai</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('floors.update', $floor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lantai</label>
                    <input type="text" name="name" class="form-control" value="{{ $floor->name }}" required>
                </div>
                <button type="submit" class="btn btn-success">Perbarui</button>
                <a href="{{ route('floors.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
