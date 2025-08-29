<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function homepage()
    {
        // Get the authenticated user. This will be null if no user is logged in.
        $user = Auth::user();

        // Check if a user is authenticated AND if they are an admin
        if ($user && $user->user_type === 'admin') {
            // Posts per day for the last 7 days
            $postsPerDay = [];
            $usersPerDay = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i)->toDateString();
                $postsCount = Post::whereDate('created_at', $date)->count();
                $usersCount = User::whereDate('created_at', $date)->count();
                $postsPerDay[] = ['date' => $date, 'count' => $postsCount];
                $usersPerDay[] = ['date' => $date, 'count' => $usersCount];
            }

            // Engagement counts
            $likesCount = Like::count();
            $commentsCount = Comment::count();

            // Get all users with follower and report counts, excluding the current admin
            $users = User::withCount(['followers', 'reports'])
                ->where('id', '!=', $user->id)
                ->get();

            return view('Admin.dashboard', [
                'totalUsers'    => User::count(),
                'totalPosts'    => Post::count(),
                'postsPerDay'   => $postsPerDay,
                'usersPerDay'   => $usersPerDay,
                'likesCount'    => $likesCount,
                'commentsCount' => $commentsCount,
                'users'         => $users,
            ]);
        }

        // Logic for regular authenticated users and guests
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

        // Initialize stories as an empty collection to prevent errors for guests
        $stories = collect();

        // Fetch stories only if a user is authenticated
        if ($user) {
            $followingIds = $user->following()->pluck('users.id');
            // Include the current user's own ID to show their stories too
            $visibleUserIds = $followingIds->push($user->id);

            $stories = Story::with('user')
                ->active()
                ->whereIn('user_id', $visibleUserIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('user_id');
        }

        return view('welcome', [
            'user'       => $user,
            'postGroups' => $postGroups,
            'stories'    => $stories,
        ]);
    }
}
