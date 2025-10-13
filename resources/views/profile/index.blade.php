@extends('layouts.app')

@section('title', $user->username . ' Profile')
@include('layouts.head')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
{{-- Add a specific style block for the tabs if you want to keep them here or in profile.css --}}
<style>
    /* Tabs Styles */

</style>
@endsection

@section('content')
<div class="profile-container">
    {{-- Profile Header (Existing content remains the same) --}}
    <div class="profile-header">
        <div class="profile-image {{ $user->stories->count() > 0 ? 'has-stories' : '' }}">
            @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="avatar">
            @else
                <div class="default-avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
            @endif
        </div>
        <div class="profile-info">
            <div class="top-row">
                <h2>{{ $user->username }}</h2>
                @if($user->is_verified)
                     <i class="bi bi-check-circle-fill verified-badge" title="Verified User"></i>
                 @endif
                @if (auth()->id() === $user->id)
                    <button id="openEditForm" class="editbtn">Edit Profile</button>
                @elseif (auth()->check())
                    <button class="follow-btn {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                        data-user-id="{{ $user->id }}">
                        {{ auth()->user()->isFollowing($user) ? 'Following' : 'Follow' }}
                    </button>
                    {{-- Fixed Form Action for Messaging --}}
                    <form action="{{ route('conversations.create', ['user' => $user->id]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="message-btn">Message</button>
                    </form>
                    <button id="openReportModal" class="report-btn">Report User</button>
                @endif
            </div>
            @php
                $followersCount = $user->followers()->count();
                $followingCount = $user->following()->count();
                $postsCount = $user->posts->count();
                // Check if the current user is viewing their own profile for the 'Saved' tab
                $is_own_profile = auth()->id() === $user->id;
            @endphp
            <ul class="profile-stats">
                <li><strong>{{ $postsCount }}</strong> {{ $postsCount === 1 ? 'Post' : 'Posts' }}</li>
                <li><strong>{{ $followersCount }}</strong> {{ $followersCount === 1 ? 'Follower' : 'Followers' }}</li>
                <li><strong>{{ $followingCount }}</strong> {{ $followingCount <= 1 ? 'Following' : 'Followings' }}</li>
            </ul>
            <p class="bio">{{ $user->bio }}</p>
            @if ($user->website)
                <a href="{{ $user->website }}" target="_blank" class="website">{{ $user->website }}</a>
            @endif
        </div>
    </div>

    {{-- MODALS (Report and Edit) - Leave existing modal code here --}}
    {{-- ... (Existing Report User Modal code) ... --}}
    @if (auth()->check() && auth()->id() !== $user->id)
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Report User</h2>
                <button class="close-btn" id="closeReportModal">&times;</button>
            </div>
            <form action="{{ route('users.report', $user) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="reason">Reason for reporting:</label>
                    <select name="reason" id="reason" required>
                        <option value="" disabled selected>Select a reason</option>
                        <option value="harassment">Harassment</option>
                        <option value="spam">Spam</option>
                        <option value="inappropriate">Inappropriate Behavior</option>
                        <option value="fake_account">Fake Account</option>
                        <option value="hate_speech">Hate Speech</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group" id="customReasonGroup" style="display: none;">
                    <label for="details">Please specify:</label>
                    <textarea name="details" id="details" rows="4" placeholder="Describe the reason..."></textarea>
                </div>
                <button type="submit" class="submit-btn">Submit Report</button>
            </form>
        </div>
    </div>
    @endif

    @if(auth()->id() === $user->id)
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <button class="close-btn" id="closeEditModal">&times;</button>
            </div>
            <form action="{{ route('update-profile', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" class="profile-picture-preview" alt="Current Profile Picture">
                    @endif
                    <input type="file" name="profile_picture" id="profile_picture">
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio" placeholder="Tell us about yourself...">{{ $user->bio ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label for="cover_picture">Cover Picture</label>
                    @if($user->cover_picture)
                        <img src="{{ asset('storage/' . $user->cover_picture) }}" class="cover-picture-preview" alt="Current Cover Picture">
                    @endif
                    <input type="file" name="cover_picture" id="cover_picture">
                </div>
                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="url" name="website" id="website" value="{{ $user->website }}">
                </div>
                <button type="submit" class="submit-btn">Save Changes</button>
            </form>
        </div>
    </div>
    @endif
    {{-- END MODALS --}}

    {{-- New Profile Tabs Navigation --}}
    <ul class="profile-tabs" role="tablist">
        <li>
            <button class="tab-button active" data-tab="posts" role="tab" aria-selected="true" aria-controls="posts-tab-pane">
                <i class="bi bi-grid-3x3"></i>
                <span>POSTS</span>
            </button>
        </li>
        @if($is_own_profile)
        <li>
            <button class="tab-button" data-tab="saved" role="tab" aria-selected="false" aria-controls="saved-tab-pane">
                <i class="bi bi-bookmark"></i>
                <span>SAVED</span>
            </button>
        </li>
        @endif
        <li>
            <button class="tab-button" data-tab="tagged" role="tab" aria-selected="false" aria-controls="tagged-tab-pane">
                <i class="bi bi-person-badge"></i>
                <span>TAGGED</span>
            </button>
        </li>
    </ul>

    {{-- Tab Content Containers --}}
    <div class="tab-content">
        {{-- POSTS Tab Pane (Original Content) --}}
        <div class="tab-pane active" id="posts-tab-pane" role="tabpanel">
            <div class="posts-grid">
                @forelse($groupedPosts as $groupId => $group)
                    @php
                        $firstPost = $group->first();
                        $firstMedia = $firstPost->media->first();
                        $isVideo = $firstMedia && $firstMedia->file_type === 'video';
                        $likesCount = $firstPost->likes->count();
                        $commentsCount = $firstPost->comments->count();
                    @endphp
                    <div class="post-item" data-post-id="{{ $firstPost->id }}">
                        <a href="{{ route('posts.show', $firstPost->id) }}" class="view-comments-link" data-post-id="{{ $firstPost->id }}">
                            @if($firstMedia)
                                @if($firstMedia->file_type === 'image')
                                    <img src="{{ asset('storage/' . $firstMedia->file_path) }}" alt="Post">
                                @elseif($firstMedia->file_type === 'video')
                                    <video muted loop>
                                        <source src="{{ asset('storage/' . $firstMedia->file_path) }}">
                                    </video>
                                @elseif($firstMedia->file_type === 'text')
                                    <div class="text-post">
                                        <p>{{ $firstMedia->text_content }}</p>
                                    </div>
                                @endif
                            @endif
                            @if($isVideo)
                                {{-- Reels Icon SVG --}}
                                <div class="reels-icon">
                                    <svg aria-label="Reels" class="x1lliihq x1n2onr6 x5n08af" fill="currentColor" height="24" role="img" viewBox="0 0 24 24" width="24">
                                        <line fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2" x1="2.049" x2="21.95" y1="7.002" y2="7.002"></line>
                                        <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="13.504" x2="16.362" y1="2.001" y2="7.002"></line>
                                        <line fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="7.207" x2="10.002" y1="2.11" y2="7.002"></line>
                                        <path d="M2 12.001v3.449c0 2.849.698 4.006 1.606 4.945.94.908 2.098 1.607 4.946 1.607h6.896c2.848 0 4.006-.699 4.946-1.607.908-.939 1.606-2.096 1.606-4.945V8.552c0-2.848-.698-4.006-1.606-4.945C19.454 2.699 18.296 2 15.448 2H8.552c-2.848 0-4.006.699-4.946 1.607C2.698 4.546 2 5.704 2 8.552Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                        <path d="M9.763 17.664a.908.908 0 0 1-.454-.787V11.63a.909.909 0 0 1 1.364-.788l4.545 2.624a.909.909 0 0 1 0 1.575l-4.545 2.624a.91.91 0 0 1-.91 0Z" fill-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            @if($firstPost->media->count() > 1)
                                {{-- Stack Icon SVG (Multi-media indicator) --}}
                                <div class="stack-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg" class="stroke-[2] size-4"><rect x="3" y="8" width="13" height="13" rx="4" stroke="white"></rect><path fill-rule="white" clip-rule="evenodd" d="M13 2.00004L12.8842 2.00002C12.0666 1.99982 11.5094 1.99968 11.0246 2.09611C9.92585 2.31466 8.95982 2.88816 8.25008 3.69274C7.90896 4.07944 7.62676 4.51983 7.41722 5.00004H9.76392C10.189 4.52493 10.7628 4.18736 11.4147 4.05768C11.6802 4.00488 12.0228 4.00004 13 4.00004H14.6C15.7366 4.00004 16.5289 4.00081 17.1458 4.05121C17.7509 4.10066 18.0986 4.19283 18.362 4.32702C18.9265 4.61464 19.3854 5.07358 19.673 5.63807C19.8072 5.90142 19.8994 6.24911 19.9488 6.85428C19.9992 7.47112 20 8.26343 20 9.40004V11C20 11.9773 19.9952 12.3199 19.9424 12.5853C19.8127 13.2373 19.4748 13.8114 19 14.2361V16.5829C20.4795 15.9374 21.5804 14.602 21.9039 12.9755C22.0004 12.4907 22.0002 11.9334 22 11.1158L22 11V9.40004V9.35725C22 8.27346 22 7.3993 21.9422 6.69141C21.8826 5.96256 21.7568 5.32238 21.455 4.73008C20.9757 3.78927 20.2108 3.02437 19.27 2.545C18.6777 2.24322 18.0375 2.1174 17.3086 2.05785C16.6007 2.00002 15.7266 2.00003 14.6428 2.00004L14.6 2.00004H13Z" fill="white"></path></svg>
                                </div>
                            @endif
                            <div class="hover-overlay">
                                <div class="post-stats-overlay">
                                    <span class="stat-item"><i class="bi bi-heart-fill"></i>{{ $likesCount }}</span>
                                    <span class="stat-item"><i class="fa-regular fa-comment"></i>{{ $commentsCount }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-camera-fill" style="font-size: 3rem;"></i>
                        <p>No posts yet.</p>
                        @if($is_own_profile)
                            <p>Share your first moment!</p>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SAVED Tab Pane (Only for own profile) --}}
        @if($is_own_profile)
        <div class="tab-pane" id="saved-tab-pane" role="tabpanel">
            {{--
                NOTE: You will need to pass an array of saved posts to this view from your controller.
                For now, let's assume you have a variable `$savedPosts` which contains the user's saved posts.
            --}}
            <div class="posts-grid">
                @if(isset($savedPosts) && $savedPosts->count() > 0)
