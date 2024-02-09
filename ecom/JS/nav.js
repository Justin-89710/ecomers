document.addEventListener('DOMContentLoaded', function () {
    var searchOverlay = document.getElementById('searchOverlay');
    var closeSearchOverlayBtn = document.getElementById('closeSearchOverlayBtn');

    // Button click event
    closeSearchOverlayBtn.addEventListener('click', function () {
        searchOverlay.style.display = 'none'; // Set display to 'none' to hide the overlay
    });
});