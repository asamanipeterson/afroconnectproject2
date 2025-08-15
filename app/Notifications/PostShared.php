<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PostShared extends Notification
{
    use Queueable;

    protected $post;
    protected $sharer;

    public function __construct(Post $post, User $sharer)
    {
        $this->post = $post;
        $this->sharer = $sharer;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail' or other channels if needed
    }

    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'sharer_id' => $this->sharer->id,
            'sharer_username' => $this->sharer->username,
            'message' => "{$this->sharer->username} shared a post with you."
        ];
    }
}
