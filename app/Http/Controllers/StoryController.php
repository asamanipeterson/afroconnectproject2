<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StoryController extends Controller
{

    public function fetchUserStories(User $user)
    {
        // The accessor 'media_url' should now be automatically appended due to $appends in Story model
        $stories = $user->stories()
            ->active()
            ->with('user') // Eager load the user relationship for better performance
            ->orderBy('created_at', 'asc') // Ensure stories are ordered for consistent viewing
            ->get();

        // The media_url accessor will be included when converted to JSON
        return response()->json($stories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'media' => 'nullable|array',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:25600',
            'text_content' => 'nullable|array',
            'text_content.*' => 'nullable|string|max:500',
            'caption' => 'nullable|array',
            'caption.*' => 'nullable|string|max:200',
            'background' => 'nullable|array',
            'background.*' => 'nullable|string|max:7',
        ]);

        $user = Auth::user();
        $now = now();
        $expiresAt = $now->copy()->addHours(24);

        $mediaFiles = $request->file('media') ?? [];
        $texts = $request->input('text_content') ?? [];
        $captions = $request->input('caption') ?? [];
        $backgrounds = $request->input('background') ?? [];

        $max = max(count($mediaFiles), count($texts));

        for ($i = 0; $i < $max; $i++) {
            $media = $mediaFiles[$i] ?? null;
            $text = $texts[$i] ?? null;
            $caption = $captions[$i] ?? null;
            $background = $backgrounds[$i] ?? '#3B82F6';

            if ($media) {
                $path = $media->store('stories', 'public');
                $extension = strtolower($media->getClientOriginalExtension());
                $storyType = in_array($extension, ['mp4', 'mov']) ? 'video' : 'image';

                Story::create([
                    'user_id' => $user->id,
                    'story_type' => $storyType,
                    'media_path' => $path,
                    'text_content' => null, // Media stories don't have text_content
                    'background' => null, // Media stories don't have background color
                    'caption' => $caption,
                    'expires_at' => $expiresAt,
                ]);
            }

            if (!empty(trim($text))) {
                Story::create([
                    'user_id' => $user->id,
                    'story_type' => 'text',
                    'media_path' => null, // Text stories don't have media path
                    'text_content' => $text,
                    'background' => $background,
                    'caption' => $caption,
                    'expires_at' => $expiresAt,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Stories created successfully!');
    }

    public function index()
    {
        $user = Auth::user();

        // Get user IDs of people who either follow me or I follow
        $followerIds = $user->followers()->pluck('id');
        $followingIds = $user->followings()->pluck('id');

        $visibleUserIds = $followerIds
            ->merge($followingIds)
            ->push($user->id)
            ->unique();

        // Get active stories
        // Group stories by user_id
        $stories = Story::with('user')
            ->whereIn('user_id', $visibleUserIds)
            ->where('expires_at', '>', now())
            ->latest() // Order by latest story for better grouping display
            ->get()
            ->groupBy('user_id');

        return view('welcome', compact('stories')); // Assuming your main view is 'welcome.blade.php'
    }

    public function showUserStories(User $user)
    {
        $stories = $user->stories()->active()->orderBy('created_at')->get();

        return view('stories.modal', compact('user', 'stories'));
    }
}
