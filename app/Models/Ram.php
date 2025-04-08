<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ram extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ram_speed', 'capacity', 'ram_type', 'stick_count', 'price'];

    // Define attribute aliases to match the controller's expectations
    public function getSpeedAttribute()
    {
        return $this->ram_speed;
    }

    public function setSpeedAttribute($value)
    {
        $this->attributes['ram_speed'] = $value;
    }

    public function getTypeAttribute()
    {
        return $this->ram_type;
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['ram_type'] = $value;
    }
}