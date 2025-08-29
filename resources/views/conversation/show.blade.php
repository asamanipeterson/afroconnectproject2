<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages • afroConnect</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/message.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main_content.css') }}">
</head>
<body>
    <div class="navigation-sidebar">
        <a href="{{ route('welcome') }}" class="logo"><img src="{{ asset('2projlogo.png') }}" alt="afroConnect"></a>
        <a href="{{ route('welcome') }}" class="nav-item {{ request()->routeIs('welcome') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('search') ? 'active' : '' }}" id="openSearchModal">
            <i class="bi bi-search"></i>
        </a>
        <a href="{{ route('marketshowroom') }}" class="nav-item {{ request()->routeIs('marketshowroom') ? 'active' : '' }}">
            <i class="bi bi-handbag"></i>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('live') ? 'active' : '' }}">
            <i class="bi bi-camera-video"></i>
        </a>
        <a href="{{ route('notifications.index') }}" class="nav-item notification-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" id="notificationBell">
            <div class="icon-wrapper" style="position: relative;">
                <i class="bi bi-bell"></i>
                @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="notifications-badge">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </div>

        </a>
        <a href="#" class="nav-item {{ request()->routeIs('conversations.show') ? 'active' : '' }}" onclick="createConversation(event, '{{ route('conversations.show', $user ) }}')">
            <i class="bi bi-chat"></i>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('stories.create') ? 'active' : '' }}" id="openStoryModalSidebarNav">
            <i class="bi bi-plus-circle"></i>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('posts.create') ? 'active' : '' }}" id="openPostModalSidebarNav">
            <i class="bi bi-images"></i>
        </a>
        <div class="menu-bar">
            <a href="#" class="nav-item menu-trigger {{ request()->routeIs(['settings', 'logout']) ? 'active' : '' }}">
                <i class="bi bi-list"></i>
            </a>
            <div class="dropdown-menu sidebar-dropdown">
                <div class="settingsdrop">
                    <ul class="main-drop">
                        <li class="main-li">
                            <a href="" class="dropdown-items {{ request()->routeIs('settings') ? 'active' : '' }}" id="openSettingsModal">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-settings text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject" style="cursor: pointer">Settings</p>
                                </div>
                            </a>
                        </li>
                        <li class="main-li">
                            <a href="{{ route('logout') }}" class="dropdown-items">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-logout text-danger"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject">Log out</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if (request()->routeIs(['marketplace.newlisting', 'marketshowroom']))
            <a href="" class="nav-item {{ request()->routeIs('marketplace.categories') ? 'active' : '' }}" id="openCategoryModal">
                <i class="bi bi-grid"></i><span>Categories</span>
            </a>
        @endif
        <a href="{{ route('user.profile', auth()->user()) }}" class="nav-item profile-nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-nav-icon" alt="Profile Picture">
            @else
                <i class="bi bi-person-circle profile-nav-icon"></i>
            @endif
            {{-- <span>Profile</span> --}}
        </a>
    </div>
    <div class="messages-container">
        <div class="conversations-sidebar">
            <div class="conversations-header">
                <a href="{{ route('user.profile', auth()->user()->id) }}">
                    {{ auth()->user()->username }}
                    @if(auth()->user()->is_verified)
                        <i class="bi bi-check-circle-fill verified-badge" title="Verified User"></i>
                    @endif
                </a>
                <div>
                    <button class="theme-toggle" id="themeToggle"></button>
                    <button class="new-message-btn"><i class="bi bi-pencil"></i></button>
                </div>
            </div>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search">
            </div>
            <div class="conversations-list" id="conversations-list">
                <div class="conversation-items" data-conversation-id="null">
                    <div class="conversation-avatar" style="background-color: #efefef; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-chat" style="font-size: 24px; color: #8e8e8e;"></i>
                    </div>
                    <div class="conversation-info">
                        <div class="conversation-preview">Messages</div>
                    </div>
                </div>
                @foreach($conversations as $convo)
                    @php
                        $otherUser = $convo->participants->where('id', '!=', Auth::id())->first();
                        $latestMessage = $convo->messages->last();
                    @endphp
                    <div class="conversation-item {{ $convo->id == $conversation->id ? 'active' : '' }}"
                         data-conversation-id="{{ $convo->id }}"
                         data-user-id="{{ $otherUser->id }}"
                         data-last-updated="{{ $latestMessage ? $latestMessage->updated_at->timestamp : $convo->updated_at->timestamp }}">
                        @if($otherUser->profile_picture)
                            <img src="{{ asset('storage/' . $otherUser->profile_picture) }}" class="conversation-avatar" alt="{{ $otherUser->username }}">
                        @else
                            <div class="conversation-avatar" style="background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                {{ strtoupper(substr($otherUser->username, 0, 1)) }}
                            </div>
                        @endif
                        <div class="conversation-info">
                            <div class="conversation-top">
                                <div class="conversation-username">
                                    <a href="{{ route('user.profile', $otherUser->id) }}" class="chat-username">
                                        {{ $otherUser->username }}
                                        @if($otherUser->is_verified)
                                            <i class="bi bi-check-circle-fill verified-badge" title="Verified User"></i>
                                        @endif
                                    </a>
                                </div>
                                <div class="conversation-time">{{ $latestMessage ? $latestMessage->created_at->diffForHumans() : '' }}</div>
                            </div>
                            <div class="conversation-preview">
                                {{ $latestMessage ? ($latestMessage->user_id == Auth::id() ? 'You: ' : '') . ($latestMessage->type === 'audio' ? 'Audio message' : $latestMessage->body) : 'No messages yet' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="chat-container">
            <div class="chat-header">
    <div class="chat-user-info">
        @php
            $otherUser = $conversation->participants->where('id', '!=', Auth::id())->first();
        @endphp
        @if($otherUser)
            @if($otherUser->profile_picture)
                <img src="{{ asset('storage/' . $otherUser->profile_picture) }}" class="chat-user-avatar" alt="{{ $otherUser->username }}">
            @else
                <div class="chat-user-avatar" style="background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                    {{ strtoupper(substr($otherUser->username, 0, 1)) }}
                </div>
            @endif
            <a href="{{ route('user.profile', $otherUser->id) }}" class="chat-username">
                {{ $otherUser->username }}
                @if($otherUser->is_verified)
                    <i class="bi bi-check-circle-fill verified-badge" title="Verified User"></i>
                @endif
            </a>
        @else
            <div class="chat-user-info">
                <span class="chat-username">User Not Found</span>
            </div>
        @endif
    </div>
</div>
            <div class="messages-area" id="messages-container">
                @php
                    $currentDate = '';
                @endphp
                @foreach($messages as $message)
                    @php
                        $messageDate = $message->created_at->format('j M Y');
                        $messageTime = $message->created_at->format('H:i');
                        $isCurrentUser = $message->user_id == Auth::id();
                        $isEmojiOnly = preg_match('/^(\p{Emoji_Modifier_Base}\p{Emoji_Modifier}?|\p{Emoji_Presentation}|\p{Emoji}\x{FE0F}?\x{200D}?)+$/u', $message->body) && ($message->type === 'text');
                    @endphp
                    @if ($messageDate != $currentDate)
                        <div class="message-time-divider">
                            {{ $message->created_at->format('j M Y') }} {{ $messageTime }}
                        </div>
                        @php
                            $currentDate = $messageDate;
                        @endphp
                    @endif
                    <div class="message-wrapper {{ $isCurrentUser ? 'message-wrapper-sent' : 'message-wrapper-received' }}">
                        @if (!$isCurrentUser)
                            @if($otherUser->profile_picture)
                                <img src="{{ asset('storage/' . $otherUser->profile_picture) }}" class="message-avatar" alt="{{ $otherUser->username }}">
                            @else
                                <div class="message-avatar fallback-avatar">
                                    {{ strtoupper(substr($otherUser->username, 0, 1)) }}
                                </div>
                            @endif
                        @endif
                        <div class="message-bubble">
                            <div class="message {{ $isCurrentUser ? 'message-sent' : 'message-received' }} {{ $message->type !== 'text' ? 'media-message' : '' }} {{ $isEmojiOnly ? 'emoji-only' : '' }}">
                                @if($message->type === 'audio')
                                    <div class="custom-audio-player" data-src="{{ asset('storage/' . $message->audio_path) }}">
                                        <button class="play-btn"><i class="bi bi-play-fill"></i></button>
                                        <div class="waveform">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="audio-duration">0:00</div>
                                    </div>
                                @elseif($message->type === 'image')
                                    <img src="{{ asset('storage/' . $message->image_path) }}" alt="Image" class="message-image">
                                @elseif($message->type === 'video')
                                    <video controls class="message-video">
                                        <source src="{{ asset('storage/' . $message->video_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <div class="message-text">{{ $message->body }}</div>
                                @endif
                            </div>
                            <div class="message-timestamp {{ $isCurrentUser ? 'message-timestamp-sent' : 'message-timestamp-received' }}">
                                {{ $messageTime }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="message-input-container">
                <form action="{{ route('messages.store', $conversation) }}" method="POST" id="message-form" class="message-input-form" enctype="multipart/form-data">
                    @csrf
                    <button type="button" class="mic-btn" id="record-audio-button">
                        <i class="bi bi-mic-fill"></i>
                    </button>
                    <input type="file" name="audio" id="audio-input" accept="audio/*" style="display: none;">
                    <input type="text" name="body" class="message-input" placeholder="Message...">
                    <button type="button" class="emoji-btn" id="open-emoji-picker">
                        <i class="bi bi-emoji-smile"></i>
                    </button>
                    <label for="image-upload" class="image-upload-label">
                        <i class="bi bi-image"></i>
                    </label>
                    <input type="file" name="image" id="image-upload" accept="image/*" style="display: none;">

                    {{-- NEW: ADDED FOR VIDEO UPLOAD --}}
                    <label for="video-upload" class="video-upload-label">
                        <i class="bi bi-camera-video"></i>
                    </label>
                    <input type="file" name="video" id="video-upload" accept="video/*" style="display: none;">

                    <button type="submit" class="message-send-btn">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
                <div id="recording-status" class="recording-status">
                    <i class="bi bi-record-circle"></i> Recording... Speak now
                </div>
                <div id="audio-controls" class="audio-controls">
                    <audio id="audio-preview" class="audio-preview" controls></audio>
                    <div class="audio-controls-buttons">
                        <button type="button" id="send-audio-button" class="send-audio-btn">
                            <i class="bi bi-send-fill"></i>
                        </button>
                        <button type="button" id="cancel-audio" class="cancel-audio-btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/message.js') }}"></script>
    <script src="{{ asset('js/main_content.js') }}"></script>
</body>
</html>
