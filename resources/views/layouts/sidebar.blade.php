@section('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('css/main_content.css') }}"> --}}
<aside class="sidebar">

    {{-- ---------------------------------------------------------------- --}}
    {{-- START: Profile Box - Entirely dependent on authenticated user --}}
    @auth
    <div class="profile-box">
        {{-- Cover Picture --}}
        @if(auth()->user()->cover_picture)
            <img src="{{ asset('storage/' . auth()->user()->cover_picture) }}" class="cover-picture" alt="Cover Picture">
        @else
            <div class="cover-placeholder">
                <i class="bi bi-image"></i>
                <span>Add Cover Picture</span>
            </div>
        @endif

        {{-- Profile Picture --}}
        @if(auth()->user()->profile_picture)
            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-picture" alt="Profile Picture">
        @else
            <img src="{{ asset('default-avatar.png') }}" class="profile-picture">
        @endif

        {{-- Username and Link --}}
        <a href="{{ route('user.profile', auth()->user()) }}"><p>@ {{ auth()->user()->username }}</p></a>

        @php
            $followersCount = auth()->user()->followers()->count();
            $followingCount = auth()->user()->following()->count();
            $postsCount = auth()->user()->posts->count();
        @endphp

        {{-- Stats --}}
        <div class="stats">
            <div><strong>{{ $followersCount }}</strong> <span>{{ $followersCount === 1 ? 'Follower' : 'Followers' }}</span></div>
            <div><strong>{{ $followingCount }}</strong> <span>{{ $followingCount <= 1 ? 'Following' : 'Followings' }}</span></div>
            <div><strong>{{ $postsCount }}</strong> <span>{{ $postsCount === 1 ? 'Post' : 'Posts' }}</span></div>
        </div>

        <a href="#" class="btn" id="openProfileModalSidebar">Edit Profile</a>
    </div>
    @endauth
    {{-- END: Profile Box --}}
    {{-- ---------------------------------------------------------------- --}}


    <div class="navigation">
    <a href="{{ route('welcome') }}" class="logo"><img src="{{ asset('2projlogo.png') }}" alt=""></a>
    <a href="{{ route('welcome') }}" class="nav-item {{ request()->routeIs('welcome') ? 'active' : '' }}">
        <i class="bi {{ request()->routeIs('welcome') ? 'bi-house-door-fill' : 'bi-house-door' }}"></i><span>Home</span>
    </a>
    <a href="" class="nav-item {{ request()->routeIs('search') ? 'active' : '' }}" id="openSearchModal">
        <i class="bi {{ request()->routeIs('search') ? 'bi-search-fill' : 'bi-search' }}"></i><span>Search</span>
    </a>
    <a href="{{ route('marketshowroom') }}" class="nav-item {{ request()->routeIs('marketshowroom') ? 'active' : '' }}">
        <i class="bi {{ request()->routeIs('marketshowroom') ? 'bi-handbag-fill' : 'bi-handbag' }}"></i><span>Market</span>
    </a>
    <a href="{{ route('live') }}" class="nav-item {{ request()->routeIs('live') ? 'active' : '' }}">
                <i class="bi {{ request()->routeIs('live') ? 'bi-camera-video-fill' : 'bi-camera-video' }}"></i><span>Live</span>
        </a>

    {{-- ---------------------------------------------------------------- --}}
    {{-- START: Navigation Items dependent on authenticated user --}}
    @auth
        <a href="{{ route('notifications.index') }}" class="nav-item notification-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" id="notificationBell" style="font-size: 10px">
            <div class="icon-wrapper" style="position: relative;">
                <i class="bi {{ request()->routeIs('notifications.index') ? 'bi-bell-fill' : 'bi-bell' }}"></i>
                {{-- Notification Count (Problematic line if not guarded) --}}
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="notifications-badge">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </div>
            <span class="nav-label">Notifications</span>
        </a>
        <a href="{{ route('conversations.index') }}" class="nav-item {{ request()->routeIs(['conversations.index', 'conversations.show']) ? 'active' : '' }}">
            <div class="icon-wrapper" style="position: relative;">
                <i class="bi {{ request()->routeIs(['conversations.index', 'conversations.show']) ? 'bi-chat-fill' : 'bi-chat' }}"></i>
                {{-- Check for unread messages (assuming $unreadMessageCount is safely passed or defaults to 0) --}}
                @if(isset($unreadMessageCount) && $unreadMessageCount > 0)
                    <span class="notifications-badge">
                        {{ $unreadMessageCount }}
                    </span>
                @endif
            </div>
            <span>Messages</span>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('stories.create') ? 'active' : '' }}" id="openStoryModalSidebarNav">
            <i class="bi {{ request()->routeIs('stories.create') ? 'bi-plus-circle-fill' : 'bi-plus-circle' }}"></i><span>Create Story</span>
        </a>
        <a href="" class="nav-item {{ request()->routeIs('posts.create') ? 'active' : '' }}" id="openPostModalSidebarNav">
            <i class="bi {{ request()->routeIs('posts.create') ? 'bi-images' : 'bi-images' }}"></i><span>Create Post</span>
        </a>
        <div class="menu-bar">
            <a href="#" class="nav-items menu-trigger {{ request()->routeIs(['settings', 'logout']) ? 'active' : '' }}">
                <i class="bi {{ request()->routeIs(['settings', 'logout']) ? 'bi-list' : 'bi-list' }}"></i><span>More</span>
            </a>
            <div class="dropdown-menu sidebar-dropdown">
                <div class="settingsdrop">
                    <ul class="main-drop">
                        <li class="main-li">
                            <a href="" class="dropdown-items {{ request()->routeIs('settings') ? 'active' : '' }}" id="openSettingsModal">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-settings text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject" style="cursor: pointer">Settings</p>
                                </div>
                            </a>
                        </li>
                        <li class="main-li">
                            <a href="{{ route('logout') }}" class="dropdown-items">
                                <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-logout text-danger"></i>
                                        </div>
                                </div>
                                <div class="preview-item-content">
                                        <p class="preview-subject">Log out</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if (request()->routeIs(['marketplace.newlisting', 'marketshowroom']))
            <a href="" class="nav-item {{ request()->routeIs('marketplace.categories') ? 'active' : '' }}" id="openCategoryModal">
                <i class="bi {{ request()->routeIs('marketplace.categories') ? 'bi-grid-fill' : 'bi-grid' }}"></i><span>Categories</span>
            </a>
        @endif
        {{-- Profile Link at the bottom (Problematic line if not guarded) --}}
        <a href="{{ route('user.profile', auth()->user()) }}" class="nav-item profile-nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-nav-icon" alt="Profile Picture">
            @else
                <i class="bi {{ request()->routeIs('user.profile') ? 'bi-person-fill' : 'bi-person' }} profile-nav-icon"></i>
            @endif
            <span>Profile</span>
        </a>
    @endauth
    {{-- END: Navigation Items dependent on authenticated user --}}
    {{-- ---------------------------------------------------------------- --}}

    {{-- You could add a Log In / Register link here for guests using @guest --}}

