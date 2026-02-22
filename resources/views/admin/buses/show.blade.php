@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.buses.index') }}" class="btn btn-outline-light me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="h3 mb-0 text-white">Detail Bus: {{ $bus->bus_number }}</h1>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card bg-dark border-secondary shadow-lg mb-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="card-title mb-0">Informasi Bus</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-white-50 small d-block">Nomor Bus</label>
                        <span class="text-info fs-5">{{ $bus->bus_number }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-white-50 small d-block">Rute</label>
                        <span class="text-white fs-5">{{ $bus->route_name }}</span>
                    </div>
                    <div class="mb-0">
                        <label class="text-white-50 small d-block">Tipe Layout</label>
                        <span class="text-info fs-5">{{ $bus->layout_type == '2-2' ? 'Standard (2-2)' : 'Executive (2-1)' }}</span>
                    </div>
                </div>
            </div>

            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="card-title mb-0">Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50">Tersedia</span>
                        <span class="badge bg-success">{{ $bus->seats->where('status', 'available')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-white-50">Dipesan (Pending)</span>
                        <span class="badge bg-warning text-dark">{{ $bus->seats->where('status', 'reserved')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-white-50">Terisi</span>
                        <span class="badge bg-danger">{{ $bus->seats->where('status', 'occupied')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="card-title mb-0">Layout Kursi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="bus-layout border border-secondary rounded p-4 bg-black">
                        <div class="text-center text-white-50 small mb-4 border-bottom border-secondary pb-2">
                            <i class="fas fa-steering-wheel me-2"></i> DEPAN / SUPIR
                        </div>
                        <div class="seat-grid-container" style="display: grid; grid-template-columns: repeat({{ $bus->seats->max('column') }}, 45px); gap: 10px; justify-content: center;">
                            @foreach($bus->seats as $seat)
                                <div class="text-center" style="grid-row: {{ $seat->row }}; grid-column: {{ $seat->column }};">
                                    <div class="seat-item p-2 rounded {{ $seat->status == 'available' ? 'bg-success' : ($seat->status == 'reserved' ? 'bg-warning text-dark' : 'bg-danger') }}" 
                                         style="width: 45px; height: 45px; cursor: default; display: flex; align-items: center; justify-content: center;"
                                         title="Kursi {{ $seat->seat_number }} ({{ $seat->status }})">
                                        <small class="fw-bold">{{ $seat->seat_number }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-3 justify-content-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-success me-2" style="width: 20px; height: 20px; border-radius: 4px;"></div>
                            <small class="text-white-50">Tersedia</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning me-2" style="width: 20px; height: 20px; border-radius: 4px;"></div>
                            <small class="text-white-50">Dipesan</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-danger me-2" style="width: 20px; height: 20px; border-radius: 4px;"></div>
                            <small class="text-white-50">Terisi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
