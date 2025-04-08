<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ram;

class RamSeeder extends Seeder
{
    public function run()
    {
        // Clear existing RAMs to avoid duplicates
        Ram::truncate();

        $rams = [
            ['name' => 'Corsair Vengeance LPX 16GB', 'ram_speed' => 3200, 'capacity' => 16, 'ram_type' => 'DDR4', 'stick_count' => 2],
            ['name' => 'G.SKILL Ripjaws V 32GB', 'ram_speed' => 3600, 'capacity' => 32, 'ram_type' => 'DDR4', 'stick_count' => 2],
            ['name' => 'Crucial Ballistix 16GB', 'ram_speed' => 3000, 'capacity' => 16, 'ram_type' => 'DDR4', 'stick_count' => 1],
            ['name' => 'Kingston Fury Beast 32GB', 'ram_speed' => 5200, 'capacity' => 32, 'ram_type' => 'DDR5', 'stick_count' => 2],
        ];

        foreach ($rams as $ram) {
            Ram::create($ram);
        }
    }
}