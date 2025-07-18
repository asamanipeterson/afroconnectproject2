<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewCommentPosted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('post.' . $this->comment->post_id);
    }

    public function broadcastAs(): string
    {
        return 'NewComment';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'created_at' => $this->comment->created_at->toDateTimeString(),
            'user' => [
                'id' => $this->comment->user->id,
                'username' => $this->comment->user->username,
                'avatar' => $this->comment->user->avatar ?? null, // optional
            ],
        ];
    }
}
