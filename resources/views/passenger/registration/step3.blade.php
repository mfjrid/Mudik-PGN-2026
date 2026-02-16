@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-5">
                <h1 class="display-5 text-white fw-bold">Pendaftaran Mudik PGN 2026</h1>
                <p class="text-white-50">Langkah 3: Pilih Kursi & Isi Data Peserta</p>
            </div>

            <form action="{{ route('passenger.registration.step3.post') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Seat Selection -->
                    <div class="col-lg-6">
                        <div class="card bg-dark border-secondary shadow-lg h-100">
                            <div class="card-header bg-secondary text-white py-3">
                                <h5 class="card-title mb-0">Pilih Kursi (Pilih {{ $data['total_members'] }})</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="bus-layout border border-secondary rounded p-4 bg-black mb-4">
                                    <div class="row row-cols-4 g-3" id="seat-grid">
                                        @foreach($bus->seats as $seat)
                                            <div class="col text-center">
                                                <input type="checkbox" name="selected_seats[]" value="{{ $seat->id }}" 
                                                       id="seat-{{ $seat->id }}" class="d-none seat-checkbox" 
                                                       {{ $seat->status != 'available' ? 'disabled' : '' }}>
                                                <label for="seat-{{ $seat->id }}" 
                                                       class="seat-item p-2 rounded {{ $seat->status == 'available' ? 'bg-outline-success cursor-pointer' : 'bg-danger-subtle text-muted opacity-50 cursor-not-allowed' }}" 
                                                       style="width: 50px; height: 50px;"
                                                       data-seat-number="{{ $seat->seat_number }}">
                                                    <small class="fw-bold">{{ $seat->seat_number }}</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex gap-3 justify-content-center small text-white-50">
                                    <div class="d-flex align-items-center"><div class="bg-success me-1" style="width:12px; height:12px; border-radius:2px;"></div> Tersedia</div>
                                    <div class="d-flex align-items-center"><div class="bg-primary me-1" style="width:12px; height:12px; border-radius:2px;"></div> Pilihan Anda</div>
                                    <div class="d-flex align-items-center"><div class="bg-danger me-1" style="width:12px; height:12px; border-radius:2px;"></div> Terisi</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Member Info -->
                    <div class="col-lg-6">
                        <div class="card bg-dark border-secondary shadow-lg">
                            <div class="card-header bg-secondary text-white py-3">
                                <h5 class="card-title mb-0">Data Anggota Keluarga</h5>
                            </div>
                            <div class="card-body p-4">
                                @for($i = 0; $i < $data['total_members']; $i++)
                                    <div class="mb-4 p-3 border border-secondary rounded">
                                        <h6 class="text-info mb-3">Peserta #{{ $i+1 }} {{ $i == 0 ? '(Pemilik KK)' : '' }}</h6>
                                        <div class="mb-3">
                                            <label class="form-label text-white-50 small">Nama Lengkap (Sesuai KTP/KK)</label>
                                            <input type="text" name="family[{{ $i }}][name]" class="form-control bg-dark text-white border-secondary" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-white-50 small">NIK</label>
                                                <input type="text" name="family[{{ $i }}][identity_number]" class="form-control bg-dark text-white border-secondary" required maxlength="16">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label text-white-50 small">Usia</label>
                                                <input type="number" name="family[{{ $i }}][age]" class="form-control bg-dark text-white border-secondary" required min="0">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-5">
                    <a href="{{ route('passenger.registration.step2') }}" class="btn btn-outline-light px-4">Kembali</a>
                    <button type="submit" id="submit-btn" class="btn btn-primary px-5 py-2 fw-bold text-uppercase" disabled>Selesaikan Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-outline-success { border: 2px solid #198754; color: #198754; }
    .bg-outline-success:hover { background: #198754; color: #fff; }
    .seat-checkbox:checked + label { background: #0d6efd !important; border-color: #0d6efd !important; color: #fff !important; }
    .cursor-pointer { cursor: pointer; }
    .cursor-not-allowed { cursor: not-allowed; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.seat-checkbox');
        const maxSeats = {{ $data['total_members'] }};
        const submitBtn = document.getElementById('submit-btn');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.seat-checkbox:checked').length;
                
                if (checkedCount > maxSeats) {
                    this.checked = false;
                    alert(`Maksimal pilihan adalah ${maxSeats} kursi.`);
                }

                submitBtn.disabled = (document.querySelectorAll('.seat-checkbox:checked').length !== maxSeats);
            });
        });
    });
</script>
@endsection
