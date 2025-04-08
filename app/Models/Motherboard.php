<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motherboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'socket_type', 'ram_type', 'ram_speed',
        'form_factor', 'ram_slots', 'sata_slots', 'm2_slots',
        'm2_nvme_support', 'pcie_version', 'price'
    ];
}