<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResearcherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        \App\Models\Researcher::create([
            'username' => 'researcher',
            'email' => 'researcher@usep.edu.ph',
            'password' => bcrypt('password123'), // Default password
            // You can add other fields specific to Researcher
        ]);
    }
}
