<div class="topbar">
    <div class="logo-section">
        <a href="{{ route('welcome') }}" class="logo-link">
            <span>Afroconnect</span>
        </a>
    </div>
    <div class="search-section">
        <div class="search-container">
            <input type="text" class="search" placeholder="Search...">
        </div>
    </div>
    <div class="profile-section">
        <div class="icon-section">
            <div class="top-icons">
                <a href="{{ route('notifications.index') }}" class="notification-icon">
                    <i class="fa-regular fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count())
                        <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <a href="#"><i class="bi bi-bookmark-fill"></i></a>
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
                <a href="#" class="dropdown-item" id="openSettings"><i class="bi bi-gear"></i> Settings</a>
                <div class="hover-menu">
                    <a href="#" class="hover-item"><i class="bi bi-activity"></i> Your Activity</a>
                    <a href="#" class="hover-item"><i class="bi bi-bookmark"></i> Saved</a>
                    <a href="#" class="hover-item" id="switchAppearance"><i class="bi bi-brilliance"></i>Switch appearance</a>
                    <a href="#" class="hover-item"><i class="bi bi-exclamation-circle"></i> Report a problem</a>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profileLink = document.getElementById('toggleDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (profileLink && dropdownMenu) {
            profileLink.addEventListener('click', e => {
                e.preventDefault();
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
                profileLink.classList.toggle('active', !isVisible);
            });

            document.addEventListener('click', e => {
                if (!profileLink.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.style.display = 'none';
                    profileLink.classList.remove('active');
                }
            });
        }
    });
</script>
