<style>

body{
    font-family: 'Inter', Helvetica Neue, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}
/* === OVERLAY === */
.post-modal-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

/* === MODAL CONTAINER === */
.post-modal {
    background:#f1f1f1;
    width: 90%;
    max-width: 1000px;
    height: 90vh;
    display: flex;
    flex-direction: column;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
}

/* === CLOSE BUTTON === */
.close-button {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 2rem;
    color: #fff;
    z-index: 1001;
    cursor: pointer;
    transition: color 0.2s ease;
}

/* === MODAL CONTENT LAYOUT === */
.post-modal-content {
    display: flex;
    flex: 1;
    flex-direction: row;
    height: 100%;
}

/* === LEFT SIDE: MEDIA === */
.post-modal-left {
    flex: 1;
    background-color: black;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

/* === CAROUSEL === */
.carousel-wrapper {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.post-carousel {
    display: flex;
    transition: transform 0.3s ease-in-out;
    height: 100%;
}

.carousel-slide {
    flex: 0 0 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.post-modal-left img,
.post-modal-left video {
    max-width: 100%;
    height: 100%;
    object-fit: contain;
}

/* === CAROUSEL ARROWS === */
.carousel-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2rem;
    color: white;
    background: rgba(0, 0, 0, 0.4);
    border: none;
    padding: 10px;
    z-index: 2;
    cursor: pointer;
    border-radius: 50%;
    user-select: none;
}

.carousel-arrow.left {
    left: 10px;
    font-size:14px;
}

.carousel-arrow.right {
    right: 10px;
    font-size:14px;
}

/* === CAROUSEL COUNTER === */
.carousel-counter {
    position: absolute;
    bottom: 10px;
    right: 15px;
    background: rgba(0, 0, 0, 0.5);
    padding: 2px 6px;
    color: #fff;
    font-size: 0.85rem;
    border-radius: 4px;
}

/* === CAROUSEL DOTS === */
.carousel-dots {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 6px;
}

.carousel-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    cursor: pointer;
}

.carousel-dot.active {
    background: #fff;
}

/* === RIGHT SIDE: INFO & COMMENTS === */
.post-modal-right {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background-color: var(--right-bg);
    color: var(--text);
}

.post-modal-header .user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.post-modal-header .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.username {
    font-weight: bold;
}

/* === POST CAPTION === */
.post-caption {
    margin: 10px 0;
}

.post-caption p {
    margin: 4px 0 0;
}

/* === COMMENTS === */
.comment-form {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-grow: 1;
    background-color: #f5f8fa00;
    border-radius: 25px;
    border:none;
    padding: 5px;
}
.commentForm{
    background-color: #fff;
    border-radius: 25px;
    border:none;
    padding: 5px;
}
.comment-form input {
    flex: 1;
    border: none;
    font-family: 'Inter', Helvetica Neue, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    background: transparent;
    color: inherit;
    outline: none;
    padding: 5px 10px;
    border-radius: 25px;
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
    width: 100%;
    font-size: 1rem;
}

.comment-form button {
    background: none;
    border: none;
    /* color: #f03838; */
    font-weight: bold;
    margin-left: 10px;
    cursor: pointer;
}

.comment {
    margin-top: 10px;
}

.comment.reply {
    margin-left: 20px;
    opacity: 0.8;
    font-size: 0.9rem;
}

/* === LIKE SECTION === */
.like-section {
    margin: 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.like-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #888;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.like-btn.liked {
    color: #e74c3c;
    animation: pop 0.3s ease;
}

@keyframes pop {
    0% { transform: scale(1); }
    50% { transform: scale(1.4); }
    100% { transform: scale(1); }
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .post-modal {
        flex-direction: column;
        height: 95vh;
        width: 95%;
    }

    .post-modal-content {
        flex-direction: column;
    }

    .post-modal-left,
    .post-modal-right {
        flex: none;
        width: 100%;
        height: 50%;
    }

    .post-modal-right {
        height: auto;
    }

    .carousel-arrow {
        font-size: 1.5rem;
        padding: 6px;
    }
}

/* === LIGHT THEME === */
:root {
    --bg: #ffffff;
    --right-bg: #fff;
    --text: #000;
    --border: #ccc;
}

/* === DARK THEME === */
@media (prefers-color-scheme: dark) {
    :root {
        --bg: #1a1a1a;
        --right-bg: #121212;
        --text: #f1f1f1;
        --border: #333;
    }

    .close-button {
        color: #ccc;
    }

    .comment-form button {
        color: #66b3ff;
    }
}
/* === MODAL ANIMATION === */
.post-modal {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* === MODAL CLOSE ANIMATION === */
.post-modal-overlay.closing {
    animation: fadeOut 0.3s ease-in-out forwards;
}
@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
        pointer-events: none;
    }
}

