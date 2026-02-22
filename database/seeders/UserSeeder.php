<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Superadmin
        $superadmin = User::firstOrCreate([
            'email' => 'superadmin@mudikpgn.com',
        ], [
            'name' => 'Superadmin Mudik',
            'password' => Hash::make('password'),
            'no_kk' => '0000000000000000',
        ]);
        $superadmin->assignRole('superadmin');

        // 2. Admin KC
        $adminKc = User::firstOrCreate([
            'email' => 'adminkc@mudikpgn.com',
        ], [
            'name' => 'Admin KC',
            'password' => Hash::make('password'),
            'no_kk' => '1111111111111111',
        ]);
        $adminKc->assignRole('admin-kc');

        // 3. Check-in Officer
        $officer = User::firstOrCreate([
            'email' => 'officer@mudikpgn.com',
        ], [
            'name' => 'Petugas Check-in',
            'password' => Hash::make('password'),
            'no_kk' => '2222222222222222',
        ]);
        $officer->assignRole('check-in-officer');

        // 4. Passenger (Example)
        $passenger = User::firstOrCreate([
            'email' => 'passenger@gmail.com',
        ], [
            'name' => 'Penumpang Contoh',
            'password' => Hash::make('password'),
            'no_kk' => '3201010101010001',
        ]);
        $passenger->assignRole('passenger');
    }
}
