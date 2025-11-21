@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Daftar Gedung</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Gedung</th>
                <th>Total Lantai</th>
            </tr>
        </thead>

        <tbody>
            @foreach($buildings as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $b->name }}</td>
                <td>{{ $b->total_floors }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
