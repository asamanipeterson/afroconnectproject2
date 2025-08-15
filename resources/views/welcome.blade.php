@extends('layouts.app')

@section('title')
    afroConnect
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
        .report-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .report-modal-content { background: white; width: 90%; max-width: 500px; margin: 100px auto; padding: 20px; border-radius: 8px; }
        .report-modal-content h2 { margin-bottom: 20px; }
        .report-modal-content .form-label { font-weight: 500; }
        .report-modal-content .form-control { resize: vertical; }
        .report-modal-actions { display: flex; justify-content: flex-end; gap: 10px; }
        .verified-badge { color: #3498db; margin-left:0px; font-size: 0.9em; }
    </style>
@endsection

@section('content')
<div class="posts-container">
    <div class="feed posts-grid">
        @forelse($postGroups as $group)
            @php
                $firstPost = $group->first();
                $user = $firstPost->user;
                $userHasStories = isset($stories[$user->id]) && $stories[$user->id]->isNotEmpty();
                $shareUrl = urlencode(route('posts.show', $firstPost->id));
                $shareText = urlencode($firstPost->caption ?: 'Check out this post!');
            @endphp
            <div class="post-card" data-post-id="{{ $firstPost->id }}">
                <div class="post-header">
                    <div class="user-info-container">
                        <a href="{{ route('user.profile', $user->id) }}" class="profile-avatar-wrapper @if($userHasStories) has-stories @endif">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" class="avatar" alt="Profile Picture">
                            @else
                                <i class="bi bi-person-circle avatar-placeholder"></i>
                            @endif
                        </a>
                        <div class="username-time-group">
                            <a href="{{ route('user.profile', $user->id) }}" class="post-username">
                                {{ $user->username }}
                                @if($user->is_verified)
                                <i class="bi bi-check-circle-fill verified-badge" title="Verified User"></i>
                            @endif
                            </a>
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
                            <a href="#" class="dropdown-item share-post" data-post-id="{{ $firstPost->id }}"><i class="bi bi-share"></i> Share</a>
                            <a href="{{ route('post.bookmark', $firstPost->id) }}" class="dropdown-item"><i class="bi bi-bookmark"></i> Saved</a>
                            @if(Auth::check() && Auth::id() === $user->id)
                                <a href="#" class="dropdown-item delete-post" data-post-id="{{ $firstPost->id }}"><i class="bi bi-trash"></i> Delete Post</a>
                            @endif
                        </div>
                    </div>
                </div>
                <p class="post-caption">{{ $firstPost->caption }}</p>
                @if($firstPost->media->isNotEmpty())
                    <div class="post-media-carousel">
                        <div class="carousel-inner" data-post-id="{{ $firstPost->id }}">
                            @foreach($firstPost->media as $media)
                                <div class="carousel-item">
                                    @if($media->file_type === 'image')
                                        <img src="{{ asset('storage/' . $media->file_path) }}" class="post-image" alt="Post Image">
                                    @elseif($media->file_type === 'video')
                                        <div class="video-container">
                                            <video class="post-video" autoplay muted loop>
                                                <source src="{{ asset('storage/' . $media->file_path) }}" type="{{ $media->mime_type }}">
                                            </video>
                                            <div class="custom-video-controls">
                                                <div class="top-controls">
                                                    <span class="time-display">0:00</span>
                                                </div>
                                                <div class="bottom-controls">
                                                    <button class="play-pause-btn"><i class="bi bi-pause-fill"></i></button>
                                                    <input type="range" class="seek-bar" value="0" min="0" max="100">
                                                    <button class="mute-btn"><i class="bi bi-volume-mute-fill"></i></button>
                                                </div>
                                            </div>
                                        </div>
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
                            <button class="carousel-arrow left-arrow" id="prevArrow-{{ $firstPost->id }}">&#10094;</button>
                            <button class="carousel-arrow right-arrow" id="nextArrow-{{ $firstPost->id }}">&#10095;</button>
                            <div class="carousel-dots" id="carouselDots-{{ $firstPost->id }}"></div>
                            <div class="carousel-counter" id="carouselCounter-{{ $firstPost->id }}">
                                <span id="currentSlide-{{ $firstPost->id }}">1</span>/<span id="totalSlides-{{ $firstPost->id }}">{{ $firstPost->media->count() }}</span>
                            </div>
                        @endif
                    </div>
                @endif
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
                            <a href="#" class="share-button share-post" data-post-id="{{ $firstPost->id }}">
                                <svg width="24" height="24" fill="green" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21.0808 4.08454c.0817-.26553.0099-.55446-.1865-.7509-.1964-.19644-.4854-.2682-.7509-.1865L1.75863 8.80399c-.2994.09213-.51001.36063-.52817.67336-.01816.31273.15995.60385.44668.72995l8.57186 3.7716 3.7716 8.5719c.1262.2867.4173.4648.73.4467.3127-.0182.5812-.2288.6734-.5282l5.6568-18.38476ZM10.6505 12.5168 4.12458 9.64541 19.2305 4.99743l-4.648 15.10597-2.8714-6.526 3.3496-3.3495L14 9.16725l-3.3495 3.34955Z"></path></svg>
                            </a>
                        </div>
                        <div class="icon-share-bookmark">
                            <a href="{{ route('post.bookmark', $firstPost->id) }}" class="bookmark-button"><i class="bi bi-bookmark"></i></a>
                        </div>
                    </div>
                </div>
                <div class="recent-comments-section px-3 py-2">
                    @foreach($firstPost->comments->sortByDesc('created_at')->take(3) as $comment)
                        <div class="recent-comment-item mb-1">
                            <a href="{{ route('user.profile', $comment->user->id) }}" class="comment-username font-weight-bold mr-1">{{ $comment->user->username }}
                                @if($comment->user->is_verified)
                                    <i class="bi bi-check-circle-fill verified-badge" title="Verified User"></i>
                                @endif
                            </a>
                            <span class="comment-text">{{ $comment->content }}</span>
                        </div>
                    @endforeach
                </div>
                <a href="" class="views-comment">View all {{ $firstPost->comments->count() }} comments</a>
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
                            <svg width="30" height="30" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21.0808 4.08454c.0817-.26553.0099-.55446-.1865-.7509-.1964-.19644-.4854-.2682-.7509-.1865L1.75863 8.80399c-.2994.09213-.51001.36063-.52817.67336-.01816.31273.15995.60385.44668.72995l8.57186 3.7716 3.7716 8.5719c.1262.2867.4173.4648.73.4467.3127-.0182.5812-.2288.6734-.5282l5.6568-18.38476ZM10.6505 12.5168 4.12458 9.64541 19.2305 4.99743l-4.648 15.10597-2.8714-6.526 3.3496-3.3495L14 9.16725l-3.3495 3.34955Z"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="report-modal" id="reportModal-{{ $firstPost->id }}">
                    <div class="report-modal-content">
                        <h2>Report Post</h2>
                        <form action="{{ route('posts.report', $firstPost->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="reason-{{ $firstPost->id }}" class="form-label">Reason for Report</label>
                                <select class="form-control" id="reason-{{ $firstPost->id }}" name="reason" required>
                                    <option value="" disabled selected>Select a reason</option>
                                    <option value="harassment">Harassment</option>
                                    <option value="spam">Spam</option>
                                    <option value="inappropriate">Inappropriate Content</option>
                                    <option value="hate_speech">Hate Speech</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3" id="details-group-{{ $firstPost->id }}" style="display: none;">
                                <label for="details-{{ $firstPost->id }}" class="form-label">Details (required for 'Other')</label>
                                <textarea class="form-control" id="details-{{ $firstPost->id }}" name="details" rows="4" maxlength="500"></textarea>
                            </div>
                            <div class="report-modal-actions">
                                <button type="button" class="btn btn-secondary cancel-report-btn">Cancel</button>
                                <button type="submit" class="btn btn-danger">Submit Report</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="share-modal" id="shareModal-{{ $firstPost->id }}">
                    <div class="share-modal-content">
                        <h2>Share Post</h2>
                        @if(Auth::check())
                            <div class="followers-list">
                                <h3>Share with Followers</h3>
                                <input type="text" class="follower-search" placeholder="Search followers...">
                                <div class="followers-container">
                                    @foreach(Auth::user()->following as $follower)
                                        <div class="follower-item">
                                            <input type="checkbox" name="followers" value="{{ $follower->id }}">
                                            <div class="follower-avatar-wrapper">
                                                @if($follower->profile_picture)
                                                    <img src="{{ asset('storage/' . $follower->profile_picture) }}" class="avatar" alt="{{ $follower->username }}'s Profile Picture">
                                                @else
                                                    <i class="bi bi-person-circle avatar-placeholder"></i>
                                                @endif
                                            </div>
                                            <span>{{ $follower->username }}</span>
                                            @if($follower->is_verified)
                                                <i class="bi bi-check-circle-fill verified-badge" title="Verified User"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="social-media-list">
                            <h3>Share to Social Media</h3>
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" class="social-media-button twitter">
                                <i class="fab fa-twitter"></i> Twitter/X
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="social-media-button facebook">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" class="social-media-button whatsapp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" class="social-media-button linkedin">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                            <a href="mailto:?subject=Check out this post&body={{ $shareText }}%20{{ $shareUrl }}" class="social-media-button email">
                                <i class="fas fa-envelope"></i> Email
                            </a>
                            <a href="https://www.instagram.com/share?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" class="social-media-button instagram">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                            <a href="https://www.tiktok.com/share?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" class="social-media-button tiktok">
                                <i class="fab fa-tiktok"></i> TikTok
                            </a>
                            <a href="https://www.youtube.com/share?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" class="social-media-button youtube">
                                <i class="fab fa-youtube"></i> YouTube
                            </a>
                            <a href="https://www.snapchat.com/share?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" class="social-media-button snapchat">
                                <i class="fab fa-snapchat-ghost"></i> Snapchat
                            </a>
                        </div>
                        @if(Auth::check())
                            <div class="share-modal-actions">
                                <button class="cancel-share-btn">Cancel</button>
                                <button class="confirm-share-btn" data-post-id="{{ $firstPost->id }}">Share with Followers</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="no-posts-message">No posts yet. Be the first to create one!</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/welcome.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reportButtons = document.querySelectorAll('.report-post');
            reportButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const postId = button.getAttribute('data-post-id');
                    const modal = document.getElementById(`reportModal-${postId}`);
                    modal.style.display = 'block';
                });
            });

            const cancelButtons = document.querySelectorAll('.cancel-report-btn');
            cancelButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.report-modal');
                    modal.style.display = 'none';
                });
            });

            const reasonSelects = document.querySelectorAll('.report-modal-content select[name="reason"]');
            reasonSelects.forEach(select => {
                const postId = select.id.replace('reason-', '');
                const detailsGroup = document.getElementById(`details-group-${postId}`);
                const detailsTextarea = document.getElementById(`details-${postId}`);
                select.addEventListener('change', () => {
                    detailsGroup.style.display = select.value === 'other' ? 'block' : 'none';
                    detailsTextarea.required = select.value === 'other';
                });
            });
        });
    </script>
@endsection
