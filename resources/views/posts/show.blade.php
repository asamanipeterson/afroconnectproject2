
<style>
    body {
        font-family: 'Inter', Helvetica Neue, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        --light-bg: #f9f9f9;
        --light-text: #333;
        --light-border: #eee;
        --light-placeholder: #888;
        --light-button: #007bff;
        --light-button-hover: #0056b3;
        /* --light-close-button-bg: rgba(0, 0, 0, 0.6); */
        --light-close-button-color: #fff;
        --light-left-panel-bg: #e0e0e0;
        --light-carousel-arrow-color: #fff;
        --light-carousel-arrow-bg: rgba(0, 0, 0, 0.5);
        --light-carousel-arrow-hover-bg: rgba(0, 0, 0, 0.7);
        --light-carousel-counter-bg: rgba(0, 0, 0, 0.6);
        --light-carousel-counter-color: #fff;
        --light-carousel-dot-inactive: rgba(0, 0, 0, 0.4);
        --light-carousel-dot-active: #333;
        --light-text-post-color: #333;

        --dark-bg: #1a1a1a;
        --dark-text: #f1f1f1;
        --dark-border: #444;
        --dark-placeholder: #bbb;
        --dark-button: #66b3ff;
        --dark-button-hover: #99ccff;
        /* --dark-close-button-bg: rgba(255, 255, 255, 0.3); */
        --dark-close-button-color: #f1f1f1;
        --dark-left-panel-bg: #000000;
        --dark-carousel-arrow-color: #fff;
        --dark-carousel-arrow-bg: rgba(0, 0, 0, 0.5);
        --dark-carousel-arrow-hover-bg: rgba(0, 0, 0, 0.7);
        --dark-carousel-counter-bg: rgba(0, 0, 0, 0.5);
        --dark-carousel-counter-color: #fff;
        --dark-carousel-dot-inactive: rgba(255, 255, 255, 0.4);
        --dark-carousel-dot-active: #fff;
        --dark-text-post-color: #f1f1f1;

        --modal-bg: var(--light-bg);
        --text: var(--light-text);
        --border: var(--light-border);
        --placeholder: var(--light-placeholder);
        --button: var(--light-button);
        --button-hover: var(--light-button-hover);
        --close-button-bg: var(--light-close-button-bg);
        --close-button-color: var(--light-close-button-color);
        --left-panel-bg: var(--light-left-panel-bg);
        --carousel-arrow-color: var(--light-carousel-arrow-color);
        --carousel-arrow-bg: var(--light-carousel-arrow-bg);
        --carousel-arrow-hover-bg: var(--light-carousel-arrow-hover-bg);
        --carousel-counter-bg: var(--light-carousel-counter-bg);
        --carousel-counter-color: var(--light-carousel-counter-color);
        --carousel-dot-inactive: var(--light-carousel-dot-inactive);
        --carousel-dot-active: var(--light-carousel-dot-active);
        --text-post-color: var(--light-text-post-color);
    }

    body.dark-mode {
        --modal-bg: var(--dark-bg);
        --text: var(--dark-text);
        --border: var(--dark-border);
        --placeholder: var(--dark-placeholder);
        --button: var(--dark-button);
        --button-hover: var(--dark-button-hover);
        --close-button-bg: var(--dark-close-button-bg);
        --close-button-color: var(--dark-close-button-color);
        --left-panel-bg: var(--dark-left-panel-bg);
        --carousel-arrow-color: var(--dark-carousel-arrow-color);
        --carousel-arrow-bg: var(--dark-carousel-arrow-bg);
        --carousel-arrow-hover-bg: var(--dark-carousel-arrow-hover-bg);
        --carousel-counter-bg: var(--dark-carousel-counter-bg);
        --carousel-counter-color: var(--dark-carousel-counter-color);
        --carousel-dot-inactive: var(--dark-carousel-dot-inactive);
        --carousel-dot-active: var(--dark-carousel-dot-active);
        --text-post-color: var(--dark-text-post-color);
    }

    .post-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .post-modal {
        background: var(--modal-bg);
        width: 90%;
        max-width: 1000px;
        height: 90vh;
        display: flex;
        flex-direction: column;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-in-out;
    }

    .close-button {
        position: absolute;
        top: 15px;
        right: 20px;
        background: var(--close-button-bg);
        color:#000;
        border: none;
        font-size:2rem;
        padding: 5px 8px;
        border-radius: 50%;
        z-index: 1001;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
body.dark-mode .close-button {
        color: var(--close-button-color);
    }


    .post-modal-content {
        display: flex;
        flex: 1;
        flex-direction: row;
        height: 100%;
    }

    .post-modal-left {
        flex: 1;
        background-color: var(--left-panel-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .carousel-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .post-carousel {
        display: flex;
        transition: transform 0.4s ease-in-out;
        height: 100%;
        width: 100%;
    }

    .carousel-slide {
        flex: 0 0 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 100%;
    }

    .post-modal-left img,
    .post-modal-left video {
        max-width: 100%;
        height: auto;
        max-height: 100%;
        object-fit: contain;
    }

    .carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: var(--carousel-arrow-bg);
        color: var(--carousel-arrow-color);
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        border-radius: 50%;
        border: none;
        padding: 0;
        z-index: 2;
        cursor: pointer;
        user-select: none;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .carousel-arrow:hover {
        background: var(--carousel-arrow-hover-bg);
    }

    .carousel-arrow.left {
        left: 10px;
    }

    .carousel-arrow.right {
        right: 10px;
    }

    .carousel-counter {
        position: absolute;
        bottom: 10px;
        right: 15px;
        background: var(--carousel-counter-bg);
        padding: 2px 6px;
        color: var(--carousel-counter-color);
        font-size: 0.85rem;
        border-radius: 4px;
    }

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
        background: var(--carousel-dot-inactive);
        cursor: pointer;
    }

    .carousel-dot.active {
        background: var(--carousel-dot-active);
    }

    .post-modal-right {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: var(--left-panel-bg);
        color: var(--text);
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .post-modal-header {
        display: flex;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-info .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-info .default-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--modal-bg);
        color: var(--placeholder);
        font-size: 1.5rem;
    }

    .user-info .username {
        font-weight: 600;
        font-size: .9em;
        color: var(--text);
        letter-spacing: 0.5px;
    }

    .post-caption {
        margin-top: 10px;
        line-height: 1;
        color: var(--text);
    }

    .post-caption .username {
        font-weight: 600;
        margin-right: 5px;
        color: var(--text);
        font-size: 0.9em;
    }

    .post-caption p {
        display: inline;
    }

    .text-post {
        background-color: transparent;
        font-size: 1.2em;
        padding: 20px;
        text-align: center;
        word-break: break-word;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
    }

    .text-post p {
        color: var(--text-post-color);
        margin: 0;
        padding: 0;
        max-width: 90%;
        line-height: 1.6;
    }

    .like-section {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
    }

    .like-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text);
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .like-btn i {
        color: #ff0000;
        cursor: pointer;
    }
    .like-btn.liked i,
    body.dark-mode .like-button i,
    .like-button i.liked {
        color: #ff0000;
    }

    /* Comment Button Styling */
    .comment-btn i {
        color: #007bff;
    }
    body.dark-mode .comment-btn:hover i {
        color: #66b3ff;
    }

    .like-count {
        font-weight: bold;
        color: var(--text);
    }

    .comment-section {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-left: 15px;
    }

    .comment-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text);
        cursor: pointer;
    }

    .comment-count {
        font-weight: bold;
        color: var(--text);
    }

    .post-comments {
        flex-grow: 1;
        overflow-y: auto;
        padding-right: 5px;
    }

    .comment {
        margin-bottom: 10px;
        line-height: 1.4;
        word-wrap: break-word;
        color: var(--text);
        display: flex;
        align-items: center;
    }

    .comment .username {
        font-weight: 600;
        margin-right: 5px;
        color: var(--text);
    }

    .comment.reply {
        margin-left: 20px;
        border-left: 2px solid var(--border);
        padding-left: 10px;
    }

    .comment-form {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top:5px;
        border-top: 1px solid var(--border);
        margin-top: auto;
        background-color:#f5f8fa;
        border-radius:25px;

        color: var(--text);
    }
