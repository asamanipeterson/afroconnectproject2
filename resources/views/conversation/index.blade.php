@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Conversations</div>

                <div class="card-body">
                    @foreach($conversations as $conversation)
                        @php
                            $otherUser = $conversation->participants->where('id', '!=', Auth::id())->first();
                        @endphp
                        <a href="{{ route('conversations.show', $conversation) }}" class="d-block p-3 border-bottom text-decoration-none text-dark">
                            <div class="d-flex align-items-center">
                                <img src="{{ $otherUser->profile_picture ? asset('storage/'.$otherUser->profile_picture) : 'https://ui-avatars.com/api/?name='.$otherUser->username }}"
                                     class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h5 class="mb-0">{{ $otherUser->username }}</h5>
                                    @if($conversation->latestMessage)
                                        <p class="mb-0 text-muted">{{ Str::limit($conversation->latestMessage->body, 50) }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages • afroConnect</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-primary: #fff;
            --bg-secondary: #f8f9fa;
            --text-primary: #262626;
            --text-secondary: #8e8e8e;
            --border-color: #dbdbdb;
            --message-received: #f0f0f0;
            --message-sent: #007aff;
            --message-send-btn:#007aff;
            --message-send-btn-hover:#1b86f8;
            --accent-color: #007aff;
            --hover-bg: #e9e9e9;
        }

        [data-theme="dark"] {
            --bg-primary: #000;
            --bg-secondary: #121212;
            --text-primary: #f1f1f1;
            --text-secondary: #e3e1e1;
            --border-color: #363636;
            --message-received: #262626;
            --message-sent: #0a84ff;
            --message-send-btn:#007aff;
            --message-send-btn-hover:#1b86f8;
            --accent-color: #0a84ff;
            --hover-bg: #262626;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
            height: 100vh;
        }

        .messages-container {
            display: flex;
            height: 100vh;
            max-width: 2000px;
            margin: 0 auto;
            background: var(--bg-primary);
        }

        .conversations-sidebar {
            width: 350px;
            border-right: 1px solid var(--border-color);
            height: 100%;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-primary);
        }

        .conversations-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .conversations-header h1 {
            font-size: 16px;
            font-weight: 600;
        }

        .new-message-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 20px;
            cursor: pointer;
        }

        .search-container {
            padding: 8px 16px;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px;
            background-color: var(--bg-secondary);
            border: none;
            border-radius: 8px;
            color: var(--text-primary);
        }

        .search-input::placeholder {
            color: var(--text-secondary);
        }

        .conversations-list {
            flex: 1;
            overflow-y: auto;
        }

        .conversation-item, .conversation-items {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            border-bottom: 1px solid var(--border-color);
        }

        .conversation-item:hover, .conversation-item.active,
        .conversation-items:hover, .conversation-items.active {
            background-color: var(--hover-bg);
        }

        .conversation-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .conversation-info {
            flex: 1;
            min-width: 0;
        }

        .conversation-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .conversation-username {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conversation-time {
            font-size: 13px;
            color: var(--text-secondary);
            flex-shrink: 0;
            margin-left: 8px;
        }

        .conversation-preview {
            font-size: 14px;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .chat-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--bg-primary);
        }

        .chat-user-info {
            display: flex;
            align-items: center;
        }

        .chat-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }

        .chat-username {
            font-weight: 600;
            font-size: 16px;
        }

        .chat-actions {
            display: flex;
            gap: 16px;
        }

        .chat-action-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 18px;
            cursor: pointer;
        }

        .messages-area {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-primary);
        }

        .message-wrapper {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            margin-bottom: 10px;
        }

        .message-wrapper-sent {
            justify-content: flex-end;
        }

        .message-wrapper-received {
            justify-content: flex-start;
        }

        .message-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .fallback-avatar {
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .message {
            max-width: 70%;
            padding: 10px 14px;
            position: relative;
            word-wrap: break-word;
            font-size: 15px;
            line-height: 1.35;
        }

        .message-sent {
            background-color: var(--message-sent);
            color: white;
            border-radius: 18px 18px 5px 18px;
        }

        .message-sent::after {
            content: '';
            position: absolute;
            bottom: 0px;
            right: -7px;
            width: 12px;
            height: 12px;
            background-color: var(--message-sent);
            clip-path: polygon(100% 100%, 0% 100%, 100% 0%);
            transform: scaleX(-1);
        }

        .message-received {
            background-color: var(--message-received);
            color: var(--text-primary);
            border-radius: 18px 18px 18px 5px;
        }

        .message-received::after {
            content: '';
            position: absolute;
            bottom: 0px;
            left: -7px;
            width: 12px;
            height: 12px;
            background-color: var(--message-received);
            clip-path: polygon(100% 100%, 0% 100%, 100% 0%);
        }

        .message-time-divider {
            font-size: 12px;
            color: var(--text-secondary);
            text-align: center;
            margin: 15px 0;
            user-select: none;
            display: block;
        }

        .message-input-container {
            padding: 10px 16px;
            border-top: 1px solid var(--border-color);
            background-color: var(--bg-primary);
        }

        .message-input-form {
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }

        .message-input {
            flex: 1;
            background-color: var(--bg-secondary);
            border: none;
            border-radius: 22px;
            padding: 12px 18px;
            color: var(--text-primary);
            min-height: 44px;
        }

        .message-input::placeholder {
            color: var(--text-secondary);
        }

        .message-send-btn {
            background:transparent;
            color:var(--message-send-btn);
            border: none;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .message-send-btn:hover{
            color:var(--message-send-btn-hover)
        }
        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            font-size: 18px;
            margin-left: 10px;
        }

        .notification-badge {
            background-color: var(--accent-color);
            color: white;
            font-size: 12px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }

        .debug-panel {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            max-width: 300px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }

        .debug-toggle {
            position: fixed;
            bottom: 10px;
            right: 320px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            z-index: 1000;
        }

        .loading-spinner {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="messages-container">
        <div class="conversations-sidebar">
            <div class="conversations-header">
                <h1>{{ auth()->user()->username }}</h1>
                <div>
                    <button class="theme-toggle" id="themeToggle">🌙</button>
                    <button class="new-message-btn">✏️</button>
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
                                <div class="conversation-username">{{ $otherUser->username }}</div>
                                <div class="conversation-time">{{ $latestMessage ? $latestMessage->created_at->diffForHumans() : '' }}</div>
                            </div>
                            <div class="conversation-preview">
                                {{ $latestMessage ? ($latestMessage->user_id == Auth::id() ? 'You: ' : '') . $latestMessage->body : 'No messages yet' }}
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
                    @if($otherUser->profile_picture)
                        <img src="{{ asset('storage/' . $otherUser->profile_picture) }}" class="chat-user-avatar" alt="{{ $otherUser->username }}">
                    @else
                        <div class="chat-user-avatar" style="background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            {{ strtoupper(substr($otherUser->username, 0, 1)) }}
                        </div>
                    @endif
                    <span class="chat-username">{{ $otherUser->username }}</span>
                </div>
                <div class="chat-actions">
                    <button class="chat-action-btn">📞</button>
                    <button class="chat-action-btn">ℹ️</button>
                </div>
            </div>

            <div class="messages-area" id="messages-container">
                @php
                    $currentDate = '';
                @endphp
                @foreach($messages as $message)
                    @php
                        $messageDate = $message->created_at->format('j M Y');
                    @endphp
                    @if ($messageDate != $currentDate)
                        <div class="message-time-divider">
                            {{ $message->created_at->format('j M Y, H:i') }}
                        </div>
                        @php
                            $currentDate = $messageDate;
                        @endphp
                    @endif
                    <div class="message-wrapper {{ $message->user_id == Auth::id() ? 'message-wrapper-sent' : 'message-wrapper-received' }}">
                        @if ($message->user_id != Auth::id())
                            @if($otherUser->profile_picture)
                                <img src="{{ asset('storage/' . $otherUser->profile_picture) }}" class="message-avatar" alt="{{ $otherUser->username }}">
                            @else
                                <div class="message-avatar fallback-avatar">
                                    {{ strtoupper(substr($otherUser->username, 0, 1)) }}
                                </div>
                            @endif
                        @endif
                        <div class="message {{ $message->user_id == Auth::id() ? 'message-sent' : 'message-received' }}">
                            <div class="message-text">{{ $message->body }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="message-input-container">
                <form action="{{ route('messages.store', $conversation) }}" method="POST" id="message-form" class="message-input-form">
                    @csrf
                    <input type="text" name="body" class="message-input" placeholder="Message..." required>
                    <button type="submit" class="message-send-btn">Send</button>
                </form>
            </div>
        </div>
    </div>

    <div class="debug-toggle" id="debugToggle">Debug</div>
    <div class="debug-panel" id="debugPanel" style="display: none;">
        <h4>Debug Information <span id="pollingStatus"></span></h4>
        <div id="debugContent"></div>
    </div>

    <script>
        function debugLog(message) {
            const debugContent = document.getElementById('debugContent');
            debugContent.innerHTML += `<div>${new Date().toLocaleTimeString()}: ${message}</div>`;
            debugContent.scrollTop = debugContent.scrollHeight;
            console.log(message);
        }

        document.getElementById('debugToggle').addEventListener('click', function() {
            const debugPanel = document.getElementById('debugPanel');
            debugPanel.style.display = debugPanel.style.display === 'none' ? 'block' : 'none';
        });

        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        function applyTheme(theme) {
            if (theme === 'dark') {
                body.setAttribute('data-theme', 'dark');
                themeToggle.textContent = '☀️';
                localStorage.setItem('theme', 'dark');
            } else {
                body.removeAttribute('data-theme');
                themeToggle.textContent = '🌙';
                localStorage.setItem('theme', 'light');
            }
        }

        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            applyTheme('dark');
        } else {
            applyTheme('light');
        }

        themeToggle.addEventListener('click', () => {
            if (body.getAttribute('data-theme') === 'dark') {
                applyTheme('light');
            } else {
                applyTheme('dark');
            }
        });

        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', function() {
                const conversationId = this.getAttribute('data-conversation-id');
                if (conversationId && conversationId !== "null") {
                    window.location.href = '/conversations/' + conversationId;
                }
            });
        });

        const currentConversationId = '{{ $conversation->id }}';
        const conversationsList = document.getElementById('conversations-list');
        const currentUserId = '{{ Auth::id() }}';

        debugLog(`Current conversation ID: ${currentConversationId}`);
        debugLog(`Current user ID: ${currentUserId}`);

        function updateConversationSidebar(conversationId, messageBody, isCurrentUser) {
            debugLog(`Updating sidebar for conversation ${conversationId}`);

            const conversationItem = conversationsList.querySelector(`[data-conversation-id="${conversationId}"]`);

            if (conversationItem) {
                debugLog('Conversation item found in sidebar');

                const previewElement = conversationItem.querySelector('.conversation-preview');
                const timeElement = conversationItem.querySelector('.conversation-time');

                if (previewElement) {
                    previewElement.textContent = (isCurrentUser ? 'You: ' : '') + messageBody;
                    debugLog('Updated preview text: ' + previewElement.textContent);
                }

                if (timeElement) {
                    timeElement.textContent = 'Just now';
                    debugLog('Updated time text: ' + timeElement.textContent);
                }

                conversationItem.setAttribute('data-last-updated', Math.floor(Date.now() / 1000));

                const firstItem = conversationsList.querySelector('.conversation-item');
                conversationsList.insertBefore(conversationItem, firstItem.nextSibling);
                debugLog('Moved conversation to top of list');

                if (conversationId != currentConversationId) {
                    let badge = conversationItem.querySelector('.notification-badge');
                    if (!badge) {
                        badge = document.createElement('div');
                        badge.className = 'notification-badge';
                        badge.textContent = '1';
                        conversationItem.appendChild(badge);
                        debugLog('Added notification badge');
                    } else {
                        badge.textContent = parseInt(badge.textContent) + 1;
                        debugLog('Updated notification badge: ' + badge.textContent);
                    }
                }
            } else {
                debugLog(`Conversation ${conversationId} not found in sidebar`);
            }
        }

        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var form = this;
            var formData = new FormData(form);
            const inputField = form.querySelector('.message-input');
            const messageBody = inputField.value;

            if (messageBody.trim() === '') {
                debugLog('Message is empty, not sending');
                return;
            }

            debugLog('Sending message: ' + messageBody);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                debugLog('Server response status: ' + response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                debugLog('Server response data: ' + JSON.stringify(data));

                const messagesContainer = document.getElementById('messages-container');

                var messageHtml = `
                    <div class="message-wrapper message-wrapper-sent">
                        <div class="message message-sent">
                            <div class="message-text">${messageBody}</div>
                        </div>
                    </div>
                `;

                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                updateConversationSidebar('{{ $conversation->id }}', messageBody, true);

                form.reset();
            })
            .catch(error => {
                debugLog('Fetch error: ' + error.message);
                console.error('Error:', error);
                form.submit();
            });
        });

        window.addEventListener('load', function() {
            const messagesContainer = document.getElementById('messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            debugLog('Page loaded, scrolled to bottom of messages');
        });
    </script>
</body>
</html> --}}
