<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cabang
        DB::table('branches')->upsert([
            ['id' => 1, 'name' => 'Sport Center MERR Surabaya', 'slug' => 'sport-center-merr-surabaya', 'address' => 'Jl. Dr. Ir. H. Soekarno No. 123, Surabaya', 'phone' => '081234567890', 'is_active' => 1],
            ['id' => 2, 'name' => 'Sport Center Barat Surabaya', 'slug' => 'sport-center-barat-surabaya', 'address' => 'Jl. Raya Barat No. 45, Surabaya', 'phone' => '081298765432', 'is_active' => 1],
        ], ['id'], ['name', 'slug', 'address', 'phone', 'is_active']);

        // Pengguna: password default password123
        DB::table('users')->upsert([
            ['id' => 1, 'branch_id' => null, 'name' => 'Superadmin Utama', 'email' => 'superadmin@sportcenter.com', 'phone_number' => '08111111111', 'password' => Hash::make('password123'), 'role' => 'superadmin'],
            ['id' => 2, 'branch_id' => 1, 'name' => 'Admin Cabang MERR', 'email' => 'admin.merr@sportcenter.com', 'phone_number' => '08222222222', 'password' => Hash::make('password123'), 'role' => 'admin'],
            ['id' => 3, 'branch_id' => 1, 'name' => 'Operator Kasir MERR', 'email' => 'operator.merr@sportcenter.com', 'phone_number' => '08333333333', 'password' => Hash::make('password123'), 'role' => 'operator'],
            ['id' => 4, 'branch_id' => null, 'name' => 'Budi Santoso (Customer)', 'email' => 'budi@gmail.com', 'phone_number' => '085712345678', 'password' => Hash::make('password123'), 'role' => 'customer'],
        ], ['id'], ['branch_id', 'name', 'email', 'phone_number', 'password', 'role']);

        // Jenis olahraga
        DB::table('sports')->upsert([
            ['id' => 1, 'name' => 'Futsal', 'slug' => 'futsal', 'icon' => 'heroicon-o-futbol'],
            ['id' => 2, 'name' => 'Badminton', 'slug' => 'badminton', 'icon' => 'heroicon-o-trophy'],
            ['id' => 3, 'name' => 'Basket', 'slug' => 'basket', 'icon' => 'heroicon-o-variable'],
        ], ['id'], ['name', 'slug', 'icon']);

        // Lapangan
        DB::table('courts')->upsert([
            ['id' => 1, 'branch_id' => 1, 'sport_id' => 1, 'name' => 'Lapangan Futsal A (Rumput Sintetis)', 'slug' => 'futsal-a-sintetis', 'base_price_per_hour' => 120000, 'description' => 'Rumput sintetis standar nasional dengan pencahayaan LED.', 'is_active' => 1],
            ['id' => 2, 'branch_id' => 1, 'sport_id' => 2, 'name' => 'Lapangan Badminton 1 (Karpet Vinyil)', 'slug' => 'badminton-1-vinyil', 'base_price_per_hour' => 50000, 'description' => 'Karpet vinyil standar BWF.', 'is_active' => 1],
        ], ['id'], ['branch_id', 'sport_id', 'name', 'slug', 'base_price_per_hour', 'description', 'is_active']);

        // Fasilitas
        DB::table('facilities')->upsert([
            ['id' => 1, 'name' => 'WiFi Gratis', 'icon' => 'wifi'],
            ['id' => 2, 'name' => 'Kantin / Resto', 'icon' => 'shopping-bag'],
            ['id' => 3, 'name' => 'Ruang Ganti & Shower', 'icon' => 'user'],
        ], ['id'], ['name', 'icon']);

        // Relasi lapangan dan fasilitas
        DB::table('court_facility')->insertOrIgnore([
            ['court_id' => 1, 'facility_id' => 1],
            ['court_id' => 1, 'facility_id' => 2],
            ['court_id' => 1, 'facility_id' => 3],
            ['court_id' => 2, 'facility_id' => 1],
            ['court_id' => 2, 'facility_id' => 3],
        ]);

        // Tarif khusus futsal Senin-Jumat pukul 18.00-23.00
        foreach ([1, 2, 3, 4, 5] as $day) {
            DB::table('court_price_rules')->updateOrInsert(
                ['court_id' => 1, 'day_of_week' => $day, 'start_time' => '18:00:00', 'end_time' => '23:00:00'],
                ['price_per_hour' => 150000]
            );
        }
    }
}
