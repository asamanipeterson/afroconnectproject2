<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewCommentPosted;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate(15);

        return view('comments.index', compact('post', 'comments'));
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $comment->load('user');

        // 🔥 Broadcast to others in real-time
        broadcast(new NewCommentPosted($comment))->toOthers();

        return response()->json([
            'message' => 'Comment posted successfully!',
            'comment' => $comment,
            'user' => [
                'id' => $comment->user->id,
                'username' => $comment->user->username,
            ]
        ], 201);
    }

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
