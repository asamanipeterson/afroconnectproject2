<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Afroconnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <aside class="sidebar">
        <div class="profile-box">
            @if(auth()->user()->cover_picture)
                <img src="{{ asset('storage/' . auth()->user()->cover_picture) }}" class="cover-picture" alt="Cover Picture">
            @else
                <div class="cover-placeholder">
                    <i class="bi bi-image"></i>
                    <span>Add Cover Picture</span>
                </div>
            @endif

            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-picture" alt="Profile Picture">
            @endif

            <a href="{{ route('user.profile', auth()->user()) }}"><p>@ {{ auth()->user()->username }}</p></a>

            @php
                $followersCount = auth()->user()->followers()->count();
                $followingCount = auth()->user()->following()->count();
                $postsCount = auth()->user()->posts->count();
            @endphp

            <div class="stats">
                <div><strong>{{ $followersCount }}</strong> <span>{{ $followersCount === 1 ? 'Follower' : 'Followers' }}</span></div>
                <div><strong>{{ $followingCount }}</strong> <span>{{ $followingCount <= 1 ? 'Following' : 'Followings' }}</span></div>
                <div><strong>{{ $postsCount }}</strong> <span>{{ $postsCount === 1 ? 'Post' : 'Posts' }}</span></div>
            </div>

            <a href="#" class="btn" id="openProfileModalSidebar">Edit Profile</a>
        </div>

        <div class="navigation">
            <a href="{{ route('welcome') }}" class="logo"><img src="{{ asset('afrlogo.png') }}" alt=""></a>
            <a href="{{ route('welcome') }}" class="nav-item active"><i class="bi bi-house-door"></i><span>Home</span></a>
            <a href="#" class="nav-item" id="openSearchModal"><i class="bi bi-search"></i><span>Search</span></a>
            <a href="#" class="nav-item"><i class="bi bi-handbag"></i><span>Market</span></a>
            <a href="#" class="nav-item"><i class="bi bi-camera-video"></i><span>Live</span></a>
            <a href="{{ route('notifications.index') }}" class="nav-item notification-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" id="notificationBell">
                <div class="icon-wrapper" style="position: relative;">
                    <i class="bi bi-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="notifications-badge">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </div>
                <span class="nav-label">Notifications</span>
            </a>
            <a href="#" class="nav-item"><i class="bi bi-chat"></i><span>Messages</span></a>
            <a href="#" class="nav-item" id="openStoryModalSidebarNav"><i class="bi bi-plus-circle"></i><span>Create Story</span></a>
            <a href="#" class="nav-item" id="openPostModalSidebarNav"><i class="bi bi-images"></i><span>Create Post</span></a>
            <div class="menu-bar">
                <a href="#" class="nav-item menu-trigger"><i class="bi bi-three-dots"></i><span>More</span></a>
                <div class="dropdown-menu sidebar-dropdown">
                    <div class="settingsdrop">
                        <ul class="main-drop">
                            <li class="main-li">
                                <a href="#" class="dropdown-items"><i class="bi bi-gear"></i> Settings</a>
                                <ul class="dropdown">
                                    <div class="drop-container">
                                        <li><a href="#" class="dropdown-item"><i class="bi bi-activity"></i> Your Activity</a></li>
                                        <li><a href="#" class="dropdown-item"><i class="bi bi-bookmark"></i> Saved</a></li>
                                        <li><a href="#" class="dropdown-item" id="toggleDarkMode"><i class="bi bi-brilliance"></i> Switch appearance</a></li>
                                        <li><a href="#" class="dropdown-item"><i class="bi bi-exclamation-circle"></i> Report a problem</a></li>
                                    </div>
                                </ul>
                            </li>
                            <li class="main-li"><a href="{{ route('logout') }}" class="dropdown-items"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <a href="{{ route('user.profile', auth()->user()) }}" class="nav-item profile-nav-link">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-nav-icon" alt="Profile Picture">
                @else
                    <i class="bi bi-person-circle profile-nav-icon"></i>
                @endif
                <span>Profile</span>
            </a>
        </div>

        <div class="footer-links">
            <a href="#">Privacy terms</a> <span>|</span> <a href="#">Advertising</a> <span>|</span> <a href="#">Cookies</a>
            <p>Platform © 1000</p>
        </div>
    </aside>

    <!-- Edit Profile Modal -->
    <div class="modal" id="profileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <button class="close-btn" id="closeProfileModal">×</button>
            </div>
            <form action="{{ route('update-profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-picture-preview" alt="Current Profile Picture">
                    @endif
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" placeholder="Tell us about yourself...">{{ auth()->user()->bio ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label for="cover_picture">Cover Picture</label>
                    @if(auth()->user()->cover_picture)
                        <img src="{{ asset('storage/' . auth()->user()->cover_picture) }}" class="cover-picture-preview" alt="Current Cover Picture">
                    @endif
                    <input type="file" id="cover_picture" name="cover_picture" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="url" id="website" name="website" placeholder="https://yourwebsite.com" value="{{ auth()->user()->website ?? '' }}">
                </div>
                <button type="submit" class="submit-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Search Modal -->
    <div class="modal search-modal" id="searchModal">
        <div class="search-modal-content">
            <div class="modal-header">
                <h2>Search</h2>
                <button class="close-btn" id="closeSearchModal">×</button>
            </div>
            <div class="search-container">
                <input type="text" class="search-modal-input" id="searchModalInput" placeholder="Search by username or location...">
                <div id="searchModalResults" class="search-modal-results"></div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all navigation items (using the consistent class name)
    const navItems = document.querySelectorAll('.nav-item');

    // Function to set active state based on current URL
    function setActiveNav() {
        const currentPath = window.location.pathname;

        navItems.forEach(item => {
            const itemPath = item.getAttribute('href');

            // Remove all active classes first
            item.classList.remove('active');

            // Check if this item's path matches current URL
            if (itemPath && currentPath.startsWith(itemPath)) {
                item.classList.add('active');
            }

            // Special case for home page
            if (currentPath === '/' && itemPath === "{{ route('welcome') }}") {
                item.classList.add('active');
            }
        });
    }

    // Set initial active state
    setActiveNav();

    // Add click handlers for visual feedback
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            // For modal links (#), update active state immediately
            if (this.getAttribute('href') === '#') {
                navItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            }
            // For real links, the page reload will handle active state
        });
    });
});
</script>
