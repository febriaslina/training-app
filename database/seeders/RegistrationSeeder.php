<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;
use Carbon\Carbon;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Registration::create([
            'registration_code' => 'REG-' . date('Ym') . '-0001',
            'training_id' => 1,
            'customer_id' => 1,
            'verified_by' => 2,
            'status' => 'confirmed',
            'graduation_status' => 'not_assessed',
            'notes' => 'Mohon modul dikirimkan via email',
            'confirmed_at' => Carbon::now(),
            'confirmed_via' => 'system',
        ]);
    }
}
