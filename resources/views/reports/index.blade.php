@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Laporan Kerusakan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Perangkat</th>
                <th>Kategori</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $key => $report)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->device }}</td>
                    <td>{{ $report->category->name }}</td>
                    <td>{{ $report->reason }}</td>
                    <td>
                        @if($report->status == 'accepted')
                            <span class="badge bg-success">Diterima</span>
                        @elseif($report->status == 'rejected')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="accepted">
                            <button class="btn btn-success btn-sm" {{ $report->status != 'pending' ? 'disabled' : '' }}>Terima</button>
                        </form>

                        <form action="{{ route('damage-reports.update', $report->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-danger btn-sm" {{ $report->status != 'pending' ? 'disabled' : '' }}>Tolak</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
