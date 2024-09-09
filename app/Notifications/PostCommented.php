<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $post;
    protected $comment;

    public function __construct($user, $post, $comment)
    {
        $this->user = $user;
        $this->post = $post;
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Komentar Baru pada Postingan Anda')
            ->line('Pengguna ' . $this->user->name . ' telah mengomentari postingan Anda.')
            ->line('Postingan: ' . $this->post->title)
            ->line('Komentar: ' . $this->comment->content)
            ->action('Lihat Komentar', url('/posts/' . $this->post->id . '#comment-' . $this->comment->id))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_name' => $this->user->name,
            'post_title' => $this->post->title,
            'comment_content' => $this->comment->content,
            'post_id' => $this->post->id,
            'comment_id' => $this->comment->id,
        ];
    }
}
