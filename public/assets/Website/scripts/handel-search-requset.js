/**
 * handle-search-request.js
 * -------------------------
 * This file provides AJAX-driven search functionality for both the Resource Search page
 * and for Home/Header search suggestions.
 *
 * Features include:
 *   - Live search with debouncing for resources.
 *   - Displaying search suggestions and search results (with pagination) using the existing card design.
 *   - Filtered search results based on department, branch, and sort options.
 *   - Home and Header search suggestions that redirect to the search results page when selected.
 *
 * Special Behavior:
 *   • If the search input is empty (and the department/branch filters are empty),
 *     a dynamic (AJAX) request is made to return all subjects without reloading the page.
 *
 * Usage:
 *   - Resource search input: #resource-search with suggestions container (#resource-suggestions-container)
 *   - Home search input:    #home-search with suggestions container (#home-suggestions-container)
 *   - Header search input:  #header-search with suggestions container (#header-suggestions-container)
 *   - Filter elements:      #department-filter, #branch-filter, and #sort-filter.
 *
 */
$(function () {
    // ------------------------------------------------------
    // Resource Search Functionality
    // ------------------------------------------------------
    const $resourceSearch = $("#resource-search");
    const $resourceSuggestionsContainer = $("#resource-suggestions-container");
    const $resultsContainer = $("#results-container");
    const $pageinitContainer = $("#pageinit-container");
    const $departmentFilter = $("#department-filter");
    const $branchFilter = $("#branch-filter");
    const $sortFilter = $("#sort-filter");
    const $staticResources = $("#static-resources"); // Container for fixed data (if any)
    let debounceTimer;

    // Clear search icon functionality (if it exists)
    const clearIcon = document.getElementById("clear-resource-search");
    if (clearIcon) {
        clearIcon.addEventListener("click", () => {
            // Clear the search input and suggestions, then fetch all subjects dynamically.
            $resourceSearch.val("");
            $resourceSuggestionsContainer.empty();
            fetchLiveSearchResults("");
        });
    }

    /**
     * Display search or filter results with pagination.
     * Expects a response with the format:
     * {
     *   data: [subject objects],
     *   pagination: "<pagination HTML>"
     * }
     * Renders each result using the predefined card design.
     *
     * @param {Object} response - The AJAX response object.
     */
    const displaySearchResults = (response) => {
        const data = response.data;
        const pagination = response.pagination;
        $resultsContainer.empty();
        $pageinitContainer.empty();

        if (data && data.length > 0) {
            // Hide static resources when dynamic search results are available.
            $staticResources.hide();

            data.forEach((subject) => {
                const subjectName = subject.name || "No Name Provided";
                const subjectDescription = subject.description || "";
                const subjectId = subject.id || "#";
                const resultItem = `
          <div class="col">
            <div class="card folder-card h-100 text-center p-4">
              <i class="bi bi-folder-fill display-4 text-primary"></i>
              <div class="card-body">
                <h5 class="card-title">${subjectName}</h5>
                <p class="card-text">${subjectDescription}</p>
                <a href="/resources/subjects/${subjectId}" class="btn btn-primary">Open</a>
              </div>
            </div>
          </div>`;
                $resultsContainer.append(resultItem);
            });

            if (pagination) {
                const paginationControls = `
          <div class="d-flex justify-content-center mt-4">
            ${pagination}
          </div>`;
                $pageinitContainer.append(paginationControls);
            }
        } else {
            // No dynamic results found; display a message.
            $resultsContainer.html("<p>No subjects found.</p>");
        }
    };

    /**
     * AJAX helper function to retrieve search suggestions.
     *
     * @param {string} query - The search term.
     * @returns {JQuery.jqXHR} - A jQuery AJAX promise.
     */
    function getSearchSuggestions(query) {
        return $.ajax({
            url: "/resources/search",
            type: "GET",
            data: { query },
            dataType: "json",
        });
    }
    // Expose globally for Home/Header search suggestions.
    window.getSearchSuggestions = getSearchSuggestions;

    /**
     * Fetch live search results and display them.
     *
     * @param {string} query - The search term.
     * @param {string} [url="/resources/search"] - Optional URL for pagination.
     */
    const fetchLiveSearchResults = (query, url = "/resources/search") => {
        $.ajax({
            url: url,
            type: "GET",
            data: { query },
            dataType: "json",
            success: displaySearchResults,
            error: () => {
                $resultsContainer.html("<p>Error fetching search results</p>");
            },
        });
    };

    /**
     * Fetch filtered search results based on selected filter options.
     * If both department and branch filters are empty, it fetches all subjects dynamically.
     */
    const fetchFilteredResults = () => {
        const department = $departmentFilter.val();
        const branch = $branchFilter.val();
        const sort = $sortFilter.val();

        if (!department && !branch) {
            // If no department or branch filter is set, fetch all subjects dynamically.
            fetchLiveSearchResults("");
            return;
        }

        $.ajax({
            url: "/resources/filter",
            type: "GET",
            data: { department, branch, sort },
            dataType: "json",
            success: displaySearchResults,
            error: () => {
                $resultsContainer.html(
                    "<p>Error fetching filtered results</p>"
                );
            },
        });
    };

    /**
     * Fetch and display search suggestions for the resource search input.
     *
     * @param {string} query - The search term.
     */
    const fetchResourceSearchSuggestions = (query) => {
        getSearchSuggestions(query)
            .done((response) => {
                const data = response.data;
                $resourceSuggestionsContainer.empty();
                if (data && data.length) {
                    data.forEach((subject) => {
                        const subjectName = subject.name || "No Name Provided";
                        const $suggestionItem = $(`
              <div class="suggestion-item p-2 bg-light border">
                <strong>${subjectName}</strong> (${subject.code || ""})
              </div>
            `);
                        $resourceSuggestionsContainer.append($suggestionItem);

                        // On click: fill the search input with the suggestion and trigger an AJAX search.
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

    /**
     * Resource search input event: live search with debouncing.
     * If the input is empty, a dynamic (AJAX) request is made to fetch all subjects.
     */
    $resourceSearch.on("input", function () {
        clearTimeout(debounceTimer);
        const query = $(this).val().trim();

        debounceTimer = setTimeout(() => {
            if (query) {
                fetchResourceSearchSuggestions(query);
                fetchLiveSearchResults(query);
            } else {
                // If search input is empty, clear suggestions and fetch all subjects dynamically.
                $resourceSuggestionsContainer.empty();
                fetchLiveSearchResults("");
            }
        }, 300); // 300ms debounce delay.
    });

    /**
     * Handle resource search form submission.
     * If no search query and no department/branch filters are provided,
     * the dynamic AJAX request fetches all subjects.
     */
    $("#search-form").on("submit", function (e) {
        e.preventDefault();
        const query = $resourceSearch.val().trim();
        const department = $departmentFilter.val();
        const branch = $branchFilter.val();

        if (!query && !department && !branch) {
            // If no query and filters, fetch all subjects dynamically.
            fetchLiveSearchResults("");
        } else {
            fetchLiveSearchResults(query);
        }
    });

    // Trigger filtering when any filter option changes.
    $departmentFilter
        .add($branchFilter)
        .add($sortFilter)
        .on("change", fetchFilteredResults);

    /**
     * Hide suggestions and cancel any pending debounce when clicking outside search areas.
     */
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

    /**
     * Update URL query parameter and search header text as the user types in the resource search.
     */
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

    /**
     * Delegate pagination link clicks (applies to both search and filter results).
     */
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const url = $(this).attr("href");
        const query = $resourceSearch.val().trim();
        if (url) {
            fetchLiveSearchResults(query, url);
        }
    });

    /**
     * Global function to hide all suggestion containers.
     */
    window.hideSuggestions = function () {
        $("#resource-suggestions-container").empty();
        $("#home-suggestions-container").empty();
        $("#header-suggestions-container").empty();
    };

    // ------------------------------------------------------
    // Home and Header Search Suggestions Functionality
    // ------------------------------------------------------
    // Define the search inputs and their corresponding suggestion container selectors.
    const homeHeaderSearchInputs = ["home-search", "header-search"];
    const suggestionContainers = {
        "home-search": "#home-suggestions-container",
        "header-search": "#header-suggestions-container",
    };

    // Loop through each search input (if present) and bind input events.
    homeHeaderSearchInputs.forEach((inputId) => {
        const $searchInput = $(`#${inputId}`);
        const $suggestionsContainer = $(suggestionContainers[inputId]);

        if ($searchInput.length) {
            $searchInput.on("input", function () {
                const query = $searchInput.val().trim();
                if (query.length >= 1) {
                    // Use the globally exposed AJAX helper.
                    getSearchSuggestions(query)
                        .done((response) => {
                            const data = response.data;
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

                                    // On click: fill the search input, clear suggestions, and redirect.
                                    $suggestionItem.on("click", () => {
                                        $searchInput.val(subjectName);
                                        $suggestionsContainer.empty();
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
                } else {
                    // If the search input is cleared, clear suggestions and fetch all subjects dynamically.
                    $suggestionsContainer.empty();
                    fetchLiveSearchResults("");
                }
            });
        }
    });

    /**
     * Handle form submission for Home/Header search inputs.
     * If no query is provided, a dynamic AJAX request fetches all subjects.
     */
    if ($("#home-search").length || $("#header-search").length) {
        $("#search-form").on("submit", function (e) {
            e.preventDefault();
            const query =
                $("#home-search").val().trim() ||
                $("#header-search").val().trim();
            if (query) {
                window.location.href = `/resources/search?query=${encodeURIComponent(
                    query
                )}`;
            } else {
                fetchLiveSearchResults("");
            }
        });
    }
});
