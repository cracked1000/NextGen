<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'cpu_id',
        'motherboard_id',
        'gpu_id',
        'power_supply_id',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cpu()
    {
        return $this->belongsTo(Cpu::class);
    }

    public function motherboard()
    {
        return $this->belongsTo(Motherboard::class);
    }

    public function gpu()
    {
        return $this->belongsTo(Gpu::class);
    }

    public function rams()
    {
        return $this->belongsToMany(Ram::class, 'build_ram');
    }

    public function storages()
    {
        return $this->belongsToMany(Storage::class, 'build_storage');
    }

    public function powerSupply()
    {
        return $this->belongsTo(PowerSupply::class, 'power_supply_id');
    }
}