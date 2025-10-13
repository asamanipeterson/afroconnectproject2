<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfileController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a user's profile with POSTS, SAVED, TAGGED tabs
     */
    public function index(User $user)
    {
        $is_own_profile = auth()->id() === $user->id;

        // POSTS tab - grouped by post_group_id
        $groupedPosts = $user->posts()
            ->with(['media', 'likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('post_group_id');

        // SAVED tab (only for own profile)
        $savedPosts = $is_own_profile
            ? $user->bookmarkedPosts()->with(['media', 'likes', 'comments'])->latest()->get()
            : collect();

        // TAGGED tab
        $taggedPosts = $user->taggedPosts()->with(['media', 'likes', 'comments'])->latest()->get();

        // Counts
        $postsCount = $groupedPosts->count();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // Follow status
        $isFollowing = false;
        if (Auth::check() && !$is_own_profile) {
            $isFollowing = Auth::user()->following()->where('followed_id', $user->id)->exists();
        }

        return view('profile.index', compact(
            'user',
            'groupedPosts',
            'savedPosts',
            'taggedPosts',
            'is_own_profile',
            'postsCount',
            'followersCount',
            'followingCount',
            'isFollowing'
        ));
    }

    /**
     * Update profile details (profile picture, cover picture, bio, website)
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bio' => 'nullable|string|max:255',
            'website' => 'nullable|url',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) Storage::disk('public')->delete($user->profile_picture);
            $user->profile_picture = $request->file('profile_picture')->store('profiles', 'public');
        }

        if ($request->hasFile('cover_picture')) {
            if ($user->cover_picture) Storage::disk('public')->delete($user->cover_picture);
            $user->cover_picture = $request->file('cover_picture')->store('covers', 'public');
        }

        $user->bio = $request->input('bio');
        $user->website = $request->input('website');
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show bookmarked posts (only for own profile)
     */
    public function bookmarks(User $user)
    {
        if (Auth::id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $bookmarkedPosts = $user->bookmarkedPosts()->with('user', 'media')->latest()->get();

        return view('profile.bookmarks', compact('user', 'bookmarkedPosts'));
    }
}
