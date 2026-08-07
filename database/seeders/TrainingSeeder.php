<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;
use Carbon\Carbon;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Training::create([
            'name' => 'Pelatihan Ekspor Impor Lanjutan',
            'type' => 'technical',
            'description' => 'Pelatihan mendalam mengenai prosedur dan regulasi ekspor impor terkini.',
            'duration_days' => 5,
            'requirements' => 'Memiliki dasar pengetahuan perdagangan internasional.',
            'start_date' => Carbon::now()->addDays(14),
            'end_date' => Carbon::now()->addDays(18),
            'location' => 'Gedung A Bapekomdag',
            'max_quota' => 30,
            'filled_quota' => 0,
            'status' => 'open',
            'is_active' => true,
        ]);
        
        Training::create([
            'name' => 'Sertifikasi Manajemen Ritel',
            'type' => 'managerial',
            'description' => 'Pelatihan dan sertifikasi untuk manajer ritel modern.',
            'duration_days' => 3,
            'requirements' => 'Pengalaman minimal 1 tahun di bidang ritel.',
            'start_date' => Carbon::now()->addDays(30),
            'end_date' => Carbon::now()->addDays(32),
            'location' => 'Gedung B Bapekomdag',
            'max_quota' => 50,
            'filled_quota' => 0,
            'status' => 'open',
            'is_active' => true,
        ]);
    }
}
