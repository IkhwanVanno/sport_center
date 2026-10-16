# Database Schema & Data Integrity Rules

## 1. Diagram ERD Ringkas
* `branches` 1 --- N `courts`
* `sports` 1 --- N `courts`
* `courts` 1 --- N `court_price_rules`
* `courts` N --- N `facilities` (via `court_facility`)
* `users` 1 --- N `bookings`
* `courts` 1 --- N `bookings`
* `bookings` 1 --- N `booking_items`
* `bookings` 1 --- 1 `payments`

## 2. Spesifikasi Tabel

### `users`
| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key |
| `branch_id` | `BIGINT UNSIGNED` | `FK -> branches(id), NULL` | Khusus Operator/Admin Cabang |
| `name` | `VARCHAR(100)` | `NOT NULL` | Nama Lengkap |
| `email` | `VARCHAR(100)` | `NOT NULL, UNIQUE` | Email |
| `phone_number` | `VARCHAR(20)` | `NULLABLE` | No HP/WhatsApp |
| `password` | `VARCHAR(255)` | `NOT NULL` | Hashed Password |
| `role` | `ENUM` | `DEFAULT 'customer'` | `'superadmin','admin','operator','customer'` |

### `branches`
| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key |
| `name` | `VARCHAR(100)` | `NOT NULL` | Nama Cabang |
| `slug` | `VARCHAR(100)` | `NOT NULL, UNIQUE` | Slug URL |
| `address` | `TEXT` | `NOT NULL` | Alamat Lengkap |
| `phone` | `VARCHAR(20)` | `NOT NULL` | Telepon Kontak |

### `courts`
| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key |
| `branch_id` | `BIGINT UNSIGNED` | `FK -> branches(id)` | FK Cabang |
| `sport_id` | `BIGINT UNSIGNED` | `FK -> sports(id)` | FK Olahraga |
| `name` | `VARCHAR(100)` | `NOT NULL` | Nama Lapangan |
| `base_price_per_hour`|`DECIMAL(12,2)`| `NOT NULL` | Harga Sewa Standar/Jam |

### `court_price_rules`
| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key |
| `court_id` | `BIGINT UNSIGNED` | `FK -> courts(id)` | FK Lapangan |
| `day_of_week` | `TINYINT` | `NOT NULL` | `0` (Minggu) - `6` (Sabtu) |
| `start_time` | `TIME` | `NOT NULL` | Jam Mulai Peak Hour |
| `end_time` | `TIME` | `NOT NULL` | Jam Selesai Peak Hour |
| `price_per_hour` | `DECIMAL(12,2)` | `NOT NULL` | Harga Khusus Slot Tersebut |

### `bookings`
| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key |
| `booking_code` | `VARCHAR(30)` | `NOT NULL, UNIQUE` | Kode Unik Booking |
| `user_id` | `BIGINT UNSIGNED` | `FK -> users(id)` | FK Pelanggan Pemesan |
| `court_id` | `BIGINT UNSIGNED` | `FK -> courts(id)` | FK Lapangan |
| `booking_date` | `DATE` | `NOT NULL` | Tanggal Sewa |
| `total_amount` | `DECIMAL(12,2)` | `NOT NULL` | Total Biaya Akhir |
| `status` | `ENUM` | `DEFAULT 'pending'` | `'pending','confirmed','in_use','completed','cancelled','expired'` |
| `payment_status` | `ENUM` | `DEFAULT 'unpaid'` | `'unpaid','pending_verification','paid','refunded'` |
| `expired_at` | `TIMESTAMP` | `NOT NULL` | Batas Waktu Bayar |

### `booking_items`
| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key |
| `booking_id` | `BIGINT UNSIGNED` | `FK -> bookings(id)` | FK Transaksi Induk |
| `start_time` | `TIME` | `NOT NULL` | Jam Mulai Slot |
| `end_time` | `TIME` | `NOT NULL` | Jam Selesai Slot |
| `price` | `DECIMAL(12,2)` | `NOT NULL` | Snapshot Harga Saat Transaksi |

### `payments`
| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | `PK, AUTO_INCREMENT` | Primary Key |
| `booking_id` | `BIGINT UNSIGNED` | `FK -> bookings(id)` | FK Transaksi Booking |
| `payment_method` | `VARCHAR(50)` | `NOT NULL` | Method (qris/cash/transfer) |
| `amount` | `DECIMAL(12,2)` | `NOT NULL` | Nominal Bayar |
| `proof_of_payment`| `VARCHAR(255)` | `NULLABLE` | Path File Bukti |
| `status` | `ENUM` | `DEFAULT 'pending'` | `'pending','success','failed'` |

## 3. Aturan Integritas & Performa
1. **Pessimistic Locking Index:** `bookings` memiliki composite index `idx_bookings_availability` pada `(court_id, booking_date, status)`.
2. **Data Deletion Safety:** Menghapus data `courts` yang sudah digunakan dalam transaksi dilarang secara langsung (`ON DELETE RESTRICT`).
3. **Price Snapshotting:** `booking_items.price` mencatat nominal harga saat pemesanan dilakukan sehingga tidak akan berubah meskipun master harga lapangan mengalami penyesuaian.