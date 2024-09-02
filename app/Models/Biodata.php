<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use HasFactory;

    protected $table = 'biodata';

    protected $fillable = ['user_id', 'full_name', 'birth_date', 'birth_place', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
