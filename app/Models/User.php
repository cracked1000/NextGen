<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Build;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'role', 'address', 'zipcode',
        'phone_number', 'optional_phone_number', 'description', 'status','profile_photo',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function builds()
    {
        return $this->hasMany(Build::class);
    }
}