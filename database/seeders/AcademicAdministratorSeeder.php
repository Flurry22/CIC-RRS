<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicAdministrator; // Adjust with the correct model namespace
use Illuminate\Support\Facades\Hash;
class AcademicAdministratorSeeder extends Seeder
{
    public function run()
    {
        // Seeding an academic administrator with @usep.edu.ph email
        \App\Models\AcademicAdministrator::create([
            'username' => 'admin', 
            'email' => 'admin@usep.edu.ph',
            'password' => Hash::make('password123'), // Default password
            // You can add other fields specific to AcademicAdministrator
        ]);
    }
}

