<!DOCTYPE html>
<html>
<head>
    @include('layouts.head')
    <style>
        .container { padding: 20px; }
        #video-container { margin-top: 20px; }
        #local-player { border: 1px solid #ddd; border-radius: 8px; }
        .btn-danger { margin-top: 10px; background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 4px; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    @include('layouts.sidebar') <!-- Assuming your sidebar is in a partial -->
    <div class="container">
        <h2>{{ $stream->title }}</h2>
        <p>By @{{ $stream->user->username }}</p>
        <div id="video-container">
            <div id="local-player" style="width: 640px; height: 480px;"></div>
            @if(auth()->check() && auth()->id() === $stream->user_id)
                <button id="stop-stream" class="btn btn-danger">Stop Stream</button>
            @endif
        </div>
    </div>

    <script src="https://cdn.agora.io/sdk/web/AgoraRTC-4.20.1.js"></script>
    <script>
        const client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
        const appId = '{{ env('AGORA_APP_ID') }}';
        const channel = '{{ $stream->channel_name }}';
        const token = '{{ $token }}';
        const uid = {{ auth()->id() ?? 0 }};

        async function joinStream() {
            await client.join(appId, channel, token, uid);

            @if(auth()->check() && auth()->id() === $stream->user_id)
                // Broadcaster logic
                const localVideoTrack = await AgoraRTC.createCameraVideoTrack();
                const localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                localVideoTrack.play('local-player');
                await client.publish([localVideoTrack, localAudioTrack]);
            @else
                // Viewer logic
                client.on('user-published', async (user, mediaType) => {
                    await client.subscribe(user, mediaType);
                    if (mediaType === 'video') {
                        user.videoTrack.play('local-player');
                    }
                    if (mediaType === 'audio') {
                        user.audioTrack.play();
                    }
                });
            @endif
        }

        @if(auth()->check() && auth()->id() === $stream->user_id)
            document.getElementById('stop-stream').addEventListener('click', async () => {
                await fetch('{{ route('live.stop', $stream) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                }).then(() => window.location.href = '{{ route('live') }}');
            });
        @endif

        joinStream();
    </script>
</body>
</html>
