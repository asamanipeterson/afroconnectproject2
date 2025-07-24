<div class="main-content">
    @if (!Request::is('user/*'))
        <div class="story-bar">
            {{-- Your Story --}}
            @php
                // Check if the authenticated user has any stories
                // Assuming $stories is a collection where keys are user IDs
                $userHasStories = isset($stories[auth()->id()]) && $stories[auth()->id()]->isNotEmpty();
            @endphp
            <div class="story-item your-story">
                <div class="story-avatar-wrapper @if($userHasStories) has-stories @endif">
                    {{-- Use profile_picture_url accessor for consistency --}}
                    @if(auth()->user()->profile_picture)
                        <img src="{{ auth()->user()->profile_picture_url }}" class="story-avatar" alt="Your Profile"
                             @if($userHasStories) onclick="openStoryViewer({{ auth()->id() }})" @endif>
                    @else
                        <i class="bi bi-person-circle story-avatar"
                           @if($userHasStories) onclick="openStoryViewer({{ auth()->id() }})" @endif></i>
                    @endif
                    {{-- Only the plus badge opens the creation modal --}}
                    <div class="plus-badge" id="openStoryCreationModal">+</div>
                </div>
                <p class="story-username">Your story</p>
            </div>

            @foreach($stories as $userId => $userStories)
                @if($userId !== auth()->id())
                    @php $storyUser = $userStories->first()->user; @endphp
                    <div class="story-item" onclick="openStoryViewer({{ $storyUser->id }})">
                        <div class="story-avatar-wrapper">
                            {{-- Use profile_picture_url accessor for consistency --}}
                            <img src="{{ $storyUser->profile_picture_url }}" class="story-avatar active-story" alt="{{ $storyUser->username }}">
                        </div>
                        {{-- <p class="story-username">{{$storyUser->username }}</p> --}}
                    </div>
                @endif
            @endforeach
        </div>

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
                        <span id="story-count" class="ml-2">1/10 stories</span>
                        <div class="form-submit-container">
                            <button type="submit" class="story-btn submit-btn">Submit Stories</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- STORY VIEWER MODAL --}}
        <div id="storyViewerModal" class="story-viewer-modal">
            <div class="story-viewer-content">
                {{-- Close button for story viewer --}}
                <span class="close-story-viewer-btn" onclick="closeStoryViewer()">×</span>
                <div id="storyDisplayArea">


                </div>
                <button class="nav-btn prev" onclick="prevStory()">←</button>
                <button class="nav-btn next" onclick="nextStory()">→</button>
            </div>
        </div>

        <div class="post-creation-section">
            {{-- Use profile_picture_url accessor for consistency --}}
            @if(auth()->user()->profile_picture)
                <img src="{{ auth()->user()->profile_picture_url }}" class="avatar" alt="Profile Picture">
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
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="add-media-item" class="add-media-item">Add Another Media Item</button>
                        <span id="media-count">1/20 items</span>
                    </div>

                    <div class="form-submit-container">
                        <button type="submit" class="create-post-btn submit-btn">Create Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
