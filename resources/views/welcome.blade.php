@extends('layouts.app')

@section('title', 'Home')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
<div class="posts-container">
    <div class="feed posts-grid">
        @forelse($postGroups as $group)
            @php
                $firstPost = $group->first();
                $user = $firstPost->user;
                $userHasStories = isset($stories[$user->id]) && $stories[$user->id]->isNotEmpty();
            @endphp
            <div class="post-card" data-post-id="{{ $firstPost->id }}">

                {{-- Post Header --}}
                <div class="post-header">
                    <div class="user-info-container">
                        <a href="" class="profile-avatar-wrapper @if($userHasStories) has-stories @endif">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" class="avatar" alt="Profile Picture">
                            @else
                                <i class="bi bi-person-circle avatar-placeholder"></i>
                            @endif
                        </a>
                        <div class="username-time-group">
                            <a href="{{ route('user.profile', $user->id) }}" class="post-username">{{ $user->username }}</a>
                            <span class="post-time">{{ \App\Helpers\TimeFormatter::formatDiffForHumansAbbreviated($firstPost->created_at) }}</span>
                        </div>

                        @if(Auth::check() && Auth::id() !== $user->id)
                            <button class="follow-btn {{ Auth::user()->isFollowing($user) ? 'following' : '' }}" data-user-id="{{ $user->id }}">
                                {{ Auth::user()->isFollowing($user) ? 'Following' : 'Follow' }}
                            </button>
                        @endif
                    </div>

                    <div class="post-menu-dropdown">
                        <div class="menu-dot"><i class="bi bi-three-dots"></i></div>
                        <div class="dropdown-content">
                            <a href="#" class="dropdown-item report-post" data-post-id="{{ $firstPost->id }}"><i class="bi bi-flag"></i> Report</a>
                            <a href="#" class="dropdown-item not-interested" data-post-id="{{ $firstPost->id }}"><i class="bi bi-eye-slash"></i> Not interested</a>
                            <a href="{{ route('post.share', $firstPost->id) }}" class="dropdown-item"><i class="bi bi-share"></i> Share</a>
                            <a href="{{ route('post.bookmark', $firstPost->id) }}" class="dropdown-item"><i class="bi bi-bookmark"></i> Saved</a>
                            @if(Auth::check() && Auth::id() === $user->id)
                                <a href="#" class="dropdown-item delete-post" data-post-id="{{ $firstPost->id }}"><i class="bi bi-trash"></i> Delete Post</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Caption --}}
                <p class="post-caption">{{ $firstPost->caption }}</p>

                {{-- Media --}}
                @if($firstPost->media->isNotEmpty())
                    <div class="post-media-carousel relative">
                        <div class="carousel-inner" data-post-id="{{ $firstPost->id }}">
                            @foreach($firstPost->media as $media)
                                <div class="carousel-item">
                                    @if($media->file_type === 'image')
                                        <img src="{{ asset('storage/' . $media->file_path) }}" class="post-image" alt="Post Image">
                                    @elseif($media->file_type === 'video')
                                        <video controls class="post-video" autoplay muted loop>
                                            <source src="{{ asset('storage/' . $media->file_path) }}" type="{{ $media->mime_type }}">
                                        </video>
                                    @elseif($media->file_type === 'audio')
                                        <audio controls class="post-audio">
                                            <source src="{{ asset('storage/' . $media->file_path) }}" type="{{ $media->mime_type }}">
                                        </audio>
                                    @elseif($media->file_type === 'text')
                                        <div class="text-content"><p>{{ $media->text_content }}</p></div>
                                    @endif

                                    @if($media->sound_path && $media->file_type !== 'audio')
                                        <audio controls class="post-audio-overlay">
                                            <source src="{{ asset('storage/' . $media->sound_path) }}" type="audio/mpeg">
                                        </audio>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($firstPost->media->count() > 1)
                            <button class="carousel-arrow left-arrow"><i class="fa-solid fa-chevron-left"></i></button>
                            <button class="carousel-arrow right-arrow"><i class="fa-solid fa-chevron-right"></i></button>
                        @endif
                    </div>
                @endif

                {{-- Bottom Section --}}
                <div class="post-bottom-section">
                    <div class="post-actions">
                        <div class="icons">
                            <a class="like-button action-btn" data-post-id="{{ $firstPost->id }}">
                                <i class="bi {{ Auth::check() && Auth::user()->hasLiked($firstPost) ? 'bi-heart-fill liked' : 'bi-heart' }}"></i>
                            </a>
                            <span class="likes-count">{{ $firstPost->likes->count() }}</span>
                            <a class="comment-button show-comments-btn" data-post-id="{{ $firstPost->id }}">
                                <i class="fa-regular fa-comment"></i>
                            </a>
                            <span class="comments-count">{{ $firstPost->comments->count() }}</span>
                            <a href="{{ route('post.share', $firstPost->id) }}" class="share-button">
                               <i class="fa-regular fa-paper-plane"></i>
                            </a>
                        </div>
                        <div class="icon-share-bookmark">
                            <a href="{{ route('post.bookmark', $firstPost->id) }}" class="bookmark-button"><i class="fa-regular fa-bookmark"></i></a>
                        </div>
                    </div>
                </div>

                {{-- Comments --}}
                <div class="recent-comments-section px-3 py-2">
                    @foreach($firstPost->comments->sortByDesc('created_at')->take(3) as $comment)
                        <div class="recent-comment-item mb-1">
                            <a href="{{ route('user.profile', $comment->user->id) }}" class="comment-username font-weight-bold mr-1">{{ $comment->user->username }}</a>
                            <span class="comment-text">{{ $comment->content }}</span>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('posts.show', $firstPost->id) }}" class="view-comments-link" data-post-id="{{ $firstPost->id }}">View all comments</a>

                {{-- Add Comment Section --}}
                <div class="add-comments">
                    <div class="add-comment-avatar-wrapper @if(isset($stories[Auth::id()]) && $stories[Auth::id()]->isNotEmpty()) has-stories @endif">
                        @if(Auth::check() && Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="avatar" alt="My Profile Picture">
                        @else
                            <i class="bi bi-person-circle avatar-placeholder"></i>
                        @endif
                    </div>
                    <div class="comment-input-wrapper">
                        <form class="comment-form" data-post-id="{{ $firstPost->id }}">
                            <input type="text" class="comment-input" placeholder="Add a comment..." name="content" required>
                        </form>
                        <button type="button" class="submit-comment-button" data-post-id="{{ $firstPost->id }}">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="no-posts-message">No posts yet. Be the first to create one!</p>
        @endforelse
    </div>
</div>
@endsection

<script>
    function toggleModal(modalElement, show) {
        if (modalElement) {
            modalElement.style.display = show ? 'flex' : 'none';
        }
    }

    function previewMedia(input) {
        const preview = input.nextElementSibling;
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileType = file.type;
                if (fileType.startsWith('image/')) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; height: auto;">`;
                } else if (fileType.startsWith('video/')) {
                    preview.innerHTML = `<video controls style="max-width: 100%;"><source src="${e.target.result}" type="${fileType}"></video>`;
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>

@section('scripts')
<script src="{{ asset('js/welcome.js') }}"></script>
@endsection
