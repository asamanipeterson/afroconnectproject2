<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FollowNotification;

class FollowController extends Controller
{
    public function toggleFollow(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'You must be logged in.'], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in to follow users.');
        }

        if ($currentUser->id === $targetUser->id) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'You cannot follow yourself.'], 403);
            }
            return redirect()->back()->with('error', 'You cannot follow yourself.');
        }

        if ($currentUser->isFollowing($targetUser)) {
            // Unfollow
            $currentUser->following()->detach($targetUser->id);
            $status = 'unfollowed';
            $message = 'You have unfollowed the user.';
        } else {
            // Follow
            $currentUser->following()->attach($targetUser->id);
            // Send follow notification
            $targetUser->notify(new FollowNotification($currentUser));
            $status = 'followed';
            $message = 'You are now following ' . $targetUser->username;
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => $status, 'success' => true]);
        }

        return redirect()->back()->with('success', $message);
    }
}
