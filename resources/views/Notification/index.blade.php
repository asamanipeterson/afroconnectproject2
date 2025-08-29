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
        min-height: 100px;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background 0.3s ease, color 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }
    body.dark-mode .notification-item {
        background: #1e1e1e;
        border-color: #333;
    }
    .notification-content {
        flex: 1;
    }
    .notification-item .notification-link {
        color: #333;
        text-decoration: none;
        display: block;
    }
    body.dark-mode .notification-item .notification-link {
        color: #e0e0e0;
    }
    .notification-item .message {
        font-weight: normal;
        margin-bottom: 5px;
    }
    .notification-item .timestamp {
        font-size: 12px;
        color: #777;
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
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight:500;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #007bff;
        background-color: transparent;
        color: #036ee0;
        font-family: 'Inter', Helvetica Neue, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .follow-back-button:hover {
        background-color: rgba(0, 123, 255, 0.1);
        border-color: #0062cc;
    }
    body.dark-mode .follow-back-button {
        border-color: #007bff;
    }
    body.dark-mode .follow-back-button:hover {
        background-color: rgba(0, 123, 255, 0.1);
        border-color: #0062cc;
    }
    .follow-back-button.following {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight:500;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #007bff;
        background-color: transparent;
        color: #007bff;
        font-family: 'Inter', Helvetica Neue, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .follow-back-button.following:hover {
        background-color: rgba(0, 123, 255, 0.1);
        border-color: #0062cc;
    }
    body.dark-mode .follow-back-button.following {
         color: #66b3ff;
    }
    body.dark-mode .follow-back-button.following:hover {
        background-color: rgba(102, 179, 255, 0.1);
    }
    .notification-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-left: 15px;
    }
    .mark-read-btn {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        padding: 5px;
        border-radius: 3px;
    }
    .mark-read-btn:hover {
        background: #f0f0f0;
    }
    body.dark-mode .mark-read-btn {
        color: #aaa;
    }
    body.dark-mode .mark-read-btn:hover {
        background: #333;
    }
</style>

<div class="notifications-container">
    <h2 class="notifications-title">Notifications</h2>

    @if ($notifications->count() > 0)
        <ul class="notification-list">
            @foreach ($notifications as $notification)
                @php
                    // Determine the appropriate URL based on notification type
                    $notificationUrl = '#';
                    $notificationType = class_basename($notification->type);

                    if ($notificationType === 'FollowNotification' && isset($notification->data['follower_id'])) {
                        // For follow notifications, go to user profile
                        $notificationUrl = route('user.profile', $notification->data['follower_id']);
                    } elseif ($notificationType === 'PostShared' && isset($notification->data['post_id'])) {
                        // For post share notifications, go to the post
                        $notificationUrl = route('posts.show', $notification->data['post_id']);
                    } else {
                        // Default: mark as read
                        $notificationUrl = route('notifications.read', $notification->id);
                    }
                @endphp

                <li class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}"
                    onclick="handleNotificationClick('{{ $notification->id }}', '{{ $notificationUrl }}', '{{ $notificationType }}', event)">

                    <div class="notification-content">
                        <span class="message">{{ $notification->data['message'] ?? 'New notification' }}</span>
                        <small class="timestamp">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>

                    <div class="notification-actions">
                        @if ($notificationType === 'FollowNotification' && isset($notification->data['show_follow_back_button']) && $notification->data['show_follow_back_button'] && isset($notification->data['follower_id']))
                            <form method="POST" action="{{ route('toggle.follow', ['id' => $notification->data['follower_id']]) }}" class="follow-form">
                                @csrf
                                <button type="submit" class="follow-back-button {{ $notification->data['follower_id'] && auth()->user()->isFollowing(\App\Models\User::find($notification->data['follower_id'])) ? 'following' : '' }}"
                                    data-user-id="{{ $notification->data['follower_id'] }}"
                                    data-is-following="{{ $notification->data['follower_id'] && auth()->user()->isFollowing(\App\Models\User::find($notification->data['follower_id'])) ? 'true' : 'false' }}"
                                    onclick="event.stopPropagation()">
                                    {{ $notification->data['follower_id'] && auth()->user()->isFollowing(\App\Models\User::find($notification->data['follower_id'])) ? 'Following' : 'Follow Back' }}
                                </button>
                            </form>
                        @endif

                        @if(is_null($notification->read_at))
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="mark-read-form">
                                @csrf
                                <button type="submit" class="mark-read-btn" title="Mark as read" onclick="event.stopPropagation()">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="no-notifications">You have no notifications.</p>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Follow button functionality
            const forms = document.querySelectorAll('.follow-form');
            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const button = form.querySelector('.follow-back-button');
                    const userId = button.getAttribute('data-user-id');
                    const isFollowing = button.getAttribute('data-is-following') === 'true';

                    button.textContent = 'Loading...';
                    button.disabled = true;

                    fetch(`/toggle-follow/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ isFollowing: isFollowing })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const newIsFollowing = data.isFollowing;
                            button.textContent = newIsFollowing ? 'Following' : 'Follow Back';
                            button.setAttribute('data-is-following', newIsFollowing);
                            button.classList.toggle('following', newIsFollowing);
                        } else {
                            alert(data.message || 'An error occurred.');
                            button.textContent = isFollowing ? 'Following' : 'Follow Back';
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Error: ' + error.message);
                        button.textContent = isFollowing ? 'Following' : 'Follow Back';
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
                });
            });

            // Mark as read button functionality
            const markReadForms = document.querySelectorAll('.mark-read-form');
            markReadForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const notificationItem = this.closest('.notification-item');
                            notificationItem.classList.remove('unread');
                            this.remove();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });

        function handleNotificationClick(notificationId, targetUrl, notificationType, event) {
            // Don't trigger if user clicked on a button or form
            if (event.target.tagName === 'BUTTON' || event.target.tagName === 'FORM' || event.target.closest('button') || event.target.closest('form')) {
                return;
            }

            // Mark notification as read via AJAX
            fetch(`/notifications/read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationItem = event.target.closest('.notification-item');
                    notificationItem.classList.remove('unread');

                    // Remove mark-as-read button if it exists
                    const markReadBtn = notificationItem.querySelector('.mark-read-form');
                    if (markReadBtn) {
                        markReadBtn.remove();
                    }

                    // Navigate to the appropriate page
                    if (targetUrl && targetUrl !== '#') {
                        window.location.href = targetUrl;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endpush
@endsection
