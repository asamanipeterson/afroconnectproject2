<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FollowNotification;

class FollowController extends Controller
{
    public function toggleFollow($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($currentUser->id === $targetUser->id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot follow yourself.'], 403);
        }

        if ($currentUser->isFollowing($targetUser)) {
            // Unfollow
            $currentUser->following()->detach($targetUser->id);
            return response()->json(['status' => 'unfollowed']);
        } else {
            // Follow
            $currentUser->following()->attach($targetUser->id);

            // Send follow notification
            $targetUser->notify(new FollowNotification($currentUser));

            return response()->json(['status' => 'followed']);
        }
    }
}
