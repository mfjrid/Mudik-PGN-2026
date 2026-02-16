@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-light me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="h3 mb-0 text-white">Detail Pendaftaran #{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</h1>
            </div>

            <div class="card bg-dark border-secondary shadow-lg mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Daftar Anggota Keluarga</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark mb-0">
                            <thead>
                                <tr class="text-white-50">
                                    <th class="ps-4">Nama</th>
                                    <th>NIK</th>
                                    <th>Usia</th>
                                    <th>Kursi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registration->familyMembers as $member)
                                    <tr>
                                        <td class="ps-4">{{ $member->name }}</td>
                                        <td>{{ $member->identity_number }}</td>
                                        <td>{{ $member->age }} Thn</td>
                                        <td class="text-info FW-BOLD">{{ $member->seat->seat_number }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-dark border-secondary shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Aksi Verifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-white-50 small d-block">Status Pembayaran</label>
                        <span class="badge {{ $registration->payment_status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }} fs-6">
                            {{ strtoupper($registration->payment_status) }}
                        </span>
                    </div>

                    @if($registration->status == 'pending' && $registration->payment_status == 'paid')
                        <form action="{{ route('admin.registrations.verify', $registration) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 mb-3 fw-bold">
                                <i class="fas fa-check-circle me-2"></i>VERIFIKASI DATA
                            </button>
                            <p class="text-white-50 small text-center">Verifikasi ini menyatakan data KK dan identitas peserta sudah valid.</p>
                        </form>
                    @elseif($registration->status == 'accepted')
                        <div class="text-center py-3">
                            <i class="fas fa-check-double text-info mb-2 fs-2"></i>
                            <h6 class="text-info fw-bold">TERVERIFIKASI</h6>
                        </div>
                    @else
                        <div class="alert alert-secondary bg-opacity-10 border-secondary text-white-50">
                            <small>Menunggu pembayaran deposit dari penumpang sebelum verifikasi dapat dilakukan.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
