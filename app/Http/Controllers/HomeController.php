<?php

namespace App\Http\Controllers;
<<<<<<< HEAD
=======

>>>>>>> c0d373d (admin side working left small touches)
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
<<<<<<< HEAD
//use App\Models\Share; // ✅ Only if you have a shares table/model
=======
>>>>>>> c0d373d (admin side working left small touches)
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function homepage()
    {
        $user = Auth::user();
        $user_type = $user->user_type;

        if ($user_type === 'admin') {
            // Posts per day
            $postsPerDay = [];
            $usersPerDay = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i)->toDateString();

                $postsCount = Post::whereDate('created_at', $date)->count();
                $usersCount = User::whereDate('created_at', $date)->count();

                $postsPerDay[] = ['date' => $date, 'count' => $postsCount];
                $usersPerDay[] = ['date' => $date, 'count' => $usersCount];
            }

            // ✅ Engagement counts from DB
            $likesCount = Like::count();
            $commentsCount = Comment::count();
            //$sharesCount = class_exists(Share::class) ? Share::count() : 0;

            return view('Admin.dashboard', [
                'totalUsers'   => User::count(),
                'totalPosts'   => Post::count(),
                'postsPerDay'  => $postsPerDay,
                'usersPerDay'  => $usersPerDay,
                'likesCount'   => $likesCount,
<<<<<<< HEAD
                'commentsCount'=> $commentsCount,
=======
                'commentsCount' => $commentsCount,
>>>>>>> c0d373d (admin side working left small touches)
                //'sharesCount'  => $sharesCount,
            ]);
        }

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
            'user'       => $user,
            'postGroups' => $postGroups,
            'stories'    => $stories,
<<<<<<< HEAD
=======
        ]);
    }

    public function usertable()
    {
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $postsPerDay = [];
        $usersPerDay = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();

            $postsCount = Post::whereDate('created_at', $date)->count();
            $usersCount = User::whereDate('created_at', $date)->count();

            $postsPerDay[] = ['date' => $date, 'count' => $postsCount];
            $usersPerDay[] = ['date' => $date, 'count' => $usersCount];
        }

        // ✅ Engagement counts from DB
        $likesCount = Like::count();
        $commentsCount = Comment::count();
        return view('adminPages.usertable', [
            'totalUsers' => $totalUsers,
            'totalPosts' => $totalPosts,
            'postsPerDay'  => $postsPerDay,
            'usersPerDay'  => $usersPerDay,
            'likesCount'   => $likesCount,
            'commentsCount' => $commentsCount,
>>>>>>> c0d373d (admin side working left small touches)
        ]);
    }
}
