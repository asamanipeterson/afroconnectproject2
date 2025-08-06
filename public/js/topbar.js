document.addEventListener('DOMContentLoaded', () => {
        // Toggle Modal Function
        function toggleModal(modalElement, show) {
            if (!modalElement) return;
            if (show) {
                modalElement.style.display = 'flex';
                setTimeout(() => {
                    modalElement.classList.add('show');
                    document.body.classList.add('modal-open');
                }, 10);
            } else {
                modalElement.classList.remove('show');
                modalElement.addEventListener('transitionend', function handler() {
                    modalElement.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    modalElement.removeEventListener('transitionend', handler);
                });
            }
        }

        // Search Functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        let debounceTimeout;

        if (searchInput && searchResults) {
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(async () => {
                    const query = searchInput.value.trim();
                    if (query.length < 2) {
                        searchResults.style.display = 'none';
                        searchResults.innerHTML = '';
                        return;
                    }

                    try {
                        searchResults.innerHTML = '<div class="search-result-item">Loading...</div>';
                        const response = await fetch(`/search-users?q=${encodeURIComponent(query)}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await response.json();

                        console.log('Search response:', data); // Debug: Log the full response

                        searchResults.innerHTML = '';
                        if (data.users && data.users.length > 0) {
                            data.users.forEach(user => {
                                console.log('User profile_picture_url:', user.profile_picture_url, 'for user:', user.username); // Debug: Log profile_picture_url
                                const resultItem = document.createElement('div');
                                resultItem.className = 'search-result-item';
                                const avatarUrl = user.profile_picture_url || '/default-avatar.png';
                                const avatarHtml = `<img src="${avatarUrl}" alt="${user.username}'s profile picture" class="avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;" onload="console.log('Image loaded for ${user.username}: ${avatarUrl}')" onerror="console.log('Image failed to load for ${user.username}: ${avatarUrl}'); this.outerHTML='<i class=\\'bi bi-person-circle avatar\\' style=\\'font-size: 40px; margin-right: 10px;\\'></i>'">`;
                                resultItem.innerHTML = `
                                    <a href="/user/${user.id}">
                                        ${avatarHtml}
                                        <span>${user.username}</span>
                                    </a>
                                `;
                                searchResults.appendChild(resultItem);
                            });
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.innerHTML = '<div class="search-result-item">No users found</div>';
                            searchResults.style.display = 'block';
                        }
                    } catch (error) {
                        console.error('Error fetching search results:', error);
                        searchResults.innerHTML = '<div class="search-result-item">Error loading results</div>';
                        searchResults.style.display = 'block';
                    }
                }, 300);
            });

            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });
        }

        // Profile Dropdown
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

        // Sidebar Main Menu Dropdown
        const menuTrigger = document.querySelector('.menu-trigger');
        const sidebarDropdown = menuTrigger ? menuTrigger.nextElementSibling : null;

        if (menuTrigger && sidebarDropdown) {
            menuTrigger.addEventListener('click', (e) => {
                e.preventDefault();
                sidebarDropdown.style.display = sidebarDropdown.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', (e) => {
                if (!menuTrigger.contains(e.target) && !sidebarDropdown.contains(e.target)) {
                    sidebarDropdown.style.display = 'none';
                }
            });
        }

        // Settings Hover Menu
        const settingsHover = document.querySelector('.settings-hover');
        const hoverMenu = document.querySelector('.hover-menu');
        const openSettings = document.getElementById('openSettings');

        if (settingsHover && hoverMenu && openSettings) {
            let hoverTimeout;
            settingsHover.addEventListener('mouseenter', () => {
                clearTimeout(hoverTimeout);
                hoverMenu.style.display = 'block';
            });

            settingsHover.addEventListener('mouseleave', () => {
                hoverTimeout = setTimeout(() => {
                    if (!hoverMenu.matches(':hover')) {
                        hoverMenu.style.display = 'none';
                    }
                }, 300);
            });

            hoverMenu.addEventListener('mouseenter', () => {
                clearTimeout(hoverTimeout);
                hoverMenu.style.display = 'block';
            });

            hoverMenu.addEventListener('mouseleave', () => {
                hoverTimeout = setTimeout(() => {
                    hoverMenu.style.display = 'none';
                }, 300);
            });

            openSettings.addEventListener('click', (e) => {
                e.preventDefault();
                toggleModal(document.getElementById('settingsModal'), true);
                hoverMenu.style.display = 'none';
            });
        }

        // Profile Modal
        const profileModal = document.getElementById('profileModal');
        const openProfileModalSidebar = document.getElementById('openProfileModalSidebar');
        const closeProfileModalButton = document.getElementById('closeProfileModal');

        if (profileModal && openProfileModalSidebar && closeProfileModalButton) {
            openProfileModalSidebar.addEventListener('click', (e) => {
                e.preventDefault();
                toggleModal(profileModal, true);
            });

            closeProfileModalButton.addEventListener('click', () => {
                toggleModal(profileModal, false);
            });

            profileModal.addEventListener('click', (e) => {
                if (e.target === profileModal) {
                    toggleModal(profileModal, false);
                }
            });
        }

        // Settings Modal
        const settingsModal = document.getElementById('settingsModal');
        const closeSettingsModalButton = document.getElementById('closeSettingsModal');

        if (settingsModal && closeSettingsModalButton) {
            closeSettingsModalButton.addEventListener('click', () => {
                toggleModal(settingsModal, false);
            });

            settingsModal.addEventListener('click', (e) => {
                if (e.target === settingsModal) {
                    toggleModal(settingsModal, false);
                }
            });
        }

        // Story Creation Modal
        const storyCreationModal = document.getElementById('storyCreationModal');
        const openStoryModalSidebarNav = document.getElementById('openStoryModalSidebarNav');

        if (storyCreationModal && openStoryModalSidebarNav) {
            openStoryModalSidebarNav.addEventListener('click', (e) => {
                e.preventDefault();
                toggleModal(storyCreationModal, true);
                if (sidebarDropdown) sidebarDropdown.style.display = 'none';
                if (hoverMenu) hoverMenu.style.display = 'none';
            });
        }

        // Post Creation Modal
        const postCreationModal = document.getElementById('postCreationModal');
        const openPostModalSidebarNav = document.getElementById('openPostModalSidebarNav');

        if (postCreationModal && openPostModalSidebarNav) {
            openPostModalSidebarNav.addEventListener('click', (e) => {
                e.preventDefault();
                toggleModal(postCreationModal, true);
                if (sidebarDropdown) sidebarDropdown.style.display = 'none';
                if (hoverMenu) hoverMenu.style.display = 'none';
            });
        }

        // Dark/Light Mode Switch
        const switchAppearanceButton = document.getElementById('switchAppearance');

        if (switchAppearanceButton) {
            switchAppearanceButton.addEventListener('click', (e) => {
                e.preventDefault();
                document.body.classList.toggle('dark-mode');
                localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
                if (hoverMenu) hoverMenu.style.display = 'none';
                if (sidebarDropdown) sidebarDropdown.style.display = 'none';
            });
        }

        // Apply theme on page load
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    });
