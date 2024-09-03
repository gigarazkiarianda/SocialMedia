<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            $actor = User::where('id', '!=', $user->id)->inRandomOrder()->first();
            if ($actor) {
                Notification::create([
                    'user_id' => $user->id,
                    'actor_id' => $actor->id,
                    'actor_name' => $actor->name,
                    'type' => 'follow',
                ]);
            }
        }
    }
}
