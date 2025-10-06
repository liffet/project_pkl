@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<h2>Edit Kategori</h2>

<form action="{{ route('categories.update', $category) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nama Kategori</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        @error('name')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
