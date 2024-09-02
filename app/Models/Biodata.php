<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    // Specify the table name if it does not follow Laravel's convention
    protected $table = 'biodatas';

    // Fillable fields
    protected $fillable = [
        'user_id', 'full_name', 'birth_date', 'birth_place', 'photo'
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