</div>
</aside>

{{-- ---------------------------------------------------------------- --}}
{{-- START: Profile Modal - Must be wrapped as it contains auth()->user() access --}}
@auth
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
@endauth
{{-- END: Profile Modal --}}
{{-- ---------------------------------------------------------------- --}}


{{-- Other Modals (Category, Search, Settings) that do NOT rely on auth()->user() can remain outside @auth --}}

<div class="modals category-modal" id="categoryModal">
    <div class="category-modal-content">
        <div class="modals-header">
            <h2>Categories</h2>
            <button class="close-btn" id="closeCategoryModal">×</button>
        </div>
        <div class="category-container">
            <ul class="category-list">
                <li><a href="{{ route('marketshowroom', ['category' => 'phones']) }}">Phones</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'vehicles']) }}">Vehicles</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'clothing']) }}">Clothing</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'games']) }}">Games</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'electronics']) }}">Electronics</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'african-crafts']) }}">African Crafts</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'african-textiles']) }}">African Textiles</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'jewelry']) }}">Jewelry</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'home-decor']) }}">Home Decor</a></li>
                <li><a href="{{ route('marketshowroom', ['category' => 'furniture']) }}">Furniture</a></li>
                <div class="hidden-categories" id="hiddenCategoryItems">
                    <li><a href="{{ route('marketshowroom', ['category' => 'books']) }}">Books</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'sports']) }}">Sports Equipment</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'beauty']) }}">Beauty Products</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'toys']) }}">Toys</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'appliances']) }}">Appliances</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'music']) }}">Musical Instruments</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'art']) }}">Art & Collectibles</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'pets']) }}">Pet Supplies</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'food']) }}">Food & Beverages</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'african-spices']) }}">African Spices</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'tools']) }}">Tools & Hardware</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'baby']) }}">Baby Products</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'health']) }}">Health & Wellness</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'garden']) }}">Garden & Outdoor</a></li>
                    <li><a href="{{ route('marketshowroom', ['category' => 'african-beadwork']) }}">African Beadwork</a></li>
                </div>
            </ul>
            <div class="more-button" id="moreCategoryButton">
                <span id="moreCategoryButtonText">More</span> <i class="bi bi-caret-down-fill"></i>
            </div>
        </div>
    </div>
