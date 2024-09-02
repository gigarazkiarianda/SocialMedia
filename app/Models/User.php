<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Fillable fields
    protected $fillable = [
        'name', 'email', 'password',
    ];

    // Hidden fields
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Define the relationship with Biodata
    public function biodata()
    {
        return $this->hasOne(Biodata::class);
    }
}
