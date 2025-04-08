<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Customer::create([
            'first_name' => 'Laksen',
            'last_name' => 'Doe',
            'email' => 'johndoe@gmail.com',
            'address' => '123 Main St',
            'Zipcode' => '12345',
            'phone_number' => '123-456-7890',
            'optional_phone_number' => '098-765-4321',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'janesmith@gmail.com',
            'address' => '456 Elm St',
            'Zipcode' => '67890',
            'phone_number' => '234-567-8901',
            'optional_phone_number' => '876-543-2109',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
    }
}
