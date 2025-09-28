<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages • afroConnect</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/msgindex.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main_content.css') }}">
</head>
<body>
    <div class="navigation-sidebar">
        <a href="{{ route('welcome') }}" class="logo"><img src="{{ asset('2projlogo.png') }}" alt="afroConnect"></a>
        <a href="{{ route('welcome') }}" class="nav-item {{ request()->routeIs('welcome') ? 'active' : '' }}">
            <i class="bi {{ request()->routeIs('welcome') ? 'bi-house-door-fill' : 'bi-house-door' }}"></i>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('search') ? 'active' : '' }}" id="openSearchModal">
            <i class="bi {{ request()->routeIs('search') ? 'bi-search-fill' : 'bi-search' }}"></i>
        </a>
        <a href="{{ route('marketshowroom') }}" class="nav-item {{ request()->routeIs('marketshowroom') ? 'active' : '' }}">
            <i class="bi {{ request()->routeIs('marketshowroom') ? 'bi-handbag-fill' : 'bi-handbag' }}"></i>
        </a>
        <a href="{{ route('live') }}" class="nav-item {{ request()->routeIs('live') ? 'active' : '' }}">
                 <i class="bi {{ request()->routeIs('live') ? 'bi-camera-video-fill' : 'bi-camera-video' }}"></i>
        </a>
        <a href="{{ route('notifications.index') }}" class="nav-item notification-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" id="notificationBell">
            <div class="icon-wrapper" style="position: relative;">
                <i class="bi {{ request()->routeIs('notifications.index') ? 'bi-bell-fill' : 'bi-bell' }}"></i>
                @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="notifications-badge">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </div>
        </a>
        <a href="{{ route('conversations.index') }}" class="nav-item {{ request()->routeIs(['conversations.index', 'conversations.show']) ? 'active' : '' }}">
            <div class="icon-wrapper" style="position: relative;">
                <i class="bi {{ request()->routeIs(['conversations.index', 'conversations.show']) ? 'bi-chat-fill' : 'bi-chat' }}"></i>
                @if(isset($unreadMessageCount) && $unreadMessageCount > 0)
                    <span class="notifications-badge">
                        {{ $unreadMessageCount }}
                    </span>
                @endif
            </div>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('stories.create') ? 'active' : '' }}" id="openStoryModalSidebarNav">
            <i class="bi {{ request()->routeIs('stories.create') ? 'bi-plus-circle-fill' : 'bi-plus-circle' }}"></i>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('posts.create') ? 'active' : '' }}" id="openPostModalSidebarNav">
            <i class="bi {{ request()->routeIs('posts.create') ? 'bi-images' : 'bi-images' }}"></i>
        </a>
        <div class="menu-bar">
            <a href="#" class="nav-item menu-trigger {{ request()->routeIs(['settings', 'logout']) ? 'active' : '' }}">
                <i class="bi {{ request()->routeIs(['settings', 'logout']) ? 'bi-list' : 'bi-list' }}"></i>
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
                <i class="bi {{ request()->routeIs('marketplace.categories') ? 'bi-grid-fill' : 'bi-grid' }}"></i><span>Categories</span>
            </a>
        @endif
        <a href="{{ route('user.profile', auth()->user()) }}" class="nav-item profile-nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-nav-icon" alt="Profile Picture">
            @else
                <i class="bi {{ request()->routeIs('user.profile') ? 'bi-person-fill' : 'bi-person' }} profile-nav-icon"></i>
            @endif
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
                        $latestMessage = $convo->messages->first();
                    @endphp
                    <div class="conversation-item {{ isset($activeConversation) && $convo->id == $activeConversation->id ? 'actives' : '' }}"
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
                                @if($latestMessage)
                                    @if($latestMessage->user_id == Auth::id())
                                        You:
                                    @endif
                                    @if($latestMessage->type === 'audio')
                                        Audio message
                                    @elseif($latestMessage->type === 'shared_post')
                                        Shared a post
                                    @else
                                        {{ Str::limit($latestMessage->body, 30) }}
                                    @endif
                                @else
                                    No messages yet
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="chats-container">
            <div class="empty-chat-state">
                <div class="empty-chat-content">
                    <div class="messanger-icon">
                        <i class="bi bi-chat-dots" style="font-size: 60px;"></i>
                        {{-- <svg aria-label="" class="x1lliihq x1n2onr6 xyb1xck" fill="currentColor" height="96" role="img" viewBox="0 0 96 96" width="96"><title></title><path d="M48 0C21.532 0 0 21.533 0 48s21.532 48 48 48 48-21.532 48-48S74.468 0 48 0Zm0 94C22.636 94 2 73.364 2 48S22.636 2 48 2s46 20.636 46 46-20.636 46-46 46Zm12.227-53.284-7.257 5.507c-.49.37-1.166.375-1.661.005l-5.373-4.031a3.453 3.453 0 0 0-4.989.921l-6.756 10.718c-.653 1.027.615 2.189 1.582 1.453l7.257-5.507a1.382 1.382 0 0 1 1.661-.005l5.373 4.031a3.453 3.453 0 0 0 4.989-.92l6.756-10.719c.653-1.027-.615-2.189-1.582-1.453ZM48 25c-12.958 0-23 9.492-23 22.31 0 6.706 2.749 12.5 7.224 16.503.375.338.602.806.62 1.31l.125 4.091a1.845 1.845 0 0 0 2.582 1.629l4.563-2.013a1.844 1.844 0 0 1 1.227-.093c2.096.579 4.331.884 6.659.884 12.958 0 23-9.491 23-22.31S60.958 25 48 25Zm0 42.621c-2.114 0-4.175-.273-6.133-.813a3.834 3.834 0 0 0-2.56.192l-4.346 1.917-.118-3.867a3.833 3.833 0 0 0-1.286-2.727C29.33 58.54 27 53.209 27 47.31 27 35.73 36.028 27 48 27s21 8.73 21 20.31-9.028 20.31-21 20.31Z"></path></svg> --}}
                    </div>
                    <div class="empty-chat-title">Your messages</div>
                    <div class="empty-chat-text">Send private photos and messages to a friend or group.</div>
                    <button class="empty-chat-btn" id="openNewMessageModal">
                        Send message
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/message.js') }}"></script>
    <script src="{{ asset('js/main_content.js') }}"></script>
</body>
</html>
