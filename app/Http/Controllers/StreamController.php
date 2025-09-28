<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Services\AgoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StreamController extends Controller
{
    protected $agoraService;

    public function __construct(AgoraService $agoraService)
    {
        $this->agoraService = $agoraService;
    }

    public function index()
    {
        $streams = Stream::where('status', 'live')->with('user')->get();
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
        $this->authorize('update', $stream);
        $stream->update(['status' => 'offline']);
        return redirect()->route('live');
    }
}
