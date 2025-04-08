<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gpu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'pcie_version', 'power_requirement', 'length', 'height', 'price'];
}