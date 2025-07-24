document.addEventListener('DOMContentLoaded', function () {
    // Open modal and load content
    function openPostModal(url) {
        fetch(url)
            .then(res => res.text())
            .then(html => {
                const modalContainer = document.createElement('div');
                modalContainer.innerHTML = html;
                document.body.appendChild(modalContainer);
                document.body.style.overflow = 'hidden';

                // Init carousel in modal
                const carousel = modalContainer.querySelector('.post-carousel');
                if (carousel) {
                    const postId = carousel.dataset.postId;
                    setupCarousel(postId);
                }

                // Init like buttons in modal
                bindLikeButtons();
            });
    }

    // ✅ View Comments Link
    document.querySelectorAll('.view-comments-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            openPostModal(url);
        });
    });

    // ✅ View Post Click (THIS WAS MISSING)
document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.post-item a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');

                fetch(url)
                    .then(res => res.text())
                    .then(html => {
                        const modalContainer = document.createElement('div');
                        modalContainer.innerHTML = html;
                        document.body.appendChild(modalContainer);
                        document.body.style.overflow = 'hidden'; // prevent scroll
                    });
            });
        });
    });
    // Close modal
    document.addEventListener('click', function (event) {
        if (
            event.target.classList.contains('post-modal-overlay') ||
            event.target.classList.contains('close-button')
        ) {
            const overlay = document.querySelector('.post-modal-overlay');
            if (overlay) {
                overlay.remove();
                document.body.style.overflow = 'auto';
            }
        }
    });

    // Init carousels on page load (for visible posts)
    document.querySelectorAll('.post-carousel').forEach(carousel => {
        const postId = carousel.dataset.postId;
        setupCarousel(postId);
    });

    // Init like buttons on page load
    bindLikeButtons();
});

// ===== Carousel Setup =====
function setupCarousel(postId) {
    const carousel = document.getElementById(`carousel-${postId}`);
    if (!carousel) return;

    const slides = carousel.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
    const currentSlideEl = document.getElementById(`currentSlide-${postId}`);
    const totalSlideEl = document.getElementById(`totalSlides-${postId}`);
    const dotsContainer = document.getElementById(`carouselDots-${postId}`);
    const prevArrow = document.getElementById(`prevArrow-${postId}`);
    const nextArrow = document.getElementById(`nextArrow-${postId}`);

    let currentIndex = 0;
    totalSlideEl.innerText = totalSlides;

    // Create navigation dots
    dotsContainer.innerHTML = '';
    slides.forEach((_, i) => {
        const dot = document.createElement('span');
        dot.classList.add('carousel-dot');
        dot.onclick = () => {
            currentIndex = i;
            updateSlidePosition();
        };
        dotsContainer.appendChild(dot);
    });

    function updateSlidePosition() {


        carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        currentSlideEl.innerText = currentIndex + 1;

        // Update active dot
        dotsContainer.querySelectorAll('.carousel-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });

        // Update arrow visibility
        if (prevArrow && nextArrow) {
            prevArrow.style.display = currentIndex === 0 ? 'none' : 'block';
            nextArrow.style.display = currentIndex === totalSlides - 1 ? 'none' : 'block';
        }

        // Manage video play/pause
        slides.forEach((slide, i) => {
            const video = slide.querySelector('video');
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

    // Touch swipe support
    let startX = 0;
    carousel.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
    });

    carousel.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (diff > 50 && currentIndex < totalSlides - 1) {
            currentIndex++;
            updateSlidePosition();
        } else if (diff < -50 && currentIndex > 0) {
            currentIndex--;
            updateSlidePosition();
        }
    });

    updateSlidePosition();

    window[`carouselState_${postId}`] = {
        currentIndex,
        updateSlidePosition,
        slides,
        totalSlides
    };
}

function nextSlide(e, postId) {
    if (e) e.stopPropagation();
    const state = window[`carouselState_${postId}`];
    if (state && state.currentIndex < state.totalSlides - 1) {
        state.currentIndex++;
        state.updateSlidePosition();
    }
}

function prevSlide(e, postId) {
    if (e) e.stopPropagation();
    const state = window[`carouselState_${postId}`];
    if (state && state.currentIndex > 0) {
        state.currentIndex--;
        state.updateSlidePosition();
    }
}


// ===== Like Button Logic =====
function bindLikeButtons() {
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.onclick = function () {
            const postId = this.dataset.postId;
            fetch(`/like/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.classList.toggle('liked', data.liked);
                    this.nextElementSibling.textContent = `${data.likes} likes`;
                });
        };
    });
}

// ===== Global Modal Close =====
function closePostModal(event) {
    if (
        event.target.classList.contains('post-modal-overlay') ||
        event.target.classList.contains('close-button')
    ) {
        const overlay = document.querySelector('.post-modal-overlay');
        if (overlay) {
            overlay.remove();
            document.body.style.overflow = 'auto';
        }
    }
}
