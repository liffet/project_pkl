@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Edit Gedung</h2>

    <form action="{{ route('building.update', $building->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Gedung</label>
            <input type="text" name="name" class="form-control" value="{{ $building->name }}" required>
        </div>

        <div class="mb-3">
            <label>Total Lantai</label>
            <input type="number" name="total_floors" min="1" class="form-control" value="{{ $building->total_floors }}" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('building.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
