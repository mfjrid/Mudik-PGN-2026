@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-5">
                <h1 class="display-5 text-white fw-bold">Pendaftaran Mudik PGN 2026</h1>
                <p class="text-white-50">Langkah 2: Pilih Bus & Rute</p>
            </div>

            <div class="row g-4">
                @forelse($buses as $bus)
                    <div class="col-md-6 col-lg-4">
                        <div class="card bg-dark border-secondary h-100 shadow-sm hvr-float">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-info mb-0">{{ $bus->bus_number }}</h5>
                                    <span class="badge bg-success">Tersedia</span>
                                </div>
                                <h6 class="text-white mb-3"><i class="fas fa-route me-2 text-primary"></i>{{ $bus->route_name }}</h6>
                                <p class="text-white-50 small mb-4">Pilih bus ini untuk melanjutkan pemesanan kursi.</p>
                                
                                <div class="d-grid">
                                    <form action="{{ route('passenger.registration.step2.post') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="bus_id" value="{{ $bus->id }}">
                                        <button type="submit" class="btn btn-primary">Pilih Bus Ini</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-white-50">Maaf, saat ini tidak ada bus yang tersedia.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 text-center">
                <a href="{{ route('passenger.registration.step1') }}" class="text-white-50 text-decoration-none">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Langkah 1
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
