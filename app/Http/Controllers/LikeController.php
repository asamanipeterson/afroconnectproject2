<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import the Log facade
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

            // Broadcast the PostLiked event with the full user object
            broadcast(new PostLiked($post->id, $likesCount, $user))->toOthers();

            // Log successful unlike for debugging
            Log::info('Post unliked successfully.', ['post_id' => $post->id, 'user_id' => $user->id]);

            return response()->json([
                'message' => 'Post unliked successfully.',
                'likes_count' => $likesCount,
                'liked' => false,
            ]);
        } else {
            // Like the post
            $user->likes()->create(['post_id' => $post->id]);

            $likesCount = $post->likes()->count();

            // CRITICAL FIX: Add a check to ensure the post owner exists before trying to notify
            if ($post->user_id !== $user->id && $post->user) {
                try {
                    $post->user->notify(new PostLikedNotification($user, $post));
                    Log::info('Post liked notification sent.', ['post_id' => $post->id, 'user_id' => $user->id]);
                } catch (\Exception $e) {
                    Log::error('Failed to send PostLikedNotification: ' . $e->getMessage(), ['post_id' => $post->id, 'user_id' => $user->id]);
                    // Continue execution even if notification fails
                }
            }

            // Broadcast the PostLiked event with the full user object
            broadcast(new PostLiked($post->id, $likesCount, $user))->toOthers();

            // Log successful like for debugging
            Log::info('Post liked successfully.', ['post_id' => $post->id, 'user_id' => $user->id]);

            return response()->json([
                'message' => 'Post liked successfully.',
                'likes_count' => $likesCount,
                'liked' => true,
            ]);
        }
    }
}
