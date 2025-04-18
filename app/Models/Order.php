<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'part_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'country',
        'province',
        'district',
        'zipcode',
        'payment_option',
        'stripe_payment_id',
        'component_price',
        'verify_product',
        'verify_cost',
        'shipping_charges',
        'total',
        'status',
        'is_accepted',
        'is_shipped',
        'is_received',
        'is_verified',
        'shipping_address',
        'payment_status',
        'order_date',
    ];

    protected $casts = [
        'verify_product' => 'boolean',
        'is_accepted' => 'boolean',
        'is_shipped' => 'boolean',
        'is_received' => 'boolean',
        'is_verified' => 'boolean',
        'component_price' => 'decimal:2',
        'verify_cost' => 'decimal:2',
        'shipping_charges' => 'decimal:2',
        'total' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function part()
    {
        return $this->belongsTo(SecondHandPart::class, 'part_id');
    }

    public function getSellerNameAttribute()
    {
        return $this->part && $this->part->seller
            ? $this->part->seller->first_name . ' ' . $this->part->seller->last_name
            : 'N/A';
    }
}