.post-carousel {
    display: flex;
    transition: transform 0.4s ease-in-out;
    height: 100%;
    width: 100%;
}

.carousel-slide {
    min-width: 100%;
    flex-shrink: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>

<div class="post-modal-overlay" onclick="closePostModal(event)">
    <div class="post-modal">
        <button class="close-button" onclick="closePostModal(event)">&times;</button>

        <div class="post-modal-content">
            {{-- LEFT: Post Media --}}
            {{-- LEFT: Post Media --}}
<div class="post-modal-left">
    @php
        $allMedia = collect();
        foreach ($media as $group) {
            $allMedia = $allMedia->merge($group->media);
        }
    @endphp

    @if($allMedia->count() > 1)
        <div class="carousel-wrapper">
            <div class="post-carousel" data-post-id="{{ $post->id }}" id="carousel-{{ $post->id }}">
                @foreach($allMedia as $item)
                    <div class="carousel-slide">
                        @if($item->file_type === 'image')
                            <img src="{{ asset('storage/' . $item->file_path) }}" class="post-image" />
                        @elseif($item->file_type === 'video')
                            <video controls class="post-video" autoplay muted loop>
                                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4" />
                            </video>
                        @elseif($item->file_type === 'text')
                            <div class="text-post">
                                <p>{{ $item->text_content }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Arrows --}}
            <button class="carousel-arrow left" id="prevArrow-{{ $post->id }}" onclick="prevSlide(event, {{ $post->id }})" style="display: none;">&#10094;</button>
            <button class="carousel-arrow right" id="nextArrow-{{ $post->id }}" onclick="nextSlide(event, {{ $post->id }})">&#10095;</button>

            {{-- Counter --}}
            <div class="carousel-counter">
                <span id="currentSlide-{{ $post->id }}">1</span>/<span id="totalSlides-{{ $post->id }}">{{ $allMedia->count() }}</span>
            </div>

            {{-- Dots --}}
            <div class="carousel-dots" id="carouselDots-{{ $post->id }}"></div>
        </div>
    @else
        @php $item = $allMedia->first(); @endphp
        @if($item->file_type === 'image')
            <img src="{{ asset('storage/' . $item->file_path) }}" class="post-image" />
        @elseif($item->file_type === 'video')
            <video class="post-video" autoplay muted loop>
                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4" />
            </video>
        @elseif($item->file_type === 'text')
            <div class="text-post">
                <p>{{ $item->text_content }}</p>
            </div>
        @endif
    @endif
</div>


            {{-- RIGHT: Comments and Info --}}
            <div class="post-modal-right">
                <div class="post-modal-header">
                    <div class="user-info">
                        <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="avatar">
                        <span class="username">{{ $post->user->username }}</span>
                    </div>
                </div>

                <div class="post-caption">
                    <span class="username">{{ $post->user->username }}</span>
                    <p>{{ $post->caption }}</p>
                </div>

                <div class="like-section">
                    <button class="like-btn {{ $post->isLikedBy(auth()->user()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                        <i class="fas fa-heart"></i>
                    </button>
                    <span class="like-count">{{ $post->likesCount() }}</span>
                </div>

                <div class="post-comments" id="commentList">
                    @foreach($post->comments as $comment)
                        <div class="comment">
                            <span class="username">{{ $comment->user->username }}</span>
                            <p>{{ $comment->content }}</p>
                        </div>
                        @foreach($comment->replies as $reply)
                            <div class="comment reply">
                                <span class="username">{{ $reply->user->username }}</span>
                                <p>{{ $reply->content }}</p>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                @auth
                <div class="comment-form">
                    <form id="commentForm">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <input type="text" name="content" placeholder="Add a comment..." id="commentInput">
                    </form>

                    <button type="button" class="submit-comment-button">
                            <i class="bi bi-send-fill"></i>
                    </button>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>
