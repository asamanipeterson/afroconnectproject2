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

    .follow-back-button {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 16px;
        background-color: #007bff;
        color: #fff;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .follow-back-button:hover {
        background-color: #0056b3;
    }

    body.dark-mode .follow-back-button {
        background-color: #1a73e8;
    }

    body.dark-mode .follow-back-button:hover {
        background-color: #135ab6;
    }

    .follow-back-button.following {
        background-color: #28a745;
    }

    .follow-back-button.following:hover {
        background-color: #218838;
    }

    body.dark-mode .follow-back-button.following {
        background-color: #28a745;
    }

    body.dark-mode .follow-back-button.following:hover {
        background-color: #218838;
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
  @if (isset($notification->data['show_follow_back_button']) && $notification->data['show_follow_back_button'])
    <form method="POST" action="{{ route('toggle.follow', ['id' => $notification->data['follower_id']]) }}" class="follow-form" style="display: inline;">
        @csrf
        <button type="submit" class="follow-back-button {{ $notification->follower && auth()->user()->isFollowing($notification->follower) ? 'following' : '' }}"
            data-user-id="{{ $notification->data['follower_id'] }}"
            data-is-following="{{ $notification->follower && auth()->user()->isFollowing($notification->follower) ? 'true' : 'false' }}">
            {{ $notification->follower && auth()->user()->isFollowing($notification->follower) ? 'Following' : 'Follow Back' }}
        </button>
    </form>
@endif           </li>
            @endforeach
        </ul>
    @else
        <p class="no-notifications">You have no notifications.</p>
    @endif
</div>

@push('scripts')
    <script>
        document.querySelectorAll('.follow-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const button = form.querySelector('.follow-back-button');
                const userId = button.getAttribute('data-user-id');
                const isFollowing = button.getAttribute('data-is-following') === 'true';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.textContent = isFollowing ? 'Follow Back' : 'Following';
                        button.setAttribute('data-is-following', isFollowing ? 'false' : 'true');
                        button.classList.toggle('following');
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request.');
                });
            });
        });
    </script>
@endpush
@endsection
