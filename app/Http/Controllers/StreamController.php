<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Services\AgoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\LiveComment;
use App\Events\LiveCommentAdded;

class StreamController extends Controller
{
    protected $agoraService;

    public function __construct()
    {
        $this->agoraService = new AgoraService();
    }
    public function index()
    {
        $streams = Stream::where('status', 'live')->get();
        \Log::debug('Fetched streams: ' . $streams->count() . ' live streams');
        return view('live.index', compact('streams'));
    }

    public function show(Stream $stream)
    {
        $token = $this->agoraService->generateToken($stream->channel_name, auth()->id() ?? 0, 1); // Subscriber role
        return view('live.show', compact('stream', 'token'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $channelName = Str::random(10); // Generate unique channel name
        $stream = Stream::create([
            'user_id' => auth()->id(),
            'channel_name' => $channelName,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'live',
        ]);

        $token = $this->agoraService->generateToken($channelName, auth()->id(), 2); // Publisher role

        return redirect()->route('live.show', $stream)->with('token', $token);
    }
    public function stop(Stream $stream)
    {
        \Log::debug('Stopping stream ID: ' . $stream->id . ', User ID: ' . auth()->id() . ', Current Status: ' . $stream->status);
        $stream->update(['status' => 'offline']);
        \Log::debug('Stream ID: ' . $stream->id . ' updated to status: ' . $stream->fresh()->status);
        return redirect()->route('live');
    }

    public function storeLiveComment(Request $request, Stream $stream)
    {
        $request->validate(['content' => 'required|string|max:255']);
        $comment = LiveComment::create([
            'stream_id' => $stream->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        broadcast(new LiveCommentAdded($comment))->toOthers();
        return response()->json(['status' => 'success']);
    }
}
