@extends('layouts.app')

@section('title', $user->username . ' Profile')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
<div class="profile-container">
    {{-- Profile Header --}}
    <div class="profile-header">
        <div class="profile-image {{ $user->stories->count() > 0 ? 'has-stories' : '' }} "> {{-- Added has-stories class --}}
            @if ($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="avatar">
            @else
                <div class="default-avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
            @endif
        </div>
        <div class="profile-info">
            <div class="top-row">
                <h2>{{ $user->username }}</h2>

                {{-- Edit or Follow/Unfollow --}}
                @if (auth()->id() === $user->id)
                    <button id="openEditForm" class="editbtn">Edit Profile</button>
                @elseif (auth()->check())
                    <button class="follow-btn {{ auth()->user()->isFollowing($user) ? 'following' : '' }}"
                        data-user-id="{{ $user->id }}">
                        {{ auth()->user()->isFollowing($user) ? 'Following' : 'Follow' }}
                    </button>
                    <button class="message-btn">Message</button>
                @endif
            </div>
            @php
                $followersCount = $user->followers()->count();
                $followingCount = $user->following()->count();
                $postsCount = $user->posts->count();
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

    {{-- Edit Profile Modal --}}
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

    {{-- Posts Grid --}}
    <div class="posts-grid">
        @foreach($groupedPosts as $groupId => $group)
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
                        <div class="stack-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg" class="stroke-[2] size-4"><rect x="3" y="8" width="13" height="13" rx="4" stroke="white"></rect><path fill-rule="white" clip-rule="evenodd" d="M13 2.00004L12.8842 2.00002C12.0666 1.99982 11.5094 1.99968 11.0246 2.09611C9.92585 2.31466 8.95982 2.88816 8.25008 3.69274C7.90896 4.07944 7.62676 4.51983 7.41722 5.00004H9.76392C10.189 4.52493 10.7628 4.18736 11.4147 4.05768C11.6802 4.00488 12.0228 4.00004 13 4.00004H14.6C15.7366 4.00004 16.5289 4.00081 17.1458 4.05121C17.7509 4.10066 18.0986 4.19283 18.362 4.32702C18.9265 4.61464 19.3854 5.07358 19.673 5.63807C19.8072 5.90142 19.8994 6.24911 19.9488 6.85428C19.9992 7.47112 20 8.26343 20 9.40004V11C20 11.9773 19.9952 12.3199 19.9424 12.5853C19.8127 13.2373 19.4748 13.8114 19 14.2361V16.5829C20.4795 15.9374 21.5804 14.602 21.9039 12.9755C22.0004 12.4907 22.0002 11.9334 22 11.1158L22 11V9.40004V9.35725C22 8.27346 22 7.3993 21.9422 6.69141C21.8826 5.96256 21.7568 5.32238 21.455 4.73008C20.9757 3.78927 20.2108 3.02437 19.27 2.545C18.6777 2.24322 18.0375 2.1174 17.3086 2.05785C16.6007 2.00002 15.7266 2.00003 14.6428 2.00004L14.6 2.00004H13Z" fill="white"></path></svg>
                        </div>
                    @endif

                    <div class="hover-overlay">
                        <div class="post-stats-overlay">
                            <span class="stat-item"><i class="bi bi-heart-fill"></i>{{ $likesCount }}</span>
                            <span class="stat-item"> <i class="fa-regular fa-comment"></i> {{ $commentsCount }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/welcome.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Edit Profile modal toggle
        const openEditBtn = document.getElementById('openEditForm');
        const modal = document.getElementById('editProfileModal');
        const closeModalBtn = document.getElementById('closeEditModal');

        if (openEditBtn && modal && closeModalBtn) {
            openEditBtn.addEventListener('click', () => modal.classList.add('show'));
            closeModalBtn.addEventListener('click', () => modal.classList.remove('show'));
            window.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.remove('show');
            });
        }
    });


    function closePostModal(event) {
        if (event.target.classList.contains('post-modal-overlay') || event.target.classList.contains('close-button')) {
            const overlay = document.querySelector('.post-modal-overlay');
            if (overlay) {
                overlay.remove();
                document.body.style.overflow = 'auto';
            }
        }
    }
    function closePostModal(event) {
        if (event.target.classList.contains('post-modal-overlay') || event.target.classList.contains('close-button')) {
            document.querySelector('.post-modal-overlay')?.remove();
            document.body.style.overflow = '';
        }
    }
</script>
@endsection
