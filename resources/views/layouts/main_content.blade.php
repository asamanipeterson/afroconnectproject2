@include('layouts.head')

<div class="main-content">
    @if (!Request::is('user/*', 'live', 'marketplace'))
        <div class="story-bar">
            @php
                $userHasStories = isset($stories[auth()->id()]) && $stories[auth()->id()]->isNotEmpty();
            @endphp
            <div class="story-item your-story" id="yourStoryItem">
                <div class="story-avatar-wrapper @if($userHasStories) has-stories @endif">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ auth()->user()->profile_picture_url }}" class="story-avatar" alt="Your Profile"
                             @if($userHasStories) onclick="openStoryViewer({{ auth()->id() }})" @endif>
                    @else
                        <i class="bi bi-person-circle story-avatar"
                           @if($userHasStories) onclick="openStoryViewer({{ auth()->id() }})" @endif></i>
                    @endif
                    <div class="plus-badge" id="openStoryCreationModal">+</div>
                </div>
                <p class="story-username">Your story</p>
            </div>

            @foreach($stories as $userId => $userStories)
                @if($userId !== auth()->id())
                    @php $storyUser = $userStories->first()->user; @endphp
                    <div class="story-item has-stories" onclick="openStoryViewer({{ $storyUser->id }})">
                        <div class="story-avatar-wrapper">
                            <img src="{{ $storyUser->profile_picture_url }}" class="story-avatar" alt="{{ $storyUser->username }}">
                        </div>
                        <p class="story-username">{{ $storyUser->username }}</p>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="modal" id="storyCreationModal">
            <div class="modal-content">
                <div class="modal-headers">
                    <h2>Create Story</h2>
                    <button class="close-btn" id="closeStoryCreationModal">×</button>
                </div>
                <div class="form-container">
                    <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data" id="storyCreationForm">
                        @csrf
                        <div class="photo-section">
                            <label for="media-upload-story" class="photo-upload-box">
                                <div class="upload-icon-wrapper">
                                    <span class="upload-icon">📸</span>
                                </div>
                                <p class="add-photos-text">Add media (images, videos, audio)</p>
                                <p class="drag-drop-text">or drag and drop</p>
                                <input type="file" id="media-upload-story" name="media[]" multiple accept="image/jpeg,image/png,image/gif,video/mp4,video/quicktime" class="photo-input">
                            </label>
                            <p class="media-count" id="story-media-count">Media · 0/10 - You can add up to 10 files.</p>
                            <div class="media-preview" id="story-media-preview"></div>
                            <div class="captions-container" id="captions-container"></div>
                        </div>
                        <div class="form-group">
                            <label for="text_content">Text Content (Optional):</label>
                            <textarea name="text_content" id="text_content" rows="3" placeholder="Write something..."></textarea>
                            <small>Max 1000 characters.</small>
                        </div>
                        <div class="form-submit-container">
                            <button type="submit" class="submit-btn">Create Story</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="storyViewerModal" class="story-viewer-modal">
            <span class="close-story-viewer-btn" onclick="closeStoryViewer()">×</span>
            <div class="story-viewer-content">
                <div class="story-slider" id="storySlider"></div>
            </div>
        </div>

        <div class="post-creation-section">
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
            <div class="modal-headers">
                <h2>Create Post</h2>
                <button class="close-btn" id="closePostCreationModal">×</button>
            </div>
            <div class="form-container">
                <form action="{{ route('create-post.submit') }}" method="POST" enctype="multipart/form-data" id="postForm">
                    @csrf
                    @error('general')
                        <div class="error" style="color: red; margin-bottom: 10px;">{{ $message }}</div>
                    @enderror
                    <div class="form-group">
                        <label for="caption">Caption:</label>
                        <textarea name="caption" id="caption" rows="3" placeholder="Add a caption"></textarea>
                    </div>
                    <div class="photo-section">
                        <label for="media-upload-post" class="photo-upload-box">
                            <div class="upload-icon-wrapper">
                                <span class="upload-icon">📸</span>
                            </div>
                            <p class="add-photos-text">Add media (images, videos, audio)</p>
                            <p class="drag-drop-text">or drag and drop</p>
                            <input type="file" id="media-upload-post" name="media_files[]" multiple accept="image/*,video/*,audio/*" class="photo-input" maxlength="20">
                        </label>
                        <p class="media-count" id="post-media-count">Media · 0/20 - You can add up to 20 files.</p>
                        <div class="media-preview" id="post-media-preview"></div>
                    </div>
                    <div class="form-group">
                        <label for="text_content">Text Content (Optional):</label>
                        <textarea name="text_contents[]" id="text_content" rows="3" placeholder="Write something..."></textarea>
                        <small>Max 1000 characters.</small>
                    </div>
                    <div class="form-submit-container">
                        <button type="submit" class="submit-btn">Create Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- <div class="content">
        @yield('content')
    </div> --}}

    <script>
        window.authUserId = {{ auth()->id() }};
    </script>
    <script src="{{ asset('js/main_content.js') }}"></script>
</div>
