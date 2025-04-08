<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'first_name' => 'Ruchira',
            'last_name' => 'Kaveesha',
            'email' => 'ruchira@gmail.com',
            'password' => Hash::make('ruchira2005'),
        ]);

        Admin::create([
            'first_name' => 'Shahul',
            'last_name' => 'Hameed',
            'email' => 'Shahul@gmail.com',
            'password' => Hash::make('shahul2005'),
        ]);

        Admin::create([
            'first_name' => 'Adeesh',
            'last_name' => 'Lehan',
            'email' => 'adeesha@gmail.com',
            'password' => Hash::make('adeesha2005'),
        ]);
    }
}
