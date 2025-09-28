document.addEventListener('DOMContentLoaded', function () {
    // --- Real-time New Comment Listener via Laravel Echo ---
    if (typeof Echo !== 'undefined') {
        document.querySelectorAll('.post-card').forEach(card => {
            const postId = card.dataset.postId;
            const commentsSection = card.querySelector('.recent-comments-section');
            const commentsCountDisplay = card.querySelector('.comments-count');
            const likesCountSpan = card.querySelector('.likes-count');

            Echo.channel(`post.${postId}`)
                // FIX: Removed the dot prefix from event name
                .listen('NewComment', (e) => {
                    console.log(`[Echo] 🟢 New comment broadcast received for post ${postId}:`, e);

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
                    } else {
                        console.error(`[Echo] Comments section not found for post ${postId}`);
                    }

                    if (commentsCountDisplay) {
                        const currentCount = parseInt(commentsCountDisplay.textContent) || 0;
                        commentsCountDisplay.textContent = `${currentCount + 1}`;
                        console.log(`[Echo] Comments count updated to ${commentsCountDisplay.textContent} for post ${postId}.`);
                    } else {
                        console.error(`[Echo] Comments count display not found for post ${postId}`);
                    }
                })

                // --- Real-time Like Listener ---
                .listen('.PostLiked', (e) => {
                    console.log(`[Echo] ❤️ Post liked/unliked broadcast received for post ${postId}:`, e);
                    if (likesCountSpan) {
                        likesCountSpan.textContent = `${e.likes_count}`;
                        console.log(`[Echo] Likes count updated to ${likesCountSpan.textContent} for post ${postId}.`);
                    } else {
                        console.error(`[Echo] Likes count span not found for post ${postId}`);
                    }
                });
        });
    }

    // --- Follow Button Functionality ---
    document.addEventListener('click', function (e) {
        const followBtn = e.target.closest('.follow-btn');
        if (followBtn) {
            const userId = followBtn.dataset.userId;
            console.log(`[Follow] Follow button clicked for user ${userId}.`);

            fetch(`/toggle-follow/${userId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(res => {
                console.log(`[Follow] Server response status: ${res.status}`);
                if (!res.ok) {
                    throw new Error(`[Follow] Server response was not ok: ${res.statusText}`);
                }
                return res.json();
            })
            .then(data => {
                console.log(`[Follow] AJAX response received:`, data);
                if (data.status === 'followed') {
                    followBtn.classList.add('following');
                    followBtn.textContent = 'Following';
                    console.log('[Follow] UI updated: User is now following.');
                } else if (data.status === 'unfollowed') {
                    followBtn.classList.remove('following');
                    followBtn.textContent = 'Follow';
                    console.log('[Follow] UI updated: User is now unfollowing.');
                }
            })
            .catch(error => console.error('[Follow] Error:', error));
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
        let isMouseDown = false;
        let mouseStartX = 0;
        let scrollLeftStart = 0;

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

        // --- Touch swipe support ---
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

        // --- Mouse drag support ---
        carouselInner.addEventListener('mousedown', (e) => {
            isMouseDown = true;
            mouseStartX = e.clientX;
            scrollLeftStart = carouselInner.scrollLeft;
            carouselInner.style.scrollBehavior = 'auto';
            carouselInner.style.cursor = 'grabbing';
            document.addEventListener('mouseup', handleMouseUp);
        });

        carouselInner.addEventListener('mousemove', (e) => {
            if (!isMouseDown) return;
            e.preventDefault();
            const mouseMoveX = e.clientX - mouseStartX;
            carouselInner.scrollLeft = scrollLeftStart - mouseMoveX;
        });

        carouselInner.addEventListener('mouseleave', () => {
            if (isMouseDown) {
                handleMouseUp();
            }
        });

        function handleMouseUp() {
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
            document.removeEventListener('mouseup', handleMouseUp);
        }

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

            playPauseBtn.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                    playPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                } else {
                    video.pause();
                    playPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                }
            });

            muteBtn.addEventListener('click', () => {
                video.muted = !video.muted;
                if (video.muted) {
                    muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
                } else {
                    muteBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
                }
            });

            seekBar.addEventListener('input', () => {
                video.currentTime = (seekBar.value / 100) * video.duration;
            });

            video.addEventListener('timeupdate', () => {
                seekBar.value = (video.currentTime / video.duration) * 100 || 0;
                const minutes = Math.floor(video.currentTime / 60);
                const seconds = Math.floor(video.currentTime % 60).toString().padStart(2, '0');
                timeDisplay.textContent = `${minutes}:${seconds}`;
            });

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

        console.log(`[Like] Action initiated for post ${postId}.`);

        try {
            const response = await fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log(`[Like] Server response status: ${response.status}`);
            if (!response.ok) {
                if (response.status === 401) {
                    console.error('[Like] Authentication error: User not logged in.');
                    window.location.href = '/login';
                }
                throw new Error(`[Like] HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log(`[Like] AJAX response received:`, data);

            // Update UI based on the response
            if (data.liked) {
                heartIcon.classList.remove('bi-heart');
                heartIcon.classList.add('bi-heart-fill', 'liked');
                heartIcon.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    heartIcon.style.transform = 'scale(1)';
                }, 200);
                console.log(`[Like] UI updated: Post is now liked.`);
            } else {
                heartIcon.classList.remove('bi-heart-fill', 'liked');
                heartIcon.classList.add('bi-heart');
                console.log(`[Like] UI updated: Post is now unliked.`);
            }

            if (likesCountSpan) {
                likesCountSpan.textContent = `${data.likes_count}`;
                console.log(`[Like] Likes count updated to ${data.likes_count}.`);
            } else {
                console.error('[Like] Could not find the likes count span.');
            }

        } catch (error) {
            console.error('[Like] Error handling like:', error);
        }
    }

    // --- Dropdown Menu ---
    document.querySelectorAll('.menu-dot').forEach(dot => {
        dot.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.closest('.post-menu-dropdown').querySelector('.dropdown-content');
            console.log('[Dropdown] Menu dot clicked, toggling dropdown:', dropdown);
            if (dropdown) {
                document.querySelectorAll('.dropdown-content.show').forEach(openDropdown => {
                    if (openDropdown !== dropdown) {
                        openDropdown.classList.remove('show');
                    }
                });
                dropdown.classList.toggle('show');
                console.log(`[Dropdown] Dropdown show class: ${dropdown.classList.contains('show')}`);
            } else {
                console.error('[Dropdown] Dropdown not found for menu dot:', this);
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.post-menu-dropdown')) {
            document.querySelectorAll('.dropdown-content.show').forEach(dropdown => {
                dropdown.classList.remove('show');
                console.log('[Dropdown] Closed dropdown on outside click.');
            });
        }
    });

    document.querySelectorAll('.dropdown-content').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // --- Comment Submission FIX ---
    document.querySelectorAll('.submit-comment-button').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            console.log('[Comment] Submit button clicked.');
            await submitComment(this);
        });
    });

    document.querySelectorAll('.comment-input').forEach(input => {
        input.addEventListener('keypress', async function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                console.log('[Comment] Enter key pressed.');
                await submitComment(this);
            }
        });

        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = `${this.scrollHeight}px`;
        });
    });

    async function submitComment(element) {
        const postCard = element.closest('.post-card');
        const commentInput = postCard.querySelector('.comment-input');
        const postId = postCard.dataset.postId;
        const commentContent = commentInput.value.trim();
        const commentsCountDisplay = postCard.querySelector('.comments-count');
        const commentsSection = postCard.querySelector('.recent-comments-section');

        console.log(`[Comment] Comment submission initiated for post ${postId}.`);

        if (!commentContent) {
            console.warn('[Comment] Comment content is empty, aborting submission.');
            return;
        }

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

            console.log(`[Comment] Server response status: ${response.status}`);
            if (!response.ok) {
                if (response.status === 401) {
                    console.error('[Comment] Authentication error: User not logged in.');
                    window.location.href = '/login';
                }
                throw new Error(`[Comment] HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('[Comment] AJAX response received:', data);

            // Update UI with the new comment
            const newCommentHtml = `
                <div class="recent-comment-item mb-1">
                    <a href="/profile/${data.comment.user.id}" class="comment-username font-weight-bold mr-1">
                        ${data.comment.user.username}
                    </a>
                    <span class="comment-text">${data.comment.content}</span>
                </div>
            `;

            if (commentsSection) {
                commentsSection.insertAdjacentHTML('afterbegin', newCommentHtml);
                const currentComments = commentsSection.querySelectorAll('.recent-comment-item');
                if (currentComments.length > 3) {
                    currentComments[currentComments.length - 1].remove();
                }
                console.log('[Comment] UI updated with new comment.');
            } else {
                console.error('[Comment] Comments section not found to update.');
            }

            if (commentsCountDisplay) {
                commentsCountDisplay.textContent = `${data.comments_count}`;
                console.log(`[Comment] Comments count updated to ${data.comments_count}.`);
            } else {
                console.error('[Comment] Comments count display not found.');
            }

            commentInput.value = '';
            commentInput.style.height = 'auto';
            console.log('[Comment] Comment input field cleared.');

        } catch (error) {
            console.error('[Comment] Error submitting comment:', error);
        }
    }

    // --- Share Modal Functionality ---
    document.querySelectorAll('.share-post').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const modal = document.getElementById(`shareModal-${postId}`);
            console.log(`[Share] Opening share modal for post ${postId}.`);
            if (modal) {
                modal.style.display = 'flex';
            }
        });
    });

    document.querySelectorAll('.cancel-share-btn').forEach(button => {
        button.addEventListener('click', function () {
            const modal = this.closest('.share-modal');
            console.log('[Share] Closing share modal.');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.confirm-share-btn').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.getAttribute('data-post-id');
            const modal = this.closest('.share-modal');
            const checkboxes = modal.querySelectorAll('input[name="followers"]:checked');
            const followers = Array.from(checkboxes).map(cb => cb.value);

            console.log(`[Share] Sharing post ${postId} with followers:`, followers);

            if (followers.length === 0) {
                alert('Please select at least one follower to share with.');
                console.warn('[Share] No followers selected.');
                return;
            }

            fetch('/posts/share', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    post_id: postId,
                    followers: followers
                })
            })
            .then(response => {
                console.log(`[Share] Server response status: ${response.status}`);
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.message || 'Failed to share post'); });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message || 'Post shared successfully!');
                modal.style.display = 'none';
                console.log('[Share] Post shared successfully.');
            })
            .catch(error => {
                console.error('[Share] Error:', error);
                alert(error.message || 'An error occurred while sharing the post.');
            });
        });
    });

    // Add this inside your DOMContentLoaded listener
document.querySelectorAll('.follower-search').forEach(searchBar => {
    searchBar.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const followersContainer = this.closest('.share-modal-content').querySelector('.followers-container');
        const followerItems = followersContainer.querySelectorAll('.follower-item');

        followerItems.forEach(item => {
            const username = item.querySelector('span').textContent.toLowerCase();
            if (username.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
});
