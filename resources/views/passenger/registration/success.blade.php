@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <!-- Payment Modal -->
    <div id="xendit-modal" class="modal-backdrop d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
        <div class="modal-container" style="width: 90%; max-width: 500px; height: 80vh; background: white; border-radius: 12px; overflow: hidden; position: relative; display: flex; flex-direction: column;">
            <div class="modal-header p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h5 class="mb-0 text-dark">Pembayaran Aman</h5>
                <button type="button" id="close-modal" class="btn-close" style="cursor: pointer;"></button>
            </div>
            <div class="modal-body flex-grow-1" style="position: relative;">
                <div id="loading-spinner" class="position-absolute top-50 start-50 translate-middle text-dark">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <iframe id="xendit-iframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
            </div>
            <h1 class="display-5 text-white fw-bold mb-3">Pendaftaran Berhasil!</h1>
            <p class="text-white-50 fs-5 mb-5">Terima kasih telah mendaftar. Data Anda telah kami terima dan sedang dalam proses verifikasi.</p>

            <div class="card bg-dark border-secondary shadow-lg mb-5 text-start">
                <div class="card-body p-4">
                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2">Detail Pendaftaran</h5>
                    <div class="row mb-2">
                        <div class="col-5 text-white-50">ID Pendaftaran:</div>
                        <div class="col-7 text-white fw-bold">#{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-white-50">Rute:</div>
                        <div class="col-7 text-white">{{ $registration->bus->route_name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-white-50">Nomor Bus:</div>
                        <div class="col-7 text-white">{{ $registration->bus->bus_number }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-white-50">Jumlah Peserta:</div>
                        <div class="col-7 text-white">{{ $registration->total_members }} Orang</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-5 text-white-50">Total Deposit:</div>
                        <div class="col-7 text-info fw-bold">Rp {{ number_format($registration->deposit_amount, 0, ',', '.') }}</div>
                    </div>

                    <div class="alert alert-warning bg-opacity-10 border-warning text-warning mb-0">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle mt-1 me-3"></i>
                            <p class="mb-0 small">Silakan lakukan pembayaran deposit melalui tombol di bawah untuk mengunci kursi Anda. Setelah pembayaran diverifikasi, admin akan memproses pendaftaran Anda ke sistem Jasamarga.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center" id="payment-container">
                <button type="button" id="pay-button" class="btn btn-primary btn-lg px-5 py-3 fw-bold text-uppercase" {{ $payment_url == '#' ? 'disabled' : '' }}>
                    Bayar Sekarang (Xendit)
                </button>
                <a href="{{ url('/') }}" class="btn btn-outline-light btn-lg px-5 py-3">Kembali ke Beranda</a>
            </div>

            <div class="mt-4">
                <form action="{{ route('passenger.registration.cancel', $registration) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini? Kursi Anda akan dilepaskan.')">
                    @csrf
                    <button type="submit" class="btn btn-link text-white-50 text-decoration-none small">
                        <i class="fas fa-times me-1"></i>Batalkan Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const payButton = document.getElementById('pay-button');
    const xenditModal = document.getElementById('xendit-modal');
    const xenditIframe = document.getElementById('xendit-iframe');
    const closeModal = document.getElementById('close-modal');
    const loadingSpinner = document.getElementById('loading-spinner');

    payButton.addEventListener('click', function() {
        const paymentUrl = "{{ $payment_url }}";
        if (paymentUrl && paymentUrl !== '#') {
            // Show modal and loading
            xenditModal.classList.remove('d-none');
            xenditModal.style.display = 'flex';
            loadingSpinner.classList.remove('d-none');
            
            // Set iframe src
            xenditIframe.src = paymentUrl;

            // Hide spinner when iframe is loaded
            xenditIframe.onload = function() {
                loadingSpinner.classList.add('d-none');
            };
        }
    });

    closeModal.addEventListener('click', function() {
        xenditModal.classList.add('d-none');
        xenditModal.style.display = 'none';
        xenditIframe.src = '';
    });

    // Close on backdrop click
    xenditModal.addEventListener('click', function(e) {
        if (e.target === xenditModal) {
            closeModal.click();
        }
    });
</script>
@endpush
