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
                        <video  class="post-video" autoplay muted loop>
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
                        <input type="text" name="content" placeholder="Add a comment..." required>
                        <button type="submit">Post</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>
