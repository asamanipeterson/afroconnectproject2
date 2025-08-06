
document.addEventListener('DOMContentLoaded', function () {
    // --- Real-time New Comment Listener via Laravel Echo ---
    if (typeof Echo !== 'undefined') {
        document.querySelectorAll('.post-card').forEach(card => {
            const postId = card.dataset.postId;
            const commentsSection = card.querySelector('.recent-comments-section');
            const commentsCountDisplay = card.querySelector('.comments-count');
            const viewCommentsLink = card.querySelector('.view-comments-link');
            const likesCountSpan = card.querySelector('.likes-count');

            Echo.channel(`post.${postId}`)
                .listen('.NewComment', (e) => {
                    console.log('🟢 New comment broadcast received:', e);

                    const newCommentHtml = `
                        <div class="recent-comment-item mb-1">
                            <a href="/profile/${e.user.id}" class="comment-username font-weight-bold mr-1">
                                ${e.user.username}
                            </a>
                            <span class="comment-text">${e.content}</span>
                        </div>
                    `;

                    if (commentsSection) {
                        commentsSection.insertAdjacentHTML('afterbegin', newCommentHtml);

                        const currentComments = commentsSection.querySelectorAll('.recent-comment-item');
                        if (currentComments.length > 3) {
                            currentComments[currentComments.length - 1].remove();
                        }
                    }

                    if (viewCommentsLink) {
                        const currentCount = parseInt(viewCommentsLink.textContent.match(/\d+/)) || 0;
                        viewCommentsLink.textContent = `View all ${currentCount + 1} comments`;
                    }

                    if (commentsCountDisplay) {
                        const currentCount = parseInt(commentsCountDisplay.textContent) || 0;
                        commentsCountDisplay.textContent = `${currentCount + 1}`;
                    }
                })

                // --- Real-time Like Listener ---
                .listen('.PostLiked', (e) => {
                    console.log('❤️ Post liked/unliked by another user:', e);

                    if (likesCountSpan) {
                        likesCountSpan.textContent = `${e.likes_count}`;
                    }
                });
        });
    }

    // --- Follow Button Functionality ---
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

    // --- Carousel Functionality ---
    document.querySelectorAll('.post-media-carousel').forEach(carousel => {
        const carouselInner = carousel.querySelector('.carousel-inner');
        const items = carouselInner.querySelectorAll('.carousel-item');
        const leftArrow = carousel.querySelector('.left-arrow');
        const rightArrow = carousel.querySelector('.right-arrow');
        const dotsContainer = carousel.querySelector('.carousel-dots');
        const counter = carousel.querySelector('.carousel-counter');
        const currentSlideEl = counter?.querySelector(`#currentSlide-${carouselInner.dataset.postId}`);
        const totalSlideEl = counter?.querySelector(`#totalSlides-${carouselInner.dataset.postId}`);
        let currentIndex = 0;
        let startX = 0;
        let isDragging = false;

        // Initialize dots and counter
        if (dotsContainer && items.length > 1) {
            dotsContainer.innerHTML = '';
            items.forEach((_, i) => {
                const dot = document.createElement('span');
                dot.classList.add('carousel-dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    currentIndex = i;
                    updateCarousel();
                });
                dotsContainer.appendChild(dot);
            });
        }
        if (totalSlideEl) totalSlideEl.textContent = items.length;
        if (currentSlideEl) currentSlideEl.textContent = currentIndex + 1;

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
            if (dotsContainer) {
                dotsContainer.querySelectorAll('.carousel-dot').forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });
            }
            if (currentSlideEl) currentSlideEl.textContent = currentIndex + 1;

            // Manage video playback
            items.forEach((item, i) => {
                const video = item.querySelector('.post-video');
                if (video) {
                    if (i === currentIndex) {
                        video.play();
                    } else {
                        video.pause();
                        video.currentTime = 0;
                    }
                }
            });
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

        // Touch swipe support
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

        // Prevent vertical scrolling on mouse wheel over carousel
        carouselInner.addEventListener('wheel', (e) => {
            e.preventDefault();
        });
    });

    // --- Video Controls Functionality ---
    document.querySelectorAll('.post-media-carousel').forEach(carousel => {
        const videos = carousel.querySelectorAll('.post-video');
        videos.forEach(video => {
            const container = video.closest('.video-container');
            const playPauseBtn = container.querySelector('.play-pause-btn');
            const seekBar = container.querySelector('.seek-bar');
            const timeDisplay = container.querySelector('.time-display');
            const muteBtn = container.querySelector('.mute-btn');

            // Initialize icons
            if (video.paused) {
                playPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
            } else {
                playPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
            }
            if (video.muted) {
                muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
            } else {
                muteBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
            }

            // Play/Pause
            playPauseBtn.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                    playPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                } else {
                    video.pause();
                    playPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                }
            });

            // Mute
            muteBtn.addEventListener('click', () => {
                video.muted = !video.muted;
                if (video.muted) {
                    muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
                } else {
                    muteBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
                }
            });

            // Seek bar
            seekBar.addEventListener('input', () => {
                video.currentTime = (seekBar.value / 100) * video.duration;
            });

            // Timer
            video.addEventListener('timeupdate', () => {
                seekBar.value = (video.currentTime / video.duration) * 100 || 0;
                const minutes = Math.floor(video.currentTime / 60);
                const seconds = Math.floor(video.currentTime % 60).toString().padStart(2, '0');
                timeDisplay.textContent = `${minutes}:${seconds}`;
            });

            // Ensure duration is loaded
            video.addEventListener('loadedmetadata', () => {
                const minutes = Math.floor(video.duration / 60);
                const seconds = Math.floor(video.duration % 60).toString().padStart(2, '0');
                timeDisplay.textContent = `0:00`;
            });
        });
    });

    // --- Like Button Functionality ---
    document.addEventListener('click', function(e) {
        const likeButton = e.target.closest('.like-button');
        if (likeButton) {
            e.preventDefault();
            handleLike(likeButton);
        }
    });

    async function handleLike(button) {
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
                likesCountSpan.textContent = `${data.likes_count}`;
            }

        } catch (error) {
            console.error('Error handling like:', error);
        }
    }

    // --- Dropdown Menu ---
    document.querySelectorAll('.menu-dot').forEach(dot => {
        dot.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.closest('.post-menu-dropdown').querySelector('.dropdown-content');
            console.log('Menu dot clicked, toggling dropdown:', dropdown); // Debug
            if (dropdown) {
                // Close other open dropdowns
                document.querySelectorAll('.dropdown-content.show').forEach(openDropdown => {
                    if (openDropdown !== dropdown) {
                        openDropdown.classList.remove('show');
                    }
                });
                // Toggle current dropdown
                dropdown.classList.toggle('show');
                console.log('Dropdown show class:', dropdown.classList.contains('show')); // Debug
            } else {
                console.error('Dropdown not found for menu dot:', this);
            }
        });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.post-menu-dropdown')) {
            document.querySelectorAll('.dropdown-content.show').forEach(dropdown => {
                dropdown.classList.remove('show');
                console.log('Closed dropdown on outside click:', dropdown); // Debug
            });
        }
    });

    // Prevent dropdown from closing when clicking inside
    document.querySelectorAll('.dropdown-content').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // --- Comment Submission ---
    document.querySelectorAll('.submit-comment-button').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();

            const postId = this.dataset.postId;
            const postCard = this.closest('.post-card');
            const commentForm = postCard.querySelector(`.comment-form[data-post-id="${postId}"]`);
            const commentInput = commentForm.querySelector('.comment-input');
            const commentContent = commentInput.value.trim();

            if (!commentContent) return;

            try {
                const response = await fetch(`/posts/${postId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: commentContent })
                });

                if (!response.ok) {
                    if (response.status === 401) window.location.href = '/login';
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                commentInput.value = '';
                commentInput.style.height = 'auto';
            } catch (error) {
                console.error('Error submitting comment:', error);
                alert('Failed to post comment. Please try again.');
            }
        });
    });

    // --- Enter to Submit & Auto-resize ---
    document.querySelectorAll('.comment-input').forEach(input => {
        input.addEventListener('keypress', async function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const postId = this.closest('.comment-input-wrapper').querySelector('.submit-comment-button').dataset.postId;
                const postCard = this.closest('.post-card');
                const commentForm = postCard.querySelector(`.comment-form[data-post-id="${postId}"]`);
                const commentInput = commentForm.querySelector('.comment-input');
                const commentContent = commentInput.value.trim();

                if (!commentContent) return;

                try {
                    const response = await fetch(`/posts/${postId}/comments`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: commentContent })
                    });

                    if (!response.ok) {
                        if (response.status === 401) window.location.href = '/login';
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    commentInput.value = '';
                    commentInput.style.height = 'auto';
                } catch (error) {
                    console.error('Error submitting comment:', error);
                    alert('Failed to post comment. Please try again.');
                }
            }
        });

        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = `${this.scrollHeight}px`;
        });
    });

});
