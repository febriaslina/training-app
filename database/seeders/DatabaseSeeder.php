<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmployeeSeeder::class,
            CustomerSeeder::class,
            TrainingSeeder::class,
            RegistrationSeeder::class,
            // AttendanceSeeder::class, // Skipping empty seeds for demo
            // CertificateSeeder::class,
            FacilitySeeder::class,
            // FacilityBookingSeeder::class,
            // InvoiceSeeder::class,
            // PaymentSeeder::class,
        ]);
    }
}
