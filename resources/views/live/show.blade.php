@extends('layouts.app')

@section('content')

    <style>
        /* --- BASE SETUP & RESPONSIVENESS --- */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .stream-page-wrapper {
            display: flex;
            max-width: 1200px; /* Max desktop width */
            margin: 30px auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            min-height: 80vh;
        }

        /* --- VIDEO SECTION --- */
        .video-section {
            flex-grow: 1;
            position: relative;
            background-color: black;
            border-radius: 12px 0 0 12px;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Ensures video doesn't bleed */
        }

        .video-player {
            width: 100%;
            height: 100%;
            min-height: 500px;
            /* Target Agora container */
            position: absolute;
            top: 0;
            left: 0;
        }

        /* --- OVERLAY HEADER (Top Bar) --- */
        .stream-overlay-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), transparent);
            color: white;
        }

        .streamer-info-box {
            display: flex;
            align-items: center;
        }

        .live-tag {
            background-color: #e80000;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            margin-right: 10px;
        }

        .streamer-details {
            display: flex;
            flex-direction: column;
        }

        .streamer-name {
            font-weight: bold;
            font-size: 16px;
            line-height: 1.2;
        }

        .stream-title {
            font-size: 12px;
            opacity: 0.8;
        }

        .viewer-actions {
            display: flex;
            align-items: center;
        }

        .viewer-count-box {
            background: rgba(0, 0, 0, 0.6);
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            margin-right: 10px;
        }

        .exit-stream-btn {
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-weight: bold;
            cursor: pointer;
            line-height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        /* --- CONTROLS FOOTER (Publisher Only) --- */
        .stream-controls-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            display: flex;
            justify-content: center;
            padding: 15px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent);
        }

        .control-btn {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            border: 2px solid white;
            padding: 10px 15px;
            border-radius: 30px;
            font-size: 16px;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
        }

        .control-btn i {
            margin-right: 5px;
        }

        .stop-btn {
            background-color: #dc3545; /* Red */
            border-color: #dc3545;
        }

        .mute-on { /* Default state (mic is on) */
            background-color: #007bff; /* Blue for ON */
            border-color: #007bff;
        }

        .mute-off { /* State when muted */
            background-color: #6c757d; /* Grey for OFF/Muted */
            border-color: #6c757d;
        }


        /* --- CHAT SECTION --- */
        .chat-section {
            width: 350px; /* Fixed width on desktop */
            display: flex;
            flex-direction: column;
            border-left: 1px solid #ddd;
            border-radius: 0 12px 12px 0;
            padding: 15px;
        }

        .chat-section h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: #333;
        }

        .comments-feed {
            flex-grow: 1;
            overflow-y: auto;
            padding-right: 5px;
            margin-bottom: 10px;
        }

        .comments-feed p {
            background-color: #f7f7f7;
            padding: 8px 12px;
            border-radius: 12px;
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.4;
        }

        .comment-input-area {
            display: flex;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        #chat-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px 0 0 20px;
            font-size: 14px;
        }

        .send-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        /* --- MOBILE RESPONSIVENESS (BREAKPOINT) --- */
        @media (max-width: 900px) {
            .stream-page-wrapper {
                flex-direction: column;
                margin: 0;
                border-radius: 0;
                min-height: 100vh;
            }

            .video-section {
                border-radius: 0;
                min-height: 55vh;
            }

            .chat-section {
                width: 100%;
                height: 45vh;
                border-left: none;
                border-top: 1px solid #ddd;
                border-radius: 0;
                padding-bottom: 20px;
            }
        }
    </style>

    {{-- START OF LIVE STREAM CONTENT --}}
    <div class="stream-page-wrapper">

        {{-- 1. LIVE VIDEO CONTAINER --}}
        <div class="video-section">

            {{-- Stream Header/Overlay --}}
            <div class="stream-overlay-header">
                <div class="streamer-info-box">
                    <div class="live-tag">LIVE</div>
                    <div class="streamer-details">
                        <span class="streamer-name">@ {{ $stream->user->username }}</span>
                        <span class="stream-title">{{ $stream->title }}</span>
                    </div>
                </div>

                {{-- Viewer Count and Exit --}}
                <div class="viewer-actions">
                    <div class="viewer-count-box">
                        <i class="fas fa-eye"></i> <span id="viewer-count">0</span>
                        {{-- JS/API must update this count --}}
                    </div>
                    <button class="exit-stream-btn" onclick="window.location.href='{{ route('live') }}'">X</button>
                </div>
            </div>

            {{-- Agora Video Player --}}
            <div id="local-player" class="video-player"></div>

            {{-- Stream Controls (Publisher Only) --}}
            @if(auth()->check() && auth()->id() === $stream->user_id)
            <div class="stream-controls-footer">
                <button id="mute-toggle" class="control-btn mute-on" title="Mute/Unmute">
                    <i class="fas fa-microphone"></i>
                </button>
                <button id="stop-stream" class="control-btn stop-btn">
                    <i class="fas fa-times-circle"></i> Stop Stream
                </button>
            </div>
            @endif

        </div>

        {{-- 2. COMMENTS & CHAT SECTION --}}
        <div class="chat-section">
            <h3>Live Chat</h3>
            <div class="comments-feed" id="comments-feed">
                {{-- Comments will be appended here by JavaScript/WebSockets --}}
                <p><strong>System:</strong> Welcome to the stream!</p>
            </div>

            <div class="comment-input-area">
                <input type="text" placeholder="Add a comment..." id="chat-input">
                <button id="send-chat" class="send-btn">Send</button>
            </div>
        </div>
    </div>

    {{-- Error Status (Hidden from view) --}}
    <div id="status" style="display: none;"></div>

    <script>
        window.addEventListener('load', () => {
            if (typeof AgoraRTC === 'undefined') {
                document.getElementById('status').textContent = 'Error: Agora SDK failed to load.';
                console.error('AgoraRTC not loaded');
                return;
            }

            const client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
            const appId = '{{ config('laravel-agora.app_id') }}';
            const channel = '{{ $stream->channel_name }}';
            const token = '{{ $token }}';
            const uid = {{ auth()->id() ?? 0 }};
            const isPublisher = {{ auth()->check() && auth()->id() === $stream->user_id ? 'true' : 'false' }};

            let localAudioTrack = null; // Stored here for mute control

            async function joinStream() {
                const statusDiv = document.getElementById('status');
                try {
                    await client.join(appId, channel, token, uid);
                    console.log('Joined channel successfully');

                    if (isPublisher) {
                        const localVideoTrack = await AgoraRTC.createCameraVideoTrack();
                        localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();

                        localVideoTrack.play('local-player');
                        await client.publish([localVideoTrack, localAudioTrack]);
                        console.log('Published local tracks');

                        // MUTE TOGGLE SETUP
                        const muteToggleBtn = document.getElementById('mute-toggle');
                        if (muteToggleBtn) {
                            muteToggleBtn.addEventListener('click', () => {
                                const isMuted = localAudioTrack.muted;
                                localAudioTrack.setMuted(!isMuted);

                                if (isMuted) {
                                    // Was muted, now unmuting
                                    muteToggleBtn.classList.remove('mute-off');
                                    muteToggleBtn.classList.add('mute-on');
                                    muteToggleBtn.innerHTML = '<i class="fas fa-microphone"></i>';
                                } else {
                                    // Was unmuted, now muting
                                    muteToggleBtn.classList.remove('mute-on');
                                    muteToggleBtn.classList.add('mute-off');
                                    muteToggleBtn.innerHTML = '<i class="fas fa-microphone-slash"></i>';
                                }
                            });
                        }

                    } else {
                        // Viewer Logic
                        client.on('user-published', async (user, mediaType) => {
                            await client.subscribe(user, mediaType);
                            if (mediaType === 'video') {
                                user.videoTrack.play('local-player');
                            }
                            if (mediaType === 'audio') {
                                user.audioTrack.play();
                            }
                        });
                    }
                } catch (error) {
                    console.error('JoinStream error:', error);
                    statusDiv.textContent = 'Error: ' + error.message;
                }
            }

            if (isPublisher) {
                // Stop Stream Logic
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

            // Simple Chat Mock/Frontend Handling
            document.getElementById('send-chat').addEventListener('click', () => {
                const input = document.getElementById('chat-input');
                const feed = document.getElementById('comments-feed');
                if (input.value.trim() !== '') {
                    const newComment = document.createElement('p');
                    newComment.innerHTML = `<strong>You:</strong> ${input.value}`;
                    feed.appendChild(newComment);
                    input.value = '';
                    feed.scrollTop = feed.scrollHeight; // Scroll to new message
                    // NOTE: Real chat functionality requires WebSockets (e.g., Pusher)
                }
            });

        });
    </script>
@endsection
