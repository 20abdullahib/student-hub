/**
 * custom-script.js
 * ----------------
 * This file contains general front-end functionalities unrelated to AJAX requests.
 * It manages:
 *   - Navigation highlighting for the current page.
 *   - Modal display handling.
 *   - Randomized animation durations for marquee elements.
 *   - Live search suggestions for home and header search fields that redirect to search results.
 */

document.addEventListener("DOMContentLoaded", () => {
    // ============================================
    // Activate Current Page Link
    // ============================================
    const currentPath = window.location.pathname;
    const pageLinks = [
        { path: /^\/$/, id: "home-link" },
        { path: /^\/about-teem/, id: "about-link" },
        { path: /^\/contact-us/, id: "contact-link" },
        { path: /^\/resources/, id: "resources-link" },
    ];

    // Remove "active" from all navigation links.
    document
        .querySelectorAll(".nav li")
        .forEach((link) => link.classList.remove("active"));

    // Activate any link whose regex matches the current path.
    pageLinks.forEach((link) => {
        if (link.path.test(currentPath)) {
            const activeEl = document.getElementById(link.id);
            if (activeEl) activeEl.classList.add("active");
        }
    });

    // ============================================
    // Modal Handling
    // ============================================
    window.openModal = () => {
        const modal = document.getElementById("myModal");
        if (modal) {
            modal.style.display = "block";
        }
    };

    window.closeModal = () => {
        const modal = document.getElementById("myModal");
        if (modal) {
            modal.style.display = "none";
        }
    };

    // ============================================
    // Random Animation for Marquee Elements
    // ============================================
    const marqueeElements = document.querySelectorAll(".animate-marquee");
    const baseDuration = 30; // seconds
    const constantDifference = 5; // seconds difference per element

    marqueeElements.forEach((element, index) => {
        const duration = baseDuration + (index % 5) * constantDifference;
        element.style.animationDuration = `${duration}s`;
    });
});

// ============================================
// Search Suggestions for Home and Header Searches (jQuery)
// ============================================
$(document).ready(() => {
    // Define the search input IDs and their corresponding suggestion container selectors.
    const searchInputs = ["home-search", "header-search"];
    const suggestionContainers = {
        "home-search": "#home-suggestions-container",
        "header-search": "#header-suggestions-container",
    };

    searchInputs.forEach((inputId) => {
        const $searchInput = $(`#${inputId}`);
        const $suggestionsContainer = $(suggestionContainers[inputId]);

        $searchInput.on("input", function () {
            const query = $searchInput.val().trim();
            if (query.length >= 1) {
                // Use the globally exposed AJAX helper from handel-search-requset.js.
                if (typeof getSearchSuggestions === "function") {
                    getSearchSuggestions(query)
                        .done((data) => {
                            $suggestionsContainer.empty();
                            if (data && data.length) {
                                data.forEach((subject) => {
                                    const subjectName =
                                        subject.name || "No Name Provided";
                                    const $suggestionItem = $(`
                    <div class="suggestion-item p-2 bg-light border">
                      <strong>${subjectName}</strong> (${subject.code || ""})
                    </div>
                  `);
                                    $suggestionsContainer.append(
                                        $suggestionItem
                                    );

                                    // When a suggestion is clicked, redirect to the search results page.
                                    $suggestionItem.on("click", () => {
                                        window.location.href = `/resources/search?query=${encodeURIComponent(
                                            subjectName
                                        )}`;
                                    });
                                });
                            } else {
                                $suggestionsContainer.html(
                                    "<p>No suggestions found</p>"
                                );
                            }
                        })
                        .fail(() => {
                            $suggestionsContainer.html(
                                "<p>Error fetching suggestions</p>"
                            );
                        });
                }
            } else {
                $suggestionsContainer.empty();
            }
        });
    });

    // Handle search form submission.
    $("#search-form").on("submit", (e) => {
        e.preventDefault();
        // Use the value from either of the two search inputs.
        const query =
            $("#home-search").val().trim() || $("#header-search").val().trim();
        if (query) {
            window.location.href = `/resources/search?query=${encodeURIComponent(
                query
            )}`;
        }
    });
});




