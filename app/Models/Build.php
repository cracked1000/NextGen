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
        'ram_id',
        'storage_id',
        'power_supply_id',
        'total_price',
    ];

    // Relationships
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

    public function ram()
    {
        return $this->belongsTo(Ram::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function powerSupply()
    {
        return $this->belongsTo(PowerSupply::class, 'power_supply_id');
    }
}