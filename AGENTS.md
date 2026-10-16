# AI Development Working Guidelines (AGENTS.md)

Sebagai AI Assistant / Developer yang mengerjakan proyek **Sport Center**, Anda wajib mematuhi instruksi dan batasan teknis berikut dalam setiap pembuatan kode program:

## 1. Prinsip Utama Kode
* **No Inline Logic in Controllers:** Seluruh logika bisnis pemesanan, pengecekan ketersediaan, dan kalkulasi harga **harus** ditempatkan di dalam class Service di folder `app/Services/`.
* **Zero Race Conditions:** Pembentukan record booking wajib dibungkus di dalam `DB::transaction()` dan memanfaatkan `lockForUpdate()` untuk mencegah pembentukan booking ganda pada slot jam yang sama.
* **Strict Type Declarations:** Gunakan type hinting pada function arguments dan return values PHP 8.3+.

## 2. Standar Penulisan Kode Laravel
* **Validation:** Gunakan *Custom Form Request* (`app/Http/Requests/`) untuk seluruh validasi input dari user/admin. Jangan melakukan validasi langsung di dalam Controller.
* **Authorization:** Lindungi endpoint peka menggunakan *Laravel Policy* (`app/Policies/`) untuk mencegah celah keamanan IDOR (Insecure Direct Object References).
* **Database Migration:** Selalu sertakan foreign key constraint lengkap (`ON DELETE RESTRICT` atau `ON DELETE CASCADE`) dan index komposit pada kolom yang sering di-query.

## 3. Aturan UI/UX (Blade & Tailwind)
* Mengacu pada struktur folder `resources/views/` yang telah ditentukan di `ARCHITECTURE.md`.
* Gunakan Blade Layouts (`layouts/app.blade.php`, `layouts/admin.blade.php`) dan terapkan komponen yang reusable (`<x-badge-status>`, `<x-alert>`).
* Pastikan UI bersifat *responsive* dan teruji baik pada ukuran layar Mobile maupun Desktop.

## 4. Batasan & Larangan
* **Dilarang** hardcode ID atau nilai variabel peka di dalam kode. Selalu gunakan file konfigurasi `.env`.
* **Dilarang** menghapus atau merusak struktur tabel database yang telah ditetapkan pada `DATABASE.md`.
* **Dilarang** melakukan update status pembayaran online secara langsung di sisi frontend tanpa validasi verifikasi server-side / webhook signature key.