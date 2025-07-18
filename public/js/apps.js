// Function to manage modal visibility and body scroll
function toggleModal(modalElement, show) {
    if (!modalElement) {
        console.error('Modal element not found for toggleModal. Cannot operate.');
        return;
    }

    if (show) {
        modalElement.style.display = 'flex'; // Make modal visible
        // Short delay to allow CSS 'display' property to apply before 'opacity' transition
        setTimeout(() => {
            modalElement.classList.add('show');
            document.body.classList.add('modal-open'); // Prevent main page scrolling
        }, 10);
    } else {
        modalElement.classList.remove('show'); // Trigger fade-out/slide-up transition
        // Listen for the end of the CSS transition before hiding the element completely
        modalElement.addEventListener('transitionend', function handler() {
            modalElement.style.display = 'none'; // Hide modal after transition
            document.body.classList.remove('modal-open'); // Re-enable main page scrolling
            // Remove the event listener to avoid memory leaks and multiple firings
            modalElement.removeEventListener('transitionend', handler);
        });
    }
}

// You can add other common utility functions here if needed across multiple components
