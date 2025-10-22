@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Ruangan</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Ruangan</label>
                    <input type="text" name="name" class="form-control" value="{{ $room->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="floor_id" class="form-label">Lantai</label>
                    <select name="floor_id" class="form-select" required>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" {{ $floor->id == $room->floor_id ? 'selected' : '' }}>
                                {{ $floor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Perbarui</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
