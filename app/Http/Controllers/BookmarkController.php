<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
   public function toggle(Post $post)
{
    $user = Auth::user();

    if ($user->bookmarkedPosts()->where('post_id', $post->id)->exists()) {
        $user->bookmarkedPosts()->detach($post->id);
        return redirect()->back()->with('success', 'Bookmark removed successfully!');
    } else {
        $user->bookmarkedPosts()->attach($post->id);
        return redirect()->back()->with('success', 'Post bookmarked successfully!');
    }
}

}
