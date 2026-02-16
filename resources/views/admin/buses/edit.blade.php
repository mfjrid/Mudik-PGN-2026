@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.buses.index') }}" class="btn btn-outline-light me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="h3 mb-0 text-white">Edit Bus: {{ $bus->bus_number }}</h1>
            </div>

            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body p-4">
                    <form action="{{ route('admin.buses.update', $bus) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="bus_number" class="form-label text-white-50">Nomor Bus</label>
                            <input type="text" name="bus_number" id="bus_number" class="form-control bg-dark text-white border-secondary @error('bus_number') is-invalid @enderror" value="{{ old('bus_number', $bus->bus_number) }}" required>
                            @error('bus_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="route_name" class="form-label text-white-50">Nama Rute</label>
                            <input type="text" name="route_name" id="route_name" class="form-control bg-dark text-white border-secondary @error('route_name') is-invalid @enderror" value="{{ old('route_name', $bus->route_name) }}" required>
                            @error('route_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="capacity" class="form-label text-white-50">Kapasitas Kursi</label>
                            <input type="number" name="capacity" id="capacity" class="form-control bg-dark text-white border-secondary @error('capacity') is-invalid @enderror" value="{{ old('capacity', $bus->capacity) }}" required min="1">
                            <div class="form-text text-warning small mt-1"><i class="fas fa-exclamation-triangle me-1"></i> Mengubah kapasitas tidak akan otomatis menambah/menghapus kursi yang sudah ada.</div>
                            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 text-uppercase fw-bold">Perbarui Bus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
