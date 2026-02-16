<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mudik PGN 2026' }}</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Outfit:wght@700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --pgn-blue: #0056b3;
            --pgn-light-blue: #e7f1ff;
            --pgn-orange: #ff7b00;
        }
        body { 
            background-color: #fcfcfc; 
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        h1, h2, h3, .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }
        .navbar {
            background: rgba(0, 86, 179, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn-primary {
            background-color: var(--pgn-blue);
            border-color: var(--pgn-blue);
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #004494;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.2);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="https://www.pgn.co.id/img/logo.png" alt="PGN Logo" height="40" class="me-2 filter-white" onerror="this.src='https://placehold.co/100x40?text=PGN'">
            Mudik PGN 2026
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="#about">Tentang</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#routes">Rute</a></li>
                @guest
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light px-4" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary px-4" href="{{ route('register') }}">Daftar Sekarang</a>
                    </li>
                @else
                    @role('superadmin|admin-kc')
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.buses.index') }}">Kelola Bus</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.registrations.index') }}">Verifikasi</a></li>
                    @endrole

                    @role('passenger')
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('passenger.registration.step1') }}">Pendaftaran</a></li>
                    @endrole

                    @role('check-in-officer')
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('officer.scan') }}">Check-In</a></li>
                    @endrole

                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-white border-0 bg-transparent">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; 2026 Perusahaan Gas Negara (PGN). Semua Hak Dilindungi.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
