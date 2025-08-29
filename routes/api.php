<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Conversation;
use App\Models\Message;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Check for new messages
Route::post('/check-new-messages', function (Request $request) {
    $user = auth()->user();
    $lastCheck = $request->input('last_check');
    $conversationTimestamps = $request->input('conversations', []);

    $updates = [];
    $hasNewMessages = false;

    // Get all conversations for the user
    $conversations = Conversation::whereHas('participants', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->with(['messages' => function ($query) use ($lastCheck) {
        $query->where('created_at', '>', date('Y-m-d H:i:s', $lastCheck))
            ->orderBy('created_at', 'desc');
    }, 'participants'])->get();

    foreach ($conversations as $conversation) {
        $latestMessage = $conversation->messages->first();

        if ($latestMessage) {
            $hasNewMessages = true;

            // Check if this conversation is already in the sidebar
            $conversationInSidebar = isset($conversationTimestamps[$conversation->id]);
            $isNewer = $conversationInSidebar ?
                $latestMessage->created_at->timestamp > $conversationTimestamps[$conversation->id] : true;

            if ($isNewer) {
                $otherUser = $conversation->participants->where('id', '!=', $user->id)->first();

                $updates[] = [
                    'conversation_id' => $conversation->id,
                    'message_body' => $latestMessage->body,
                    'is_current_user' => $latestMessage->user_id == $user->id,
                    'other_user' => $otherUser ? [
                        'id' => $otherUser->id,
                        'username' => $otherUser->username,
                        'profile_picture' => $otherUser->profile_picture
                    ] : null
                ];
            }
        }
    }

    return response()->json([
        'has_new_messages' => $hasNewMessages,
        'updates' => $updates,
        'refresh_all' => false // Set to true if you want to refresh the entire list
    ]);
});

// Get a specific conversation
Route::get('/conversations/{conversation}', function (Conversation $conversation) {
    $conversation->load(['participants', 'messages' => function ($query) {
        $query->orderBy('created_at', 'desc')->limit(1);
    }]);

    return response()->json($conversation);
});

// Get all conversations
Route::get('/conversations', function () {
    $user = auth()->user();

    $conversations = Conversation::whereHas('participants', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->with(['participants', 'messages' => function ($query) {
        $query->orderBy('created_at', 'desc')->limit(1);
    }])->orderBy('updated_at', 'desc')->get();

    return response()->json($conversations);
});
