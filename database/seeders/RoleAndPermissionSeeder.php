<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Inisialisasi role default dan akun dummy untuk masing-masing role:
     * - superadmin: superadmin@sportcenter.com
     * - admin: admin@sportcenter.com
     * - operator: operator@sportcenter.com
     * - customer: customer@sportcenter.com
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        $users = [
            [
                'name' => 'Superadmin Utama',
                'email' => 'superadmin@sportcenter.com',
                'phone_number' => '08111111111',
                'password' => $defaultPassword,
                'role' => 'superadmin',
                'branch_id' => null,
            ],
            [
                'name' => 'Admin Cabang MERR',
                'email' => 'admin@sportcenter.com',
                'phone_number' => '08222222222',
                'password' => $defaultPassword,
                'role' => 'admin',
                'branch_id' => 1,
            ],
            [
                'name' => 'Operator Kasir MERR',
                'email' => 'operator@sportcenter.com',
                'phone_number' => '08333333333',
                'password' => $defaultPassword,
                'role' => 'operator',
                'branch_id' => 1,
            ],
            [
                'name' => 'Customer Demo Sport Center',
                'email' => 'customer@sportcenter.com',
                'phone_number' => '085712345678',
                'password' => $defaultPassword,
                'role' => 'customer',
                'branch_id' => null,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
