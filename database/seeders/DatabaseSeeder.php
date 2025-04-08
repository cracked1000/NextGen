<?php

use Illuminate\Database\Seeder;
use App\Models\Cpu;
use App\Models\Motherboard;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\Storage;
use App\Models\PowerSupply;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // CPUs
        Cpu::create([
            'name' => 'AMD Ryzen 5 5600X',
            'socket_type' => 'AM4',
            'power_requirement' => 65,
            'price' => 65000.00 // Estimated based on Game Street trends
        ]);
        Cpu::create([
            'name' => 'AMD Ryzen 7 5800X',
            'socket_type' => 'AM4',
            'power_requirement' => 105,
            'price' => 88000.00 // Adjusted for inflation
        ]);
        Cpu::create([
            'name' => 'AMD Ryzen 9 7950X',
            'socket_type' => 'AM5',
            'power_requirement' => 170,
            'price' => 310000.00 // Based on Winsoft.lk, adjusted
        ]);
        Cpu::create([
            'name' => 'Intel Core i5-13400',
            'socket_type' => 'LGA 1700',
            'power_requirement' => 65,
            'price' => 75000.00 // Estimated
        ]);
        Cpu::create([
            'name' => 'Intel Core i7-12700K',
            'socket_type' => 'LGA 1700',
            'power_requirement' => 125,
            'price' => 99000.00 // Adjusted for inflation
        ]);
        Cpu::create([
            'name' => 'AMD Ryzen 3 5300G',
            'socket_type' => 'AM4',
            'power_requirement' => 65,
            'price' => 45000.00 // Entry-level APU, estimated
        ]);

        // Motherboards
        Motherboard::create([
            'name' => 'ASUS ROG Strix B550-F Gaming',
            'socket_type' => 'AM4',
            'ram_type' => 'DDR4',
            'ram_speed' => 3200,
            'form_factor' => 'ATX',
            'ram_slots' => 4,
            'sata_slots' => 6,
            'm2_slots' => 2,
            'm2_nvme_support' => 1,
            'pcie_version' => 4.0,
            'price' => 49500.00 // Adjusted for inflation
        ]);
        Motherboard::create([
            'name' => 'MSI MPG Z690 Edge',
            'socket_type' => 'LGA 1700',
            'ram_type' => 'DDR5',
            'ram_speed' => 5200,
            'form_factor' => 'ATX',
            'ram_slots' => 4,
            'sata_slots' => 6,
            'm2_slots' => 3,
            'm2_nvme_support' => 1,
            'pcie_version' => 5.0,
            'price' => 66000.00 // Adjusted for inflation
        ]);
        Motherboard::create([
            'name' => 'Gigabyte B650E AORUS Elite',
            'socket_type' => 'AM5',
            'ram_type' => 'DDR5',
            'ram_speed' => 6000,
            'form_factor' => 'ATX',
            'ram_slots' => 4,
            'sata_slots' => 4,
            'm2_slots' => 3,
            'm2_nvme_support' => 1,
            'pcie_version' => 5.0,
            'price' => 85000.00 // Estimated for AM5
        ]);
        Motherboard::create([
            'name' => 'ASUS Prime B450M-A II',
            'socket_type' => 'AM4',
            'ram_type' => 'DDR4',
            'ram_speed' => 3200,
            'form_factor' => 'Micro ATX',
            'ram_slots' => 4,
            'sata_slots' => 6,
            'm2_slots' => 1,
            'm2_nvme_support' => 1,
            'pcie_version' => 3.0,
            'price' => 35000.00 // Entry-level AM4 motherboard
        ]);

        // GPUs
        Gpu::create([
            'name' => 'NVIDIA GeForce RTX 3060',
            'pcie_version' => 4.0,
            'power_requirement' => 170,
            'length' => 242,
            'height' => 112,
            'price' => 105000.00 // Decreased due to age
        ]);
        Gpu::create([
            'name' => 'AMD Radeon RX 6600 XT',
            'pcie_version' => 4.0,
            'power_requirement' => 160,
            'length' => 237,
            'height' => 111,
            'price' => 95000.00 // Decreased due to age
        ]);
        Gpu::create([
            'name' => 'NVIDIA GeForce RTX 4070 Ti',
            'pcie_version' => 4.0,
            'power_requirement' => 285,
            'length' => 310,
            'height' => 120,
            'price' => 280000.00 // Estimated
        ]);
        Gpu::create([
            'name' => 'AMD Radeon RX 7900 XT',
            'pcie_version' => 4.0,
            'power_requirement' => 300,
            'length' => 276,
            'height' => 113,
            'price' => 290000.00 // Estimated
        ]);
        Gpu::create([
            'name' => 'NVIDIA GeForce GTX 1650',
            'pcie_version' => 3.0,
            'power_requirement' => 75,
            'length' => 200,
            'height' => 100,
            'price' => 65000.00 // Entry-level GPU, estimated
        ]);

        // RAMs
        Ram::create([
            'name' => 'Corsair Vengeance LPX 16GB DDR4 3200MHz',
            'ram_type' => 'DDR4',
            'ram_speed' => 3200,
            'capacity' => 16,
            'stick_count' => 2,
            'price' => 22000.00 // Adjusted for inflation
        ]);
        Ram::create([
            'name' => 'G.Skill Ripjaws V 32GB DDR4 3600MHz',
            'ram_type' => 'DDR4',
            'ram_speed' => 3600,
            'capacity' => 32,
            'stick_count' => 2,
            'price' => 38500.00 // Adjusted for inflation
        ]);
        Ram::create([
            'name' => 'Crucial Ballistix 16GB DDR4 3000MHz',
            'ram_type' => 'DDR4',
            'ram_speed' => 3000,
            'capacity' => 16,
            'stick_count' => 1,
            'price' => 19800.00 // Adjusted for inflation
        ]);
        Ram::create([
            'name' => 'Kingston Fury Beast 32GB DDR5 5200MHz',
            'ram_type' => 'DDR5',
            'ram_speed' => 5200,
            'capacity' => 32,
            'stick_count' => 2,
            'price' => 44000.00 // Adjusted for inflation
        ]);
        Ram::create([
            'name' => 'Corsair Vengeance 64GB DDR5 5600MHz',
            'ram_type' => 'DDR5',
            'ram_speed' => 5600,
            'capacity' => 64,
            'stick_count' => 2,
            'price' => 90000.00 // Estimated
        ]);
        Ram::create([
            'name' => 'Kingston ValueRAM 8GB DDR4 2666MHz',
            'ram_type' => 'DDR4',
            'ram_speed' => 2666,
            'capacity' => 8,
            'stick_count' => 1,
            'price' => 12000.00 // Entry-level RAM, estimated
        ]);

        // Storages
        Storage::create([
            'name' => 'Samsung 970 EVO Plus 1TB NVMe SSD',
            'type' => 'M.2',
            'is_nvme' => 1,
            'capacity' => 1000,
            'price' => 33000.00 // Adjusted for inflation
        ]);
        Storage::create([
            'name' => 'WD Blue 2TB HDD',
            'type' => 'SATA',
            'is_nvme' => 0,
            'capacity' => 2000,
            'price' => 16500.00 // Adjusted for inflation
        ]);
        Storage::create([
            'name' => 'Samsung 990 PRO 2TB NVMe SSD',
            'type' => 'M.2',
            'is_nvme' => 1,
            'capacity' => 2000,
            'price' => 65000.00 // Estimated
        ]);
        Storage::create([
            'name' => 'Seagate Barracuda 4TB HDD',
            'type' => 'SATA',
            'is_nvme' => 0,
            'capacity' => 4000,
            'price' => 28000.00 // Estimated
        ]);
        Storage::create([
            'name' => 'Kingston A400 480GB SATA SSD',
            'type' => 'SATA',
            'is_nvme' => 0,
            'capacity' => 480,
            'price' => 15000.00 // Entry-level SSD, estimated
        ]);

        // Power Supplies
        PowerSupply::create([
            'name' => 'Corsair RM650x 650W 80+ Gold',
            'wattage' => 650,
            'form_factor' => 'ATX',
            'price' => 27500.00 // Adjusted for inflation
        ]);
        PowerSupply::create([
            'name' => 'EVGA 550 G3 550W 80+ Gold',
            'wattage' => 550,
            'form_factor' => 'ATX',
            'price' => 22000.00 // Adjusted for inflation
        ]);
        PowerSupply::create([
            'name' => 'Corsair RM850x 850W 80+ Gold',
            'wattage' => 850,
            'form_factor' => 'ATX',
            'price' => 38000.00 // Estimated
        ]);
        PowerSupply::create([
            'name' => 'Cooler Master MWE 750W 80+ Gold',
            'wattage' => 750,
            'form_factor' => 'ATX',
            'price' => 32000.00 // Estimated
        ]);
        PowerSupply::create([
            'name' => 'Antec NeoECO 450W 80+ Bronze',
            'wattage' => 450,
            'form_factor' => 'ATX',
            'price' => 15000.00 // Entry-level PSU, estimated
        ]);
    }
}