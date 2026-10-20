@extends('layouts.public')

@section('title', 'Sewa Lapangan Futsal, Badminton & Basket Online')

@section('content')
<div class="py-4 d-flex flex-column gap-5">

    <!-- Hero Section -->
    <section class="container">
        <div class="bg-slate-900 rounded-5 p-4 p-sm-5 text-white position-relative overflow-hidden shadow-lg" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <div class="position-relative z-1 col-lg-8 py-3 py-lg-4">
                <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="font-size: 0.75rem;">
                    <i class="bi bi-lightning-charge-fill me-1"></i>Sistem Reservasi Online Real-Time
                </span>
                
                <h1 class="display-5 fw-extrabold text-white tracking-tight lh-tight mb-3">
                    Main Olahraga Tanpa Ribet Antre Slot
                </h1>
                
                <p class="text-slate-300 fs-6 lh-base mb-4 text-secondary col-md-10">
                    Cek jadwal ketersediaan lapangan futsal, badminton, basket, dan tenis secara instan. Pesan per jam dan bayar otomatis dari mana saja dengan jaminan <em>Zero Double Booking</em>.
                </p>

                <div class="d-flex flex-wrap gap-3 align-items-center pt-2">
                    <a href="{{ route('courts.index') }}" class="btn btn-emerald btn-lg px-4 py-3 rounded-4 shadow-sm fw-bold fs-6 d-flex align-items-center gap-2">
                        <i class="bi bi-search"></i>
                        <span>Cari Lapangan Sekarang</span>
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-4 fw-semibold fs-6">
                            Daftar Akun Gratis
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-4 fw-semibold fs-6">
                            Dashboard Saya &rarr;
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Background Visual Emoji -->
            <div class="position-absolute end-0 bottom-0 text-white opacity-10 user-select-none pe-none d-none d-md-block" style="font-size: 16rem; line-height: 1; transform: translate(15%, 15%);">
                ⚽
            </div>
        </div>
    </section>

    <!-- Quick Stats Bar -->
    <section class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="card card-custom p-4 text-center h-100">
                    <div class="display-6 fw-bold text-indigo mb-1">10+</div>
                    <div class="text-muted small fw-medium">Cabang Fasilitas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom p-4 text-center h-100">
                    <div class="display-6 fw-bold text-emerald mb-1">40+</div>
                    <div class="text-muted small fw-medium">Lapangan Fisik</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom p-4 text-center h-100">
                    <div class="display-6 fw-bold text-warning mb-1">100%</div>
                    <div class="text-muted small fw-medium">Bebas Double Booking</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-custom p-4 text-center h-100">
                    <div class="display-6 fw-bold text-dark mb-1">15rb+</div>
                    <div class="text-muted small fw-medium">Pengguna Terdaftar</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Courts Section -->
    <section id="katalog" class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="badge bg-indigo-subtle text-indigo px-3 py-1.5 rounded-pill fw-bold text-uppercase small mb-2">Pilihan Populer</span>
                <h2 class="h3 fw-bold text-dark mb-1">Lapangan Pilihan Olahraga</h2>
                <p class="text-muted small mb-0">Pilih lapangan favorit dan periksa slot waktu yang tersedia secara langsung</p>
            </div>
            <a href="{{ route('courts.index') }}" class="btn btn-link text-indigo text-decoration-none fw-bold small d-flex align-items-center gap-1">
                <span>Lihat Semua Lapangan</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($courts ?? [] as $court)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-court-card :court="$court" />
                </div>
            @empty
                <div class="col-12">
                    <div class="card card-custom text-center p-5">
                        <i class="bi bi-emoji-neutral fs-1 text-muted mb-2"></i>
                        <p class="text-muted fw-semibold mb-0">Belum ada data lapangan yang tersedia saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Cara Sewa 4 Langkah -->
    <section id="cara-sewa" class="container py-3">
        <div class="card card-custom p-4 p-md-5 border-0 shadow-sm" style="background-color: #f1f5f9;">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="badge bg-emerald-subtle text-emerald px-3 py-1.5 rounded-pill fw-bold text-uppercase small mb-2">Mudah & Cepat</span>
                <h3 class="fw-bold text-dark mb-2">Cara Sewa Lapangan di Sport Center</h3>
                <p class="text-muted small mb-0">Pesan slot lapangan hanya dalam 4 tahapan ringkas tanpa harus menelepon admin</p>
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-3">
                    <div class="card card-custom p-4 text-center h-100 bg-white">
                        <div class="rounded-circle bg-emerald-subtle text-emerald fw-bold mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            1
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Pilih Lapangan</h6>
                        <p class="text-muted small mb-0">Tentukan cabang terdekat, jenis olahraga, dan fasilitas yang diinginkan.</p>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card-custom p-4 text-center h-100 bg-white">
                        <div class="rounded-circle bg-indigo-subtle text-indigo fw-bold mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            2
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Pilih Jam & Slot</h6>
                        <p class="text-muted small mb-0">Pilih tanggal dan slot jam sewa yang berstatus hijau (tersedia).</p>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card-custom p-4 text-center h-100 bg-white">
                        <div class="rounded-circle bg-warning-subtle text-warning fw-bold mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            3
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Bayar Instan</h6>
                        <p class="text-muted small mb-0">Selesaikan tagihan via QRIS otomatis atau transfer bank dalam 15 menit.</p>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card card-custom p-4 text-center h-100 bg-white">
                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.25rem;">
                            4
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Check-In QR</h6>
                        <p class="text-muted small mb-0">Bawa e-ticket smartphone ke lokasi dan scan di kasir sebelum bermain.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="container py-2">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Pertanyaan yang Sering Diajukan (FAQ)</h3>
            <p class="text-muted small">Informasi umum mengenai pemesanan dan kebijakan pembatalan</p>
        </div>

        <div class="accordion card-custom overflow-hidden border-0 shadow-sm" id="accordionFaq">
            <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header" id="faqOne">
                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Bagaimana sistem mencegah terjadinya double booking?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-secondary small">
                        Sport Center menggunakan teknologi <em>Pessimistic Locking</em> di tingkat basis data. Saat seorang pelanggan menekan tombol booking, slot tersebut dikunci seketika dalam transaksi database sehingga pengguna lain tidak dapat memesan jam yang sama.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header" id="faqTwo">
                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Berapa batas waktu untuk menyelesaikan pembayaran?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-secondary small">
                        Batas waktu pembayaran adalah 15 menit sejak kode booking terbentuk. Jika dalam 15 menit pembayaran belum diselesaikan atau bukti transfer belum diunggah, sistem otomatis membatalkan pemesanan dan mengembalikan slot tersebut ke status tersedia.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0">
                <h2 class="accordion-header" id="faqThree">
                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Apakah saya bisa memesan langsung di lokasi tanpa aplikasi?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-secondary small">
                        Bisa! Operator kasir di setiap cabang menyediakan modul <em>Walk-In Booking</em> untuk melayani pembayaran langsung secara tunai untuk slot jam yang masih tersedia hari itu.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Guest Banner CTA -->
    @guest
    <section class="container pb-4">
        <div class="bg-indigo-subtle border border-indigo-subtle rounded-5 p-4 p-md-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
            <div class="text-center text-md-start">
                <h4 class="fw-bold text-dark mb-1">Belum Punya Akun Sport Center?</h4>
                <p class="text-muted small mb-0">Daftarkan akun sekarang untuk kemudahan reservasi slot, cetak e-ticket QR, dan melihat riwayat pemesanan Anda.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('register') }}" class="btn btn-indigo px-4 py-2.5 rounded-3 fw-bold small text-nowrap">
                    Buat Akun Baru
                </a>
                <a href="{{ route('login') }}" class="btn btn-white bg-white border px-4 py-2.5 rounded-3 fw-semibold small text-nowrap">
                    Masuk
                </a>
            </div>
        </div>
    </section>
    @endguest

</div>
@endsection