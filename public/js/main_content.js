document.addEventListener('DOMContentLoaded', () => {
    // Utility: Modal toggle
    function toggleModal(modalElement, show) {
        if (!modalElement) return;
        if (show) {
            modalElement.style.display = 'flex';
            setTimeout(() => {
                modalElement.classList.add('show');
                document.body.classList.add('modal-open');
            }, 10);
        } else {
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    }
    // Add global variables to track users and their stories
    let allUsersWithStories = [];
    let currentUserIndex = 0;
    let currentStories = [];
    let currentStoryIndex = 0;
    let storySlider = document.querySelector('.story-slider');
    let timerInterval = null;
    let isPlaying = true;
    let isMuted = true; // Start muted as per the initial video setup

    window.openStoryViewer = function(userId) {
        fetch('/stories/all' + (userId ? `?start_user_id=${userId}` : ''))
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                allUsersWithStories = data.map(user => user.id);
                currentUserIndex = allUsersWithStories.indexOf(parseInt(userId)) || 0;
                if (allUsersWithStories.length === 0) console.error('No users with stories found');
                loadUserStories();
            })
            .catch(error => console.error('Error fetching stories:', error));
    };

    function loadUserStories() {
        if (!storySlider) {
            console.error('storySlider element not found');
            return;
        }
        storySlider.innerHTML = '';
        allUsersWithStories.forEach((userId, index) => {
            fetch(`/stories/user/${userId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`Failed to fetch stories for user ${userId}`);
                    return response.json();
                })
                .then(stories => {
                    if (!stories || stories.length === 0) console.warn(`No stories for user ${userId}`);
                    const card = createStoryCard(userId, stories);
                    storySlider.appendChild(card);
                    if (index === currentUserIndex) {
                        card.classList.add('active');
                        currentStories = stories;
                        currentStoryIndex = 0;
                        displayCurrentStory();
                    }
                })
                .catch(error => console.error(`Error fetching stories for user ${userId}:`, error));
        });
        toggleModal(storyViewerModal, true);
    }

    function createStoryCard(userId, stories) {
        const card = document.createElement('div');
        card.className = 'story-user-card';
        if (stories && stories.length > 0) {
            const story = stories[0];
            const createdAt = getRelativeTime(story.created_at);
            const username = userId == window.authUserId ? 'Your story' : (story.user.username || 'Unknown');
            const profileUrl = userId == window.authUserId ? '#' : (story.user.profile_url || `/profile/${userId}`);
            const progressBars = stories.map((_, i) => `
                <div class="progress-bar">
                    <div class="progress-bar-fill" data-index="${i}"></div>
                </div>
            `).join('');
            card.innerHTML = `
                <button class="play-pause-btn">${isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>'}</button>
                <button class="mute-unmute-btn">${isMuted ? '<i class="fa-solid fa-volume-mute"></i>' : '<i class="fa-solid fa-volume-up"></i>'}</button>
                <div class="progress-bar-container">${progressBars}</div>
                <div class="story-header">
                    <img src="${story.user.profile_picture_url || '/default-avatar.png'}" class="story-viewer-avatar">
                    <p class="story-viewer-username"><a href="${profileUrl}" class="profile-link">${username}</a> <span class="story-time">${createdAt}</span></p>
                </div>
                <div class="story-media-area"></div>
            `;
            card.querySelector('.play-pause-btn').addEventListener('click', togglePlayPause);
            card.querySelector('.mute-unmute-btn').addEventListener('click', toggleMuteUnmute);
        } else {
            card.innerHTML = `<p>No stories available</p>`;
        }
        return card;
    }

    function displayCurrentStory() {
        const activeCard = storySlider.querySelector('.story-user-card.active');
        if (activeCard && currentStories.length > 0) {
            const story = currentStories[currentStoryIndex];
            const mediaArea = activeCard.querySelector('.story-media-area');
            const video = mediaArea.querySelector('video');
            mediaArea.innerHTML = getStoryContent(story) + `
                <button class="nav-btn prev" onclick="prevStory(this)"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="nav-btn next" onclick="nextStory(this)"><i class="fa-solid fa-chevron-right"></i></button>
            `;
            if (story.caption) {
                mediaArea.insertAdjacentHTML('beforeend', `<div class="story-caption">${story.caption || 'No caption'}</div>`);
            }
            const progressBars = activeCard.querySelectorAll('.progress-bar-fill');
            progressBars.forEach((bar, index) => {
                bar.style.width = index < currentStoryIndex ? '100%' : index === currentStoryIndex ? '0%' : '0%';
            });
            const timeElement = activeCard.querySelector('.story-time');
            if (timeElement) timeElement.textContent = getRelativeTime(story.created_at);
            updateNavigation(activeCard);
            const playPauseBtn = activeCard.querySelector('.play-pause-btn');
            const muteUnmuteBtn = activeCard.querySelector('.mute-unmute-btn');
            playPauseBtn.innerHTML = isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
            muteUnmuteBtn.innerHTML = isMuted ? '<i class="fa-solid fa-volume-mute"></i>' : '<i class="fa-solid fa-volume-up"></i>';
            if (isPlaying) {
                startProgressTimer(activeCard.querySelector(`.progress-bar-fill[data-index="${currentStoryIndex}"]`));
                if (video) {
                    video.play().catch(error => console.error('Video play failed:', error));
                    video.muted = isMuted;
                }
            } else {
                if (timerInterval) clearInterval(timerInterval);
                if (video) video.pause();
            }
            const cardWidth = 400;
            const gap = 20;
            const offset = currentUserIndex * (cardWidth + gap) - (window.innerWidth - cardWidth) / 2 + gap;
            storySlider.style.transform = `translateX(-${offset}px)`;
        }
    }

    function startProgressTimer(progressBar) {
        if (timerInterval) clearInterval(timerInterval);
        let duration = currentStories[currentStoryIndex].story_type === 'video' ? 30000 : 5000;
        let startTime = Date.now();
        timerInterval = setInterval(() => {
            let elapsed = Date.now() - startTime;
            let progress = Math.min((elapsed / duration) * 100, 100);
            progressBar.style.width = `${progress}%`;
            if (progress >= 100) {
                clearInterval(timerInterval);
                nextStory(progressBar.closest('.story-user-card').querySelector('.next'));
            }
        }, 50);
    }

    function togglePlayPause() {
        isPlaying = !isPlaying;
        const activeCard = storySlider.querySelector('.story-user-card.active');
        const playPauseBtn = activeCard.querySelector('.play-pause-btn');
        const video = activeCard.querySelector('.story-media-area video');
        playPauseBtn.innerHTML = isPlaying ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
        if (isPlaying) {
            startProgressTimer(activeCard.querySelector(`.progress-bar-fill[data-index="${currentStoryIndex}"]`));
            if (video) video.play().catch(error => console.error('Video play failed:', error));
        } else {
            if (timerInterval) clearInterval(timerInterval);
            if (video) video.pause();
        }
    }

    function toggleMuteUnmute() {
        isMuted = !isMuted;
        const activeCard = storySlider.querySelector('.story-user-card.active');
        const muteUnmuteBtn = activeCard.querySelector('.mute-unmute-btn');
        const video = activeCard.querySelector('.story-media-area video');
        muteUnmuteBtn.innerHTML = isMuted ? '<i class="fa-solid fa-volume-mute"></i>' : '<i class="fa-solid fa-volume-up"></i>';
        if (video) video.muted = isMuted;
    }

    function getStoryContent(story) {
        if (story.story_type === 'image') return `<img src="${story.media_url || '/default-image.png'}" class="story-media">`;
        if (story.story_type === 'video') return `<video src="${story.media_url || '/default-video.mp4'}" autoplay loop muted class="story-media"></video>`;
        if (story.story_type === 'text') return `<div class="story-text-content" style="background-color: ${story.background || '#3B82F6'}"><p>${story.text_content || 'No text'}</p></div>`;
        return '<p>Content not supported</p>';
    }

    function getRelativeTime(createdAt) {
        const now = new Date();
        const created = new Date(createdAt);
        const diffMs = now - created;
        const diffMin = Math.floor(diffMs / 60000);
        const diffHour = Math.floor(diffMs / 3600000);
        if (diffMin < 1) return 'Just now';
        if (diffMin < 60) return `${diffMin}m`;
        return `${diffHour}h`;
    }

    window.nextStory = function(button) {
        const card = button.closest('.story-user-card');
        if (currentStoryIndex < currentStories.length - 1) {
            currentStoryIndex++;
        } else {
            if (currentUserIndex < allUsersWithStories.length - 1) {
                currentUserIndex++;
                const nextUserId = allUsersWithStories[currentUserIndex];
                fetch(`/stories/user/${nextUserId}`)
                    .then(response => response.json())
                    .then(data => {
                        currentStories = data || [];
                        currentStoryIndex = 0;
                        storySlider.querySelectorAll('.story-user-card').forEach(c => c.classList.remove('active'));
                        storySlider.querySelectorAll('.story-user-card')[currentUserIndex].classList.add('active');
                        displayCurrentStory();
                    })
                    .catch(error => console.error(`Error fetching stories for user ${nextUserId}:`, error));
            } else {
                if (currentStoryIndex >= currentStories.length - 1) {
                    closeStoryViewer();
                    return;
                }
            }
        }
        displayCurrentStory();
    };

    window.prevStory = function(button) {
        const card = button.closest('.story-user-card');
        if (currentStoryIndex > 0) {
            currentStoryIndex--;
        } else {
            if (currentUserIndex > 0) {
                currentUserIndex--;
                const prevUserId = allUsersWithStories[currentUserIndex];
                fetch(`/stories/user/${prevUserId}`)
                    .then(response => response.json())
                    .then(data => {
                        currentStories = data || [];
                        currentStoryIndex = data.length - 1;
                        storySlider.querySelectorAll('.story-user-card').forEach(c => c.classList.remove('active'));
                        storySlider.querySelectorAll('.story-user-card')[currentUserIndex].classList.add('active');
                        displayCurrentStory();
                    })
                    .catch(error => console.error(`Error fetching stories for user ${prevUserId}:`, error));
            }
        }
        displayCurrentStory();
    };

    function updateNavigation(card) {
        const prevBtn = card.querySelector('.play-pause-btn');
        const nextBtn = card.querySelector('.nav-btn.next');
        if (prevBtn && nextBtn) {
            const hasMultipleStories = currentStories.length > 1;
            prevBtn.style.display = hasMultipleStories || currentUserIndex > 0 ? 'flex' : 'none';
            nextBtn.style.display = hasMultipleStories || currentUserIndex < allUsersWithStories.length - 1 ? 'flex' : 'none';
        }
    }

    window.closeStoryViewer = function() {
        if (timerInterval) clearInterval(timerInterval);
        toggleModal(storyViewerModal, false);
        storyDisplayArea.innerHTML = '';
        currentStories = [];
        currentStoryIndex = 0;
    };

    // --- Story Creation Modal ---
    const storyCreationModal = document.getElementById('storyCreationModal');
    const openStoryCreationModalButton = document.getElementById('openStoryCreationModal');
    const closeStoryCreationModal = document.getElementById('closeStoryCreationModal');
    const storyItemsContainer = document.getElementById('story-items-container');
    const storyCountSpan = document.getElementById('story-count');
    const maxStories = 10;
    let storyIndex = storyItemsContainer ? storyItemsContainer.querySelectorAll('.story-item-block').length : 0;

    if (storyCreationModal && openStoryCreationModalButton && closeStoryCreationModal) {
        openStoryCreationModalButton.addEventListener('click', (e) => {
            e.preventDefault();
            toggleModal(storyCreationModal, true);
        });
        closeStoryCreationModal.addEventListener('click', () => toggleModal(storyCreationModal, false));
        storyCreationModal.addEventListener('click', (e) => {
            if (e.target === storyCreationModal) toggleModal(storyCreationModal, false);
        });
    }

    const updateStoryCount = () => {
        const count = storyItemsContainer ? storyItemsContainer.querySelectorAll('.story-item-block').length : 0;
        if (storyCountSpan) {
            storyCountSpan.textContent = `${count}/${maxStories} stories`;
            storyItemsContainer.querySelectorAll('.story-item-block').forEach((block, index) => {
                const removeBtn = block.querySelector('.remove-story-item');
                const counter = block.querySelector('.story-counter');
                if (removeBtn) removeBtn.style.display = count > 1 ? 'inline-block' : 'none';
                if (counter) counter.textContent = `${index + 1}/${maxStories} stories`;
            });
        }
    };

    // --- Post Creation Modal ---
    const postCreationModal = document.getElementById('postCreationModal');
    const openPostCreationModal = document.getElementById('openPostCreationModal');
    const closePostCreationModal = document.getElementById('closePostCreationModal');
    const sharePostBtn = document.querySelector('.share-post-btn');

    if (postCreationModal && openPostCreationModal && closePostCreationModal && sharePostBtn) {
        [openPostCreationModal, sharePostBtn].forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                toggleModal(postCreationModal, true);
            });
        });
        closePostCreationModal.addEventListener('click', () => {
            toggleModal(postCreationModal, false);
        });
    }

    const storyMediaInput = document.querySelector('#media-upload-story');
    const storyMediaCountText = document.querySelector('#story-media-count');
    const storyMediaPreview = document.querySelector('#story-media-preview');
    const captionsContainer = document.querySelector('#captions-container');
    const postMediaInput = document.querySelector('#media-upload-post');
    const postMediaCountText = document.querySelector('#post-media-count');
    const postMediaPreview = document.querySelector('#post-media-preview');

    function generateMediaPreview(input, previewContainer, maxFiles, captionsContainer = null) {
        previewContainer.innerHTML = '';
        if (captionsContainer) captionsContainer.innerHTML = '';
        const files = Array.from(input.files).slice(0, maxFiles);
        const dt = new DataTransfer();
        files.forEach((file, index) => {
            const reader = new FileReader();
            const previewItem = document.createElement('div');
            previewItem.classList.add('media-preview-item');
            previewItem.setAttribute('data-index', index);
            if (file.type.startsWith('image/')) {
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    previewItem.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                reader.onload = function(e) {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    video.style.maxWidth = '100px';
                    previewItem.appendChild(video);
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('audio/')) {
                reader.onload = function(e) {
                    const audio = document.createElement('audio');
                    audio.src = e.target.result;
                    audio.controls = true;
                    previewItem.appendChild(audio);
                };
                reader.readAsDataURL(file);
            }
            const removeBtn = document.createElement('button');
            removeBtn.classList.add('remove-media-btn');
            removeBtn.textContent = '×';
            removeBtn.onclick = function(event) {
                event.stopPropagation();
                const idx = parseInt(previewItem.getAttribute('data-index'));
                previewItem.remove();
                if (captionsContainer) {
                    const captionGroup = captionsContainer.querySelector(`.caption-group[data-index="${idx}"]`);
                    if (captionGroup) captionGroup.remove();
                }
                const newFiles = Array.from(input.files).filter((_, i) => i !== idx);
                const newDt = new DataTransfer();
                newFiles.forEach(f => newDt.items.add(f));
                input.files = newDt.files;
                generateMediaPreview(input, previewContainer, maxFiles, captionsContainer);
            };
            previewItem.appendChild(removeBtn);
            previewContainer.appendChild(previewItem);

            if (captionsContainer) {
                const captionGroup = document.createElement('div');
                captionGroup.classList.add('caption-group');
                captionGroup.setAttribute('data-index', index);
                const label = document.createElement('label');
                label.textContent = `Caption for file ${index + 1}:`;
                const textarea = document.createElement('textarea');
                textarea.name = `captions[]`;
                textarea.rows = 2;
                textarea.placeholder = `Caption for ${file.name}`;
                textarea.maxLength = 200;
                captionGroup.appendChild(label);
                captionGroup.appendChild(textarea);
                captionsContainer.appendChild(captionGroup);
            }

            dt.items.add(file);
        });
        input.files = dt.files;
        updateMediaCount(input, input === storyMediaInput ? storyMediaCountText : postMediaCountText, maxFiles);
    }

    function updateMediaCount(input, countText, maxFiles) {
        const fileCount = input.files.length;
        countText.textContent = `Media · ${fileCount}/${maxFiles} - You can add up to ${maxFiles} files.`;
    }

    storyMediaInput?.addEventListener('change', function() {
        generateMediaPreview(this, storyMediaPreview, 10, captionsContainer);
    });

    postMediaInput?.addEventListener('change', function() {
        generateMediaPreview(this, postMediaPreview, 20);
    });

    window.previewMedia = function(input) {
        const previewContainer = input.nextElementSibling;
        previewContainer.innerHTML = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '120px';
                    img.style.maxHeight = '120px';
                    img.style.objectFit = 'cover';
                    previewContainer.appendChild(img);
                } else if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.controls = true;
                    video.style.maxWidth = '120px';
                    video.style.maxHeight = '120px';
                    video.style.objectFit = 'cover';
                    previewContainer.appendChild(video);
                }
            };
            reader.readAsDataURL(file);
        }
    };
});
