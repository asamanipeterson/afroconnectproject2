<div class="main-content">
    @if (!Request::is('user/*'))
        <!-- ✅ Story Bar -->
<div class="story-bar">
    <!-- Your Story -->
    <div class="story-item your-story" id="openStoryCreationModal">
        <div class="story-avatar-wrapper">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="story-avatar" alt="Your Profile">
            @else
                <i class="bi bi-person-circle story-avatar"></i>
            @endif
            <div class="plus-badge">+</div>
        </div>
        <p class="story-username">Your story</p>
    </div>

    <!-- Other Users' Stories -->
    {{-- @foreach($stories as $story)
        @if($story->user_id !== auth()->id())
            <div class="story-item" onclick="openStoryViewer({{ $story->id }})">
                <div class="story-avatar-wrapper">
                    <img src="{{ asset('storage/' . $story->user->profile_picture) }}" class="story-avatar active-story" alt="{{ $story->user->username }}">
                </div>
                <p class="story-username">{{ '@' . $story->user->username }}</p>
            </div>
        @endif
    @endforeach --}}
</div>

<!-- ✅ Story Creation Modal -->
<div class="modal" id="storyCreationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create Stories</h2>
            <button class="close-btn" id="closeStoryCreationModal">×</button>
        </div>
        <div class="form-container">
            <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div id="story-items-container">
                    <div class="story-item-block" data-index="0">
                        <h4>Story 1</h4>

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
                    </div>
                </div>

                <button type="button" id="addStoryBlock" class="story-btn">+ Add Another Story</button>

                <div class="form-submit-container">
                    <button type="submit" class="story-btn submit-btn">Submit Stories</button>
                </div>
            </form>
        </div>
    </div>
</div>




        <div class="post-creation-section">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Profile Picture">
            @else
                <i class="bi bi-person-circle avatar"></i>
            @endif
            <div class="post-input">
                <input type="text" placeholder="What's on your mind?" id="openPostCreationModal">
            </div>
            <button class="share-post-btn">Share Post</button>
        </div>
    @endif

    <div class="content">
        @yield('content')
    </div>


    <!-- ✅ Post Creation Modal -->
    <div class="modal" id="postCreationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Create Post</h2>
                <button class="close-btn" id="closePostCreationModal">×</button>
            </div>
            <div class="form-container">
                <form action="{{ route('create-post.submit') }}" method="POST" enctype="multipart/form-data" id="postForm">
                    @csrf

                    <div class="form-group">
                        <label for="caption">Caption:</label>
                        <textarea name="caption" id="caption" rows="3"></textarea>
                    </div>

                    <div id="media-inputs-container">
                        <h3>Media & Text Content (Max 10):</h3>
                        <div class="media-item" data-index="0">
                            <div class="form-group">
                                <label for="media_file_0">Upload File (Image/Video/Audio):</label>
                                <input type="file" name="media_files[0]" id="media_file_0" accept="image/*,video/*,audio/*">
                                <small>Max 50MB (jpeg, png, jpg, gif, mp4, mov, mp3, wav)</small>
                            </div>
                            <div class="form-group">
                                <label for="text_content_0">Or Add Text Content:</label>
                                <textarea name="text_contents[0]" id="text_content_0" rows="2"></textarea>
                                <small>Max 1000 characters.</small>
                            </div>
                            {{-- <div class="form-group">
                                <label for="sound_file_0">Add Sound (Optional):</label>
                                <input type="file" name="sound_files[0]" id="sound_file_0" accept="audio/*">
                                <small>Max 10MB (mp3, wav)</small>
                            </div> --}}
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="add-media-item" class="add-media-item">Add Another Media Item</button>
                        <span id="media-count">1/10 items</span>
                    </div>

                    <div class="form-submit-container">
                        <button type="submit" class="create-post-btn submit-btn">Create Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Scripts for modal toggle -->
<script>
    document.getElementById('openStoryCreationModal').addEventListener('click', () => {
        document.getElementById('storyCreationModal').style.display = 'flex';
    });

    document.getElementById('closeStoryCreationModal').addEventListener('click', () => {
        document.getElementById('storyCreationModal').style.display = 'none';
    });

    document.getElementById('openPostCreationModal').addEventListener('click', () => {
        document.getElementById('postCreationModal').style.display = 'flex';
    });

    document.getElementById('closePostCreationModal').addEventListener('click', () => {
        document.getElementById('postCreationModal').style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    };

    // Add Media Item Functionality
    let storyIndex = 1;

    document.getElementById('addStoryBlock').addEventListener('click', () => {
        const container = document.getElementById('story-items-container');

        const block = document.createElement('div');
        block.className = 'story-item-block';
        block.dataset.index = storyIndex;

        block.innerHTML = `
            <h4>Story ${storyIndex + 1}</h4>
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

        container.appendChild(block);
        storyIndex++;
    });

    function previewMedia(input) {
        const previewDiv = input.nextElementSibling;
        previewDiv.innerHTML = '';

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const type = file.type;

            const reader = new FileReader();
            reader.onload = function(e) {
                if (type.startsWith('image/')) {
                    previewDiv.innerHTML = `<img src="${e.target.result}" style="max-height: 150px;">`;
                } else if (type.startsWith('video/')) {
                    previewDiv.innerHTML = `<video controls style="max-height: 150px;"><source src="${e.target.result}"></video>`;
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Modal toggling
    document.getElementById('openStoryCreationModal').addEventListener('click', () => {
        document.getElementById('storyCreationModal').style.display = 'flex';
    });
    document.getElementById('closeStoryCreationModal').addEventListener('click', () => {
        document.getElementById('storyCreationModal').style.display = 'none';
    });
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    };

</script>
