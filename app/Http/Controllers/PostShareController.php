<?php

// app/Http/Controllers/PostShareController.php

// app/Http/Controllers/PostShareController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostShareController extends Controller
{
    public function share(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'followers' => 'required|array',
            'followers.*' => 'exists:users,id',
        ]);

        $sharer = Auth::user();
        $postId = $request->input('post_id');
        $recipientIds = $request->input('followers');

        foreach ($recipientIds as $recipientId) {
            // Find or create conversation between sharer and recipient
            $conversation = Conversation::firstOrCreateConversation($sharer->id, $recipientId);

            // Create a message of type 'shared_post'
            $conversation->messages()->create([
                'user_id' => $sharer->id,
                'type' => 'shared_post',
                'post_id' => $postId,
                'body' => '' // Optional, you can leave empty or add a message
            ]);

            // Update conversation timestamp
            $conversation->touch();
        }

        return response()->json(['message' => 'Post shared successfully as messages'], 200);
    }
}
