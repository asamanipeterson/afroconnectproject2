document.addEventListener('DOMContentLoaded', () => {
    console.log('Script loaded');

    // --- Debug Modal Elements ---
    const settingsModal = document.getElementById('settingsModal');
    const openSettingsModal = document.getElementById('openSettingsModal');
    const closeSettingsModal = document.getElementById('closeSettingsModal');
    const sidebarDropdown = document.querySelector('.sidebar-dropdown');
    const menuTrigger = document.querySelector('.menu-trigger');

    console.log('Settings Modal Elements:', {
        settingsModal: !!settingsModal,
        openSettingsModal: !!openSettingsModal,
        closeSettingsModal: !!closeSettingsModal,
        sidebarDropdown: !!sidebarDropdown,
        menuTrigger: !!menuTrigger
    });

    // --- Settings Modal Handling ---
    if (openSettingsModal && settingsModal && closeSettingsModal) {
        openSettingsModal.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('Opening settings modal');
            settingsModal.style.display = 'flex';
            settingsModal.classList.add('show');
            if (sidebarDropdown) {
                sidebarDropdown.classList.remove('show');
                console.log('Closed sidebar dropdown');
            }
        });

        closeSettingsModal.addEventListener('click', () => {
            console.log('Closing settings modal');
            settingsModal.classList.remove('show');
            settingsModal.addEventListener('transitionend', function handler() {
                settingsModal.style.display = 'none';
                settingsModal.removeEventListener('transitionend', handler);
            });
        });

        settingsModal.addEventListener('click', (e) => {
            if (e.target === settingsModal) {
                console.log('Closing settings modal via backdrop click');
                settingsModal.classList.remove('show');
                settingsModal.addEventListener('transitionend', function handler() {
                    settingsModal.style.display = 'none';
                    settingsModal.removeEventListener('transitionend', handler);
                });
            }
        });
    } else {
        console.error('Settings modal elements not found:', {
            settingsModal,
            openSettingsModal,
            closeSettingsModal
        });
    }

    // --- Sidebar Dropdown ---
    if (menuTrigger && sidebarDropdown) {
        console.log('Menu trigger and sidebar dropdown found');
        menuTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            sidebarDropdown.classList.toggle('show');
            console.log('Sidebar dropdown toggled:', sidebarDropdown.classList.contains('show'));
        });

        document.addEventListener('click', (e) => {
            if (!menuTrigger.contains(e.target) && !sidebarDropdown.contains(e.target)) {
                sidebarDropdown.classList.remove('show');
                console.log('Sidebar dropdown closed');
            }
        });
    } else {
        console.error('Menu trigger or sidebar dropdown not found:', { menuTrigger, sidebarDropdown });
    }

    // --- Dark Mode Toggle ---
    const toggleDarkMode = document.getElementById('toggleDarkMode');
    if (toggleDarkMode) {
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }

        toggleDarkMode.addEventListener('click', (e) => {
            e.preventDefault();
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
            console.log('Dark mode toggled:', document.body.classList.contains('dark-mode'));
        });
    }

    // --- Profile Modal ---
    const profileModal = document.getElementById('profileModal');
    const openProfileModalSidebar = document.getElementById('openProfileModalSidebar');
    const closeProfileModalButton = document.getElementById('closeProfileModal');

    if (profileModal && openProfileModalSidebar && closeProfileModalButton) {
        openProfileModalSidebar.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('Opening profile modal');
            profileModal.style.display = 'flex';
            setTimeout(() => profileModal.classList.add('show'), 10);
        });

        closeProfileModalButton.addEventListener('click', () => {
            console.log('Closing profile modal');
            profileModal.classList.remove('show');
            profileModal.addEventListener('transitionend', function handler() {
                profileModal.style.display = 'none';
                profileModal.removeEventListener('transitionend', handler);
            });
        });

        profileModal.addEventListener('click', (e) => {
            if (e.target === profileModal) {
                console.log('Closing profile modal via backdrop click');
                profileModal.classList.remove('show');
                profileModal.addEventListener('transitionend', function handler() {
                    profileModal.style.display = 'none';
                    profileModal.removeEventListener('transitionend', handler);
                });
            }
        });
    }

    // --- Search Modal ---
    function toggleModalSearch(modalElement, show) {
        if (!modalElement) return;
        if (show) {
            modalElement.style.display = 'block';
            setTimeout(() => {
                modalElement.classList.add('show');
                document.body.classList.add('modal-open');
                const input = modalElement.querySelector('#searchModalInput');
                if (input) input.focus();
                console.log('Opening search modal');
            }, 10);
            if (sidebarDropdown) sidebarDropdown.classList.remove('show');
        } else {
            modalElement.classList.remove('show');
            modalElement.addEventListener('transitionend', function handler() {
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                console.log('Closing search modal');
                modalElement.removeEventListener('transitionend', handler);
            });
        }
    }

    const openSearchModal = document.getElementById('openSearchModal');
    const searchModal = document.getElementById('searchModal');
    const closeSearchModal = document.getElementById('closeSearchModal');
    const searchModalInput = document.getElementById('searchModalInput');
    const searchModalResults = document.getElementById('searchModalResults');
    let debounceTimeout;

    if (openSearchModal && searchModal && closeSearchModal) {
        openSearchModal.addEventListener('click', (e) => {
            e.preventDefault();
            toggleModalSearch(searchModal, true);
        });

        closeSearchModal.addEventListener('click', () => {
            toggleModalSearch(searchModal, false);
        });

        searchModal.addEventListener('click', (e) => {
            if (e.target === searchModal) {
                toggleModalSearch(searchModal, false);
            }
        });
    }

    if (searchModalInput && searchModalResults) {
        searchModalInput.addEventListener('input', () => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(async () => {
                const query = searchModalInput.value.trim();
                if (query.length < 2) {
                    searchModalResults.style.display = 'none';
                    searchModalResults.innerHTML = '';
                    return;
                }

                try {
                    searchModalResults.innerHTML = '<div class="search-modal-result-item">Loading...</div>';
                    const response = await fetch(`/search-users?q=${encodeURIComponent(query)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await response.json();

                    console.log('Search modal response:', data);

                    searchModalResults.innerHTML = '';
                    if (data.users && data.users.length > 0) {
                        data.users.forEach(user => {
                            console.log('User profile_picture_url:', user.profile_picture_url, 'for user:', user.username);
                            const resultItem = document.createElement('div');
                            resultItem.className = 'search-modal-result-item';
                            const avatarUrl = user.profile_picture_url || '/default-avatar.png';
                            const avatarHtml = `<img src="${avatarUrl}" alt="${user.username}'s profile picture" class="avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;" onload="console.log('Image loaded for ${user.username}: ${avatarUrl}')" onerror="console.log('Image failed to load for ${user.username}: ${avatarUrl}'); this.outerHTML='<i class=\\'bi bi-person-circle avatar\\' style=\\'font-size: 40px; margin-right: 10px;\\'></i>'">`;
                            resultItem.innerHTML = `
                                <a href="/user/${user.id}">
                                    ${avatarHtml}
                                    <span>${user.username}</span>
                                </a>
                            `;
                            searchModalResults.appendChild(resultItem);
                        });
                        searchModalResults.style.display = 'block';
                    } else {
                        searchModalResults.innerHTML = '<div class="search-modal-result-item">No users found</div>';
                        searchModalResults.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error fetching search modal results:', error);
                    searchModalResults.innerHTML = '<div class="search-modal-result-item">Error loading results</div>';
                    searchModalResults.style.display = 'block';
                }
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!searchModalInput.contains(e.target) && !searchModalResults.contains(e.target) && !openSearchModal.contains(e.target)) {
                toggleModalSearch(searchModal, false);
            }
        });
    }



    // --- Category Modal ---
function toggleModalCategory(modalElement, show) {
    if (!modalElement) return;
    if (show) {
        modalElement.style.display = 'block';
        setTimeout(() => {
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
            console.log('Opening category modal');
        }, 10);
        if (sidebarDropdown) sidebarDropdown.classList.remove('show');
    } else {
        modalElement.classList.remove('show');
        modalElement.addEventListener('transitionend', function handler() {
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
            console.log('Closing category modal');
            modalElement.removeEventListener('transitionend', handler);
        });
    }
}

const openCategoryModal = document.getElementById('openCategoryModal');
const categoryModal = document.getElementById('categoryModal');
const closeCategoryModal = document.getElementById('closeCategoryModal');
const moreCategoryButton = document.getElementById('moreCategoryButton');
const hiddenCategoryItems = document.getElementById('hiddenCategoryItems');
const moreCategoryButtonText = document.getElementById('moreCategoryButtonText');

if (openCategoryModal && categoryModal && closeCategoryModal) {
    openCategoryModal.addEventListener('click', (e) => {
        e.preventDefault();
        toggleModalCategory(categoryModal, true);
    });

    closeCategoryModal.addEventListener('click', () => {
        toggleModalCategory(categoryModal, false);
    });

    categoryModal.addEventListener('click', (e) => {
        if (e.target === categoryModal) {
            toggleModalCategory(categoryModal, false);
        }
    });
}

if (moreCategoryButton && hiddenCategoryItems && moreCategoryButtonText) {
    moreCategoryButton.addEventListener('click', () => {
        hiddenCategoryItems.classList.toggle('show');
        moreCategoryButton.classList.toggle('active');
        moreCategoryButtonText.textContent = hiddenCategoryItems.classList.contains('show') ? 'View Less' : 'More';
        console.log('Category toggle:', hiddenCategoryItems.classList.contains('show') ? 'Showing' : 'Hiding');
    });
}
});
