<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('posts.{postId}', function ($user, $postId) {
    return true; // Or add permission logic
});
Broadcast::channel('comments.{postId}', function ($user, $postId) {
    return true; // Allow all authenticated users to listen to comments on a post
});
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId; // Only allow the user to listen to their own notifications
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
