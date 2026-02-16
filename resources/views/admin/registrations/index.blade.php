@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Verifikasi Pendaftaran</h1>
    </div>

    <div class="card bg-dark border-secondary shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead class="table-light text-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Pemesan</th>
                            <th>Rute</th>
                            <th>Peserta</th>
                            <th>Status Bayar</th>
                            <th>Status Reg</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                            <tr>
                                <td class="ps-4 text-info">#{{ str_pad($reg->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $reg->user->name }}</td>
                                <td>{{ $reg->bus->route_name }}</td>
                                <td>{{ $reg->total_members }} Orang</td>
                                <td>
                                    <span class="badge {{ $reg->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ strtoupper($reg->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $reg->status == 'accepted' ? 'bg-info' : 'bg-secondary' }}">
                                        {{ strtoupper($reg->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.registrations.show', $reg) }}" class="btn btn-sm btn-outline-light">
                                        <i class="fas fa-search me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Belum ada pendaftaran masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
