document.addEventListener('DOMContentLoaded', () => {
    // Story Creation Modal functionality
    const storyCreationModal = document.getElementById('storyCreationModal');
    const openStoryCreationModal = document.getElementById('openStoryCreationModal');
    const closeStoryCreationModal = document.getElementById('closeStoryCreationModal');

    if (storyCreationModal && openStoryCreationModal && closeStoryCreationModal) {
        openStoryCreationModal.addEventListener('click', (e) => {
            e.preventDefault();
            // toggleModal is expected to be global from common.js
            if (typeof toggleModal === 'function') {
                toggleModal(storyCreationModal, true);
            }
        });

        closeStoryCreationModal.addEventListener('click', () => {
            if (typeof toggleModal === 'function') {
                toggleModal(storyCreationModal, false);
            }
        });

        storyCreationModal.addEventListener('click', (e) => {
            if (e.target === storyCreationModal) {
                if (typeof toggleModal === 'function') {
                    toggleModal(storyCreationModal, false);
                }
            }
        });
    }

    // Story type toggle functionality
    const storyTypeRadios = document.querySelectorAll('input[name="story_type"]');
    const storyMediaInput = document.querySelector('#story-media-container #story-media');
    const storyTextContainer = document.getElementById('story-text-container');
    const storyBackgroundInput = document.getElementById('story-background');

    storyTypeRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'text') {
                storyMediaInput?.classList.add('hidden');
                storyTextContainer?.classList.remove('hidden');
                if (storyBackgroundInput) storyBackgroundInput.disabled = false;
                if (storyMediaInput) storyMediaInput.required = false;
                if (storyTextContainer?.querySelector('textarea')) storyTextContainer.querySelector('textarea').required = true;
            } else {
                storyMediaInput?.classList.remove('hidden');
                storyTextContainer?.classList.add('hidden');
                if (storyBackgroundInput) storyBackgroundInput.disabled = true;
                if (storyMediaInput) storyMediaInput.required = true;
                if (storyTextContainer?.querySelector('textarea')) storyTextContainer.querySelector('textarea').required = false;
            }
        });
    });

    // Set initial state based on default checked radio (usually 'image')
    const initialStoryType = document.querySelector('input[name="story_type"]:checked')?.value;
    if (initialStoryType === 'text') {
        storyMediaInput?.classList.add('hidden');
        storyTextContainer?.classList.remove('hidden');
        if (storyBackgroundInput) storyBackgroundInput.disabled = false;
        if (storyMediaInput) storyMediaInput.required = false;
        if (storyTextContainer?.querySelector('textarea')) storyTextContainer.querySelector('textarea').required = true;
    } else {
        storyMediaInput?.classList.remove('hidden');
        storyTextContainer?.classList.add('hidden');
        if (storyBackgroundInput) storyBackgroundInput.disabled = true;
        if (storyMediaInput) storyMediaInput.required = true;
        if (storyTextContainer?.querySelector('textarea')) storyTextContainer.querySelector('textarea').required = false;
    }


    // Post Creation Modal functionality
    const postCreationModal = document.getElementById('postCreationModal');
    const openPostCreationModal = document.getElementById('openPostCreationModal');
    const closePostCreationModal = document.getElementById('closePostCreationModal');
    const sharePostBtn = document.querySelector('.share-post-btn'); // Button in main content area

    if (postCreationModal && openPostCreationModal && closePostCreationModal && sharePostBtn) {
        // Attach listener to both the input field and the "Share Post" button
        [openPostCreationModal, sharePostBtn].forEach(element => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                if (typeof toggleModal === 'function') {
                    toggleModal(postCreationModal, true);
                }
            });
        });

        closePostCreationModal.addEventListener('click', () => {
            if (typeof toggleModal === 'function') {
                toggleModal(postCreationModal, false);
            }
        });

        postCreationModal.addEventListener('click', (e) => {
            if (e.target === postCreationModal) {
                if (typeof toggleModal === 'function') {
                    toggleModal(postCreationModal, false);
                }
            }
        });
    }

    // Post media item functionality with dynamic height adjustment
    const addMediaBtn = document.getElementById('add-media-item');
    const mediaContainer = document.getElementById('media-inputs-container');
    const postForm = document.getElementById('postForm');
    let itemIndex = 0; // Starting index for new media items

    // Initialize itemIndex based on existing items if any
    if (mediaContainer) {
        const existingMediaItems = mediaContainer.querySelectorAll('.media-item');
        if (existingMediaItems.length > 0) {
            let maxExistingIndex = -1;
            existingMediaItems.forEach(item => {
                const dataIndex = parseInt(item.getAttribute('data-index'));
                if (!isNaN(dataIndex) && dataIndex > maxExistingIndex) {
                    maxExistingIndex = dataIndex;
                }
            });
            itemIndex = maxExistingIndex + 1;
        }
    }

    const maxItems = 20;
    const mediaCountSpan = document.getElementById('media-count'); // Renamed to avoid conflict

    function updateMediaCount() {
        if (!mediaContainer) return;
        const currentCount = mediaContainer.querySelectorAll('.media-item').length;
        if (mediaCountSpan) mediaCountSpan.textContent = `${currentCount}/${maxItems} items`;
        if (addMediaBtn) {
            addMediaBtn.disabled = currentCount >= maxItems;
            addMediaBtn.classList.toggle('opacity-50', currentCount >= maxItems); // Optional visual feedback
        }

        // Show/hide remove button based on item count
        mediaContainer.querySelectorAll('.media-item').forEach((item, index) => {
            const removeButton = item.querySelector('.remove-media-item');
            if (removeButton) {
                // Only allow removal if it's not the first item (data-index 0) and there's more than one item
                if (item.getAttribute('data-index') !== '0' && currentCount > 1) {
                    removeButton.classList.remove('hidden');
                } else {
                    removeButton.classList.add('hidden');
                }
            }
        });
        adjustFormHeight();
    }



    if (addMediaBtn) {
        addMediaBtn.addEventListener('click', function () {
            if (mediaContainer.querySelectorAll('.media-item').length >= maxItems) {
                return; // Do nothing if max items reached
            }

            const newItem = document.createElement('div');
            newItem.classList.add('media-item'); // Add relevant classes
            newItem.setAttribute('data-index', itemIndex);

            // Populate inner HTML for the new media item
            newItem.innerHTML = `
                <div class="form-group">
                    <label for="media_file_${itemIndex}">Upload File (Image/Video/Audio):</label>
                    <input type="file" name="media_files[${itemIndex}]" id="media_file_${itemIndex}" accept="image/*,video/*,audio/*">
                    <small>Max 50MB (jpeg, png, jpg, gif, mp4, mov, mp3, wav)</small>
                </div>
                <div class="form-group">
                    <label for="text_content_${itemIndex}">Or Add Text Content:</label>
                    <textarea name="text_contents[${itemIndex}]" id="text_content_${itemIndex}" rows="2"></textarea>
                    <small>Max 1000 characters.</small>
                </div>

                <button type="button" class="remove-media-item">Remove</button>
            `;
{/* <div class="form-group">
    <label for="sound_file_${itemIndex}">Add Sound (Optional):</label>
    <input type="file" name="sound_files[${itemIndex}]" id="sound_file_${itemIndex}" accept="audio/*">
<small>Max 10MB (mp3, wav). Will apply to this media item.</small>
</div> */}
            mediaContainer.appendChild(newItem); // Add new item to container
            itemIndex++; // Increment index for next item
            updateMediaCount(); // Update count and button state

            // Add event listener for the remove button of the newly created item
            newItem.querySelector('.remove-media-item')?.addEventListener('click', function () {
                newItem.remove(); // Remove the item
                // Re-index all remaining items to maintain correct array keys for form submission
                mediaContainer.querySelectorAll('.media-item').forEach((item, idx) => {
                    item.setAttribute('data-index', idx);
                    // Update 'name' and 'id' attributes for all inputs/textareas
                    item.querySelectorAll('[name^="media_files"], [name^="text_contents"], [name^="sound_files"]').forEach(input => {
                        const currentName = input.name;
                        const newName = currentName.replace(/\[\d+\]/, `[${idx}]`);
                        input.name = newName;
                        input.id = newName.replace(/\[|\]/g, '_'); // Update ID as well
                    });
                    // Update 'for' attributes of labels
                    item.querySelectorAll('label').forEach(label => {
                        const currentFor = label.getAttribute('for');
                        if (currentFor) {
                            const newFor = currentFor.replace(/_\d+/, `_${idx}`);
                            label.setAttribute('for', newFor);
                        }
                    });
                });
                itemIndex = mediaContainer.querySelectorAll('.media-item').length; // Reset itemIndex based on current count
                updateMediaCount(); // Update count and button state again
            });
        });
    }

    // Initial update on load and listener for window resize
    updateMediaCount();
    window.addEventListener('resize', adjustFormHeight);
});
