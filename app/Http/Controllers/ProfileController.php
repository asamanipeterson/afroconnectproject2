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
    public function index(User $user)
    {
        // Group user's posts by post_group_id (like Instagram albums)
        $groupedPosts = Post::with('media')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('post_group_id');

        $postsCount = $groupedPosts->count();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // Check if viewer is authenticated and already follows the user
        $isFollowing = false;
        if (Auth::check() && Auth::id() !== $user->id) {
            $isFollowing = Auth::user()->following()->where('followed_id', $user->id)->exists();
        }

        return view('profile.index', compact(
            'user',
            'groupedPosts',
            'postsCount',
            'followersCount',
            'followingCount',
            'isFollowing'
        ));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user); // Optional: Add policy to restrict edit to owner

        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg',
            'cover_picture' => 'nullable|image|mimes:jpeg,png,jpg',
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

        return redirect()->back()->with('success', 'Profile updated.');
    }

    // public function follow(User $user)
    // {
    //     if (Auth::check() && Auth::id() !== $user->id) {
    //         Auth::user()->following()->syncWithoutDetaching([$user->id]);
    //         return back()->with('success', 'Followed user.');
    //     }
    //     return back()->with('error', 'Cannot follow user.');
    // }

    // public function unfollow(User $user)
    // {
    //     if (Auth::check() && Auth::id() !== $user->id) {
    //         Auth::user()->following()->detach($user->id);
    //         return back()->with('success', 'Unfollowed user.');
    //     }
    //     return back()->with('error', 'Cannot unfollow user.');
    // }
}
