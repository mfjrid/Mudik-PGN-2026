@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-white fw-bold mb-0">Dashboard Penumpang</h1>
        @if(!$hasActiveRegistration)
            <a href="{{ route('passenger.registration.step1') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Daftar Baru
            </a>
        @endif
    </div>

    @if($registrations->isEmpty())
        <div class="card bg-dark border-secondary">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list text-white-50 mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-white">Belum ada pendaftaran</h5>
                <p class="text-white-50">Silakan lakukan pendaftaran untuk mulai mudik bersama PGN.</p>
                <a href="{{ route('passenger.registration.step1') }}" class="btn btn-outline-light mt-2">Daftar Sekarang</a>
            </div>
        </div>
    @else
        @foreach($registrations as $reg)
            <div class="card bg-dark border-secondary shadow mb-4">
                <div class="card-header border-secondary bg-opacity-25 bg-secondary d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="text-white-50 small text-uppercase">ID Pendaftaran:</span>
                        <span class="text-white fw-bold ms-1">#{{ str_pad($reg->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        @php
                            $statusClass = [
                                'pending' => 'bg-warning text-dark',
                                'accepted' => 'bg-success',
                                'rejected' => 'bg-danger',
                                'cancelled' => 'bg-secondary'
                            ][$reg->status];
                        @endphp
                        <span class="badge {{ $statusClass }} text-uppercase px-3 py-2">{{ $reg->status }}</span>
                        
                        @if($reg->payment_status === 'paid')
                            <span class="badge bg-info text-dark text-uppercase px-3 py-2">Lunas</span>
                        @else
                            <span class="badge bg-outline-warning text-warning border border-warning text-uppercase px-3 py-2">Belum Bayar</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4 border-end border-secondary">
                            <h6 class="text-white-50 small text-uppercase mb-3">Informasi Bus</h6>
                            <h5 class="text-white mb-1">{{ $reg->bus->route_name }}</h5>
                            <p class="text-info mb-0 fw-bold">Bus #{{ $reg->bus->bus_number }}</p>
                            <p class="text-white-50 small mb-0 mt-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $reg->departure_location }}
                            </p>
                        </div>
                        <div class="col-md-5 border-end border-secondary">
                            <h6 class="text-white-50 small text-uppercase mb-3">Peserta & Kursi</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-dark table-borderless mb-0">
                                    <thead>
                                        <tr class="text-white-50 small border-bottom border-secondary">
                                            <th>Nama</th>
                                            <th>NIK</th>
                                            <th class="text-center">Kursi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reg->familyMembers as $member)
                                            <tr>
                                                <td class="text-white small">{{ $member->name }}</td>
                                                <td class="text-white-50 x-small">{{ $member->identity_number }}</td>
                                                <td class="text-center text-info fw-bold">{{ $member->seat->seat_number }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex flex-column justify-content-center text-center">
                            @if($reg->status === 'accepted')
                                <div class="mb-3">
                                    <i class="fas fa-ticket-alt text-success" style="font-size: 2.5rem;"></i>
                                </div>
                                <button class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-download me-2"></i>Unduh E-Ticket
                                </button>
                            @elseif($reg->status === 'pending')
                                @if($reg->payment_status !== 'paid')
                                    <a href="{{ route('passenger.registration.success', $reg) }}" class="btn btn-warning w-100 fw-bold">
                                        <i class="fas fa-wallet me-2"></i>Bayar Refundable
                                    </a>
                                @else
                                    <div class="text-info mb-2 small">
                                        <i class="fas fa-clock me-1"></i> Menunggu Verifikasi Admin
                                    </div>
                                @endif
                                <form action="{{ route('passenger.registration.cancel', $reg) }}" method="POST" onsubmit="return confirm('Batalkan pendaftaran ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger text-decoration-none small">Batalkan</button>
                                </form>
                            @else
                                <div class="text-white-50 small italic">Tidak ada aksi tersedia</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
