<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationAction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'build_details'];

    protected $casts = [
        'build_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}