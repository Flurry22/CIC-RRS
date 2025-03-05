<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResearchStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ResearchStaff::create([
            'username' => 'staff',
            'email' => 'staff@usep.edu.ph',
            'password' => bcrypt('password123'), // Default password
            // You can add other fields specific to ResearchStaff
        ]);
    }
}
