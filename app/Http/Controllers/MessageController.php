<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\MessageSent;
use App\Events\MessageSentToUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'nullable|string',
            // Validation for audio, allowing various common audio types
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,mp4,m4a,flac,webm|max:10240', // 10MB
            // Validation for image, allowing common image types
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB
            // Validation for video, allowing common video types
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200' // 50MB
        ]);

        $messageData = [
            'user_id' => auth()->id(),
        ];

        $type = 'text'; // Default message type

        // Check for file uploads first to determine the message type
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('images', 'public');
            $messageData['image_path'] = $imagePath;
            $type = 'image';
            $messageData['body'] = 'Image'; // Default body for media message
        } else if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $videoPath = $request->file('video')->store('videos', 'public');
            $messageData['video_path'] = $videoPath;
            $type = 'video';
            $messageData['body'] = 'Video'; // Default body for media message
        } else if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $audioPath = $request->file('audio')->store('audio_messages', 'public');
            $messageData['audio_path'] = $audioPath;
            $type = 'audio';
            $messageData['body'] = 'Audio message'; // Default body for media message
        }
        // Handle plain text message (including emojis)
        else if ($request->has('body') && !empty($request->body)) {
            $messageData['body'] = $request->body;
            $type = 'text';
        } else {
            return response()->json(['error' => 'No content to send'], 422);
        }

        $messageData['type'] = $type;
        $message = $conversation->messages()->create($messageData);

        // Update the conversation's updated_at timestamp
        $conversation->touch();

        // Return the updated conversation along with the message
        return response()->json([
            'message' => $message,
            'conversation' => $conversation
        ], 201);
    }

    // Optional: Add new methods to get the image and video files
    public function getMedia(Message $message, $type)
    {
        // Check if the user is part of the conversation
        if (!$message->conversation->participants->contains(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $filePath = null;
        $contentType = null;

        if ($type === 'image' && $message->image_path) {
            $filePath = $message->image_path;
            $contentType = Storage::disk('public')->mimeType($filePath);
            if (!str_contains($contentType, 'image/')) {
                abort(404, 'File is not an image');
            }
        } else if ($type === 'video' && $message->video_path) {
            $filePath = $message->video_path;
            $contentType = Storage::disk('public')->mimeType($filePath);
            if (!str_contains($contentType, 'video/')) {
                abort(404, 'File is not a video');
            }
        } else if ($type === 'audio' && $message->audio_path) {
            $filePath = $message->audio_path;
            $contentType = Storage::disk('public')->mimeType($filePath);
            if (!str_contains($contentType, 'audio/')) {
                abort(404, 'File is not audio');
            }
        } else {
            abort(404, 'Media not found');
        }

        $path = Storage::disk('public')->path($filePath);

        return response()->file($path, [
            'Content-Type' => $contentType,
        ]);
    }
}
