<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'name' => 'Admin Utama',
            'email' => 'admin@bapekomdag.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);
        
        Employee::create([
            'name' => 'Operator Pelatihan',
            'email' => 'operator@bapekomdag.go.id',
            'password' => Hash::make('password123'),
            'role' => 'operator',
            'phone' => '081234567891',
            'is_active' => true,
        ]);
        
        Employee::create([
            'name' => 'Pimpinan Bapekomdag',
            'email' => 'pimpinan@bapekomdag.go.id',
            'password' => Hash::make('password123'),
            'role' => 'leader',
            'phone' => '081234567892',
            'is_active' => true,
        ]);
    }
}
