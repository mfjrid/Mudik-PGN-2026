@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-5">
                <h1 class="display-5 text-white fw-bold">Pendaftaran Mudik PGN 2026</h1>
                <p class="text-white-50">Langkah 1: Informasi Keberangkatan & Jumlah Peserta</p>
            </div>

            <div class="card bg-dark border-secondary shadow-lg overflow-hidden">
                <div class="card-header bg-primary p-1"></div>
                <div class="card-body p-5">
                    <form action="{{ route('passenger.registration.step1') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="total_members" class="form-label text-white-50">Jumlah Peserta (Termasuk Anda)</label>
                                <select name="total_members" id="total_members" class="form-select bg-dark text-white border-secondary @error('total_members') is-invalid @enderror" required>
                                    @for($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ old('total_members') == $i ? 'selected' : '' }}>{{ $i }} Orang</option>
                                    @endfor
                                </select>
                                <div class="form-text text-white-50 small">Maksimal 4 orang dalam 1 Kartu Keluarga (KK).</div>
                                @error('total_members') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="departure_location" class="form-label text-white-50">Lokasi Keberangkatan</label>
                                <select name="departure_location" id="departure_location" class="form-select bg-dark text-white border-secondary @error('departure_location') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Lokasi</option>
                                    <option value="Jakarta" {{ old('departure_location') == 'Jakarta' ? 'selected' : '' }}>Jakarta (Kantor Pusat PGN)</option>
                                    <option value="Semarang" {{ old('departure_location') == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                                    <option value="Surabaya" {{ old('departure_location') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                                </select>
                                @error('departure_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="alert alert-info bg-opacity-10 border-info text-info mb-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle mt-1 me-3"></i>
                                <p class="mb-0 small">Pastikan data yang Anda masukkan sesuai dengan Kartu Keluarga yang sah. Untuk anak berusia minimal 3 tahun wajib didaftarkan sebagai peserta.</p>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary py-3 text-uppercase fw-bold hvr-grow">Lanjutkan ke Pilih Bus <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
