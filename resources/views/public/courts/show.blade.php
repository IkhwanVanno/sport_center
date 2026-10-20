@extends('layouts.public')

@section('title', $court->name . ' - Detail & Reservasi Lapangan')

@php
    $sportSlug = strtolower($court->sport->slug ?? 'sport');
    $imageMap = [
        'futsal' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1000&q=80',
        'badminton' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=1000&q=80',
        'basket' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=1000&q=80',
    ];
    $imageUrl = $court->photo_url ?? ($imageMap[$sportSlug] ?? 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1000&q=80');
@endphp

@section('content')
<div class="container py-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('courts.index') }}" class="text-decoration-none text-muted">Katalog</a></li>
            <li class="breadcrumb-item active text-emerald fw-semibold" aria-current="page">{{ $court->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Detail Column -->
        <div class="col-lg-8">
            <!-- Court Banner Image -->
            <div class="card card-custom overflow-hidden mb-4 border-0 shadow-sm">
                <div class="position-relative" style="height: 360px; background-color: #0f172a;">
                    <img src="{{ $imageUrl }}" alt="{{ $court->name }}" class="w-100 h-100 object-fit-cover">
                    <span class="position-absolute top-0 end-0 m-3 badge bg-emerald text-white px-3 py-2 rounded-pill fw-bold text-uppercase fs-7 shadow">
                        {{ $court->sport->name ?? 'Olahraga' }}
                    </span>
                    <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);">
                        <span class="badge bg-light text-dark px-2.5 py-1 mb-2 fw-semibold">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $court->branch->name ?? 'Cabang Sport Center' }}
                        </span>
                        <h2 class="display-6 fw-bold mb-0 text-white">{{ $court->name }}</h2>
                    </div>
                </div>
            </div>

            <!-- Court Info Card -->
            <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3">Tentang Lapangan Ini</h5>
                <p class="text-secondary lh-base mb-4">
                    {{ $court->description ?? 'Lapangan olahraga dengan spesifikasi dan standar turnamen, didukung sistem pencahayaan modern serta fasilitas pendukung yang lengkap untuk kenyamanan bermain Anda.' }}
                </p>

                <!-- Facilities -->
                <h6 class="fw-bold text-dark mb-2">Fasilitas Termasuk:</h6>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @forelse($court->facilities as $facility)
                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill d-flex align-items-center gap-1.5 fs-7">
                            <i class="bi bi-check-circle-fill text-emerald"></i>
                            <span>{{ $facility->name }}</span>
                        </span>
                    @empty
                        <span class="text-muted small">Pencahayaan LED, Ruang Ganti, Parkir.</span>
                    @endforelse
                </div>

                <!-- Price Rules / Special Hours -->
                @if($court->priceRules && $court->priceRules->isNotEmpty())
                    <h6 class="fw-bold text-dark mb-2">Ketentuan Jam Sibuk (Peak Hours):</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-center small">
                            <thead class="table-light">
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Tarif / Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                @endphp
                                @foreach($court->priceRules as $rule)
                                    <tr>
                                        <td>{{ $dayNames[$rule->day_of_week] ?? $rule->day_of_week }}</td>
                                        <td>{{ substr($rule->start_time, 0, 5) }} - {{ substr($rule->end_time, 0, 5) }}</td>
                                        <td class="fw-bold text-emerald">Rp {{ number_format($rule->price_per_hour, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Interactive Slot Schedule Section -->
            <div class="card card-custom p-4 border-0 shadow-sm" id="booking-section">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 border-bottom pb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Pilih Tanggal & Jam Main</h5>
                        <p class="text-muted small mb-0">Klik slot jam yang berstatus hijau untuk menambahkannya ke pemesanan Anda</p>
                    </div>

                    <!-- Date Picker Input -->
                    <div class="d-flex align-items-center gap-2">
                        <label for="booking_date" class="small fw-semibold text-secondary text-nowrap">Tanggal:</label>
                        <input type="date" 
                               id="booking_date" 
                               class="form-control form-control-sm border-emerald" 
                               value="{{ date('Y-m-d') }}" 
                               min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Availability Slots Container -->
                <div id="slots-loading" class="text-center py-5">
                    <div class="spinner-border text-emerald" role="status">
                        <span class="visually-hidden">Memuat jadwal...</span>
                    </div>
                    <p class="text-muted small mt-2">Memeriksa ketersediaan slot terkini...</p>
                </div>

                <div id="slots-container" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-2 d-none">
                    <!-- Dynamic slot items will be injected here via JavaScript -->
                </div>

                <div id="slots-empty" class="text-center py-4 d-none text-muted small">
                    <i class="bi bi-calendar-x fs-2 d-block mb-1"></i>
                    Tidak ada slot operasional untuk tanggal ini.
                </div>

                <!-- Slot Legend -->
                <div class="d-flex flex-wrap gap-3 align-items-center mt-4 pt-3 border-top small text-muted">
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="badge bg-outline-primary border border-primary px-2 py-1 text-primary">Tersedia</span>
                        <span>Bisa dipilih</span>
                    </div>
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="badge bg-indigo text-white px-2 py-1">Dipilih</span>
                        <span>Slot pilihan Anda</span>
                    </div>
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="badge bg-warning text-dark px-2 py-1">Diproses</span>
                        <span>Menunggu bayar (15m)</span>
                    </div>
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="badge bg-secondary text-white px-2 py-1">Terisi</span>
                        <span>Sudah dibooking</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Sidebar / Checkout Card -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 border-0 shadow-sm sticky-top" style="top: 80px;">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">Ringkasan Reservasi</h5>

                <!-- Price summary list -->
                <div class="mb-3 small">
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Tarif Standar:</span>
                        <strong class="text-dark">Rp {{ number_format($court->base_price_per_hour, 0, ',', '.') }} / jam</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Tanggal Sewa:</span>
                        <strong class="text-dark" id="summary-date">{{ date('d M Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Jumlah Slot Terpilih:</span>
                        <strong class="text-dark"><span id="summary-slots-count">0</span> jam</strong>
                    </div>
                </div>

                <!-- Selected Slots Badges List -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Slot Jam yang Dipilih:</label>
                    <div id="summary-selected-slots" class="d-flex flex-wrap gap-1 min-h-30">
                        <span class="text-muted small fst-italic">Belum ada slot yang dipilih.</span>
                    </div>
                </div>

                <div class="border-top pt-3 mb-4">
                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                        <span class="text-secondary small fw-semibold">Estimasi Total Biaya:</span>
                        <span class="fs-4 fw-extrabold text-emerald fw-bold" id="summary-total-price">Rp 0</span>
                    </div>
                    <div class="text-muted small" style="font-size: 0.72rem;">*Termasuk penyesuaian tarif dinamis / jam sibuk.</div>
                </div>

                <!-- Form Booking Submission -->
                @auth
                    @if(Auth::user()->isCustomer())
                        <form id="booking-form" method="POST" action="{{ route('user.bookings.store') }}">
                            @csrf
                            <input type="hidden" name="court_id" value="{{ $court->id }}">
                            <input type="hidden" name="date" id="form-date" value="{{ date('Y-m-d') }}">
                            <div id="form-slots-inputs"></div>

                            <div class="mb-3">
                                <label for="notes" class="form-label small fw-semibold text-secondary">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="2" class="form-control form-control-sm" placeholder="Contoh: Tolong siapkan rompi futsal..."></textarea>
                            </div>

                            <button type="submit" id="btn-submit-booking" class="btn btn-emerald w-100 py-3 rounded-3 fw-bold shadow-sm" disabled>
                                Lanjutkan Booking & Pembayaran
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info small mb-0">
                            Anda masuk sebagai <strong>{{ strtoupper(Auth::user()->role) }}</strong>. Gunakan akun Pelanggan untuk melakukan reservasi online atau gunakan modul Operator.
                        </div>
                    @endif
                @else
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-indigo py-3 rounded-3 fw-bold text-center shadow-sm">
                            Masuk untuk Melanjutkan Booking
                        </a>
                        <p class="text-center text-muted small mb-0 mt-1">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-emerald fw-semibold text-decoration-none">Daftar sekarang</a>
                        </p>
                    </div>
                @endauth
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const courtId = {{ $court->id }};
    const dateInput = document.getElementById('booking_date');
    const formDateInput = document.getElementById('form-date');
    const summaryDate = document.getElementById('summary-date');
    const slotsLoading = document.getElementById('slots-loading');
    const slotsContainer = document.getElementById('slots-container');
    const slotsEmpty = document.getElementById('slots-empty');
    const summaryCount = document.getElementById('summary-slots-count');
    const summaryList = document.getElementById('summary-selected-slots');
    const summaryTotal = document.getElementById('summary-total-price');
    const formSlotsInputs = document.getElementById('form-slots-inputs');
    const btnSubmit = document.getElementById('btn-submit-booking');

    let selectedSlots = new Map(); // key: startTime, val: {startTime, endTime, price}

    function formatRupiah(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    function fetchAvailability(date) {
        slotsLoading.classList.remove('d-none');
        slotsContainer.classList.add('d-none');
        slotsEmpty.classList.add('d-none');
        selectedSlots.clear();
        updateSummary();

        fetch(`/api/courts/${courtId}/availability?date=${date}`)
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat slot');
                return res.json();
            })
            .then(data => {
                slotsLoading.classList.add('d-none');
                renderSlots(data.slots || []);
            })
            .catch(err => {
                slotsLoading.classList.add('d-none');
                slotsEmpty.classList.remove('d-none');
                slotsEmpty.innerText = 'Terjadi kesalahan saat memeriksa jadwal. Silakan coba kembali.';
            });
    }

    function renderSlots(slots) {
        slotsContainer.innerHTML = '';
        if (slots.length === 0) {
            slotsEmpty.classList.remove('d-none');
            return;
        }

        slots.forEach(slot => {
            const col = document.createElement('div');
            col.className = 'col';

            const isAvailable = (slot.status === 'available');
            const slotId = 'slot_' + slot.start_time.replace(':', '');

            let btnClass = 'btn-outline-primary border-primary-subtle';
            let statusText = formatRupiah(slot.price_per_hour);
            let disabledAttr = isAvailable ? '' : 'disabled';
            let cursorStyle = isAvailable ? 'cursor: pointer;' : 'cursor: not-allowed;';

            if (slot.status === 'pending') {
                btnClass = 'btn-light border-warning text-warning-emphasis opacity-75';
                statusText = '<span class="badge bg-warning text-dark">Diproses</span>';
            } else if (slot.status === 'booked') {
                btnClass = 'btn-light border-secondary text-muted opacity-50';
                statusText = '<span class="badge bg-secondary">Terisi</span>';
            }

            col.innerHTML = `
                <div class="slot-item text-center">
                    <input type="checkbox" 
                           class="btn-check slot-check-input" 
                           id="${slotId}" 
                           value="${slot.start_time}" 
                           data-start="${slot.start_time}"
                           data-end="${slot.end_time}"
                           data-price="${slot.price_per_hour}"
                           ${disabledAttr}>
                    <label class="btn w-100 p-2 rounded-3 border transition-all ${btnClass}" for="${slotId}" style="${cursorStyle}">
                        <div class="fw-bold fs-6">${slot.start_time}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">s/d ${slot.end_time}</div>
                        <div class="mt-1 pt-1 border-top" style="font-size: 0.75rem;">
                            ${statusText}
                        </div>
                    </label>
                </div>
            `;

            slotsContainer.appendChild(col);
        });

        slotsContainer.classList.remove('d-none');

        // Add change event listener for checkboxes
        document.querySelectorAll('.slot-check-input').forEach(input => {
            input.addEventListener('change', function () {
                const start = this.dataset.start;
                const end = this.dataset.end;
                const price = parseFloat(this.dataset.price);

                if (this.checked) {
                    selectedSlots.set(start, { start, end, price });
                } else {
                    selectedSlots.delete(start);
                }

                updateSummary();
            });
        });
    }

    function updateSummary() {
        const count = selectedSlots.size;
        summaryCount.innerText = count;

        if (count === 0) {
            summaryList.innerHTML = '<span class="text-muted small fst-italic">Belum ada slot yang dipilih.</span>';
            summaryTotal.innerText = 'Rp 0';
            if (formSlotsInputs) formSlotsInputs.innerHTML = '';
            if (btnSubmit) btnSubmit.disabled = true;
            return;
        }

        let total = 0;
        let listHtml = '';
        let formInputsHtml = '';

        // Sort keys by time
        const sortedKeys = Array.from(selectedSlots.keys()).sort();

        sortedKeys.forEach(key => {
            const item = selectedSlots.get(key);
            total += item.price;
            listHtml += `<span class="badge bg-indigo px-2.5 py-1.5 rounded-pill small">${item.start} - ${item.end}</span> `;
            formInputsHtml += `<input type="hidden" name="slots[]" value="${item.start}">`;
        });

        summaryList.innerHTML = listHtml;
        summaryTotal.innerText = formatRupiah(total);
        if (formSlotsInputs) formSlotsInputs.innerHTML = formInputsHtml;
        if (btnSubmit) btnSubmit.disabled = false;
    }

    // Date change listener
    dateInput.addEventListener('change', function () {
        const val = this.value;
        if (formDateInput) formDateInput.value = val;
        
        const d = new Date(val);
        summaryDate.innerText = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        
        fetchAvailability(val);
    });

    // Initial load
    fetchAvailability(dateInput.value);
});
</script>
@endpush
