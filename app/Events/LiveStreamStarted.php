<?php

namespace App\Events;

use App\Models\Stream;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveStreamStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stream;

    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    public function broadcastOn()
    {
        return $this->stream->user->followers->map(function ($follower) {
            return new Channel('private-user-id-' . $follower->id);
        });
    }

    public function broadcastWith()
    {
        return ['stream' => $this->stream, 'message' => 'Live stream started'];
    }
}
