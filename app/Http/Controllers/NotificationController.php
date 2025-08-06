<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->get();

        // Fetch users the authenticated user follows
        $followedUsers = Auth::user()->following()->pluck('followed_id');

        // Fetch active stories from followed users (last 24 hours)
        $stories = Story::whereIn('user_id', $followedUsers)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->active() // Custom scope
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');

        return view('Notification.index', compact('notifications', 'stories'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('status', 'Notification marked as read');
    }
}