@foreach($savedPosts as $post)
    @php
        $firstMedia = $post->media->first();
        $isVideo = $firstMedia && $firstMedia->file_type === 'video';
        $likesCount = $post->likes->count();
        $commentsCount = $post->comments->count();
    @endphp
    <div class="post-item" data-post-id="{{ $post->id }}">
        <a href="{{ route('posts.show', $post->id) }}">
            @if($firstMedia)
                @if($firstMedia->file_type === 'image')
                    <img src="{{ asset('storage/' . $firstMedia->file_path) }}" alt="Post">
                @elseif($firstMedia->file_type === 'video')
                    <video muted loop>
                        <source src="{{ asset('storage/' . $firstMedia->file_path) }}">
                    </video>
                @elseif($firstMedia->file_type === 'text')
                    <div class="text-post">
                        <p>{{ $firstMedia->text_content }}</p>
                    </div>
                @endif
            @endif

            @if($isVideo)
                <div class="reels-icon">...</div>
            @endif
            @if($post->media->count() > 1)
                <div class="stack-icon">...</div>
            @endif

            <div class="hover-overlay">
                <div class="post-stats-overlay">
                    <span class="stat-item"><i class="bi bi-heart-fill"></i>{{ $likesCount }}</span>
                    <span class="stat-item"><i class="fa-regular fa-comment"></i>{{ $commentsCount }}</span>
                </div>
            </div>
        </a>

        {{-- Optional: Unbookmark button --}}
        <form action="{{ route('posts.bookmark', $post->id) }}" method="POST" class="unbookmark-form">
    @csrf
    <button type="submit" class="unbookmark-btn">Unsave</button>
