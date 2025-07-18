<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:25600',
            'text_content.*' => 'nullable|string|max:500',
            'caption.*' => 'nullable|string|max:200',
            'background.*' => 'nullable|string|max:7',
        ]);

        $user = Auth::user();
        $now = now();
        $expiresAt = $now->copy()->addHours(24);

        // ✅ Handle media stories (image/video)
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('stories', 'public');
                $extension = strtolower($file->getClientOriginalExtension());
                $storyType = in_array($extension, ['mp4', 'mov']) ? 'video' : 'image';

                Story::create([
                    'user_id' => $user->id,
                    'story_type' => $storyType,
                    'media_path' => $path,
                    'text_content' => null,
                    'background' => null,
                    'caption' => $request->caption[$index] ?? null,
                    'expires_at' => $expiresAt,
                ]);
            }
        }

        // ✅ Handle text-only stories
        if ($request->text_content && !empty(array_filter($request->text_content))) {
            foreach ($request->text_content as $index => $text) {
                if (!empty(trim($text))) {
                    Story::create([
                        'user_id' => $user->id,
                        'story_type' => 'text',
                        'media_path' => null,
                        'text_content' => $text,
                        'background' => $request->background[$index] ?? '#3B82F6',
                        'caption' => $request->caption[$index] ?? null,
                        'expires_at' => $expiresAt,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Stories created successfully!');
    }

    public function index()
    {
        $user = Auth::user();

        // Get user IDs of people you follow or who follow you
        $visibleUserIds = $user->followers()->pluck('id')
            ->merge($user->followings()->pluck('id'))
            ->unique()
            ->push($user->id); // include your own stories

        // Get their active stories
        $stories = Story::with('user')
            ->whereIn('user_id', $visibleUserIds)
            ->where('expires_at', '>', now())
            ->latest()
            ->get();

        return view('stories.index', compact('stories'));
    }
}
