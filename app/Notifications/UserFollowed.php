<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    protected $actor;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\User  $actor
     * @param  string  $type
     * @return void
     */
    public function __construct($actor, $type)
    {
        $this->actor = $actor;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Store notifications in the database
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'type' => $this->type,
        ];
    }
}
