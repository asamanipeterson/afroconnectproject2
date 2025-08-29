<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Story;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['participants', 'messages' => function ($query) {
                $query->latest();
            }])
            ->get();

        return view('conversation.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // Get all conversations for the sidebar, ordered by latest activity
        $conversations = Conversation::whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->with(['participants', 'messages' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('updated_at', 'desc') // This is important!
            ->get();

        // Get messages for the current conversation
        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get();

        return view('conversation.show', [
            'conversation' => $conversation,
            'conversations' => $conversations,
            'messages' => $messages,
            'user' => auth()->user()
        ]);
    }
    public function createOrOpen(User $user)
    {
        $currentUser = Auth::user();

        // Check if conversation already exists between these users
        $conversation = Conversation::whereHas('participants', function ($query) use ($currentUser) {
            $query->where('user_id', $currentUser->id);
        })->whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        if (!$conversation) {
            // Create new conversation
            $conversation = Conversation::create();
            $conversation->participants()->attach([$currentUser->id, $user->id]);
        }

        return redirect()->route('conversations.show', $conversation);
    }
}
