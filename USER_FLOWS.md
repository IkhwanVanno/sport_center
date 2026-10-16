# User Flows & System Sequence Diagrams

## 1. Alur Pemesanan Online (Customer)

1. **Eksplorasi:** Customer membuka katalog -> Memilih cabang & jenis lapangan -> Memilih tanggal sewa.
2. **Pilih Slot:** System menampilkan grid ketersediaan slot jam -> Customer memilih 1 atau beberapa slot berurutan.
3. **Kalkulasi:** `PricingService` menghitung rincian total biaya (termasuk penyesuaian tarif peak hours).
4. **Checkout & Lock:** Customer menekan tombol _Booking_:
    - Sistem membuka `DB::transaction()`.
    - Melakukan `SELECT ... FOR UPDATE` pada slot terkait.
    - Jika slot masih kosong, simpan record `bookings` (`status='pending'`) dan `booking_items`.
    - Dispatch `ExpirePendingBookingJob` dengan penundaan 15 menit.
5. **Pembayaran:** Customer diarahkan ke halaman Instruksi Pembayaran (disertai timer _countdown_ 15 menit).

## 2. Alur Pembayaran & Konfirmasi

### A. Melalui Payment Gateway (Otomatis)

- Customer menyelesaikan pembayaran via QRIS / VA.
- Payment Gateway mengirimkan request _Webhook Notification_ ke `/api/payments/midtrans-notification`.
- System memverifikasi signature key payload -> Mengubah `payments.status='success'`, `bookings.status='confirmed'`, dan `bookings.payment_status='paid'`.
- E-Ticket QR Code otomatis terbit di dashboard customer.

### B. Melalui Transfer Manual

- Customer mengunggah foto bukti transfer sebelum timer 15 menit habis.
- Status transaksi berubah menjadi `payment_status='pending_verification'`.
- Admin Cabang memeriksa bukti di dashboard admin -> Klik tombol _Konfirmasi Lunas_.
- Status transaksi berubah menjadi `confirmed` & `paid`.

## 3. Alur Verifikasi Check-In di Lokasi (Operator)

1. Pelanggan datang ke lokasi cabang dan menunjukkan QR Code E-Ticket pada smartphone.
2. Operator membuka menu `/operator/checkin` dan melakukan scanning QR Code (atau memasukkan kode booking manual).
3. System menampilkan detail booking, nama pemesan, dan status pembayaran.
4. Operator menekan tombol _Verifikasi Check-In_ -> Status booking diperbarui dari `confirmed` menjadi `in_use`.
