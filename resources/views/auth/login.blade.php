@extends('layouts.app')

@section('content')
<style>
    .hp-field {
        display: none !important;
        visibility: hidden;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-5 auth-card">
        <div class="card shadow-sm">
            <div class="card-header bg-white text-center py-3">
                <h4 class="mb-0">Login Mudik PGN 2026</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="hp-field">
                        <label>Leave this field blank</label>
                        <input type="text" name="my_hp_field" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label for="login" class="form-label">Email</label>
                        <input type="email" name="login" id="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required autofocus>
                        @error('login') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    </div>

                    <div class="text-center mt-3">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai Penumpang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