body.dark-mode .comment-form {
        background-color: #3d3d3d;
        color: var(--text);
    }
    .comment-form form {
        flex-grow: 1;
    }

    .comment-form input[type="text"] {
       width: 100%;
        max-height: 100px;
        resize: vertical;
        border: none;
        outline: none;
        font-size: 0.95rem;
        color:#000;
        background: transparent;
        border-radius: 25px;
        padding-left: 15px;
        margin-bottom: 15px;
    }
 body.dark-mode .comment-form input[type="text"] {
        color:#fff;
        font-size: 1rem;

    }
    .comment-form input[type="text"]::placeholder {
        color: var(--placeholder);
    }

    .comment-form input[type="text"]:focus {
        /* border-color: var(--button); */
    }

    .submit-comment-button {
    cursor: pointer;
    border: none;
    background: none;
    color: var(--light-button);
    font-size: 1.2rem;
    padding: 0 10px;
    transition: color 0.2s ease;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height:40px;
    width:40px;
    /* border-radius: 50%; */
    }



    .custom-video-controls {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 10px;
    }

    .custom-video-controls .top-controls {
        align-self: flex-end;
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(0, 0, 0, 0.4);
        padding: 5px 10px;
        border-radius: 6px;
        pointer-events: auto;
    }

    .custom-video-controls .bottom-controls {
        align-self: flex-start;
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(0, 0, 0, 0.4);
        padding: 6px 12px;
        border-radius: 8px;
        pointer-events: auto;
        margin-bottom: 20px;
        margin-left: 10px;
    }

    .custom-video-controls button {
        background: none;
        border: none;
        color: white;
        font-size: 1rem;
        cursor: pointer;
    }

    .custom-video-controls .seek-bar {
        width: 120px;
        height: 4px;
        cursor: pointer;
        -webkit-appearance: none;
        appearance: none;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 5px;
        display: none;
    }

    .custom-video-controls .seek-bar::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 12px;
        height: 12px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
    }

    .custom-video-controls .seek-bar::-moz-range-thumb {
        width: 12px;
        height: 12px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
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
</style>

<div class="post-modal-overlay" onclick="closePostModal(event)">
    <div class="post-modal">
        <button class="close-button" onclick="closePostModal(event)">&times;</button>

        <div class="post-modal-content">
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
                                        <div style="position: relative; width: 100%; height: 100%;">
                                            <video class="post-video" autoplay muted loop>
                                                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4" />
                                            </video>
                                            <div class="custom-video-controls">
                                                <div class="top-controls">
                                                    <span class="time-display">0:00</span>
                                                    <button class="mute-btn"><i class="bi bi-volume-mute-fill"></i></button>
                                                </div>
                                                <div class="bottom-controls">
                                                    <button class="play-pause-btn"><i class="bi bi-pause-fill"></i></button>
                                                    <input type="range" class="seek-bar" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($item->file_type === 'text')
                                        <div class="text-post">
                                            <p>{{ $item->text_content }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <button class="carousel-arrow left" id="prevArrow-{{ $post->id }}" style="display: none;">&#10094;</button>
                        <button class="carousel-arrow right" id="nextArrow-{{ $post->id }}">&#10095;</button>

                        <div class="carousel-counter">
                            <span id="currentSlide-{{ $post->id }}">1</span>/<span id="totalSlides-{{ $post->id }}">{{ $allMedia->count() }}</span>
                        </div>

                        <div class="carousel-dots" id="carouselDots-{{ $post->id }}"></div>
                    </div>
                @else
                    @php $item = $allMedia->first(); @endphp
                    @if($item->file_type === 'image')
                        <img src="{{ asset('storage/' . $item->file_path) }}" class="post-image" />
                    @elseif($item->file_type === 'video')
                        <div style="position: relative; width: 100%; height: 100%;">
                            <video class="post-video" autoplay muted loop>
                                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4" />
                            </video>
                            <div class="custom-video-controls">
                                <div class="top-controls">
                                    <span class="time-display">0:00</span>
                                    <button class="mute-btn"><i class="bi bi-volume-mute-fill"></i></button>
                                </div>
                                <div class="bottom-controls">
                                    <button class="play-pause-btn"><i class="bi bi-pause-fill"></i></button>
                                    <input type="range" class="seek-bar" value="0">
                                </div>
                            </div>
                        </div>
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
                        @if($post->user->profile_picture)
                            <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="avatar">
                        @else
                            <span class="default-avatar"><i class="fas fa-user"></i></span>
                        @endif
                        <span class="username">{{ $post->user->username }}</span>
                    </div>
                </div>

                <div class="post-caption">
                    <span class="username">{{ $post->user->username }}</span>
                    <p>{{ $post->caption }}</p>
                </div>

                <div class="like-section">
                    <button class="like-btn" data-post-id="{{ $post->id }}">
                        @if ($post->isLikedBy(auth()->user()))
                            <i class="fas fa-heart"></i>
                        @else
                            <i class="far fa-heart"></i>
                        @endif
                    </button>
                    <span class="like-count" id="likeCount-{{ $post->id }}">{{ $post->likesCount() }}</span>

                    <div class="comment-section">
                        <button class="comment-btn">
                            <i class="bi bi-chat"></i>
                        </button>
                        <span class="comment-count" id="commentCount-{{ $post->id }}">{{ $post->comments->count() }}</span>
                    </div>
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
                               <svg width="30" height="30" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21.0808 4.08454c.0817-.26553.0099-.55446-.1865-.7509-.1964-.19644-.4854-.2682-.7509-.1865L1.75863 8.80399c-.2994.09213-.51001.36063-.52817.67336-.01816.31273.15995.60385.44668.72995l8.57186 3.7716 3.7716 8.5719c.1262.2867.4173.4648.73.4467.3127-.0182.5812-.2288.6734-.5282l5.6568-18.38476ZM10.6505 12.5168 4.12458 9.64541 19.2305 4.99743l-4.648 15.10597-2.8714-6.526 3.3496-3.3495L14 9.16725l-3.3495 3.34955Z"></path></svg>
                    </button>
                </div>
                <form action="{{ route('post.bookmark', $post->id) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="btn btn-sm btn-outline-primary">
        <i class="fas fa-bookmark"></i> Save
    </button>
</form>

                @endauth
            </div>
        </div>
    </div>
</div>