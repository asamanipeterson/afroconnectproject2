// JS to handle post modal + carousel + video controls

// Wait for DOM to be ready
if (document.readyState !== 'loading') init();
else document.addEventListener('DOMContentLoaded', init);

function init() {
  document.querySelectorAll('.view-comments-link, .post-item a').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const url = this.getAttribute('href');
      openPostModal(url);
    });
  });

  bindLikeButtons();

  document.addEventListener('click', closePostModal);

  document.querySelectorAll('.post-carousel').forEach(carousel => {
    const postId = carousel.dataset.postId;
    setupCarousel(postId);
  });
}

function openPostModal(url) {
  fetch(url)
    .then(res => res.text())
    .then(html => {
      const modalContainer = document.createElement('div');
      modalContainer.innerHTML = html;
      document.body.appendChild(modalContainer);
      document.body.style.overflow = 'hidden';

      const carousel = modalContainer.querySelector('.post-carousel');
      if (carousel) setupCarousel(carousel.dataset.postId);
      bindLikeButtons();
      initCustomVideoControls();
    });
}

function closePostModal(e) {
  if (e.target.classList.contains('post-modal-overlay') || e.target.classList.contains('close-button')) {
    const overlay = document.querySelector('.post-modal-overlay');
    if (overlay) {
      overlay.classList.add('closing');
      overlay.addEventListener('animationend', () => overlay.remove());
      document.body.style.overflow = 'auto';
    }
  }
}

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
  totalSlideEl.textContent = totalSlides;

  dotsContainer.innerHTML = '';
  slides.forEach((_, i) => {
    const dot = document.createElement('span');
    dot.classList.add('carousel-dot');
    dot.addEventListener('click', () => {
      currentIndex = i;
      updateUI();
    });
    dotsContainer.appendChild(dot);
  });

  function updateUI() {
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
    currentSlideEl.textContent = currentIndex + 1;

    dotsContainer.querySelectorAll('.carousel-dot').forEach((dot, i) => {
      dot.classList.toggle('active', i === currentIndex);
    });

    if (prevArrow) prevArrow.style.display = currentIndex > 0 ? 'block' : 'none';
    if (nextArrow) nextArrow.style.display = currentIndex < totalSlides - 1 ? 'block' : 'none';

    slides.forEach((slide, i) => {
      const video = slide.querySelector('video');
      if (video) {
        if (i === currentIndex) {
          // Only play if it's the current slide
          video.play();
        } else {
          // Pause and reset other videos
          video.pause();
          video.currentTime = 0;
        }
      }
    });
  }

  if (prevArrow) prevArrow.onclick = e => { e.stopPropagation(); if (currentIndex > 0) { currentIndex--; updateUI(); } };
  if (nextArrow) nextArrow.onclick = e => { e.stopPropagation(); if (currentIndex < totalSlides - 1) { currentIndex++; updateUI(); } };

  let startX = 0;
  carousel.addEventListener('touchstart', e => startX = e.touches[0].clientX);
  carousel.addEventListener('touchend', e => {
    const diff = startX - e.changedTouches[0].clientX;
    if (diff > 50 && currentIndex < totalSlides - 1) currentIndex++;
    else if (diff < -50 && currentIndex > 0) currentIndex--;
    updateUI();
  });

  updateUI();

  window[`carouselState_${postId}`] = {
    currentIndex,
    totalSlides,
    goTo: i => { if (i >= 0 && i < totalSlides) { currentIndex = i; updateUI(); } }
  };
}

function bindLikeButtons() {
  document.querySelectorAll('.like-btn').forEach(btn => {
    btn.onclick = function () {
      const postId = this.dataset.postId;
      fetch(`/like/${postId}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
        .then(res => res.json())
        .then(data => {
          this.classList.toggle('liked', data.liked);
          this.nextElementSibling.textContent = `${data.likes} likes`;
        });
    };
  });
}

function initCustomVideoControls() {
  document.querySelectorAll('.post-video').forEach(video => {
    const container = video.closest('.carousel-slide');
    const playPauseBtn = container.querySelector('.play-pause-btn');
    const seekBar = container.querySelector('.seek-bar');
    const timeDisplay = container.querySelector('.time-display');
    const muteBtn = container.querySelector('.mute-btn');

    // Set initial play/pause icon
    if (video.paused) {
      playPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
    } else {
      playPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
    }

    // Set initial mute icon
    if (video.muted) {
      muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
    } else {
      muteBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
    }

    video.addEventListener('timeupdate', () => {
      seekBar.value = (video.currentTime / video.duration) * 100 || 0;
      const minutes = Math.floor(video.currentTime / 60);
      const seconds = Math.floor(video.currentTime % 60).toString().padStart(2, '0');
      timeDisplay.textContent = `${minutes}:${seconds}`;
    });

    seekBar.addEventListener('input', () => {
      video.currentTime = (seekBar.value / 100) * video.duration;
    });

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
  });
}
