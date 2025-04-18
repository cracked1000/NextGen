<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecondHandPart extends Model
{
    protected $fillable = [
        'part_name',
        'description',
        'price',
        'status',
        'condition',
        'category',
        'seller_id',
        'listing_date',
        'image1',
        'image2',
        'image3',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'listing_date' => 'datetime',
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