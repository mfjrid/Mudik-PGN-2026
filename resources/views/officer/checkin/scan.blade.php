@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="h3 mb-4 text-white">Petugas Check-In</h1>
            
            <div class="card bg-dark border-secondary shadow-lg mb-4">
                <div class="card-body p-5">
                    <div id="reader" style="width: 100%;" class="mb-4"></div>
                    <div id="result-container" class="d-none">
                        <div id="result-alert" class="alert mb-4"></div>
                        <button onclick="resetScanner()" class="btn btn-outline-light">Scan Lagi</button>
                    </div>
                    <p class="text-white-50 small" id="scan-hint">Arahkan kamera ke QR Code yang ada di e-tiket penumpang untuk melakukan check-in.</p>
                </div>
            </div>

            <div class="alert alert-info bg-opacity-10 border-info text-info text-start small">
                <ul class="mb-0">
                    <li>Pastikan penumpang membawa e-tiket asli.</li>
                    <li>Sistem hanya akan memproses status "Accepted".</li>
                    <li>Satu pendaftaran hanya bisa check-in satu kali per bus.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning after success
        html5QrcodeScanner.clear();
        document.getElementById('reader').classList.add('d-none');
        document.getElementById('scan-hint').classList.add('d-none');
        document.getElementById('result-container').classList.remove('d-none');

        const alertDiv = document.getElementById('result-alert');
        alertDiv.className = 'alert alert-info';
        alertDiv.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses Verifikasi...';

        fetch("{{ route('officer.verify') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_data: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertDiv.className = 'alert alert-success';
                alertDiv.innerHTML = `<i class="fas fa-check-circle me-2"></i> <strong>Check-In Berhasil!</strong><br>${data.registration.user.name} - ${data.registration.bus.bus_number}`;
            } else {
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = `<i class="fas fa-times-circle me-2"></i> <strong>Gagal:</strong> ${data.message}`;
            }
        })
        .catch(error => {
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan sistem.';
        });
    }

    function resetScanner() {
        location.reload();
    }

    let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endpush
@endsection
