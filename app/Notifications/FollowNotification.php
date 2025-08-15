<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class FollowNotification extends Notification
{
    use Queueable;
    protected $follower;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $isFollowingBack = $notifiable->following()->where('followed_id', $this->follower->id)->exists();
        $message = $isFollowingBack
            ? "{$this->follower->username} has followed you."
            : "{$this->follower->username} followed you.";

        $mail = (new MailMessage)
            ->line($message)
            ->line('Thank you for using our application!');

        if (!$isFollowingBack) {
            // Assuming you have a route to follow a user, e.g., /follow/{id}
            $mail->action('Follow Back', url('/follow/' . $this->follower->id));
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        $isFollowingBack = $notifiable->following()->where('followed_id', $this->follower->id)->exists();
        $message = $isFollowingBack
            ? "{$this->follower->username} has followed you"
            : "{$this->follower->username} followed you";

        return [
            'message' => $message,
            'follower_id' => $this->follower->id,
            'show_follow_back_button' => !$isFollowingBack, // Flag for frontend to show button if needed
        ];
    }
}
