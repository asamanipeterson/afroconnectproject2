<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;
use App\Models\User;

class PostShared extends Notification implements ShouldQueue
{
    use Queueable;

    public $post;
    public $sharer;

    public function __construct(Post $post, User $sharer)
    {
        $this->post = $post;
        $this->sharer = $sharer;
    }

    public function via($notifiable)
    {
        return ['database']; // Or ['mail', 'database'] if you want email too
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->sharer->username . ' shared a post with you!',
            'post_id' => $this->post->id,
            'sharer_id' => $this->sharer->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Post Shared With You')
            ->line($this->sharer->username . ' shared a post with you!')
            ->action('View Post', url('/posts/' . $this->post->id))
            ->line('Thank you for using our application!');
    }
}
