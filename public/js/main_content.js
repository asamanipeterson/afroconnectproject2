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
    // Changed this to target the plus-badge specifically
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
            const counter = block.querySelector('.story-counter'); // This counter doesn't exist in your HTML, consider removing or adding.
            if (removeBtn) removeBtn.style.display = count > 1 ? 'inline-block' : 'none';
            if (counter) counter.textContent = `${index + 1}/${maxStories} stories`;
        });
    };

    if (storyCreationModal && openStoryCreationModalButton && closeStoryCreationModal) {
        // Event listener for the plus badge
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

    // --- Story Viewer Modal ---
    let currentStories = [];
    let currentStoryIndex = 0;
    const storyViewerModal = document.getElementById('storyViewerModal');
    const storyDisplayArea = document.getElementById('storyDisplayArea');

    window.openStoryViewer = function(userId) {
        // This will now handle both other users' stories and the authenticated user's own stories
        fetch(`/stories/user/${userId}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.length === 0) {
                    alert('No active stories available for this user.');
                    return;
                }
                currentStories = data;
                currentStoryIndex = 0;
                displayCurrentStory();
                toggleModal(storyViewerModal, true);
            })
            .catch(error => {
                console.error('Error fetching stories:', error);
                alert('Could not load stories. Please try again later.');
            });
    };

    function displayCurrentStory() {
        if (currentStories.length === 0) {
            storyDisplayArea.innerHTML = '<p>No stories to display.</p>';
            return;
        }

        const story = currentStories[currentStoryIndex];
        let storyContentHtml = '';
        let userInfoHtml = '';

        if (story.user) {
            const profilePicUrl = story.user.profile_picture_url;
            userInfoHtml = `
                <div class="story-header">
                    <img src="${profilePicUrl}" alt="${story.user.username}" class="story-viewer-avatar">
                    <p class="story-viewer-username">${story.user.username}</p>
                </div>
            `;
        }

        if (story.story_type === 'image' && story.media_url) {
            storyContentHtml = `<img src="${story.media_url}" alt="Story Media" class="story-media">`;
        } else if (story.story_type === 'video' && story.media_url) {
            storyContentHtml = `<video src="${story.media_url}" controls autoplay loop muted class="story-media"></video>`;
        } else if (story.story_type === 'text' && story.text_content) {
            storyContentHtml = `<div class="story-text-content" style="background-color: ${story.background || '#3B82F6'};"><p>${story.text_content}</p></div>`;
        } else {
            storyContentHtml = '<p>Story content not available or not supported.</p>';
        }

        let captionHtml = story.caption ? `<p class="story-caption">${story.caption}</p>` : '';

        storyDisplayArea.innerHTML = `
            ${userInfoHtml}
            <div class="story-media-area">${storyContentHtml}</div>
            ${captionHtml}
        `;

        const prevBtn = document.querySelector('.nav-btn.prev');
        const nextBtn = document.querySelector('.nav-btn.next');

        if (currentStories.length <= 1) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        } else if (currentStoryIndex === 0) {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'block';
        } else if (currentStoryIndex === currentStories.length - 1) {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'block';
            nextBtn.style.display = 'block';
        }
    }

    window.prevStory = function() {
        if (currentStories.length > 0) {
            currentStoryIndex = (currentStoryIndex - 1 + currentStories.length) % currentStories.length;
            displayCurrentStory();
        }
    };

    window.nextStory = function() {
        if (currentStories.length > 0) {
            currentStoryIndex = (currentStoryIndex + 1) % currentStories.length;
            displayCurrentStory();
        }
    };

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
