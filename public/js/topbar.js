document.addEventListener('DOMContentLoaded', () => {
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
            });
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


