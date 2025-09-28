<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewCommentPosted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $id;
    public $content;
    public $user;
    public $postId;

    /**
     * Create a new event instance.
     * We pass simple, serializable data instead of the full model.
     */
    public function __construct(int $id, string $content, array $user, int $postId)
    {
        $this->id = $id;
        $this->content = $content;
        $this->user = $user;
        $this->postId = $postId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        // The channel name must be consistent with the front-end listener.
        return new Channel('post.' . $this->postId);
    }

    /**
     * The event's broadcast name.
     * This must match what Echo is listening for (without the dot prefix)
     */
    public function broadcastAs(): string
    {
        return 'NewComment';
    }

    /**
     * Get the data to broadcast.
     * This method defines the payload received by the front-end.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => $this->user,
            'post_id' => $this->postId // Added this to ensure consistency
        ];
    }
}
