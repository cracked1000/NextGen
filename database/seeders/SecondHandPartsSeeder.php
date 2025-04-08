<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SecondHandPart;

class SecondHandPartsSeeder extends Seeder
{
    public function run()
    {
        SecondHandPart::create([
            'part_name' => 'Asus RTX 3090',
            'seller' => 'John Doe',
            'price' => 300000.00,
            'status' => 'Available',
            'condition' => 'Used',
            'description' => 'High-performance GPU in great condition.',
            'listing_date' => now(),
            'image1' => '/images/Asus_RTX_3090.png', 
            'image2' => 'path/to/image2.jpg', 
            'image3' => 'path/to/image3.jpg',
            'category' => 'GPU' 
        ]);

        SecondHandPart::create([
            'part_name' => 'Intel i9 10900K',
            'seller' => 'Jane Smith',
            'price' => 100000.00,
            'status' => 'Available',
            'condition' => 'New',
            'description' => 'Brand new, never used CPU.',
            'listing_date' => now(),
            'image1' => '/images/Intel®-Core™-i9-10900K-Processor3.webp', 
            'image2' => 'path/to/image2.jpg', 
            'image3' => 'path/to/image3.jpg',
            'category' => 'CPU' 
        ]);
        
        // Add more data as needed
    }
}

