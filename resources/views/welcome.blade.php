@extends('layouts.app')

@section('title')
    afroConnect
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
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
                $shareText = urlencode($firstPost->caption ?: 'Check out this post on afroConnect!');
                $shareHashtags = urlencode('afroConnect');
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
                            <a href="#" class="dropdown-item report-post" data-post-id="{{ $firstPost->id }}"><i class="bi bi-flag"></i>Report</a>
                            <a href="#" class="dropdown-item share-post" data-post-id="{{ $firstPost->id }}"><i class="bi bi-share"></i> Share</a>
                            <form action="{{ route('posts.bookmark', $firstPost->id) }}" method="POST" class="bookmark-toggle-form d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item bookmark-btn">
                                        <i class="bi bi-bookmark{{ auth()->user()->bookmarkedPosts->contains($firstPost->id) ? '-fill text-primary' : '' }}"></i>
                                        Save
                                    </button>
                            </form>
                                    @if(Auth::check() && Auth::id() === $user->id)
                                        <form action="{{ route('posts.destroy', $firstPost->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item delete-post-btn">
                                                <i class="bi bi-trash"></i> Delete Post
                                            </button>
                                        </form>

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

                            <form action="{{ route('posts.bookmark', $firstPost->id) }}" method="POST" class="bookmark">
                                    @csrf
                                    <button type="submit" class="icon-share-bookmark bookmark-button">
                                        <i class="bi bi-bookmark{{ auth()->user()->bookmarkedPosts->contains($firstPost->id) ? '-fill text-primary' : '' }}"></i>
                                    </button>
                            </form>
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
                            <input type="text"class="comment-input" placeholder="Add a comment..."name="content"required style="outline: none; box-shadow: none;">
                        </form>
                        <button type="button" class="submit-comment-button" data-post-id="{{ $firstPost->id }}">
                            <svg width="30" height="30" fill="currentColor" viewBox="0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21.0808 4.08454c.0817-.26553.0099-.55446-.1865-.7509-.1964-.19644-.4854-.2682-.7509-.1865L1.75863 8.80399c-.2994.09213-.51001.36063-.52817.67336-.01816.31273.15995.60385.44668.72995l8.57186 3.7716 3.7716 8.5719c.1262.2867.4173.4648.73.4467.3127-.0182.5812-.2288.6734-.5282l5.6568-18.38476ZM10.6505 12.5168 4.12458 9.64541 19.2305 4.99743l-4.648 15.10597-2.8714-6.526 3.3496-3.3495L14 9.16725l-3.3495 3.34955Z"></path></svg>
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
                                <button type="button" class="cancel-btn btn-danger cancel-report-btn">Cancel</button>
                                <button type="submit" class="submits-btn btn-danger">Submit Report</button>
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
                            <div class="social-media-grid">
                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}&hashtags={{ $shareHashtags }}" target="_blank" class="social-media-button twitter">
                                    <div class="social-media-icon"><i class="fab fa-twitter"></i></div>
                                    <span>Twitter/X</span>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="social-media-button facebook">
                                    <div class="social-media-icon"><i class="fab fa-facebook-f"></i></div>
                                    <span>Facebook</span>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" class="social-media-button whatsapp">
                                    <div class="social-media-icon"><i class="fab fa-whatsapp"></i></div>
                                    <span>WhatsApp</span>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" class="social-media-button linkedin">
                                    <div class="social-media-icon"><i class="fab fa-linkedin-in"></i></div>
                                    <span>LinkedIn</span>
                                </a>
                                <a href="https://www.instagram.com/?url={{ $shareUrl }}" target="_blank" class="social-media-button instagram">
                                    <div class="social-media-icon"><i class="fab fa-instagram"></i></div>
                                    <span>Instagram</span>
                                </a>
                                <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareText }}" target="_blank" class="social-media-button pinterest">
                                    <div class="social-media-icon"><i class="fab fa-pinterest"></i></div>
                                    <span>Pinterest</span>
                                </a>
                                <a href="https://reddit.com/submit?url={{ $shareUrl }}&title={{ $shareText }}" target="_blank" class="social-media-button reddit">
                                    <div class="social-media-icon"><i class="fab fa-reddit"></i></div>
                                    <span>Reddit</span>
                                </a>
                                <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" class="social-media-button telegram">
                                    <div class="social-media-icon"><i class="fab fa-telegram"></i></div>
                                    <span>Telegram</span>
                                </a>
                                <a href="https://www.tiktok.com/share?url={{ $shareUrl }}" target="_blank" class="social-media-button tiktok">
                                    <div class="social-media-icon"><i class="fab fa-tiktok"></i></div>
                                    <span>TikTok</span>
                                </a>
                                <a href="https://www.snapchat.com/scan?attachmentUrl={{ $shareUrl }}" target="_blank" class="social-media-button snapchat">
                                    <div class="social-media-icon"><i class="fab fa-snapchat"></i></div>
                                    <span>Snapchat</span>
                                </a>
                                <a href="mailto:?subject=Check out this post on afroConnect&body={{ $shareText }}%0A%0A{{ $shareUrl }}" class="social-media-button email">
                                    <div class="social-media-icon"><i class="fas fa-envelope"></i></div>
                                    <span>Email</span>
                                </a>
                                <button class="social-media-button copy-link" onclick="copyToClipboard('{{ route('posts.show', $firstPost->id) }}')">
                                    <div class="social-media-icon"><i class="fas fa-link"></i></div>
                                    <span>Copy Link</span>
                                </button>
                            </div>
                        </div>
                        @if(Auth::check())
                            <div class="share-modal-actions">
                                <button class="cancel-share-btn">Cancel</button>
                                <button class="confirm-share-btn" data-post-id="{{ $firstPost->id }}">Share</button>
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
            // Report Modal Logic
            const reportButtons = document.querySelectorAll('.report-post');
            reportButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const postId = button.getAttribute('data-post-id');
                    const modal = document.getElementById(`reportModal-${postId}`);
                    modal.style.display = 'flex';
                    setTimeout(() => {
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');
                    }, 10);
                });
            });

            const cancelReportButtons = document.querySelectorAll('.cancel-report-btn');
            cancelReportButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.report-modal');
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
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

            // Copy to clipboard function
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    alert('Link copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        alert('Link copied to clipboard!');
                    } catch (err) {
                        alert('Failed to copy link. Please try again.');
                    }
                    document.body.removeChild(textArea);
                });
            }

            // Make copyToClipboard available globally
            window.copyToClipboard = copyToClipboard;
        });
    </script>
@endsection
