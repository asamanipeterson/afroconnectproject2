<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class PostLiked implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $postId;
    public $likesCount;
    public $user;

    public function __construct(int $postId, int $likesCount, User $user)
    {
        $this->postId = $postId;
        $this->likesCount = $likesCount;
        $this->user = $user;
    }

    public function broadcastOn(): Channel
    {
        return new Channel("post.{$this->postId}");
    }

    public function broadcastAs(): string
    {
        return 'PostLiked'; // ✅ Keep it WITHOUT the dot
    }

    public function broadcastWith(): array
    {
        return [
            'post_id' => $this->postId,
            'likes_count' => $this->likesCount,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->profile_picture_url // ✅ Fixed: use profile_picture_url instead of avatar
            ],
        ];
    }
}