</form>

    </div>
@endforeach
                    {{-- For demonstration, I'll just put an empty state: --}}
                    {{--  <div class="empty-state">
                        <i class="bi bi-bookmark-fill" style="font-size: 3rem;"></i>
                        <p>No posts saved yet.</p>
                    </div>  --}}
                @else
                    <div class="empty-state">
                        <i class="bi bi-bookmark-fill" style="font-size: 3rem;"></i>
                        <p>Save photos and videos you want to see again.</p>
                        <p>Only you can see what you've saved.</p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- TAGGED Tab Pane --}}
        <div class="tab-pane" id="tagged-tab-pane" role="tabpanel">
            {{--
                NOTE: You will need to pass an array of posts the user is tagged in to this view from your controller.
                For now, let's assume you have a variable `$taggedPosts` which contains the posts where the user is tagged.
            --}}
            <div class="posts-grid">
                 @if(isset($taggedPosts) && $taggedPosts->count() > 0)
                    {{-- Loop through $taggedPosts here, using the same structure as the posts-grid above --}}
                    {{-- For demonstration, I'll just put an empty state: --}}
                    <div class="empty-state">
                        <i class="bi bi-person-badge-fill" style="font-size: 3rem;"></i>
                        <p>No posts tagged in yet.</p>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-person-badge-fill" style="font-size: 3rem;"></i>
                        <p>Photos and videos you're tagged in will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/welcome.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ... (Existing Edit Profile and Report User modal toggle code) ...

        // Edit Profile modal toggle
        const openEditBtn = document.getElementById('openEditForm');
        const editModal = document.getElementById('editProfileModal');
        const closeEditModalBtn = document.getElementById('closeEditModal');
        if (openEditBtn && editModal && closeEditModalBtn) {
            openEditBtn.addEventListener('click', () => editModal.classList.add('show'));
            closeEditModalBtn.addEventListener('click', () => editModal.classList.remove('show'));
            window.addEventListener('click', (e) => {
                if (e.target === editModal) editModal.classList.remove('show');
            });
        }

        // Report User modal toggle
        const openReportBtn = document.getElementById('openReportModal');
        const reportModal = document.getElementById('reportModal');
        const closeReportModalBtn = document.getElementById('closeReportModal');
        if (openReportBtn && reportModal && closeReportModalBtn) {
            openReportBtn.addEventListener('click', (e) => {
                e.preventDefault();
                reportModal.classList.add('show');
            });
            closeReportModalBtn.addEventListener('click', () => reportModal.classList.remove('show'));
            window.addEventListener('click', (e) => {
                if (e.target === reportModal) reportModal.classList.remove('show');
            });
        }

        // Dynamic Other Reason textarea
        const reasonSelect = document.getElementById('reason');
        const customReasonGroup = document.getElementById('customReasonGroup');
        if (reasonSelect && customReasonGroup) {
            reasonSelect.addEventListener('change', () => {
                customReasonGroup.style.display = reasonSelect.value === 'other' ? 'block' : 'none';
            });
        }

        // **NEW: Tab Functionality**
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                const targetPaneId = targetTab + '-tab-pane';

                // Deactivate all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Activate the clicked button and its corresponding pane
                this.classList.add('active');
                document.getElementById(targetPaneId).classList.add('active');
                this.setAttribute('aria-selected', 'true');

                // Set other buttons to not selected
                tabButtons.forEach(btn => {
                    if (btn !== this) {
                        btn.setAttribute('aria-selected', 'false');
                    }
                });
            });
        });
    });

    // Maintain existing post modal close function
    function closePostModal(event) {
        if (event.target.classList.contains('post-modal-overlay') || event.target.classList.contains('close-button')) {
            const overlay = document.querySelector('.post-modal-overlay');
            if (overlay) {
                overlay.remove();
                document.body.style.overflow = 'auto';
            }
        }
    }
</script>
@endsection
