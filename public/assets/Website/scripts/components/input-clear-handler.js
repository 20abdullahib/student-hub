/**
 * Toggle the visibility of the clear icon based on input value.
 * @param {string} inputId - The ID of the input field.
 * @param {string} clearIconId - The ID of the clear icon.
 */
window.toggleClearIcon = function (inputId, clearIconId) {
    const searchInput = document.getElementById(inputId);
    const clearIcon = document.getElementById(clearIconId);
    if (searchInput && clearIcon) {
        if (searchInput.value.length > 0) {
            clearIcon.classList.remove("d-none");
        } else {
            clearIcon.classList.add("d-none");
        }
    } else {
        console.warn("toggleClearIcon: Invalid input or icon ID");
    }
};

/**
 * Clear the input field and hide the clear icon.
 * @param {string} inputId - The ID of the input field.
 * @param {string} clearIconId - The ID of the clear icon.
 */
window.clearSearch = function (inputId, clearIconId) {
    const searchInput = document.getElementById(inputId);
    if (searchInput) {
        searchInput.value = "";
        window.toggleClearIcon(inputId, clearIconId); // Call with parameters
    } else {
        console.warn("clearSearch: Invalid input ID");
    }
};

// example usage:
// <!-- Search 1 -->
// <input type="text" id="home-search" oninput="toggleClearIcon('home-search', 'clear-home-search')">
// <span id="clear-home-search" class="d-none" onclick="clearSearch('home-search', 'clear-home-search')">×</span>


