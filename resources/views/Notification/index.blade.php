@extends('layouts.app')

@section('content')
<style>
    .notifications-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 0 15px;
    }

    .notifications-title {
        font-size: 24px;
        margin-bottom: 20px;
        color: #111;
    }

    body.dark-mode .notifications-title {
        color: #e9ecef;
    }

    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .notification-item {
        background: #f9f9f9;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background 0.3s ease, color 0.3s ease;
    }

    body.dark-mode .notification-item {
        background: #1e1e1e;
        border-color: #333;
    }

    .notification-item a {
        color: #333;
        text-decoration: none;
        display: flex;
        flex-direction: column;
    }

    body.dark-mode .notification-item a {
        color: #e0e0e0;
    }

    .notification-item .message {
        font-weight: normal;
    }

    .notification-item .timestamp {
        font-size: 12px;
        color: #777;
        margin-top: 5px;
    }

    body.dark-mode .notification-item .timestamp {
        color: #aaa;
    }

    .notification-item.unread {
        background: #e9f3ff;
        font-weight: bold;
    }

    body.dark-mode .notification-item.unread {
        background: #2a3b57;
    }

    .no-notifications {
        text-align: center;
        color: #666;
        margin-top: 30px;
    }

    body.dark-mode .no-notifications {
        color: #bbb;
    }
</style>

<div class="notifications-container">
    <h2 class="notifications-title">Notifications</h2>

    @if ($notifications->count() > 0)
        <ul class="notification-list">
            @foreach ($notifications as $notification)
                <li class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                    <a href="{{ route('notifications.read', $notification->id) }}">
                        <span class="message">{{ $notification->data['message'] }}</span>
                        <small class="timestamp">{{ $notification->created_at->diffForHumans() }}</small>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="no-notifications">You have no notifications.</p>
    @endif
</div>
@endsection
