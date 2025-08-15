@if(!Request::is('marketplace') && !Request::is('marketplace/*'))
<div class="topbar">
    <div class="logo-section">
        <a href="{{ route('welcome') }}" class="logo-link">
            <img src="{{ asset('2projlogo.png') }}" alt="" style="width: 80px; height:70px;">
        </a>
    </div>
    <div class="search-section">
        <div class="search-container">
            <input type="text" class="search" id="searchInput" placeholder="Search by username or location...">
            <div id="searchResults" class="search-results" style="display: none;"></div>
        </div>
    </div>
    <div class="profile-section">
        <div class="icon-section">
            <div class="top-icons">
                <a href="{{ route('notifications.index') }}" class="notification-icon">
                    <i class="bi bi-bell"></i>
                    @if(auth()->user()->unreadNotifications->count())
                        <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <a href="#"><i class="bi bi-bookmark"></i></a>
            </div>
        </div>
        <a href="#" class="profile-link" id="toggleDropdown">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Profile Picture">
            @else
                <i class="bi bi-person-circle avatar"></i>
            @endif
            <i class="bi bi-caret-down-fill"></i>
        </a>
        <span class="username">{{ auth()->user()->username }}</span>
        <div class="dropdown-menu" id="dropdownMenu">
            <div class="settings-hover">
                {{-- <a href="#" class="dropdown-item" id="openSettings"><i class="bi bi-gear"></i> Settings</a> --}}
                <a class="dropdown-item preview-item" id="openSettings" >
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-settings text-success"></i>
                      </div>
                    </div>
                    <div class="preview-item-content">
                      <p class="preview-subject" style="cursor: pointer">Settings</p>
                    </div>
                  </a>
                <div class="hover-menu">
                    <a href="#" class="hover-item"><i class="bi bi-activity"></i> Your Activity</a>
                    <a href="#" class="hover-item"><i class="bi bi-bookmark"></i> Saved</a>
                    <a href="#" class="hover-item" id="switchAppearance"><i class="bi bi-brilliance"></i>Switch appearance</a>
                    <a href="#" class="hover-item"><i class="bi bi-exclamation-circle"></i> Report a problem</a>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="dropdown-item preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-logout text-danger"></i>
                      </div>
                    </div>
                    <div class="preview-item-content">
                      <p class="preview-subject">Log out</p>
                    </div>
                  </a>
        </div>
    </div>
</div>
@endif
<script src="{{ asset('js/topbar.js') }}"></script>
