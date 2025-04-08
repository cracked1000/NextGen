<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    public function run()
    {
        Seller::create([
            'first_name' => 'Sara',
            'last_name' => 'Williams',
            'email' => 'sarawilliams@example.com',
            'description' => 'Electronics Seller',
            'address' => '789 Oak St',
            'Zipcode' => '12345',
            'phone_number' => '345-678-9012',
            'optional_phone_number' => '987-654-3210',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        Seller::create([
            'first_name' => 'Emily',
            'last_name' => 'Davis',
            'email' => 'emilydavis@example.com',
            'description' => 'Clothing Seller',
            'address' => '101 Pine St',
            'Zipcode' => '67890',
            'phone_number' => '456-789-0123',
            'optional_phone_number' => '654-321-0987',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
    }
}
