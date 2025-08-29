<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FollowNotification;
use Illuminate\Support\Facades\Log;

class FollowController extends Controller
{
    // public function toggleFollow(Request $request, $id)
    // {
    //     $targetUser = User::findOrFail($id);
    //     $currentUser = Auth::user();

    //     if (!$currentUser) {
    //         if ($request->expectsJson()) {
    //             return response()->json(['status' => 'error', 'message' => 'You must be logged in.'], 401);
    //         }
    //         return redirect()->route('login')->with('error', 'You must be logged in to follow users.');
    //     }

    //     if ($currentUser->id === $targetUser->id) {
    //         if ($request->expectsJson()) {
    //             return response()->json(['status' => 'error', 'message' => 'You cannot follow yourself.'], 403);
    //         }
    //         return redirect()->back()->with('error', 'You cannot follow yourself.');
    //     }

    //     $isFollowing = $currentUser->isFollowing($targetUser);

    //     if ($isFollowing) {
    //         $currentUser->following()->detach($targetUser->id);
    //         $status = 'unfollowed';
    //         $message = 'You have unfollowed the user.';
    //         $isFollowing = false;
    //     } else {
    //         $currentUser->following()->attach($targetUser->id);
    //         $targetUser->notify(new FollowNotification($currentUser));
    //         $status = 'followed';
    //         $message = 'You are now following ' . $targetUser->username;
    //         $isFollowing = true;
    //     }

    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'status' => $status,
    //             'success' => true,
    //             'isFollowing' => $isFollowing
    //         ]);
    //     }

    //     return redirect()->back()->with('success', $message);
    // }


    public function toggleFollow(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser) {
            return response()->json(['status' => 'error', 'message' => 'You must be logged in.'], 401);
        }

        if ($currentUser->id === $targetUser->id) {
            return response()->json(['status' => 'error', 'message' => 'You cannot follow yourself.'], 403);
        }

        $isFollowing = $currentUser->isFollowing($targetUser);
        \Log::info('toggleFollow: User ' . $currentUser->id . ' isFollowing User ' . $targetUser->id . ': ' . ($isFollowing ? 'true' : 'false'));

        if ($isFollowing) {
            $currentUser->following()->detach($targetUser->id);
            $status = 'unfollowed';
            $message = 'You have unfollowed the user.';
            $isFollowing = false;
        } else {
            $currentUser->following()->attach($targetUser->id);
            $targetUser->notify(new FollowNotification($currentUser));
            $status = 'followed';
            $message = 'You are now following ' . $targetUser->username;
            $isFollowing = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'success' => true,
                'isFollowing' => $isFollowing
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
    public function checkFollowStatus($userId)
    {
        $user = Auth::user();
        $isFollowing = $user->following()->where('followed_id', $userId)->exists();
        return response()->json(['success' => true, 'isFollowing' => $isFollowing]);
    }
}
