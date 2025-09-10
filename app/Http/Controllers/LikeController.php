<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PostLikedNotification;
use App\Events\PostLiked;

class LikeController extends Controller
{
    /**
     * Toggle a like on a post.
     */
    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if the user has already liked the post
        if ($user->hasLiked($post)) {
            // Unlike the post
            $user->likes()->where('post_id', $post->id)->delete();

            $likesCount = $post->likes()->count();

            return response()->json([
                'message' => 'Post unliked successfully.',
                'likes_count' => $likesCount,
                'liked' => false,
            ]);
        } else {
            // Like the post
            $user->likes()->create(['post_id' => $post->id]);

            $likesCount = $post->likes()->count();

            // Send notification to the post owner (if not liking own post)
            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostLikedNotification($user, $post));
            }

            // Broadcast the PostLiked event to others, passing the user's ID
            broadcast(new PostLiked($post->id, $likesCount, $user->id))->toOthers();

            return response()->json([
                'message' => 'Post liked successfully.',
                'likes_count' => $likesCount,
                'liked' => true,
            ]);
        }
    }
}
