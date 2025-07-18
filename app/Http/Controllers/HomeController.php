<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function homepage()
    {
        // Fetch posts with related user, likes, and comments
        $posts = Post::with([
            'user',
            'likes',
            'comments.user'
        ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('post_group_id'); // Group posts by group_id to get multiple media under one post

        // You can keep the variable name as $postGroups for Blade compatibility
        $postGroups = $posts;

        return view('welcome', [
            'user' => Auth::user(),
            'postGroups' => $postGroups
        ]);
    }
}
