document.addEventListener('DOMContentLoaded', () => {


    const toggle = document.getElementById('toggleDarkMode');
        // Check stored mode in localStorage
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            document.body.classList.toggle('dark-mode');

            // Save preference
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.removeItem('theme');
            }
        });
    const settingsLi = document.querySelector('.settingsdrop > ul > li');
            const settingsDropdown = settingsLi ? settingsLi.querySelector('.dropdown') : null;

            if (settingsLi && settingsDropdown) {
                settingsLi.addEventListener('click', (event) => {
                    event.preventDefault();
                    settingsDropdown.style.display = settingsDropdown.style.display === 'block' ? 'none' : 'block';
                    settingsDropdown.style.position = 'absolute';
                    settingsDropdown.style.left = '100%';
                    settingsDropdown.style.top = '100%';
                });

                document.addEventListener('click', (event) => {
                    if (!settingsLi.contains(event.target) && !settingsDropdown.contains(event.target)) {
                        settingsDropdown.style.display = 'none';
                    }
                });
            }

            const menuTrigger = document.querySelector('.menu-trigger');
            const sidebarDropdown = document.querySelector('.dropdown-menu.sidebar-dropdown');

            if (menuTrigger && sidebarDropdown) {
                menuTrigger.addEventListener('click', (event) => {
                    event.preventDefault();
                    sidebarDropdown.classList.toggle('show');
                    if (!sidebarDropdown.classList.contains('show') && settingsDropdown) {
                        settingsDropdown.style.display = 'none';
                    }
                });

                document.addEventListener('click', (event) => {
                    if (!menuTrigger.contains(event.target) && !sidebarDropdown.contains(event.target)) {
                        sidebarDropdown.classList.remove('show');
                        if (settingsDropdown) {
                            settingsDropdown.style.display = 'none';
                        }
                    }
                });
            }

            const openProfileModalSidebar = document.getElementById('openProfileModalSidebar');
            const closeProfileModal = document.getElementById('closeProfileModal');
            const profileModal = document.getElementById('profileModal');

            if (openProfileModalSidebar && closeProfileModal && profileModal) {
                openProfileModalSidebar.addEventListener('click', (event) => {
                    event.preventDefault();
                    profileModal.style.display = 'flex';
                });

                closeProfileModal.addEventListener('click', () => {
                    profileModal.style.display = 'none';
                });

                window.addEventListener('click', (event) => {
                    if (event.target === profileModal) {
                        profileModal.style.display = 'none';
                    }
                });
            }
});
