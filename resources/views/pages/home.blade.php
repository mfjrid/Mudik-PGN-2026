@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--pgn-blue) 0%, #00a8ff 100%);
        padding: 100px 0;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 800px;
        height: 800px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        z-index: 0;
    }
    .hero-content {
        position: relative;
        z-index: 1;
    }
    .feature-icon {
        width: 60px;
        height: 60px;
        background: var(--pgn-light-blue);
        color: var(--pgn-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }
    .route-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .route-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .backdrop-blur {
        backdrop-filter: blur(15px);
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-3 fw-bold mb-4">Mudik Tenang, Hati Senang Bersama PGN</h1>
                <p class="lead mb-5 opacity-90">Daftarkan diri Anda dan keluarga dalam program Mudik Bareng PGN 2026. Perjalanan aman, nyaman, dan gratis menuju kampung halaman.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold text-primary">Daftar Sekarang</a>
                    <a href="#about" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                <div class="p-5 bg-white bg-opacity-10 rounded-4 backdrop-blur shadow-lg border border-white border-opacity-20">
                    <h3 class="fw-bold">Pendaftaran Dibuka!</h3>
                    <p class="mb-0 text-white-50 small mb-3">Sisa Kuota: 1,240 Kursi</p>
                    <hr class="border-white border-opacity-20">
                    <p class="mb-0">Segera daftarkan diri Anda sebelum kuota penuh.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-white shadow-sm border-bottom">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <h2 class="fw-bold text-primary mb-1">5,000+</h2>
                <p class="text-muted mb-0">Total Kuota Pemudik</p>
            </div>
            <div class="col-md-4 border-md-start border-md-end">
                <h2 class="fw-bold text-primary mb-1">15+</h2>
                <p class="text-muted mb-0">Kota Tujuan Utama</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold text-primary mb-1">100%</h2>
                <p class="text-muted mb-0">Gratis & Aman</p>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-5">
    <div class="container py-lg-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://placehold.co/600x400/0056b3/white?text=Mudik+PGN+2026" alt="About Mudik" class="img-fluid rounded-4 shadow">
                    <div class="position-absolute bottom-0 end-0 bg-white p-3 rounded-4 shadow-sm m-4 d-none d-md-block">
                        <span class="text-primary fw-bold">10+ Tahun</span><br>
                        <small class="text-muted">Melayani Program Mudik</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h6 class="text-primary fw-bold text-uppercase mb-3">Tentang Program</h6>
                <h2 class="fw-bold mb-4">Wujud Kepedulian PGN untuk Masyarakat</h2>
                <p class="text-muted mb-4">Program Mudik Bersama PGN adalah inisiatif tahunan sebagai bentuk tanggung jawab sosial perusahaan untuk membantu masyarakat pulang ke kampung halaman dengan selamat dan nyaman.</p>
                <div class="row g-4 text-start">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bus-front" viewBox="0 0 16 16">
                                    <path d="M5 11a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-6-1a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2H7Zm1-6c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554c0 .447.315.837.757.907C4.883 9.345 6.43 9.5 8 9.5s3.117-.155 4.243-.316a.91.91 0 0 0 .757-.907V4.723a.5.5 0 0 0-.448-.497C11.426 4.109 9.876 4 8 4Z"/>
                                    <path d="M4 10a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1h1Zm10 0a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1h1Z"/>
                                    <path d="M2 7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7Zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H4Z"/>
                                </svg>
                            </div>
                            <div class="ms-3">
                                <h5 class="fw-bold mb-1">Bus VIP</h5>
                                <p class="small text-muted mb-0">Armada bus terbaru dengan fasilitas lengkap.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start">
                            <div class="feature-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16">
                                    <path d="M8 14.933a.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067v13.866zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                                    <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                            </div>
                            <div class="ms-3">
                                <h5 class="fw-bold mb-1">Asuransi</h5>
                                <p class="small text-muted mb-0">Perlindungan asuransi selama perjalanan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Routes Section -->
<section id="routes" class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Rute Keberangkatan</h2>
            <p class="text-muted">Pilih rute sesuai dengan tujuan kampung halaman Anda.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card route-card p-4 h-100">
                    <h5 class="fw-bold text-primary mb-3">Jawa Tengah</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Semarang</li>
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Solo</li>
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Purwokerto</li>
                        <li class="text-muted"><span class="text-success me-2">√</span> Jakarta - Magelang</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card route-card p-4 h-100">
                    <h5 class="fw-bold text-primary mb-3">Jawa Timur</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Surabaya</li>
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Malang</li>
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Madiun</li>
                        <li class="text-muted"><span class="text-success me-2">√</span> Jakarta - Kediri</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card route-card p-4 h-100">
                    <h5 class="fw-bold text-primary mb-3">Sumatera</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Lampung</li>
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Palembang</li>
                        <li class="mb-2 text-muted"><span class="text-success me-2">√</span> Jakarta - Bandar Lampung</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container py-5 text-center">
        <div class="bg-primary text-white p-5 rounded-4 shadow-lg position-relative overflow-hidden">
            <div style="z-index: 1; position: relative;">
                <h2 class="fw-bold mb-3">Tunggu Apa Lagi?</h2>
                <p class="lead mb-4 opacity-90 text-white">Jangan sampai kehabisan kuota. Segera daftarkan diri Anda dan keluarga!</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold text-primary">Daftar Sekarang</a>
            </div>
            <!-- Decorative circle -->
            <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        </div>
    </div>
</section>
@endsection
