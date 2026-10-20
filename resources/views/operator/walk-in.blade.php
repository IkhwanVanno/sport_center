@extends('layouts.operator')

@section('title', 'Input Walk-In Booking (Tunai)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-extrabold text-dark mb-1">Pemesanan Walk-In (Kasir)</h1>
                <p class="text-muted small mb-0">Input reservasi langsung di lokasi cabang dengan pembayaran tunai di kasir.</p>
            </div>
            <a href="{{ route('operator.checkin.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Check-In
            </a>
        </div>

        <form method="POST" action="{{ route('operator.walkin.store') }}" id="walkin-form">
            @csrf

            <div class="row g-4">
                <!-- Left: Booking Form -->
                <div class="col-lg-7">
                    <div class="card card-custom p-4 border-0 shadow-sm mb-4">
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">1. Pilih Lapangan & Tanggal</h6>

                        <!-- Court Selection -->
                        <div class="mb-3">
                            <label for="court_id" class="form-label small fw-semibold text-secondary">Pilih Lapangan</label>
                            <select name="court_id" id="court_id" class="form-select @error('court_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Lapangan --</option>
                                @foreach($courts as $court)
                                    <option value="{{ $court->id }}" 
                                            data-price="{{ $court->base_price_per_hour }}"
                                            {{ old('court_id', $courts->first()?->id) == $court->id ? 'selected' : '' }}>
                                        {{ $court->name }} ({{ $court->sport->name ?? 'Olahraga' }}) — Rp {{ number_format($court->base_price_per_hour, 0, ',', '.') }}/jam
                                    </option>
                                @endforeach
                            </select>
                            @error('court_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date Selection -->
                        <div class="mb-4">
                            <label for="booking_date" class="form-label small fw-semibold text-secondary">Tanggal Sewa</label>
                            <input type="date" 
                                   id="booking_date" 
                                   name="date" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">2. Pilih Slot Jam</h6>
                        
                        <div id="slots-loading" class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-emerald" role="status"></div>
                            <span class="small text-muted ms-2">Memeriksa ketersediaan slot...</span>
                        </div>

                        <div id="slots-container" class="row row-cols-3 row-cols-sm-4 g-2 d-none mb-3">
                            <!-- Injected dynamically via JS -->
                        </div>

                        <div id="slots-empty" class="text-center py-3 text-muted small d-none">
                            Silakan pilih lapangan dan tanggal untuk melihat slot yang tersedia.
                        </div>

                        <div id="form-slots-inputs"></div>
                        @error('slots')
                            <div class="text-danger small fw-semibold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card card-custom p-4 border-0 shadow-sm">
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">3. Data Pelanggan Walk-In</h6>

                        <div class="mb-3">
                            <label for="customer_name" class="form-label small fw-semibold text-secondary">Nama Lengkap Pemesan <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="customer_name" 
                                   name="customer_name" 
                                   value="{{ old('customer_name') }}" 
                                   class="form-control @error('customer_name') is-invalid @enderror" 
                                   placeholder="Contoh: Budi Santoso" 
                                   required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="customer_phone" class="form-label small fw-semibold text-secondary">Nomor WhatsApp / HP</label>
                            <input type="text" 
                                   id="customer_phone" 
                                   name="customer_phone" 
                                   value="{{ old('customer_phone') }}" 
                                   class="form-control @error('customer_phone') is-invalid @enderror" 
                                   placeholder="Contoh: 081234567890">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="notes" class="form-label small fw-semibold text-secondary">Catatan (Opsional)</label>
                            <input type="text" 
                                   id="notes" 
                                   name="notes" 
                                   value="{{ old('notes') }}" 
                                   class="form-control" 
                                   placeholder="Contoh: Pinjam rompi 2 tim">
                        </div>
                    </div>
                </div>

                <!-- Right: Summary & Action -->
                <div class="col-lg-5">
                    <div class="card card-custom p-4 border-0 shadow-sm sticky-top" style="top: 80px;">
                        <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">Ringkasan Walk-In</h5>

                        <div class="mb-3 small">
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Metode Bayar:</span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">TUNAI (KASIR)</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Petugas Kasir:</span>
                                <strong class="text-dark">{{ Auth::user()->name }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted">
                                <span>Jumlah Slot:</span>
                                <strong class="text-dark"><span id="summary-count">0</span> Jam</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Slot Terpilih:</label>
                            <div id="summary-slots-list" class="d-flex flex-wrap gap-1">
                                <span class="text-muted small fst-italic">Belum ada slot dipilih.</span>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-baseline mb-3">
                            <span class="text-secondary small fw-semibold">Total Tagihan Tunai:</span>
                            <span class="fs-4 fw-extrabold text-emerald fw-bold" id="summary-total">Rp 0</span>
                        </div>

                        <div class="alert alert-warning small py-2 px-3 mb-3" style="font-size: 0.75rem;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Pastikan uang tunai sudah diterima dari pelanggan sebelum menekan tombol simpan.
                        </div>

                        <button type="submit" id="btn-submit-walkin" class="btn btn-emerald w-100 py-3 rounded-3 fw-bold shadow-sm" disabled>
                            Simpan & Cetak Tiket
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const courtSelect = document.getElementById('court_id');
    const dateInput = document.getElementById('booking_date');
    const slotsLoading = document.getElementById('slots-loading');
    const slotsContainer = document.getElementById('slots-container');
    const slotsEmpty = document.getElementById('slots-empty');
    const summaryCount = document.getElementById('summary-count');
    const summaryList = document.getElementById('summary-slots-list');
    const summaryTotal = document.getElementById('summary-total');
    const formSlotsInputs = document.getElementById('form-slots-inputs');
    const btnSubmit = document.getElementById('btn-submit-walkin');

    let selectedSlots = new Map();

    function formatRupiah(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    function fetchAvailability() {
        const courtId = courtSelect.value;
        const date = dateInput.value;

        if (!courtId || !date) {
            slotsLoading.classList.add('d-none');
            slotsContainer.classList.add('d-none');
            slotsEmpty.classList.remove('d-none');
            return;
        }

        slotsLoading.classList.remove('d-none');
        slotsContainer.classList.add('d-none');
        slotsEmpty.classList.add('d-none');
        selectedSlots.clear();
        updateSummary();

        fetch(`/api/courts/${courtId}/availability?date=${date}`)
            .then(res => res.json())
            .then(data => {
                slotsLoading.classList.add('d-none');
                renderSlots(data.slots || []);
            })
            .catch(() => {
                slotsLoading.classList.add('d-none');
                slotsEmpty.classList.remove('d-none');
                slotsEmpty.innerText = 'Gagal memuat jadwal slot.';
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
            const slotId = 'walkin_slot_' + slot.start_time.replace(':', '');

            let btnClass = 'btn-outline-primary border-primary-subtle';
            let disabledAttr = isAvailable ? '' : 'disabled';
            let labelText = isAvailable ? formatRupiah(slot.price_per_hour) : '<span class="badge bg-secondary">Terisi</span>';

            if (slot.status === 'pending') {
                btnClass = 'btn-light border-warning text-warning-emphasis opacity-75';
                labelText = '<span class="badge bg-warning text-dark">Pending</span>';
            }

            col.innerHTML = `
                <input type="checkbox" 
                       class="btn-check walkin-slot-check" 
                       id="${slotId}" 
                       value="${slot.start_time}" 
                       data-start="${slot.start_time}"
                       data-end="${slot.end_time}"
                       data-price="${slot.price_per_hour}"
                       ${disabledAttr}>
                <label class="btn w-100 p-1.5 rounded-2 border small ${btnClass}" for="${slotId}" style="cursor: ${isAvailable ? 'pointer' : 'not-allowed'};">
                    <div class="fw-bold">${slot.start_time}</div>
                    <div style="font-size: 0.68rem;">${labelText}</div>
                </label>
            `;

            slotsContainer.appendChild(col);
        });

        slotsContainer.classList.remove('d-none');

        document.querySelectorAll('.walkin-slot-check').forEach(input => {
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
            summaryList.innerHTML = '<span class="text-muted small fst-italic">Belum ada slot dipilih.</span>';
            summaryTotal.innerText = 'Rp 0';
            formSlotsInputs.innerHTML = '';
            btnSubmit.disabled = true;
            return;
        }

        let total = 0;
        let listHtml = '';
        let formInputsHtml = '';

        const sortedKeys = Array.from(selectedSlots.keys()).sort();
        sortedKeys.forEach(key => {
            const item = selectedSlots.get(key);
            total += item.price;
            listHtml += `<span class="badge bg-indigo px-2 py-1 small">${item.start}-${item.end}</span> `;
            formInputsHtml += `<input type="hidden" name="slots[]" value="${item.start}">`;
        });

        summaryList.innerHTML = listHtml;
        summaryTotal.innerText = formatRupiah(total);
        formSlotsInputs.innerHTML = formInputsHtml;
        btnSubmit.disabled = false;
    }

    courtSelect.addEventListener('change', fetchAvailability);
    dateInput.addEventListener('change', fetchAvailability);

    fetchAvailability();
});
</script>
@endpush
