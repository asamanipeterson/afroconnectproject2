<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:1000',
            'media_files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avif,mp3,wav,ogg|max:50000',
            'text_contents.*' => 'nullable|string|max:1000',
        ]);

        // Check if at least one media file or non-empty text content is provided
        $textContents = $request->input('text_contents', []);
        $hasValidText = !empty(array_filter($textContents, fn($text) => !empty(trim($text))));
        if (empty($request->file('media_files')) && !$hasValidText) {
            throw ValidationException::withMessages([
                'general' => 'You must provide at least one media file or text content.',
            ]);
        }

        if (count($request->file('media_files', [])) > 20) {
            throw ValidationException::withMessages(['media_files' => 'You can upload up to 20 media files.']);
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
            foreach ($request->file('media_files') as $file) {
                if (!$file) continue;

                $mimeType = $file->getMimeType();
                $fileType = str_starts_with($mimeType, 'image') ? 'image' : (str_starts_with($mimeType, 'video') ? 'video' : (str_starts_with($mimeType, 'audio') ? 'audio' : 'unknown'));

                $path = $file->store('posts/media', 'public');

                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'mime_type' => $mimeType,
                    'sound_path' => null,
                    'order' => $order++,
                ]);
            }
        }

        // Handle Text Content
        if ($hasValidText) {
            foreach ($textContents as $text) {
                if (empty(trim($text))) continue;

                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => null,
                    'file_type' => 'text',
                    'mime_type' => null,
                    'text_content' => trim($text),
                    'sound_path' => null,
                    'order' => $order++,
                ]);
            }
        }

        return redirect()->route('welcome')->with('success', 'Post created successfully!');
    }

    public function show($id)
    {
        $post = Post::with(['user', 'likes', 'comments.replies.user'])->findOrFail($id);
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
            'followers' => 'required|array',
            'followers.*' => 'exists:users,id',
        ]);

        $post = Post::findOrFail($request->post_id);
        $followers = User::whereIn('id', $request->followers)->get();

        \Log::info('Starting share process for post: ' . $post->id);
        \Log::info('Followers to notify: ' . $followers->pluck('id')->implode(','));

        foreach ($followers as $follower) {
            if (Auth::user()->isFollowing($follower)) {
                \Log::info('Notifying follower: ' . $follower->id);
                $follower->notify(new \App\Notifications\PostShared($post, Auth::user()));
                \Log::info('Notification sent to follower: ' . $follower->id);
            }
        }

        \Log::info('Share process completed');
        return response()->json(['message' => 'Post shared successfully']);
    }
    public function shareWithPost(Request $request, Post $post)
    {
        // This method expects the post ID in the URL
        $request->validate([
            'followers' => 'required|array',
            'followers.*' => 'exists:users,id',
        ]);

        $followers = User::whereIn('id', $request->followers)->get();

        foreach ($followers as $follower) {
            if (Auth::user()->isFollowing($follower)) {
                $follower->notify(new \App\Notifications\PostShared($post, Auth::user()));
            }
        }

        return response()->json(['message' => 'Post shared successfully']);
    }
}
