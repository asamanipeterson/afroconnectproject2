<?php

namespace App\Events;

use App\Models\LiveComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveCommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $liveComment;

    public function __construct(LiveComment $liveComment)
    {
        $this->liveComment = $liveComment;
    }

    public function broadcastOn()
    {
        return new Channel('stream.' . $this->liveComment->stream_id);
    }

    public function broadcastWith()
    {
        return ['liveComment' => $this->liveComment, 'user' => $this->liveComment->user];
    }
}
