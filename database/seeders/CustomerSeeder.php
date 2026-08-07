<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'id_number' => '3404012345678901',
            'phone' => '08111222333',
            'position' => 'Staf IT',
            'origin_institution' => 'Dinas Perdagangan',
            'client_type' => 'individual',
            'is_active' => true,
        ]);
        
        Customer::create([
            'name' => 'PT Nusantara Maju',
            'email' => 'hrd@nusantaramaju.com',
            'password' => Hash::make('password123'),
            'id_number' => '999888777666',
            'phone' => '08222333444',
            'position' => 'HR Manager',
            'origin_institution' => 'PT Nusantara Maju',
            'client_type' => 'institutional',
            'is_active' => true,
        ]);
    }
}
