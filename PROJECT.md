# Project Overview: Sport Center Information System

## 1. Tujuan Aplikasi
Sport Center adalah sistem informasi manajemen dan penyewaan fasilitas olahraga berbasis web multi-cabang (multi-branch). Aplikasi ini dirancang untuk:
* Mengotomatiskan proses pemesanan slot lapangan olahraga berbasis durasi per jam.
* Mencegah pemesanan ganda (double booking) melalui mekanisme transaksi database yang aman.
* Transparansi dan fleksibilitas dalam penerapan tarif dinamis (Regular vs. Peak Hours/Weekend).
* Menyediakan pencatatan keuangan dan laporan operasional yang akurat bagi pemilik bisnis.

## 2. Ruang Lingkup Sistem
* **Lingkup Fungsional:** Katalog lapangan, kalender ketersediaan slot jam, booking engine, integrasi payment gateway & transfer manual, check-in QR Code di lokasi, modul kasir walk-in, dashboard analitik admin, dan laporan pendapatan.
* **Lingkup Akses:** Multi-role RBAC (Pengunjung, Pelanggan, Operator, Admin Cabang, Superadmin).
* **Platform:** Web application (Mobile-responsive).

## 3. Aktor Sistem & Hak Akses
| Aktor | Peran & Hak Akses Utama | Batasan |
| :--- | :--- | :--- |
| **Pengunjung (Guest)** | Melihat katalog lapangan, cabang, fasilitas, harga, dan ketersediaan slot jadwal secara real-time. | Tidak dapat melakukan booking sebelum mendaftar/login. |
| **Pelanggan (Customer)** | Mengelola profil, memesan slot lapangan, melakukan pembayaran, mengunduh e-ticket QR, dan melihat riwayat transaksi. | Hanya dapat mengakses data transaksi pribadi. |
| **Operator (Staff)** | Melakukan verifikasi check-in QR code pelanggan di lokasi cabang, melayani order offline (walk-in cash), dan memantau jadwal harian. | Terbatas pada cabang tempat ditugaskan. Tidak dapat mengubah tarif/master data. |
| **Admin Cabang** | Mengelola data lapangan, aturan harga dinamis cabang, verifikasi pembayaran manual transfer, dan melihat laporan cabang. | Akses terbatas pada lingkup cabang terkait. |
| **Superadmin (Owner)** | Akses penuh seluruh cabang (global access), manajemen cabang, manajemen pengguna/staf, dan konsolidasi laporan keuangan. | Mengatur konfigurasi global sistem. |

## 4. Kebutuhan Bisnis Utama
* **Interval Sewa:** Slot waktu berbasis jam genap (contoh: 08:00–09:00, 09:00–10:00).
* **Payment Expiry:** Transaksi online yang belum dibayar otomatis kedaluwarsa dalam **15 menit**.
* **Zero Double Booking:** Proteksi tingkat tinggi menggunakan Pessimistic Locking di level database.