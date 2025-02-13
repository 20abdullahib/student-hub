/**
 * handel-search-requset.js
 * -------------------------
 * This file handles all AJAX requests and live search functionality
 * for the resource search page. It includes functions for:
 *   - Displaying search results and suggestions.
 *   - Fetching live search results and filtered results.
 *   - Updating the URL query parameters and search header.
 *
 * All AJAX-related functions are consolidated here to avoid duplication.
 */

$(function () {
    // Cache frequently used selectors
    const $resourceSearch = $("#resource-search");
    const $resourceSuggestionsContainer = $("#resource-suggestions-container");
    const $resultsContainer = $("#results-container");
    const $departmentFilter = $("#department-filter");
    const $branchFilter = $("#branch-filter");
    const $sortFilter = $("#sort-filter");
    // Cache the static data container (fixed data) – make sure this id is added in your Blade template.
    const $staticResources = $("#static-resources");

    // Variable for debouncing the input event
    let debounceTimer;

    // Get references to the input field and the clear icon
    const searchInput = document.getElementById("resource-search");
    const clearIcon = document.getElementById("clear-resource-search");

    // Check if the elements exist to ensure this runs only on the resources page
    if (searchInput && clearIcon) {
        // --- Helper Functions ---
        // Function to toggle the visibility of the clear icon
        function toggleClearIcon() {
            if (searchInput.value.trim() !== "") {
                clearIcon.classList.remove("d-none"); // Show the clear icon
            } else {
                clearIcon.classList.add("d-none"); // Hide the clear icon
            }
        }

        // Add event listener to show/hide the clear icon based on input value
        searchInput.addEventListener("input", toggleClearIcon);

        // Add event listener to clear the input field when the clear icon is clicked
        clearIcon.addEventListener("click", function () {
            searchInput.value = ""; // Clear the input field
            toggleClearIcon(); // Hide the clear icon after clearing
            displaySearchResults([]);
        });
    }

    /**
     * Display search or filter results.
     * If results exist, hide the fixed data and display AJAX results.
     * If no results, show the fixed data again.
     * @param {Array} data - Array of subject objects.
     */
    const displaySearchResults = (data) => {
        $resultsContainer.empty();
        if (data && data.length > 0) {
            // Hide the fixed (static) resources if AJAX returns results.
            $staticResources.hide();

            data.forEach((subject) => {
                const subjectName = subject.name || "No Name Provided";
                const subjectDescription = subject.description || "";
                const subjectId = subject.id || "#";

                const resultItem = `
              <div class="col">
                  <div class="card folder-card h-100 text-center p-4">
                      <!-- Folder Icon -->
                      <i class="bi bi-folder-fill display-4 text-primary"></i>
                      <!-- Card Body -->
                      <div class="card-body">
                          <h5 class="card-title">${subjectName}</h5>
                          <p class="card-text">
                              ${subjectDescription}
                          </p>
                          <!-- Open Button -->
                          <a href="/resources/subjects/${subjectId}" class="btn btn-primary">Open</a>
                      </div>
                  </div>
              </div>
              `;
                $resultsContainer.append(resultItem);
            });

            // If many items (e.g. 30 or more), add a placeholder for pagination controls.
            if (data.length >= 30) {
                const paginationControls = `
              <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-3">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                  </li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav>`;
                $resultsContainer.append(paginationControls);
            }
        } else {
            // No AJAX results: show a message and reveal the fixed resources.
            // $resultsContainer.html("<p>No results found, displaying default resources.</p>");
            $staticResources.show();
        }
    };

    /**
     * Common AJAX helper to get search suggestions.
     * @param {string} query - The search term.
     * @returns {JQuery.jqXHR} - jQuery AJAX promise.
     */
    function getSearchSuggestions(query) {
        return $.ajax({
            url: "/resources/search",
            type: "GET",
            data: { query },
        });
    }

    /**
     * Fetch live search results and display them.
     * @param {string} query - The search term.
     */
    const fetchLiveSearchResults = (query) => {
        $.ajax({
            url: "/resources/search",
            type: "GET",
            data: { query },
            success: displaySearchResults,
            error: () => {
                $resultsContainer.html("<p>Error fetching search results</p>");
            },
        });
    };

    /**
     * Fetch filtered results based on selected filters.
     */
    const fetchFilteredResults = () => {
        const department = $departmentFilter.val();
        const branch = $branchFilter.val();
        const sort = $sortFilter.val();
        $.ajax({
            url: "/resources/filter",
            type: "GET",
            data: { department, branch, sort },
            success: displaySearchResults,
            error: () => {
                $resultsContainer.html(
                    "<p>Error fetching filtered results</p>"
                );
            },
        });
    };

    /**
     * Fetch and display search suggestions for resource search.
     * @param {string} query - The search term.
     */
    const fetchSearchSuggestions = (query) => {
        getSearchSuggestions(query)
            .done((data) => {
                $resourceSuggestionsContainer.empty();
                if (data && data.length) {
                    data.forEach((subject) => {
                        const subjectName = subject.name || "No Name Provided";
                        const $suggestionItem = $(`
                          <div class="suggestion-item p-2 bg-light border">
                              <strong>${subjectName}</strong> (${
                            subject.code || ""
                        })
                          </div>
                      `);
                        $resourceSuggestionsContainer.append($suggestionItem);

                        // When a suggestion is clicked, fill the search input and perform a live search.
                        $suggestionItem.on("click", () => {
                            $resourceSearch.val(subjectName);
                            $resourceSuggestionsContainer.empty();
                            fetchLiveSearchResults(subjectName);
                        });
                    });
                } else {
                    $resourceSuggestionsContainer.html(
                        "<p>No suggestions found</p>"
                    );
                }
            })
            .fail(() => {
                $resourceSuggestionsContainer.html(
                    "<p>Error fetching suggestions</p>"
                );
            });
    };

    // --- Event Handlers for Resource Search ---

    // Live search with debouncing: update suggestions and results as the user types.
    $resourceSearch.on("input", function () {
        clearTimeout(debounceTimer);
        const query = $(this).val().trim();

        debounceTimer = setTimeout(() => {
            if (query) {
                fetchSearchSuggestions(query);
                fetchLiveSearchResults(query);
            } else {
                $resourceSuggestionsContainer.empty();
                $resultsContainer.empty();
                // Show the fixed (static) data if search input is cleared.
                $staticResources.show();
            }
        }, 300); // Adjust delay (in milliseconds) as needed.
    });

    // Handle search form submission.
    $("#search-form").on("submit", function (e) {
        e.preventDefault();
        const query = $resourceSearch.val().trim();
        if (query) {
            fetchLiveSearchResults(query);
        }
    });

    // Trigger filtering when any filter option changes.
    $departmentFilter
        .add($branchFilter)
        .add($sortFilter)
        .on("change", fetchFilteredResults);

    // Hide suggestions (and cancel pending AJAX calls) when clicking outside the search or suggestion areas.
    $(document).on("click", function (event) {
        if (
            !$(event.target).closest(
                "#resource-search, #resource-suggestions-container"
            ).length
        ) {
            hideSuggestions();
            clearTimeout(debounceTimer);
        }
    });

    // Update URL query parameter and search header text as the user types.
    $resourceSearch.on("input", function () {
        const query = $(this).val().trim();
        const newUrl = new URL(window.location);
        const searchHeader = document.getElementById("search-header");
        const searchQuerySpan = document.getElementById("search-query");
        const noResultsQuerySpan = document.getElementById(
            "search-query-no-results"
        );

        if (query) {
            newUrl.searchParams.set("query", query);
            window.history.replaceState(null, "", newUrl);
            if (searchHeader) {
                searchHeader.style.display = "block";
                if (searchQuerySpan) searchQuerySpan.textContent = query;
                if (noResultsQuerySpan) noResultsQuerySpan.textContent = query;
            }
        } else {
            newUrl.searchParams.delete("query");
            window.history.replaceState(null, "", newUrl);
            if (searchHeader) {
                searchHeader.style.display = "none";
            }
        }
    });

    // Expose the AJAX helper for use in other scripts.
    window.getSearchSuggestions = getSearchSuggestions;
});

// Global function to hide suggestion containers.
function hideSuggestions() {
    $("#resource-suggestions-container").empty();
    $("#home-suggestions-container").empty();
}
