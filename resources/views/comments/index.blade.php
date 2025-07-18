@extends('layouts.app')

@section('title', 'Comments for ' . $post->user->username . '\'s Post')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/posts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comments.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection

@section('content')
<div class="comments-page-container">
    {{-- Re-adding the single post-card from your home.blade.php --}}
    <div class="post-card" data-post-id="{{ $post->id }}">

        <div class="post-header">
            <div class="user-info-container">
                <a href="{{ route('user.profile', $post->user->id) }}" class="profile-avatar-wrapper">
                    @if($post->user->profile_picture)
                        <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="avatar" alt="Profile Picture">
                    @else
                        <i class="bi bi-person-circle avatar-placeholder"></i>
                    @endif
                </a>
                <div class="username-time-group">
                    <a href="{{ route('user.profile', $post->user->id) }}" class="post-username">{{ $post->user->username }}</a>
                    <span class="post-time">
                        {{ \App\Helpers\TimeFormatter::formatDiffForHumansAbbreviated($post->created_at) }}
                    </span>
                </div>

                @if(Auth::check() && Auth::id() !== $post->user->id)
                    <button class="follow-btn {{ Auth::user()->isFollowing($post->user) ? 'following' : '' }}"
                            data-user-id="{{ $post->user->id }}">
                        {{ Auth::user()->isFollowing($post->user) ? 'Following' : 'Follow' }}
                    </button>
                @endif
            </div>

            <div class="post-menu-dropdown">
                <div class="menu-dot">
                    <i class="bi bi-three-dots"></i>
                </div>
                <div class="dropdown-content">
                    <a href="#" class="dropdown-item report-post" data-post-id="{{ $post->id }}">
                        <i class="bi bi-flag"></i> Report
                    </a>
                    <a href="#" class="dropdown-item not-interested" data-post-id="{{ $post->id }}">
                        <i class="bi bi-eye-slash"></i> Not interested
                    </a>
                    <a href="{{ route('post.share', $post->id) }}" class="dropdown-item">
                        <i class="bi bi-share"></i> Share
                    </a>
                    <a href="{{ route('post.bookmark', $post->id) }}" class="dropdown-item">
                        <i class="bi bi-bookmark"></i> Saved
                    </a>
                    @if(Auth::check() && Auth::id() === $post->user->id)
                        <a href="#" class="dropdown-item delete-post" data-post-id="{{ $post->id }}">
                            <i class="bi bi-trash"></i> Delete Post
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <p class="post-caption">
            {{ $post->caption }}
        </p>

        @if($post->file_path || $post->text_content) {{-- Check if there's any media or text content --}}
            <div class="post-media-carousel relative">
                <div class="carousel-inner" data-post-id="{{ $post->id }}">
                    {{-- Assuming $post itself can be iterated if it's a single item for carousel --}}
                    {{-- If $post is a single model, and you need to loop for multiple media files associated with it,
                         you'd need a 'media' relationship on the Post model. For now, assuming $post is the single item --}}
                    <div class="carousel-item">
                        @if($post->file_type === 'image')
                            <img src="{{ asset('storage/' . $post->file_path) }}" alt="Post Media" class="post-image">
                        @elseif($post->file_type === 'video')
                            <video controls class="post-video" autoplay muted loop>
                                <source src="{{ asset('storage/' . $post->file_path) }}" type="{{ $post->mime_type }}">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($post->file_type === 'audio')
                            <audio controls class="post-audio">
                                <source src="{{ asset('storage/' . ($post->sound_path ?: $post->file_path)) }}" type="{{ $post->mime_type }}">
                                Your browser does not support the audio element.
                            </audio>
                            <p class="audio-text-fallback">Audio Playback</p>
                        @elseif($post->file_type === 'text')
                            <div class="text-content">
                                <p>{{ $post->text_content }}</p>
                            </div>
                        @endif

                         @if($post->sound_path && $post->file_type !== 'audio')
                            <audio controls class="post-audio-overlay">
                                <source src="{{ asset('storage/' . $post->sound_path) }}" type="{{ str_contains($post->sound_path, '.mp3') ? 'audio/mpeg' : 'audio/wav' }}">
                                Your browser does not support the audio element.
                            </audio>
                        @endif
                    </div>
                    {{-- If a single post can have multiple media files, your Post model would need a 'media' relationship
                         and this loop would be @foreach($post->media as $media_item) --}}
                </div>

                {{-- Carousel arrows are usually for multiple items within one post.
                     If your 'post' can only have one file, remove these arrows.
                     If a post can have multiple media, you need a different way to structure your Post model
                     and iterate through its media (e.g., $post->media->count() > 1) --}}
                {{-- For simplicity, if $post represents a single piece of media now, these won't show. --}}
                {{-- If you had $group->count() > 1, that was for multiple posts in a carousel on home page.
                     For a single post with multiple media, you need $post->media->count() > 1 --}}
                {{-- Example if a Post had a 'media_files' relationship that returns a collection: --}}
                {{-- @if($post->media_files->count() > 1)
                    <button class="carousel-arrow left-arrow" data-direction="prev" data-post-id="{{ $post->id }}">
                        <i class="bi bi-arrow-left-short"></i>
                    </button>
                    <button class="carousel-arrow right-arrow" data-direction="next" data-post-id="{{ $post->id }}">
                        <i class="bi bi-arrow-right-short"></i>
                    </button>
                @endif --}}
            </div>
        @endif


        <div class="post-bottom-section">
            <div class="post-stats">
                @php
                    $likeCount = $post->likes->count();
                    $commentCount = $post->comments->count();
                @endphp

                <span class="likes-count">
                    {{ $likeCount }} {{ $likeCount === 1 ? 'like' : 'likes' }}
                </span>
                <span class="comments-count">{{ $commentCount }} {{ $commentCount === 1 ? 'Comment' : 'Comments' }}</span>

                <span class="shares-count">Shares</span>
            </div>

            <div class="post-actions">
                <div class="icons">
                    <a class="like-button" data-post-id="{{ $post->id }}">
                        <i class="bi {{ Auth::check() && Auth::user()->hasLiked($post) ? 'bi-heart-fill liked' : 'bi-heart' }}"></i>
                    </a>
                    {{-- On comments page, the comment button should probably just scroll down or do nothing --}}
                    <a href="javascript:void(0);" class="comment-button" onclick="document.getElementById('add-comment-form-container').scrollIntoView({behavior: 'smooth'});">
                        <i class="bi bi-chat"></i>
                    </a>

                    <a href="{{ route('post.share', $post->id) }}" class="share-button">
                        <i class="bi bi-send"></i>
                    </a>
                </div>
                <div class="icon-share-bookmark">
                    <a href="{{ route('post.bookmark', $post->id) }}" class="bookmark-button">
                        <i class="bi bi-bookmark"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Removed recent-comments-section and View All Comments Link --}}
        {{-- as this page is for all comments, not just recent ones --}}

    </div> {{-- End of .post-card --}}

    <h3 class="comments-count-display mt-4">All Comments ({{ $comments->total() }})</h3> {{-- Use $comments->total() for pagination --}}
    <hr class="my-3">

    {{-- Add New Comment Form for the original post --}}
    @auth
        <div class="add-comments mb-4" id="add-comment-form-container"> {{-- Added ID for scrolling --}}
            <div class="add-comment-avatar-wrapper">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="avatar" alt="My Profile Picture">
                @else
                    <i class="bi bi-person-circle avatar-placeholder"></i>
                @endif
            </div>
            <div class="comment-input-wrapper">
                <form class="comment-form" data-post-id="{{ $post->id }}">
                    @csrf
                    <input type="text" name="content" placeholder="Add a comment..." class="comment-input" required>
                </form>
                <button type="button" class="submit-comment-button" data-post-id="{{ $post->id }}">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    @endauth

    {{-- List all comments --}}
    <div class="comments-list">
        @forelse($comments as $comment)
            <div class="comment-card" data-comment-id="{{ $comment->id }}">
                <div class="comment-header">
                    <div class="comment-user-info">
                        <a href="{{ route('user.profile', $comment->user->id) }}" class="comment-avatar-wrapper">
                            @if($comment->user->profile_picture)
                                <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" alt="Profile Picture">
                            @else
                                <i class="bi bi-person-circle avatar-placeholder"></i>
                            @endif
                        </a>
                        <a href="{{ route('user.profile', $comment->user->id) }}" class="comment-username">{{ $comment->user->username }}</a>
                        <span class="comment-time">{{ \App\Helpers\TimeFormatter::formatDiffForHumansAbbreviated($comment->created_at) }}</span>
                    </div>
                    {{-- Comment menu (e.g., delete, edit) if needed --}}
                </div>
                <div class="comment-body">
                    <p class="comment-text">{{ $comment->content }}</p>
                </div>
                <div class="comment-actions">
                    <a href="#" class="like-comment-button" data-comment-id="{{ $comment->id }}">Like</a>
                    <a href="javascript:void(0);" class="reply-button" data-comment-id="{{ $comment->id }}">Reply</a>
                </div>

                {{-- Nested Replies --}}
                @if($comment->replies->isNotEmpty())
                    <div class="nested-comments mt-3">
                        @foreach($comment->replies as $reply)
                            <div class="comment-card nested-comment-card" data-comment-id="{{ $reply->id }}">
                                <div class="comment-header">
                                    <div class="comment-user-info">
                                        <a href="{{ route('user.profile', $reply->user->id) }}" class="comment-avatar-wrapper">
                                            @if($reply->user->profile_picture)
                                                <img src="{{ asset('storage/' . $reply->user->profile_picture) }}" alt="Profile Picture">
                                            @else
                                                <i class="bi bi-person-circle avatar-placeholder"></i>
                                            @endif
                                        </a>
                                        <a href="{{ route('user.profile', $reply->user->id) }}" class="comment-username">{{ $reply->user->username }}</a>
                                        <span class="comment-time">{{ \App\Helpers\TimeFormatter::formatDiffForHumansAbbreviated($reply->created_at) }}</span>
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p class="comment-text">{{ $reply->content }}</p>
                                </div>
                                <div class="comment-actions">
                                    <a href="#" class="like-comment-button" data-comment-id="{{ $reply->id }}">Like</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Reply Form for this specific comment (initially hidden, shown by JS) --}}
                @auth
                    <div class="reply-form-container mt-2" id="reply-form-{{ $comment->id }}" style="display:none;">
                        <div class="add-comment-avatar-wrapper">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="avatar" alt="My Profile Picture">
                            @else
                                <i class="bi bi-person-circle avatar-placeholder"></i>
                            @endif
                        </div>
                        <div class="comment-input-wrapper">
                            <form class="reply-form" data-parent-comment-id="{{ $comment->id }}" data-post-id="{{ $post->id }}">
                                @csrf
                                <input type="text" name="content" placeholder="Reply to {{ $comment->user->username }}..." class="reply-input" required>
                            </form>
                            <button type="button" class="submit-reply-button" data-parent-comment-id="{{ $comment->id }}" data-post-id="{{ $post->id }}">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                @endauth
            </div>
        @empty
            <p class="no-comments-message">No comments yet. Be the first to comment!</p>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    <div class="comments-pagination mt-4">
        {{ $comments->links() }}
    </div>

</div>
@endsection

@section('scripts')
    {{-- Include the necessary JavaScript for carousels, likes, and follows --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Follow Button Functionality --- (copied from welcome.js)
            document.addEventListener('click', function (e) {
                const followBtn = e.target.closest('.follow-btn');
                if (followBtn) {
                    const userId = followBtn.dataset.userId;

                    fetch(`/toggle-follow/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'followed') {
                            followBtn.classList.add('following');
                            followBtn.textContent = 'Following';
                        } else if (data.status === 'unfollowed') {
                            followBtn.classList.remove('following');
                            followBtn.textContent = 'Follow';
                        }
                    })
                    .catch(error => console.error('Follow error:', error));
                }
            });

            // --- Post Menu Dropdown Functionality ---
            document.querySelectorAll('.post-menu-dropdown').forEach(dropdown => {
                const menuDot = dropdown.querySelector('.menu-dot');
                const dropdownContent = dropdown.querySelector('.dropdown-content');

                menuDot.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent document click from closing immediately
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown-content.show').forEach(openDropdown => {
                        if (openDropdown !== dropdownContent) {
                            openDropdown.classList.remove('show');
                        }
                    });
                    dropdownContent.classList.toggle('show');
                });

                // Close dropdown if clicked outside
                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target)) {
                        dropdownContent.classList.remove('show');
                    }
                });
            });


            // --- Carousel Functionality --- (copied from welcome.js, adapted for single post)
            document.querySelectorAll('.post-media-carousel').forEach(carousel => {
                const carouselInner = carousel.querySelector('.carousel-inner');
                const items = carouselInner.querySelectorAll('.carousel-item');
                const leftArrow = carousel.querySelector('.left-arrow');
                const rightArrow = carousel.querySelector('.right-arrow');
                let currentIndex = 0;
                let startX = 0;
                let isDragging = false;

                function updateCarousel() {
                    if (items.length === 0) return;

                    const itemWidth = items[0].offsetWidth;
                    if (itemWidth === 0 && items.length > 0) {
                        setTimeout(updateCarousel, 50);
                        return;
                    }

                    carouselInner.scrollTo({
                        left: currentIndex * itemWidth,
                        behavior: 'smooth'
                    });

                    if (leftArrow) leftArrow.style.display = (currentIndex === 0 || items.length <= 1) ? 'none' : 'flex';
                    if (rightArrow) rightArrow.style.display = (currentIndex >= items.length - 1 || items.length <= 1) ? 'none' : 'flex';
                }

                updateCarousel();
                window.addEventListener('resize', updateCarousel);

                if (leftArrow) {
                    leftArrow.addEventListener('click', () => {
                        if (currentIndex > 0) {
                            currentIndex--;
                            updateCarousel();
                        }
                    });
                }
                if (rightArrow) {
                    rightArrow.addEventListener('click', () => {
                        if (currentIndex < items.length - 1) {
                            currentIndex++;
                            updateCarousel();
                        }
                    });
                }

                carouselInner.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    isDragging = true;
                    carouselInner.style.scrollBehavior = 'auto';
                });

                carouselInner.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    const currentX = e.touches[0].clientX;
                    const diffX = startX - currentX;

                    if (Math.abs(diffX) > 30) {
                        if (diffX > 0 && currentIndex < items.length - 1) {
                            currentIndex++;
                        } else if (diffX < 0 && currentIndex > 0) {
                            currentIndex--;
                        }
                        updateCarousel();
                        isDragging = false;
                        e.preventDefault();
                    }
                });

                carouselInner.addEventListener('touchend', () => {
                    isDragging = false;
                    carouselInner.style.scrollBehavior = 'smooth';
                });

                let isMouseDown = false;
                let mouseStartX = 0;
                let scrollLeftStart = 0;

                carouselInner.addEventListener('mousedown', (e) => {
                    isMouseDown = true;
                    mouseStartX = e.clientX;
                    scrollLeftStart = carouselInner.scrollLeft;
                    carouselInner.style.scrollBehavior = 'auto';
                    carouselInner.style.cursor = 'grabbing';
                });

                carouselInner.addEventListener('mousemove', (e) => {
                    if (!isMouseDown) return;
                    e.preventDefault();
                    const mouseMoveX = e.clientX - mouseStartX;
                    carouselInner.scrollLeft = scrollLeftStart - mouseMoveX;
                });

                carouselInner.addEventListener('mouseup', () => {
                    if (!isMouseDown) return;
                    isMouseDown = false;
                    const finalScrollLeft = carouselInner.scrollLeft;
                    const itemWidth = items[0]?.offsetWidth || 0;
                    if (itemWidth > 0) {
                        currentIndex = Math.round(finalScrollLeft / itemWidth);
                        updateCarousel();
                    }
                    carouselInner.style.scrollBehavior = 'smooth';
                    carouselInner.style.cursor = 'grab';
                });

                carouselInner.addEventListener('mouseleave', () => {
                    if (isMouseDown) { // If mouse leaves while dragging
                        isMouseDown = false;
                        const finalScrollLeft = carouselInner.scrollLeft;
                        const itemWidth = items[0]?.offsetWidth || 0;
                        if (itemWidth > 0) {
                            currentIndex = Math.round(finalScrollLeft / itemWidth);
                            updateCarousel();
                        }
                        carouselInner.style.scrollBehavior = 'smooth';
                        carouselInner.style.cursor = 'grab';
                    }
                });
            });


            // --- Basic Like Button Functionality (for posts) --- (copied from welcome.js)
            document.addEventListener('click', function(e) {
                const likeButton = e.target.closest('.like-button'); // For post likes
                if (likeButton) {
                    e.preventDefault();
                    handlePostLike(likeButton);
                }
            });

            async function handlePostLike(button) {
                const postId = button.dataset.postId;
                const heartIcon = button.querySelector('i');
                const likesCountSpan = button.closest('.post-card').querySelector('.likes-count');

                try {
                    const response = await fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        if (response.status === 401) {
                            window.location.href = '/login';
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.liked) {
                        heartIcon.classList.remove('bi-heart');
                        heartIcon.classList.add('bi-heart-fill', 'liked');
                        heartIcon.style.transform = 'scale(1.2)';
                        setTimeout(() => {
                            heartIcon.style.transform = 'scale(1)';
                        }, 200);
                    } else {
                        heartIcon.classList.remove('bi-heart-fill', 'liked');
                        heartIcon.classList.add('bi-heart');
                    }

                    if (likesCountSpan) {
                        likesCountSpan.textContent = `${data.likes_count} likes`;
                    }

                } catch (error) {
                    console.error('Error handling like:', error);
                }
            }


            // --- Comment Submission Functionality (from previous comments.blade.php) ---
            async function handleCommentSubmission(formElement, isReply = false) {
                const postId = formElement.dataset.postId;
                const commentInput = formElement.querySelector('input[name="content"]');
                const commentContent = commentInput.value.trim();
                const parentCommentId = formElement.dataset.parentCommentId;

                if (!commentContent) {
                    return;
                }

                let apiUrl = `/posts/${postId}/comments`;
                let payload = { content: commentContent };

                if (isReply) {
                    apiUrl = `/comments/${parentCommentId}/reply`;
                }

                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        if (response.status === 401) {
                            window.location.href = '/login';
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (isReply) {
                        const parentCommentCard = document.querySelector(`.comment-card[data-comment-id="${parentCommentId}"]`);
                        let nestedCommentsContainer = parentCommentCard.querySelector('.nested-comments');

                        if (!nestedCommentsContainer) {
                            nestedCommentsContainer = document.createElement('div');
                            nestedCommentsContainer.classList.add('nested-comments', 'mt-3');
                            parentCommentCard.appendChild(nestedCommentsContainer);
                        }

                        const newReplyHtml = `
                            <div class="comment-card nested-comment-card" data-comment-id="${data.reply.id}">
                                <div class="comment-header">
                                    <div class="comment-user-info">
                                        <a href="/profile/${data.user.id}" class="comment-avatar-wrapper">
                                            ${data.user.profile_picture ? `<img src="/storage/${data.user.profile_picture}" alt="Profile Picture">` : `<i class="bi bi-person-circle avatar-placeholder"></i>`}
                                        </a>
                                        <a href="/profile/${data.user.id}" class="comment-username">${data.user.username}</a>
                                        <span class="comment-time">Just now</span>
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p class="comment-text">${data.reply.content}</p>
                                </div>
                                <div class="comment-actions">
                                    <a href="#" class="like-comment-button" data-comment-id="${data.reply.id}">Like</a>
                                </div>
                            </div>
                        `;
                        nestedCommentsContainer.insertAdjacentHTML('afterbegin', newReplyHtml);
                    } else {
                        const commentsList = document.querySelector('.comments-list');
                        const newCommentHtml = `
                            <div class="comment-card" data-comment-id="${data.comment.id}">
                                <div class="comment-header">
                                    <div class="comment-user-info">
                                        <a href="/profile/${data.user.id}" class="comment-avatar-wrapper">
                                            ${data.user.profile_picture ? `<img src="/storage/${data.user.profile_picture}" alt="Profile Picture">` : `<i class="bi bi-person-circle avatar-placeholder"></i>`}
                                        </a>
                                        <a href="/profile/${data.user.id}" class="comment-username">${data.user.username}</a>
                                        <span class="comment-time">Just now</span>
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p class="comment-text">${data.comment.content}</p>
                                </div>
                                <div class="comment-actions">
                                    <a href="#" class="like-comment-button" data-comment-id="${data.comment.id}">Like</a>
                                    <a href="javascript:void(0);" class="reply-button" data-comment-id="${data.comment.id}">Reply</a>
                                </div>
                                <div class="reply-form-container mt-2" id="reply-form-${data.comment.id}" style="display:none;">
                                    <div class="add-comment-avatar-wrapper">
                                        ${document.querySelector('.add-comments .add-comment-avatar-wrapper').innerHTML}
                                    </div>
                                    <div class="comment-input-wrapper">
                                        <form class="reply-form" data-parent-comment-id="${data.comment.id}" data-post-id="${postId}">
                                            @csrf
                                            <input type="text" name="content" placeholder="Reply to ${data.user.username}..." class="reply-input" required>
                                        </form>
                                        <button type="button" class="submit-reply-button" data-parent-comment-id="${data.comment.id}" data-post-id="${postId}">
                                            <i class="bi bi-send-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        commentsList.insertAdjacentHTML('afterbegin', newCommentHtml);
                    }

                    // Update total comment count display
                    const totalCommentsCountElement = document.querySelector('.comments-count-display');
                    if (totalCommentsCountElement) {
                        const currentCountMatch = totalCommentsCountElement.textContent.match(/\((\d+)\)/);
                        let currentCount = currentCountMatch ? parseInt(currentCountMatch[1]) : 0;
                        totalCommentsCountElement.textContent = `All Comments (${currentCount + 1})`;
                    }
                    const postCardCommentCountSpan = document.querySelector('.post-card .comments-count');
                     if (postCardCommentCountSpan) {
                         const currentCountMatch = postCardCommentCountSpan.textContent.match(/(\d+)/);
                         let currentCount = currentCountMatch ? parseInt(currentCountMatch[1]) : 0;
                         postCardCommentCountSpan.textContent = `${currentCount + 1} ${currentCount + 1 === 1 ? 'Comment' : 'Comments'}`;
                     }


                    commentInput.value = '';
                    commentInput.style.height = 'auto';
                } catch (error) {
                    console.error('Error submitting comment/reply:', error);
                    alert('Failed to post comment/reply. Please try again.');
                }
            }

            // Event listener for main comment form submission button
            document.querySelectorAll('.add-comments .submit-comment-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const postCard = this.closest('.comments-page-container') || document.querySelector('.post-card'); // Fallback to .post-card if not inside comments-page-container
                    const commentForm = postCard.querySelector(`.comment-form[data-post-id="${this.dataset.postId}"]`);
                    handleCommentSubmission(commentForm, false);
                });
            });

            // Event listener for main comment form input (Enter key)
            document.querySelectorAll('.add-comments .comment-input').forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        const postCard = this.closest('.comments-page-container') || document.querySelector('.post-card');
                        const commentForm = postCard.querySelector(`.comment-form[data-post-id="${this.closest('.comment-input-wrapper').querySelector('.submit-comment-button').dataset.postId}"]`);
                        handleCommentSubmission(commentForm, false);
                    }
                });
                input.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });


            // Event listener for reply buttons (to toggle reply forms)
            document.addEventListener('click', function(e) {
                const replyButton = e.target.closest('.reply-button');
                if (replyButton) {
                    e.preventDefault();
                    const commentId = replyButton.dataset.commentId;
                    const replyFormContainer = document.getElementById(`reply-form-${commentId}`);

                    if (replyFormContainer) {
                        document.querySelectorAll('.reply-form-container').forEach(container => {
                            if (container.id !== `reply-form-${commentId}`) {
                                container.style.display = 'none';
                            }
                        });

                        replyFormContainer.style.display = replyFormContainer.style.display === 'none' ? 'flex' : 'none';
                        if (replyFormContainer.style.display === 'flex') {
                            replyFormContainer.querySelector('.reply-input').focus();
                        }
                    }
                }
            });

            // Event listener for reply form submission buttons (delegated)
            document.addEventListener('click', function(e) {
                const submitReplyButton = e.target.closest('.submit-reply-button');
                if (submitReplyButton) {
                    e.preventDefault();
                    const parentCommentId = submitReplyButton.dataset.parentCommentId;
                    const postCard = submitReplyButton.closest('.comments-page-container') || document.querySelector('.post-card');
                    const replyForm = postCard.querySelector(`.reply-form[data-parent-comment-id="${parentCommentId}"]`);
                    handleCommentSubmission(replyForm, true);
                }
            });

            // Event listener for reply form input (Enter key)
            document.addEventListener('keypress', function(e) {
                const replyInput = e.target.closest('.reply-input');
                if (replyInput && e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const parentCommentId = replyInput.closest('.reply-form').dataset.parentCommentId;
                    const postCard = replyInput.closest('.comments-page-container') || document.querySelector('.post-card');
                    const replyForm = postCard.querySelector(`.reply-form[data-parent-comment-id="${parentCommentId}"]`);
                    handleCommentSubmission(replyForm, true);
                }
            });

            // Auto-resize for reply inputs
            document.querySelectorAll('.reply-input').forEach(input => {
                input.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });

            // --- Basic Comment Like Button Functionality ---
            document.addEventListener('click', function(e) {
                const likeCommentButton = e.target.closest('.like-comment-button');
                if (likeCommentButton) {
                    e.preventDefault();
                    const commentId = likeCommentButton.dataset.commentId;
                    // Implement AJAX for liking comments
                    console.log(`Liking comment with ID: ${commentId}`);
                    // You'll need a route and controller method for this
                    alert(`Liking comment ${commentId} (functionality to be implemented)`);
                }
            });
        });
    </script>
@endsection
