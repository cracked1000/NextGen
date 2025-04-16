<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuotationAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'build_details',
        'quotation_number',
        'source',
        'build_id',
        'quotation_request_id',
        'status',           // Added
        'special_notes',   // Added
    ];

    protected $casts = [
        'build_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateQuotationNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastQuotation = self::where('quotation_number', 'like', "QTN-{$date}-%")
                            ->orderBy('quotation_number', 'desc')
                            ->first();
        $sequence = $lastQuotation ? (int) substr($lastQuotation->quotation_number, -4) + 1 : 1;
        return "QTN-{$date}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}