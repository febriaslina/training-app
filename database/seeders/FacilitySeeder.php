<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Facility::create([
            'name' => 'Ruang Kelas Eksekutif',
            'type' => 'classroom',
            'description' => 'Ruang kelas full AC, proyektor, dan sound system.',
            'capacity' => 40,
            'price_per_day' => 1500000,
            'is_active' => true,
        ]);
        
        Facility::create([
            'name' => 'Auditorium Utama',
            'type' => 'classroom',
            'description' => 'Auditorium kapasitas besar untuk seminar.',
            'capacity' => 200,
            'price_per_day' => 5000000,
            'is_active' => true,
        ]);
    }
}
