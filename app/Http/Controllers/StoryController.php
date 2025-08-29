<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function fetchUserStories(User $user)
    {
        $stories = $user->stories()
            ->active()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($stories);
    }

    public function fetchAllActiveStories(Request $request)
    {
        $clickedUserId = $request->input('start_user_id');

        $users = User::whereHas('stories', function ($query) {
            $query->where('expires_at', '>', now());
        })
            ->with(['stories' => function ($query) {
                $query->where('expires_at', '>', now())->orderBy('created_at', 'asc');
            }])
            ->get()
            ->sortBy(function ($user) use ($clickedUserId) {
                return $user->id == $clickedUserId ? 0 : 1;
            })
            ->values();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'media' => 'nullable|array|max:10',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:25600',
            'captions' => 'nullable|array|max:10',
            'captions.*' => 'nullable|string|max:200',
            'text_content' => 'nullable|string|max:500',
            'background' => 'nullable|array',
            'background.*' => 'nullable|string|max:7',
        ]);

        $user = Auth::user();
        $now = now();
        $expiresAt = $now->copy()->addHours(24);

        $mediaFiles = $request->file('media') ?? [];
        $captions = $request->input('captions') ?? [];
        $text = $request->input('text_content');
        $backgrounds = $request->input('background') ?? [];

        // Handle media files with individual captions
        foreach ($mediaFiles as $index => $media) {
            $path = $media->store('stories', 'public');
            $extension = strtolower($media->getClientOriginalExtension());
            $storyType = in_array($extension, ['mp4', 'mov']) ? 'video' : 'image';

            Story::create([
                'user_id' => $user->id,
                'story_type' => $storyType,
                'media_path' => $path,
                'text_content' => null,
                'background' => null,
                'caption' => $captions[$index] ?? null,
                'expires_at' => $expiresAt,
            ]);
        }

        // Handle text story if provided
        if (!empty(trim($text))) {
            Story::create([
                'user_id' => $user->id,
                'story_type' => 'text',
                'media_path' => null,
                'text_content' => $text,
                'background' => $backgrounds[0] ?? '#3B82F6',
                'caption' => $captions[0] ?? null,
                'expires_at' => $expiresAt,
            ]);
        }

        return redirect()->back()->with('success', 'Stories created successfully!');
    }

    public function index()
    {
        $user = Auth::user();

        $followerIds = $user->followers()->pluck('id');
        $followingIds = $user->followings()->pluck('id');

        $visibleUserIds = $followerIds
            ->merge($followingIds)
            ->push($user->id)
            ->unique();

        $stories = Story::with('user')
            ->whereIn('user_id', $visibleUserIds)
            ->where('expires_at', '>', now())
            ->latest()
            ->get()
            ->groupBy('user_id');

        return view('welcome', compact('stories'));
    }

    public function showUserStories(User $user)
    {
        $stories = $user->stories()->active()->orderBy('created_at')->get();

        return view('stories.modal', compact('user', 'stories'));
    }
}
