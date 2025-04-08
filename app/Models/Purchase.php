<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'country',
        'province',
        'district',
        'Zipcode',
        'payment_option',
        'card_details',
        'verify_product',
        'shipping_charges',
        'purchased_at',
    ];

    protected $casts = [
        'verify_product' => 'boolean',
        'purchased_at' => 'datetime',
    ];

    public function part()
    {
        return $this->belongsTo(SecondHandPart::class);
    }
}