@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-5">
                <h1 class="display-5 text-white fw-bold">Pendaftaran Mudik PGN 2026</h1>
                <p class="text-white-50" id="step-description">Langkah 1: Informasi Keberangkatan & Jumlah Peserta</p>
                
                @if($errors->any())
                    <div class="alert alert-danger bg-opacity-10 border-danger text-danger text-start mt-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Progress Stepper -->
                <div class="d-flex justify-content-center mt-4">
                    <div class="stepper d-flex align-items-center">
                        <div class="step active" id="badge-1">1</div>
                        <div class="line"></div>
                        <div class="step" id="badge-2">2</div>
                        <div class="line"></div>
                        <div class="step" id="badge-3">3</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('passenger.registration.store') }}" method="POST" id="registration-form">
                @csrf
                
                <!-- STEP 1: General Info -->
                <div class="step-content" id="step-1">
                    <div class="card bg-dark border-secondary shadow-lg">
                        <div class="card-body p-5">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="total_members" class="form-label text-white-50">Jumlah Peserta (Termasuk Anda)</label>
                                    <select name="total_members" id="total_members" class="form-select bg-dark text-white border-secondary" required onchange="resetRegistration()">
                                        @for($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ old('total_members') == $i ? 'selected' : '' }}>{{ $i }} Orang</option>
                                        @endfor
                                    </select>
                                    <div class="form-text text-white-50 small">Maksimal 4 orang dalam 1 Kartu Keluarga (KK).</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="departure_location" class="form-label text-white-50">Lokasi Keberangkatan</label>
                                    <select name="departure_location" id="departure_location" class="form-select bg-dark text-white border-secondary" required>
                                        <option value="" disabled {{ !old('departure_location') ? 'selected' : '' }}>Pilih Lokasi</option>
                                        <option value="Jakarta" {{ old('departure_location') == 'Jakarta' ? 'selected' : '' }}>Jakarta (Kantor Pusat PGN)</option>
                                        <option value="Semarang" {{ old('departure_location') == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                                        <option value="Surabaya" {{ old('departure_location') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-grid mt-5">
                                <button type="button" class="btn btn-primary py-3 fw-bold text-uppercase" onclick="goToStep(2)">
                                    Pilih Bus <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Bus Selection -->
                <div class="step-content d-none" id="step-2">
                    <div class="row g-4">
                        @foreach($buses as $bus)
                            <div class="col-md-4">
                                <div class="card bg-dark border-secondary h-100 shadow-sm bus-card cursor-pointer {{ old('bus_id') == $bus->id ? 'selected' : '' }}" onclick="selectBus(this, {{ $bus->id }}, '{{ $bus->bus_number }}', '{{ $bus->route_name }}')">
                                    <div class="card-body p-4 text-center">
                                        <input type="radio" name="bus_id" value="{{ $bus->id }}" class="d-none" {{ old('bus_id') == $bus->id ? 'checked' : '' }}>
                                        <div class="mb-3">
                                            <i class="fas fa-bus text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h5 class="text-info mb-1">{{ $bus->bus_number }}</h5>
                                        <p class="text-white small mb-0">{{ $bus->route_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-between mt-5">
                        <button type="button" class="btn btn-outline-light px-4" onclick="goToStep(1)">Kembali</button>
                    </div>
                </div>

                <!-- STEP 3: Seats & Details -->
                <div class="step-content d-none" id="step-3">
                    <div class="row g-4">
                        <!-- Seat Selection -->
                        <div class="col-lg-6">
                            <div class="card bg-dark border-secondary shadow-lg">
                                <div class="card-header bg-secondary text-white py-3">
                                    <h5 class="card-title mb-0" id="seat-selection-title">Pilih Kursi</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="bus-layout border border-secondary rounded p-4 bg-black mb-4">
                                        <div class="row row-cols-4 g-3" id="seat-grid">
                                            <!-- Dynamically populated via AJAX -->
                                            <div class="col-12 text-center py-5">
                                                <div class="spinner-border text-primary" role="status"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 justify-content-center small text-white-50">
                                        <div class="d-flex align-items-center"><div class="bg-success me-1" style="width:12px; height:12px; border-radius:1px;"></div> Tersedia</div>
                                        <div class="d-flex align-items-center"><div class="bg-primary me-1" style="width:12px; height:12px; border-radius:1px;"></div> Pilihan</div>
                                        <div class="d-flex align-items-center"><div class="bg-danger me-1" style="width:12px; height:12px; border-radius:1px;"></div> Terisi</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Details -->
                        <div class="col-lg-6">
                            <div class="card bg-dark border-secondary shadow-lg">
                                <div class="card-header bg-secondary text-white py-3">
                                    <h5 class="card-title mb-0">Data Anggota Keluarga</h5>
                                </div>
                                <div class="card-body p-4" id="family-forms">
                                    <!-- Dynamically generated based on total_members -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <button type="button" class="btn btn-outline-light px-4" onclick="goToStep(2)">Kembali</button>
                        <button type="submit" id="submit-btn" class="btn btn-primary px-5 py-2 fw-bold text-uppercase" disabled>Selesaikan Pendaftaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .stepper .step { width: 40px; height: 40px; border-radius: 50%; background: #333; color: #777; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid #444; }
    .stepper .step.active { background: #0d6efd; color: white; border-color: #0d6efd; box-shadow: 0 0 15px rgba(13, 110, 253, 0.5); }
    .stepper .line { width: 50px; height: 2px; background: #444; margin: 0 10px; }
    .bus-card { transition: all 0.3s ease; }
    .bus-card:hover { transform: translateY(-5px); border-color: #0d6efd; }
    .bus-card.selected { border-color: #0d6efd; background: rgba(13, 110, 253, 0.1) !important; }
    .cursor-pointer { cursor: pointer; }
    .seat-item { width: 45px; height: 45px; border: 2px solid #555; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: #aaa; }
    .seat-item.available:hover { border-color: #198754; color: white; }
    .seat-item.available { border-color: #198754; color: #198754; }
    .seat-item.selected { background: #0d6efd; border-color: #0d6efd; color: white; }
    .seat-item.occupied { background: #dc3545; border-color: #dc3545; opacity: 0.5; cursor: not-allowed; color: white; }
</style>

<script>
    let currentStep = 1;
    let selectedBusId = null;

    function goToStep(step) {
        if (step === 2) {
            const departure = document.getElementById('departure_location').value;
            if (!departure) {
                alert('Pilih lokasi keberangkatan terlebih dahulu.');
                return;
            }
        }

        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('d-none'));
        // Show current step
        document.getElementById('step-' + step).classList.remove('d-none');
        
        // Update Stepper
        document.querySelectorAll('.stepper .step').forEach((el, idx) => {
            if (idx + 1 <= step) el.classList.add('active');
            else el.classList.remove('active');
        });

        // Update description
        const desc = {
            1: 'Langkah 1: Informasi Keberangkatan & Jumlah Peserta',
            2: 'Langkah 2: Pilih Bus & Rute',
            3: 'Langkah 3: Pilih Kursi & Data Peserta'
        };
        document.getElementById('step-description').innerText = desc[step];
        currentStep = step;
        window.scrollTo(0, 0);
    }

    function selectBus(el, id, number, route) {
        selectedBusId = id;
        document.querySelectorAll('.bus-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input[type="radio"]').checked = true;

        // Populate Family Forms
        const total = document.getElementById('total_members').value;
        const container = document.getElementById('family-forms');
        
        // Only repopulate if the count changed or it's empty
        if (container.children.length != total) {
            container.innerHTML = '';
            for (let i = 0; i < total; i++) {
                container.innerHTML += `
                    <div class="mb-4 p-3 border border-secondary rounded">
                        <h6 class="text-info mb-3">Peserta #${i+1} ${i === 0 ? '(Pemilik KK)' : ''}</h6>
                        <div class="mb-3">
                            <label class="form-label text-white-50 small">Nama Lengkap</label>
                            <input type="text" name="family[${i}][name]" class="form-control bg-dark text-white border-secondary" required value="${getOldValue(`family.${i}.name`, '')}">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50 small">NIK</label>
                                <input type="text" name="family[${i}][identity_number]" class="form-control bg-dark text-white border-secondary" required maxlength="16" value="${getOldValue(`family.${i}.identity_number`, '')}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50 small">Usia</label>
                                <input type="number" name="family[${i}][age]" class="form-control bg-dark text-white border-secondary" required min="0" value="${getOldValue(`family.${i}.age`, '')}">
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        // Load Seats via AJAX
        const grid = document.getElementById('seat-grid');
        grid.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div></div>';
        
        document.getElementById('seat-selection-title').innerText = `Pilih Kursi Bus ${number} (Pilih ${total})`;

        fetch(`/registration/api/buses/${id}/seats`)
            .then(res => res.json())
            .then(data => {
                grid.innerHTML = '';
                const oldSeats = {!! json_encode(old('selected_seats', [])) !!};
                
                data.seats.forEach(seat => {
                    const isSelected = oldSeats.includes(seat.id.toString());
                    const statusClass = seat.status === 'available' ? (isSelected ? 'selected' : 'available') : 'occupied';
                    
                    grid.innerHTML += `
                        <div class="col text-center">
                            <div class="seat-item rounded small fw-bold ${statusClass}" 
                                 onclick="toggleSeat(this, ${seat.id})"
                                 data-id="${seat.id}">
                                ${seat.seat_number}
                            </div>
                            <input type="checkbox" name="selected_seats[]" value="${seat.id}" class="d-none seat-check" id="input-seat-${seat.id}" ${isSelected ? 'checked' : ''}>
                        </div>
                    `;
                });
                updateSubmitButton();
            });

        goToStep(3);
    }

    function toggleSeat(el, id) {
        if (el.classList.contains('occupied')) return;

        const max = parseInt(document.getElementById('total_members').value);
        const input = document.getElementById('input-seat-' + id);

        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            input.checked = false;
        } else {
            const currentSelected = document.querySelectorAll('.seat-item.selected').length;
            if (currentSelected >= max) {
                alert(`Anda hanya bisa memilih ${max} kursi.`);
                return;
            }
            el.classList.add('selected');
            input.checked = true;
        }

        updateSubmitButton();
    }

    function updateSubmitButton() {
        const max = parseInt(document.getElementById('total_members').value);
        const currentSelected = document.querySelectorAll('.seat-item.selected').length;
        document.getElementById('submit-btn').disabled = (currentSelected !== max);
    }

    function resetRegistration() {
        // If total members changes, we might need to reset selections
        document.querySelectorAll('.seat-item.selected').forEach(el => el.classList.remove('selected'));
        document.querySelectorAll('.seat-check').forEach(el => el.checked = false);
        updateSubmitButton();
    }

    // Helper to get old values in JS
    function getOldValue(key, fallback) {
        const oldInput = {!! json_encode(old()) !!};
        const keys = key.split('.');
        let value = oldInput;
        for (const k of keys) {
            if (value && value[k] !== undefined) value = value[k];
            else return fallback;
        }
        return value;
    }

    // Auto-resume if there are errors or old input
    document.addEventListener('DOMContentLoaded', function() {
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        const oldBusId = "{{ old('bus_id') }}";
        
        if (hasErrors || oldBusId) {
            const selectedBusCard = document.querySelector('.bus-card.selected');
            if (selectedBusCard) {
                // Trigger selection to load seats and forms
                selectedBusCard.click();
            } else if (hasErrors) {
                // If no bus selected but errors, maybe stay on step 1 or 2
                // (Already at step 1 by default, but errors are visible)
            }
        }
    });
</script>
@endsection
