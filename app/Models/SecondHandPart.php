<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondHandPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_name',
        'description',
        'price',
        'status',
        'condition',
        'category',
        'seller_id',
        'image1',
        'image2',
        'image3',
        'listing_date',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'part_id');
    }
}