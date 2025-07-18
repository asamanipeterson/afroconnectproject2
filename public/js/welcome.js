document.addEventListener('DOMContentLoaded', function () {
    // --- Real-time New Comment Listener via Laravel Echo ---
    if (typeof Echo !== 'undefined') {
        document.querySelectorAll('.post-card').forEach(card => {
            const postId = card.dataset.postId;
            const commentsSection = card.querySelector('.recent-comments-section');
            const commentsCountDisplay = card.querySelector('.comments-count-display');
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
                        const currentCount = parseInt(commentsCountDisplay.textContent.match(/\d+/)) || 0;
                        commentsCountDisplay.textContent = `${currentCount + 1} ${currentCount + 1 === 1 ? 'Comment' : 'Comments'}`;
                    }
                })

                // --- Real-time Like Listener ---
                .listen('.PostLiked', (e) => {
                    console.log('❤️ Post liked/unliked by another user:', e);

                    if (likesCountSpan) {
                        likesCountSpan.textContent = `${e.likes_count} likes`;
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
            if (isMouseDown) {
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
            document.querySelectorAll('.dropdown-content.show').forEach(openDropdown => {
                if (openDropdown !== dropdown) {
                    openDropdown.classList.remove('show');
                }
            });
            dropdown.classList.toggle('show');
        });
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-content').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    });

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
