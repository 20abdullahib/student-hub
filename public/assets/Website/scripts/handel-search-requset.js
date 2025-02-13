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

    /**
     * Display search or filter results.
     * @param {Array} data - Array of subject objects.
     */
    const displaySearchResults = (data) => {
        $resultsContainer.empty();
        if (data && data.length) {
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
        } else {
            $resultsContainer.html("<p>No results found</p>");
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
                        const $suggestionItem = $(
                            `<div class="suggestion-item p-2 bg-light border"></div>`
                        );
                        $suggestionItem.html(
                            `<strong>${subjectName}</strong> (${
                                subject.code || ""
                            })`
                        );
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

    // Live search: update suggestions and results as the user types.
    $resourceSearch.on("input", function () {
        const query = $(this).val().trim();
        if (query) {
            fetchSearchSuggestions(query);
            fetchLiveSearchResults(query);
        } else {
            $resourceSuggestionsContainer.empty();
            $resultsContainer.empty();
        }
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

    // Hide suggestions when clicking outside the search or suggestion areas.
    $(document).on("click", function (event) {
        if (
            !$(event.target).closest(
                "#resource-search, #resource-suggestions-container"
            ).length
        ) {
            hideSuggestions();
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
