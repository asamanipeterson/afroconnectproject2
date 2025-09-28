<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewCommentPosted;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments for a post.
     * This is likely for a separate comment page.
     */
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate(15);

        return view('comments.index', compact('post', 'comments'));
    }

    /**
     * Store a new comment for a post.
     */
    public function store(Request $request, Post $post)
    {
        // 1. Validate the incoming request data.
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // 2. Create the comment in the database.
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // 3. Eager load the user relationship to make it available for the response and event.
        $comment->load('user');

        // FIX: Correctly pass data to match the event constructor
        broadcast(new NewCommentPosted(
            $comment->id,
            $comment->content,
            [ // This should be an array with user data
                'id' => $comment->user->id,
                'username' => $comment->user->username
            ],
            $comment->post_id // This should be the post ID
        ))->toOthers();

        // 4. Return a successful JSON response to the JavaScript.
        return response()->json([
            'message' => 'Comment posted successfully!',
            'comment' => [ // Updated structure to match what JS expects
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => [
                    'id' => $comment->user->id,
                    'username' => $comment->user->username,
                ]
            ],
            'comments_count' => $post->comments()->count() // Added comments count
        ], 201);
    }

    /**
     * Store a new reply for a comment.
     */
    public function replyStore(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $reply = $comment->replies()->create([
            'user_id' => Auth::id(),
            'post_id' => $comment->post_id,
            'content' => $request->content,
            'parent_id' => $comment->id,
        ]);

        $reply->load('user');

        return response()->json([
            'message' => 'Reply posted successfully!',
            'reply' => $reply,
            'user' => [
                'id' => $reply->user->id,
                'username' => $reply->user->username,
            ]
        ], 201);
    }
}
