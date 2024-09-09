<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplied extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $post;
    protected $comment;
    protected $reply;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Post $post
     * @param \App\Models\Comment $comment
     * @param \App\Models\ReplyComment $reply
     */
    public function __construct($user, $post, $comment, $reply)
    {
        $this->user = $user;
        $this->post = $post;
        $this->comment = $comment;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Balasan pada Komentar Anda')
                    ->line('Pengguna ' . $this->user->name . ' telah membalas komentar Anda di postingan berikut.')
                    ->line('Postingan: ' . $this->post->title)
                    ->line('Komentar: ' . $this->comment->content)
                    ->line('Balasan: ' . $this->reply->content)
                    ->action('Lihat Balasan', url('/posts/' . $this->post->id . '#comment-' . $this->comment->id))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_name' => $this->user->name,
            'post_title' => $this->post->title,
            'comment_content' => $this->comment->content,
            'reply_content' => $this->reply->content,
            'post_id' => $this->post->id,
            'comment_id' => $this->comment->id,
            'reply_id' => $this->reply->id,
        ];
    }
}
