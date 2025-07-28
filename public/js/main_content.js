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

    // --- Story Creation Modal ---
    const storyCreationModal = document.getElementById('storyCreationModal');
    const openStoryCreationModalButton = document.getElementById('openStoryCreationModal');
    const closeStoryCreationModal = document.getElementById('closeStoryCreationModal');
    const storyItemsContainer = document.getElementById('story-items-container');
    const storyCountSpan = document.getElementById('story-count');
    const maxStories = 10;
    let storyIndex = storyItemsContainer ? storyItemsContainer.querySelectorAll('.story-item-block').length : 0;

    const updateStoryCount = () => {
        const count = storyItemsContainer.querySelectorAll('.story-item-block').length;
        storyCountSpan.textContent = `${count}/${maxStories} stories`;
        storyItemsContainer.querySelectorAll('.story-item-block').forEach((block, index) => {
            const removeBtn = block.querySelector('.remove-story-item');
            const counter = block.querySelector('.story-counter');
            if (removeBtn) removeBtn.style.display = count > 1 ? 'inline-block' : 'none';
            if (counter) counter.textContent = `${index + 1}/${maxStories} stories`;
        });
    };

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

    const addStoryBlockBtn = document.getElementById('addStoryBlock');

    if (addStoryBlockBtn && storyItemsContainer) {
        addStoryBlockBtn.addEventListener('click', () => {
            if (storyItemsContainer.querySelectorAll('.story-item-block').length >= maxStories) return;

            const block = document.createElement('div');
            block.className = 'story-item-block';
            block.dataset.index = storyIndex;

            block.innerHTML = `
                <div class="story-block-header">
                    <button type="button" class="remove-story-item">Remove</button>
                </div>
                <div class="form-group">
                    <label>Caption:</label>
                    <input type="text" name="caption[]" placeholder="Enter a caption">
                </div>
                <div class="form-group">
                    <label>Upload Media (Image/Video):</label>
                    <input type="file" name="media[]" accept="image/*,video/*" onchange="previewMedia(this)">
                    <div class="media-preview mt-2"></div>
                </div>
                <div class="form-group">
                    <label>Text Story:</label>
                    <textarea name="text_content[]" rows="3" placeholder="Write something..."></textarea>
                </div>
                <div class="form-group">
                    <label>Background Color:</label>
                    <input type="color" name="background[]" value="#3B82F6">
                </div>
                <hr>
            `;
            storyItemsContainer.appendChild(block);
            storyIndex++;
            updateStoryCount();
        });

        storyItemsContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-story-item')) {
                const block = e.target.closest('.story-item-block');
                if (block) {
                    block.remove();
                    updateStoryCount();
                }
            }
        });

        updateStoryCount();
    }

    // Add global variables to track users and their stories
    let allUsersWithStories = [];
    let currentUserIndex = 0;
    let currentStories = [];
    let currentStoryIndex = 0;
    let storySlider = document.querySelector('.story-slider');

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
            card.innerHTML = `
                <div class="story-header">
                    <img src="${story.user.profile_picture_url || '/default-avatar.png'}" class="story-viewer-avatar">
                    <p class="story-viewer-username" style="font-family: 'Inter', Helvetica Neue, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; font-weight: 500; font-size: 1rem;">${username} <span class="story-time">${createdAt}</span></p>
                </div>
                <div class="story-media-area">
                    ${story.story_type === 'image' ? `<img src="${story.media_url || '/default-image.png'}" class="story-media">` : ''}
                    ${story.story_type === 'video' ? `<video src="${story.media_url || '/default-video.mp4'}" class="story-media"></video>` : ''}
                    ${story.story_type === 'text' ? `<div class="story-text-content" style="background-color: ${story.background || '#3B82F6'}"><p>${story.text_content || 'No text'}</p></div>` : ''}
                    <button class="nav-btn prev" onclick="prevStory(this)"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="nav-btn next" onclick="nextStory(this)"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <div class="progress-bar" style="width: 0;"></div>
            `;
        } else {
            card.innerHTML = `<p>No stories available</p>`;
        }
        return card;
    }

    function displayCurrentStory() {
        const activeCard = storySlider.querySelector('.story-user-card.active');
        if (activeCard) {
            const story = currentStories[currentStoryIndex];
            const mediaArea = activeCard.querySelector('.story-media-area');
            mediaArea.innerHTML = getStoryContent(story) + `
                <button class="nav-btn prev" onclick="prevStory(this)"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="nav-btn next" onclick="nextStory(this)"><i class="fa-solid fa-chevron-right"></i></button>
            `;
            if (story.caption) {
                mediaArea.insertAdjacentHTML('beforeend', `<div class="story-caption"  >${story.caption || 'No caption'}</div>`);
            }
            activeCard.querySelector('.progress-bar').style.width = `${(currentStoryIndex + 1) / currentStories.length * 100}%`;
            const timeElement = activeCard.querySelector('.story-time');
            if (timeElement) timeElement.textContent = getRelativeTime(story.created_at);
            updateNavigation(activeCard);
        }
        const cardWidth = 400;
        const gap = 20;
        const offset = currentUserIndex * (cardWidth + gap) - (window.innerWidth - cardWidth) / 2 + gap;
        storySlider.style.transform = `translateX(-${offset}px)`;
    }

    function getStoryContent(story) {
        if (story.story_type === 'image') return `<img src="${story.media_url || '/default-image.png'}" class="story-media">`;
        if (story.story_type === 'video') return `<video src="${story.media_url || '/default-video.mp4'}" controls autoplay loop muted class="story-media"></video>`;
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
            } else {
                currentUserIndex = 0; // Loop back to the first user
            }
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
            } else {
                currentUserIndex = allUsersWithStories.length - 1; // Loop to the last user
            }
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
        displayCurrentStory();
    };

    function updateNavigation(card) {
        const prevBtn = card.querySelector('.nav-btn.prev');
        const nextBtn = card.querySelector('.nav-btn.next');
        if (prevBtn && nextBtn) {
            const hasMultipleStories = currentStories.length > 1;
            prevBtn.style.display = hasMultipleStories || currentUserIndex > 0 ? 'flex' : 'none';
            nextBtn.style.display = hasMultipleStories || currentUserIndex < allUsersWithStories.length - 1 ? 'flex' : 'none';
        }
    }

    window.closeStoryViewer = function() {
        toggleModal(storyViewerModal, false);
        storyDisplayArea.innerHTML = '';
        currentStories = [];
        currentStoryIndex = 0;
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

    const addMediaBtn = document.getElementById('add-media-item');
    const mediaContainer = document.getElementById('media-inputs-container');
    const mediaCountSpan = document.getElementById('media-count');
    let itemIndex = mediaContainer.querySelectorAll('.media-item').length || 0;
    const maxItems = 20;

    function updateMediaCount() {
        const currentCount = mediaContainer.querySelectorAll('.media-item').length;
        mediaCountSpan.textContent = `${currentCount}/${maxItems} items`;

        mediaContainer.querySelectorAll('.media-item').forEach(item => {
            const removeButton = item.querySelector('.remove-media-item');
            if (removeButton) {
                removeButton.style.display = currentCount > 1 ? 'inline-block' : 'none';
            }
        });

        if (addMediaBtn) {
            addMediaBtn.disabled = currentCount >= maxItems;
            addMediaBtn.classList.toggle('opacity-50', currentCount >= maxItems);
        }
    }

    if (addMediaBtn) {
        addMediaBtn.addEventListener('click', () => {
            if (mediaContainer.querySelectorAll('.media-item').length >= maxItems) return;

            const newItem = document.createElement('div');
            newItem.classList.add('media-item');
            newItem.setAttribute('data-index', itemIndex);

            newItem.innerHTML = `
                <div class="form-group">
                    <label for="media_file_${itemIndex}">Upload File (Image/Video/Audio):</label>
                    <input type="file" name="media_files[${itemIndex}]" id="media_file_${itemIndex}" accept="image/*,video/*,audio/*">
                    <small>Max 50MB</small>
                </div>
                <div class="form-group">
                    <label for="text_content_${itemIndex}">Or Add Text Content:</label>
                    <textarea name="text_contents[${itemIndex}]" id="text_content_${itemIndex}" rows="2"></textarea>
                </div>
                <button type="button" class="remove-media-item">Remove</button>
            `;

            mediaContainer.appendChild(newItem);
            itemIndex++;
            updateMediaCount();
        });

        mediaContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-media-item')) {
                const item = event.target.closest('.media-item');
                item.remove();
                itemIndex = mediaContainer.querySelectorAll('.media-item').length;
                updateMediaCount();
            }
        });

        updateMediaCount();
    }

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
