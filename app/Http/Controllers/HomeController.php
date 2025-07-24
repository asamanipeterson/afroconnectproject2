<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function homepage()
    {
        $user = Auth::user();

        // Get posts with grouped media
        $posts = Post::with([
            'user',
            'likes',
            'comments.user'
        ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('post_group_id');

        $postGroups = $posts;

        // ✅ Get IDs of users the authenticated user follows
        $followingIds = $user->following()->pluck('users.id');

        // ✅ Include own stories too
        $visibleUserIds = $followingIds->push($user->id);

        // ✅ Fetch active stories for current user + followed users
        $stories = Story::with('user')
            ->active()
            ->whereIn('user_id', $visibleUserIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');

        return view('welcome', [
            'user' => $user,
            'postGroups' => $postGroups,
            'stories' => $stories, // ✅ This fixes the error
        ]);
    }
}
