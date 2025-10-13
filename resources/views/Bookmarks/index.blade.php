@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Your Bookmarked Posts</h3>
    <div class="bookmarks-list mt-3">
        @forelse ($bookmarkedPosts as $post)
            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center">
                    <img src="{{ $post->user->profile_picture_url }}" 
                         alt="User Avatar" class="rounded-circle me-2" width="40" height="40">
                    <strong>{{ $post->user->username }}</strong>
                </div>
                <p class="mt-2">{{ $post->caption }}</p>
            </div>
        @empty
            <p>No bookmarks yet.</p>
        @endforelse
    </div>
</div>
@endsection
