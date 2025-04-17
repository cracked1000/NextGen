<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Order extends Model
{
    use HasFactory;

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
        'shipping_address',
        'payment_status',
        'order_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function part()
    {
        return $this->belongsTo(SecondHandPart::class, 'part_id');
    }
}