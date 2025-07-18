document.addEventListener('DOMContentLoaded', () => {
    // Rightbar Tabs functionality
    const tabs = document.querySelectorAll('.tabs .tab');
    if (tabs.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove 'active' from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                // Add 'active' to the clicked tab
                tab.classList.add('active');
            });
        });
    }
});
