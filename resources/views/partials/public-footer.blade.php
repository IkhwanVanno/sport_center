<footer class="bg-slate-900 text-light py-5 mt-auto border-top border-secondary border-opacity-25 no-print">
    <div class="container">
        <div class="row g-4 justify-content-between mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="fs-4">⚽</span>
                    <span class="fw-bold fs-5 text-white tracking-wide">SPORT CENTER</span>
                </div>
                <p class="text-secondary small pe-lg-4 mb-3">
                    Sistem pemesanan lapangan olahraga terintegrasi dan real-time. Bebas bentrok jadwal, transparan dalam harga, dan didukung metode pembayaran online maupun kasir.
                </p>
                <div class="d-flex gap-3 text-secondary">
                    <a href="#" class="text-secondary text-decoration-none hover-white"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-secondary text-decoration-none hover-white"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-secondary text-decoration-none hover-white"><i class="bi bi-whatsapp fs-5"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold mb-3 small text-uppercase">Navigasi</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 text-secondary">
                    <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Beranda</a></li>
                    <li><a href="{{ route('courts.index') }}" class="text-secondary text-decoration-none">Katalog Lapangan</a></li>
                    <li><a href="{{ route('courts.index') }}" class="text-secondary text-decoration-none">Jadwal & Tarif</a></li>
                    <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none">Login Akun</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="text-white fw-bold mb-3 small text-uppercase">Layanan</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 text-secondary">
                    <li><a href="#" class="text-secondary text-decoration-none">Futsal Standar</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Badminton BWF</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Basket Outdoor</a></li>
                    <li><a href="#" class="text-secondary text-decoration-none">Turnamen & Event</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3 small text-uppercase">Jam Operasional</h6>
                <p class="text-secondary small mb-2">
                    <i class="bi bi-clock me-2 text-emerald"></i>Senin - Minggu: 08:00 - 23:00 WIB
                </p>
                <p class="text-secondary small mb-2">
                    <i class="bi bi-geo-alt me-2 text-emerald"></i>Surabaya (MERR & Surabaya Barat)
                </p>
                <p class="text-secondary small">
                    <i class="bi bi-telephone me-2 text-emerald"></i>CS Hotline: 0812-3456-7890
                </p>
            </div>
        </div>

        <hr class="border-secondary border-opacity-25 my-4">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-secondary">
            <div>
                © {{ date('Y') }} <strong>Sport Center System</strong>. All rights reserved.
            </div>
            <div class="d-flex gap-4">
                <a href="#" class="text-secondary text-decoration-none">Syarat & Ketentuan</a>
                <a href="#" class="text-secondary text-decoration-none">Kebijakan Privasi</a>
                <a href="#" class="text-secondary text-decoration-none">Bantuan</a>
            </div>
        </div>
    </div>
</footer>
