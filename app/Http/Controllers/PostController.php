<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\User;
use App\Notifications\PostShared;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // // Debug incoming request data and uploaded files


        $request->validate([
            'caption' => 'nullable|string|max:2200',
            'media_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avif|max:50000',
            'text_contents.*' => 'nullable|string|max:1000',
            'sound_files.*' => 'nullable|file|mimes:mp3,wav|max:10000',
        ]);

        if (empty($request->file('media_files')) && empty($request->input('text_contents'))) {
            throw ValidationException::withMessages([
                'general' => 'You must provide at least one media file or text content.'
            ]);
        }

        $user = Auth::user();
        $groupId = Str::uuid(); // Group identifier

        // Create main post with caption
        $post = Post::create([
            'user_id' => $user->id,
            'caption' => $request->input('caption'),
            'post_group_id' => $groupId,
        ]);

        $order = 0;

        // Handle Media Files
        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $index => $file) {
                if (!$file) continue;

                $mimeType = $file->getMimeType();
                $fileType = str_starts_with($mimeType, 'image') ? 'image' : (str_starts_with($mimeType, 'video') ? 'video' : 'unknown');

                $path = $file->store('posts/media', 'public');

                $soundPath = null;
                if ($request->hasFile("sound_files.$index")) {
                    $soundPath = $request->file("sound_files.$index")->store('posts/audio', 'public');
                }

                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'mime_type' => $mimeType,
                    'sound_path' => $soundPath,
                    'order' => $order++,
                ]);
            }
        }

        // Handle Text Content
        if ($request->input('text_contents')) {
            foreach ($request->input('text_contents') as $index => $text) {
                if (empty($text)) continue;

                $soundPath = null;
                if ($request->hasFile("sound_files.$index")) {
                    $soundPath = $request->file("sound_files.$index")->store('posts/audio', 'public');
                }

                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => null,
                    'file_type' => 'text',
                    'mime_type' => null,
                    'text_content' => $text,
                    'sound_path' => $soundPath,
                    'order' => $order++,
                ]);
            }
        }

        return redirect()->route('welcome')->with('success', 'Post created successfully!');
    }

    public function show($id)
    {
        $post = Post::with(['user', 'likes', 'comments.replies.user'])->findOrFail($id);

        // If the post belongs to a post group, fetch all related media in the same group
        $media = Post::where('post_group_id', $post->post_group_id)
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('posts.show', compact('post', 'media'));
    }
    public function report(Post $post)
    {
        return response()->json([
            'success' => true,
            'message' => 'Post reported. Thank you for your feedback.'
        ]);
    }

    public function markAsNotInterested(Post $post)
    {
        return response()->json([
            'success' => true,
            'message' => "We'll show you less content like this in the future."
        ]);
    }

    public function destroy(Post $post)
    {
        $post->media()->delete();
        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }

    public function modal($id)
    {
        $post = Post::with(['user', 'likes', 'comments.replies.user'])->findOrFail($id);

        $media = Post::where('post_group_id', $post->post_group_id)
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'html' => view('posts.show', compact('post', 'media'))->render(),
        ]);
    }

    public function share(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'follower_ids' => 'required|array',
            'follower_ids.*' => 'exists:users,id'
        ]);

        $post = Post::findOrFail($request->post_id);
        $followers = User::whereIn('id', $request->follower_ids)->get();

        foreach ($followers as $follower) {
            // Ensure the user is actually followed by the authenticated user
            if (Auth::user()->isFollowing($follower)) {
                $follower->notify(new PostShared($post, Auth::user()));
            }
        }

        return response()->json(['success' => true]);
    }
}