</div>
<div class="modal settings-modal" id="settingsModal">
    <div class="settings-modal-content">
        <button class="close-btn" id="closeSettingsModal">×</button>
        <ul>
            <li><a href="#" class="dropdown-item"><i class="bi bi-activity"></i> Your Activity</a></li>
            <li><a href="#" class="dropdown-item"><i class="bi bi-bookmark"></i> Saved</a></li>
            <li><a href="#" class="dropdown-item" id="toggleDarkMode"><svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16" data-view-component="true" class="octicon octicon-paintbrush">
    <path d="M11.134 1.535c.7-.509 1.416-.942 2.076-1.155.649-.21 1.463-.267 2.069.34.603.601.568 1.411.368 2.07-.202.668-.624 1.39-1.125 2.096-1.011 1.424-2.496 2.987-3.775 4.249-1.098 1.084-2.132 1.839-3.04 2.3a3.744 3.744 0 0 1-1.055 3.217c-.431.431-1.065.691-1.657.861-.614.177-1.294.287-1.914.357A21.151 21.151 0 0 1 .797 16H.743l.007-.75H.749L.742 16a.75.75 0 0 1-.743-.742l.743-.008-.742.007v-.054a21.25 21.25 0 0 1 .13-2.284c.067-.647.187-1.287.358-1.914.17-.591.43-1.226.86-1.657a3.746 3.746 0 0 1 3.227-1.054c.466-.893 1.225-1.907 2.314-2.982 1.271-1.255 2.833-2.75 4.245-3.777ZM1.62 13.089c-.051.464-.086.929-.104 1.395.466-.018.932-.053 1.396-.104a10.511 10.511 0 0 0 1.668-.309c.526-.151.856-.325 1.011-.48a2.25 2.25 0 1 0-3.182-3.182c-.155.155-.329.485-.48 1.01a10.515 10.515 0 0 0-.309 1.67Zm10.396-10.34c-1.224.89-2.605 2.189-3.822 3.384l1.718 1.718c1.21-1.205 2.51-2.597 3.387-3.833.47-.662.78-1.227.912-1.662.134-.444.032-.551.009-.575h-.001V1.78c-.014-.014-.113-.113-.548.027-.432.14-.995.462-1.655.942Zm-4.832 7.266-.001.001a9.859 9.859 0 0 0 1.63-1.142L7.155 7.216a9.7 9.7 0 0 0-1.161 1.607c.482.302.889.71 1.19 1.192Z"></path>
</svg> Switch appearance</a></li>
            <li><a href="#" class="dropdown-item"><i class="bi bi-exclamation-circle"></i> Report a problem</a></li>
        </ul>
    </div>
</div>

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

<script src="{{ asset('js/sidebar.js') }}"></script>
<script>
function createConversation(event, url) {
    event.preventDefault(); // Prevent default link behavior
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            window.location.href = response.url; // Redirect to the response URL
        } else {
            console.error('Error:', response.status);
            if (response.status === 403) {
                alert('Access forbidden. Please check your permissions.');
            }
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
