<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'username' => 'admin2',
            'email' => 'admin2@usep.edu.ph',
            'password' => Hash::make('usep2024'), // Replace with a secure password
        ]);
    }
}
