<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class MessageSentToUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;

    public function __construct(Message $message, $userId)
    {
        $this->message = $message;
        $this->userId = $userId;

        // Load the user relationship
        $this->message->load('user');

        Log::info('MessageSentToUser event created', [
            'message_id' => $message->id,
            'user_id' => $userId
        ]);
    }

    public function broadcastOn()
    {
        Log::info('Broadcasting on channel: user.' . $this->userId);
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'new-message';
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'body' => $this->message->body,
                'user_id' => $this->message->user_id,
                'conversation_id' => $this->message->conversation_id,
                'created_at' => $this->message->created_at->toISOString(),
                'user' => [
                    'id' => $this->message->user->id,
                    'username' => $this->message->user->username,
                ]
            ]
        ];
    }
}
