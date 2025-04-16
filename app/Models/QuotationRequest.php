<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'components',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotationAction()
    {
        return $this->hasOne(QuotationAction::class, 'quotation_request_id');
    }
}