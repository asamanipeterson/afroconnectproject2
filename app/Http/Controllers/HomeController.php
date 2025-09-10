<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use App\Models\PostReport;
use App\Models\Report;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function userHomepage()
    {
        $user = Auth::user();
        $posts = Post::with([
            'user',
            'likes',
            'comments.user'
        ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('post_group_id');

        $postGroups = $posts;

        // Initialize stories and suggestedUsers as an empty collection for guests
        $stories = collect();
        $suggestedUsers = collect();


        if ($user) {
            $followingIds = $user->following()->pluck('users.id');
            $visibleUserIds = $followingIds->push($user->id);

            $stories = Story::with('user')
                ->active()
                ->whereIn('user_id', $visibleUserIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('user_id');

            // --- THIS IS THE NEW CODE BLOCK ---
            $suggestedUsers = User::where('id', '!=', $user->id)
                ->whereNotIn('id', $followingIds)
                ->where('user_type', 'user') // <-- Added this line to filter by user_type
                ->inRandomOrder()
                ->limit(5)
                ->get();
        }

        return view('welcome', [
            'user'           => $user,
            'postGroups'     => $postGroups,
            'stories'        => $stories,
            'suggestedUsers' => $suggestedUsers,
        ]);
    }

    public function adminDashboard()
    {
        $postsPerDay = [];
        $usersPerDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $postsPerDay[] = ['date' => $date, 'count' => Post::whereDate('created_at', $date)->count()];
            $usersPerDay[] = ['date' => $date, 'count' => User::whereDate('created_at', $date)->count()];
        }

        return view('Admin.dashboard', [
            'totalUsers'    => User::count(),
            'totalPosts'    => Post::count(),
            'postsPerDay'   => $postsPerDay,
            'usersPerDay'   => $usersPerDay,
            'likesCount'    => Like::count(),
            'commentsCount' => Comment::count(),
            'reportsCount'  => Report::count(),
            'postsreport' => PostReport::count(),
            'users'         => User::withCount(['followers', 'reports'])->where('id', '!=', Auth::id())->get(),
        ]);
    }
}
