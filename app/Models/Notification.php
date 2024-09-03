<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'actor_id', 'actor_name', 'type'];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function createNotification($userId, $actorId, $type)
{
    $actor = User::find($actorId);

    if ($actor) {
        Notification::create([
            'user_id' => $userId,
            'actor_id' => $actorId,
            'actor_name' => $actor->name, // Ensure actor's name is set
            'type' => $type,
        ]);
    }
}

}

