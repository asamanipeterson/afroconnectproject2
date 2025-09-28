<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Story;
use App\Models\SharedPost;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    /**
     * Display a listing of the user's conversations and related data.
     */
    public function index()
    {
        // Retrieve all conversations for the main view, ordered by most recent activity
        $conversations = Auth::user()->conversations()
            ->with(['participants', 'messages' => function ($query) {
                $query->latest()->limit(1); // ONLY GET THE LATEST MESSAGE
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Retrieve all posts shared with the authenticated user
        $sharedPosts = SharedPost::where('recipient_id', Auth::id())
            ->with(['sharer', 'post.user'])
            ->latest()
            ->get();

        // Determine the "active" conversation, which is the latest one
        $activeConversation = $conversations->first(); // Get the first conversation from the ordered list

        // Initialize unread message count
        $unreadMessageCount = 0;

        // Count unread messages if an active conversation exists
        if ($activeConversation) {
            $unreadMessageCount = $activeConversation->messages()->whereNull('read_at')->count();
        }

        $stories = collect();

        // Fetch stories only if a user is authenticated
        $user = Auth::user();
        if ($user) {
            $followingIds = $user->following()->pluck('users.id');
            $visibleUserIds = $followingIds->push($user->id);

            $stories = Story::with('user')
                ->active()
                ->whereIn('user_id', $visibleUserIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('user_id');
        }

        // Pass both conversations and shared posts to the view
        return view('conversation.index', compact('conversations', 'activeConversation', 'unreadMessageCount', 'stories', 'sharedPosts'));
    }

    /**
     * Display the specified conversation and all related messages.
     */
    public function show(Conversation $conversation)
    {
        // Get all conversations for the sidebar, ordered by latest activity
        $conversations = Conversation::whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->with(['participants', 'messages' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('updated_at', 'desc') // Order by most recently updated
            ->get();

        // Retrieve all posts shared with the authenticated user
        // $sharedPosts = SharedPost::where('recipient_id', Auth::id())
        //     ->with(['sharer', 'post.user'])
        //     ->latest()
        //     ->get();

        // Get messages for the current conversation
        $messages = $conversation->messages()->with('user')->orderBy('created_at', 'asc')->get();

        return view('conversation.show', [
            'activeConversation' => $conversation,
            'conversations' => $conversations,
            'messages' => $messages,
            'user' => auth()->user()
        ]);
    }

    /**
     * Create a new conversation or open an existing one.
     */
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
