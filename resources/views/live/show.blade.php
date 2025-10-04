
@extends('layouts.app')

<style>
        .container {
            padding: 20px;
         }
        #video-container {
            margin-top: 20px;
         }
        #local-player {
            border: 1px solid #ddd; border-radius: 8px;
        }
        .btn-danger {
            margin-top: 10px;
            background: #dc3545;
            color: white; padding: 10px 20px;
            border: none; border-radius: 4px;
         }
        .btn-danger:hover {
            background: #c82333;
        }
</style>

@section('content')

<div class="container">
        <h2>{{ $stream->title }}</h2>
        <p>By {{ $stream->user->username }}</p>
        <div id="video-container">
            <div id="local-player" style="width: 640px; height: 480px;"></div>
            @if(auth()->check() && auth()->id() === $stream->user_id)
                <button id="stop-stream" class="btn btn-danger">Stop Stream</button>
            @else
                <p style="color: red;">Stop button hidden: Auth={{ auth()->check() ? 'true' : 'false' }}, User ID={{ auth()->id() ?? 'null' }}, Stream User ID={{ $stream->user_id ?? 'null' }}</p>
            @endif
        </div>
        <div id="status" style="margin-top: 10px; color: red;"></div>
    </div>

    <script>
        window.addEventListener('load', () => {
            if (typeof AgoraRTC === 'undefined') {
                document.getElementById('status').textContent = 'Error: Agora SDK failed to load.';
                console.error('AgoraRTC not loaded');
            } else {
                const client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
                const appId = '{{ config('laravel-agora.app_id') }}';
                const channel = '{{ $stream->channel_name }}';
                const token = '{{ $token }}';
                const uid = {{ auth()->id() ?? 0 }};
                const isPublisher = {{ auth()->check() && auth()->id() === $stream->user_id ? 'true' : 'false' }};

                console.log('Joining stream:', { appId, channel, token, uid });
                console.log('Auth Check:', { isAuthenticated: {{ auth()->check() ? 'true' : 'false' }}, userId: {{ auth()->id() ?? 'null' }}, streamUserId: {{ $stream->user_id ?? 'null' }} });

                async function joinStream() {
                    const statusDiv = document.getElementById('status');
                    try {
                        await client.join(appId, channel, token, uid);
                        console.log('Joined channel successfully');
                        statusDiv.textContent = 'Connected to channel';

                        if (isPublisher) {
                            const localVideoTrack = await AgoraRTC.createCameraVideoTrack();
                            const localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                            localVideoTrack.play('local-player');
                            await client.publish([localVideoTrack, localAudioTrack]);
                            console.log('Published local tracks');
                        } else {
                            client.on('user-published', async (user, mediaType) => {
                                await client.subscribe(user, mediaType);
                                if (mediaType === 'video') {
                                    user.videoTrack.play('local-player');
                                    console.log('Playing remote video');
                                }
                                if (mediaType === 'audio') {
                                    user.audioTrack.play();
                                    console.log('Playing remote audio');
                                }
                            });
                        }
                    } catch (error) {
                        console.error('JoinStream error:', error);
                        statusDiv.textContent = 'Error: ' + error.message;
                    }
                }

                if (isPublisher) {
                    document.getElementById('stop-stream').addEventListener('click', async () => {
                        await client.leave(); // Clean up Agora session
                        await fetch('{{ route('live.stop', $stream) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        }).then(() => window.location.href = '{{ route('live') }}');
                    });
                }

                joinStream();
            }
        });
    </script>
@endsection
