<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerSupply extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'wattage', 'form_factor', 'price'];
}