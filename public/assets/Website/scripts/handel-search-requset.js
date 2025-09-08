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
    // Debug functions (commented out - uncomment for debugging)
    /*
    console.log('Script loaded, checking elements...');
    console.log('Resource search input:', $("#resource-search").length);
    console.log('Suggestions container:', $("#resource-suggestions-container").length);
    console.log('Results container:', $("#results-container").length);
    */
    
    // ------------------------------------------------------
    // Resource Search Functionality
    // ------------------------------------------------------
    const $resourceSearch = $("#resource-search");
    const $resourceSuggestionsContainer = $("#resource-suggestions-container");
    const $resultsContainer = $("#results-container");
    const $pageinitContainer = $("#pageinit-container");
    const $departmentFilter = $("#department-filter");
    const $branchFilter = $("#branch-filter");
    const $staticResources = $("#static-resources"); // Container for fixed data (if any)
    let debounceTimer;
    
    // Debug test functions (commented out - uncomment for debugging)
    /*
    if ($("#resource-search").length > 0) {
        console.log('Testing suggestions endpoint...');
        
        // Add test content to suggestions container
        $resourceSuggestionsContainer.html('<div class="p-2 bg-warning">Test content - suggestions container works!</div>');
        $resourceSuggestionsContainer.show();
        
        setTimeout(() => {
            $resourceSuggestionsContainer.empty();
        }, 3000);
        
        // Test with empty query first
        $.ajax({
            url: "/resources/suggestions",
            type: "GET",
            data: { query: "" },
            dataType: "json",
        }).done(function(response) {
            console.log('Test suggestions (empty query) successful:', response);
        }).fail(function(xhr, status, error) {
            console.error('Test suggestions (empty query) failed:', error, xhr.responseText);
        });
        
        // Test with "a" query
        $.ajax({
            url: "/resources/suggestions",
            type: "GET",
            data: { query: "a" },
            dataType: "json",
        }).done(function(response) {
            console.log('Test suggestions (with query) successful:', response);
        }).fail(function(xhr, status, error) {
            console.error('Test suggestions (with query) failed:', error, xhr.responseText);
        });
    }
    */

    // Clear search icon functionality (if it exists)
    const clearIcon = document.getElementById("clear-resource-search");
    if (clearIcon) {
        clearIcon.addEventListener("click", () => {
            // Clear the search input but keep suggestions
            $resourceSearch.val("");
            
            // Check if any filters are active
            const department = $departmentFilter.val();
            const branch = $branchFilter.val();
            
            if (department || branch) {
                // If filters are active, show filtered results without search
                fetchFilteredResults();
            } else {
                // If no filters, show static resources
                $resultsContainer.empty();
                $pageinitContainer.empty();
                $staticResources.show();
            }
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
                const subjectDescription = subject.description || "No description available.";
                const subjectId = subject.id || "#";
                const fileCount = subject.files_count || 0;
                
                const resultItem = `
          <div class="col">
            <div class="card folder-card h-100 text-center p-4">
              <i class="bi bi-folder-fill display-4 text-primary"></i>
              <div class="card-body">
                <h5 class="card-title">${subjectName}</h5>
                <p class="card-text">${subjectDescription}</p>
                <p class="card-text small text-muted">${fileCount} file(s) available</p>
                <a href="/resources/${subjectId}" class="btn btn-primary">Open</a>
              </div>
            </div>
          </div>`;
                $resultsContainer.append(resultItem);
            });

            if (pagination && pagination.trim() !== '') {
                const paginationControls = `
          <div class="d-flex justify-content-center mt-4">
            ${pagination}
          </div>`;
                $pageinitContainer.append(paginationControls);
            }
        } else {
            // No dynamic results found; hide static resources and display error message
            $staticResources.hide();
            $resultsContainer.html(`
                <div class="col-12 text-center py-5">
                    <i class="bi bi-folder-x display-1 text-muted"></i>
                    <h4 class="mt-3">No Resources Found</h4>
                    <p class="text-muted">No subjects match your search criteria.</p>
                </div>
            `);
        }
    };

    /**
     * AJAX helper function to retrieve search suggestions.
     *
     * @param {string} query - The search term.
     * @returns {JQuery.jqXHR} - A jQuery AJAX promise.
     */
    function getSearchSuggestions(query) {
        // Debug logs (commented out - uncomment for debugging)
        // console.log('Making AJAX request to:', "/resources/suggestions", 'with query:', query);
        
        return $.ajax({
            url: "/resources/suggestions",
            type: "GET",
            data: { query: query },
            dataType: "json",
            beforeSend: function() {
                // console.log('Sending request for suggestions...');
            }
        });
    }
    // Expose globally for Home/Header search suggestions.
    window.getSearchSuggestions = getSearchSuggestions;

    /**
     * Fetch live search results and display them.
     *
     * @param {string} query - The search term.
     * @param {string} [url="/resources/filter"] - Optional URL for pagination.
     */
    const fetchLiveSearchResults = (query, url = "/resources/filter") => {
        const department = $departmentFilter.val();
        const branch = $branchFilter.val();
        
        $.ajax({
            url: url,
            type: "GET",
            data: { 
                query: query,
                department: department,
                branch: branch
            },
            dataType: "json",
            success: displaySearchResults,
            error: () => {
                $resultsContainer.html(
                    `<div class="col-12 text-center py-5">
                        <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                        <h4 class="mt-3">Error</h4>
                        <p class="text-muted">Error fetching search results. Please try again.</p>
                    </div>`
                );
                $staticResources.hide();
            },
        });
    };

    /**
     * Fetch filtered search results based on selected filter options.
     */
    const fetchFilteredResults = () => {
        const query = $resourceSearch.val().trim();
        const department = $departmentFilter.val();
        const branch = $branchFilter.val();

        // If no filters and no search query, show static resources
        if (!query && !department && !branch) {
            $resultsContainer.empty();
            $pageinitContainer.empty();
            $staticResources.show();
            return;
        }

        $.ajax({
            url: "/resources/filter",
            type: "GET",
            data: { 
                query: query,
                department: department, 
                branch: branch
            },
            dataType: "json",
            success: displaySearchResults,
            error: () => {
                $resultsContainer.html(
                    `<div class="col-12 text-center py-5">
                        <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                        <h4 class="mt-3">Error</h4>
                        <p class="text-muted">Error fetching filtered results. Please try again.</p>
                    </div>`
                );
                $staticResources.hide();
            },
        });
    };

    /**
     * Fetch and display search suggestions for the resource search input.
     *
     * @param {string} query - The search term.
     */
    const fetchResourceSearchSuggestions = (query) => {
        // Debug logs (commented out - uncomment for debugging)
        // console.log('fetchResourceSearchSuggestions called with:', query);
        // console.log('Suggestions container element:', $resourceSuggestionsContainer[0]);
        
        // Simple AJAX call for suggestions using dedicated endpoint
        $.ajax({
            url: "/resources/suggestions",
            type: "GET",
            data: { query: query },
            dataType: "json",
            success: function(response) {
                // console.log('Suggestions AJAX success:', response);
                
                $resourceSuggestionsContainer.empty();
                
                // Check if response has data
                if (response && response.data && response.data.length > 0) {
                    // console.log('Found', response.data.length, 'suggestions');
                    
                    response.data.slice(0, 5).forEach((subject, index) => {
                        const subjectName = subject.name || "No Name";
                        const subjectCode = subject.code || "";
                        const isLast = index === Math.min(response.data.length - 1, 4);
                        
                        const suggestionHtml = `
                            <div class="suggestion-item p-3 ${!isLast ? 'border-bottom' : ''}" 
                                 style="cursor: pointer; transition: all 0.2s ease; background-color: white; border-color: #e9ecef;">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-folder-fill text-primary me-3" style="font-size: 1.1rem;"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">${subjectName}</div>
                                        ${subjectCode ? `<small class="text-muted">${subjectCode}</small>` : ''}
                                    </div>
                                    <i class="bi bi-arrow-right text-muted ms-2" style="font-size: 0.8rem; opacity: 0.6;"></i>
                                </div>
                            </div>
                        `;
                        
                        const $suggestionItem = $(suggestionHtml);
                        $resourceSuggestionsContainer.append($suggestionItem);

                        // Enhanced hover effects
                        $suggestionItem.on("mouseenter", function() {
                            $(this).css({
                                "background-color": "#f8f9fa",
                                "transform": "translateX(2px)"
                            });
                            $(this).find('.bi-arrow-right').css('opacity', '1');
                        }).on("mouseleave", function() {
                            $(this).css({
                                "background-color": "white",
                                "transform": "translateX(0px)"
                            });
                            $(this).find('.bi-arrow-right').css('opacity', '0.6');
                        });

                        // Click handler
                        $suggestionItem.on("click", function() {
                            // console.log('Suggestion clicked:', subjectName);
                            $resourceSearch.val(subjectName);
                            $resourceSuggestionsContainer.removeClass('show').hide();
                            fetchFilteredResults();
                        });
                    });
                    
                    // Show with smooth animation
                    $resourceSuggestionsContainer.addClass('show').show();
                    // console.log('Suggestions container should now be visible');
                    
                } else {
                    // No suggestions found - hide the container instead of showing empty message
                    // console.log('No suggestions found in response');
                    $resourceSuggestionsContainer.removeClass('show').hide();
                }
            },
            error: function(xhr, status, error) {
                // Debug error logs (commented out - uncomment for debugging)
                /*
                console.error('Suggestions AJAX error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    xhr: xhr
                });
                */
                
                // Hide container on error instead of showing error message
                $resourceSuggestionsContainer.removeClass('show').hide();
            }
        });
    };

    /**
     * Resource search input event: live search with debouncing.
     * Keep suggestions visible and show filtered results.
     */
    $resourceSearch.on("input", function () {
        clearTimeout(debounceTimer);
        const query = $(this).val().trim();
        
        // Debug logs (commented out - uncomment for debugging)
        // console.log('Search input changed:', query);
        // console.log('Input element:', this);
        // console.log('Suggestions container exists:', $resourceSuggestionsContainer.length);

        debounceTimer = setTimeout(() => {
            // console.log('Processing search after debounce:', query);
            
            if (query.length >= 1) { // Start suggestions from 1 character
                // console.log('Calling fetchResourceSearchSuggestions');
                fetchResourceSearchSuggestions(query);
                // Use filtered results which combines search with any active filters
                fetchFilteredResults();
            } else {
                // If search input is empty, hide suggestions
                // console.log('Search input empty, hiding suggestions');
                $resourceSuggestionsContainer.removeClass('show').hide();
                
                // Check if any filters are active
                const department = $departmentFilter.val();
                const branch = $branchFilter.val();
                
                if (department || branch) {
                    // If filters are active, show filtered results
                    fetchFilteredResults();
                } else {
                    // If no filters, show static resources
                    $resultsContainer.empty();
                    $pageinitContainer.empty();
                    $staticResources.show();
                }
            }
        }, 300); // Increased debounce for better UX
    });

    /**
     * Handle resource search form submission.
     */
    $("#search-form").on("submit", function (e) {
        e.preventDefault();
        const query = $resourceSearch.val().trim();
        const department = $departmentFilter.val();
        const branch = $branchFilter.val();

        // Always use filter results which handles both search and filters
        fetchFilteredResults();
    });

    // Trigger filtering when any filter option changes.
    $departmentFilter
        .add($branchFilter)
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
            $resourceSuggestionsContainer.removeClass('show').hide();
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
    $(document).on("click", "#pageinit-container .pagination a", function (e) {
        e.preventDefault();
        const url = $(this).attr("href");
        const query = $resourceSearch.val().trim();
        
        // Only proceed if the URL is valid and not a disabled/placeholder link
        if (url && !$(this).parent().hasClass('disabled') && !$(this).hasClass('disabled')) {
            // Extract parameters from the pagination URL
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const page = urlParams.get('page');
            
            // Make sure we have a valid page number
            if (page && parseInt(page) > 0) {
                // Use the filter endpoint for pagination to maintain all current filters
                const department = $departmentFilter.val();
                const branch = $branchFilter.val();
                
                $.ajax({
                    url: "/resources/filter",
                    type: "GET",
                    data: { 
                        query: query,
                        department: department,
                        branch: branch,
                        page: page
                    },
                    dataType: "json",
                    success: displaySearchResults,
                    error: () => {
                        $resultsContainer.html(
                            `<div class="col-12 text-center py-5">
                                <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                                <h4 class="mt-3">Error</h4>
                                <p class="text-muted">Error loading page. Please try again.</p>
                            </div>`
                        );
                        $staticResources.hide();
                    },
                });
            }
        }
    });

    /**
     * Global function to hide all suggestion containers.
     */
    window.hideSuggestions = function () {
        $("#resource-suggestions-container").removeClass('show').hide();
        $("#home-suggestions-container").removeClass('show').hide();
        $("#header-suggestions-container").hide(); // Header uses simple hide
    };

    // ------------------------------------------------------
    // Home and Header Search Suggestions Functionality
    // ------------------------------------------------------
    
    /**
     * Fetch and display improved search suggestions for the home search input.
     *
     * @param {string} query - The search term.
     * @param {jQuery} $container - The suggestions container element.
     */
    const fetchImprovedHomeSuggestions = (query, $container) => {
        $.ajax({
            url: "/resources/suggestions",
            type: "GET",
            data: { query: query },
            dataType: "json",
            success: function(response) {
                $container.empty();
                
                // Check if response has data
                if (response && response.data && response.data.length > 0) {
                    response.data.slice(0, 5).forEach((subject, index) => {
                        const subjectName = subject.name || "No Name";
                        const subjectCode = subject.code || "";
                        const isLast = index === Math.min(response.data.length - 1, 4);
                        
                        const suggestionHtml = `
                            <div class="suggestion-item p-3 ${!isLast ? 'border-bottom' : ''}" 
                                 style="cursor: pointer; transition: all 0.2s ease; background-color: white; border-color: #e9ecef;">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-folder-fill text-primary me-3" style="font-size: 1.1rem;"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">${subjectName}</div>
                                        ${subjectCode ? `<small class="text-muted">${subjectCode}</small>` : ''}
                                    </div>
                                    <i class="bi bi-arrow-right text-muted ms-2" style="font-size: 0.8rem; opacity: 0.6;"></i>
                                </div>
                            </div>
                        `;
                        
                        const $suggestionItem = $(suggestionHtml);
                        $container.append($suggestionItem);

                        // Enhanced hover effects
                        $suggestionItem.on("mouseenter", function() {
                            $(this).css({
                                "background-color": "#f8f9fa",
                                "transform": "translateX(2px)"
                            });
                            $(this).find('.bi-arrow-right').css('opacity', '1');
                        }).on("mouseleave", function() {
                            $(this).css({
                                "background-color": "white",
                                "transform": "translateX(0px)"
                            });
                            $(this).find('.bi-arrow-right').css('opacity', '0.6');
                        });

                        // Click handler - redirect to search results page
                        $suggestionItem.on("click", function() {
                            $container.removeClass('show').hide();
                            window.location.href = `/resources/search?query=${encodeURIComponent(subjectName)}`;
                        });
                    });
                    
                    // Show with smooth animation
                    $container.addClass('show').show();
                    
                } else {
                    // No suggestions found - hide the container
                    $container.removeClass('show').hide();
                }
            },
            error: function(xhr, status, error) {
                // Hide container on error
                $container.removeClass('show').hide();
            }
        });
    };
    
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
            let homeDebounceTimer;
            
            $searchInput.on("input", function () {
                clearTimeout(homeDebounceTimer);
                const query = $searchInput.val().trim();
                
                homeDebounceTimer = setTimeout(() => {
                    if (query.length >= 1) {
                        // Use improved suggestions for home search, regular for header search
                        if (inputId === "home-search") {
                            fetchImprovedHomeSuggestions(query, $suggestionsContainer);
                        } else {
                            // Keep original functionality for header search but fix display issues
                            getSearchSuggestions(query)
                                .done((response) => {
                                    const data = response.data;
                                    $suggestionsContainer.empty();
                                    if (data && data.length) {
                                        data.forEach((subject) => {
                                            const subjectName = subject.name || "No Name Provided";
                                            const $suggestionItem = $(`
                                                <div class="suggestion-item p-2 bg-light border">
                                                  <strong>${subjectName}</strong> (${subject.code || ""})
                                                </div>
                                            `);
                                            $suggestionsContainer.append($suggestionItem);

                                            // On click: fill the search input, clear suggestions, and redirect.
                                            $suggestionItem.on("click", () => {
                                                $searchInput.val(subjectName);
                                                $suggestionsContainer.empty().hide();
                                                window.location.href = `/resources/search?query=${encodeURIComponent(subjectName)}`;
                                            });
                                        });
                                        // Show the suggestions container
                                        $suggestionsContainer.show();
                                    } else {
                                        // Hide container when no suggestions found instead of showing message
                                        $suggestionsContainer.hide();
                                    }
                                })
                                .fail(() => {
                                    // Hide container on error instead of showing error message
                                    $suggestionsContainer.hide();
                                });
                        }
                    } else {
                        // If the search input is empty, hide suggestions
                        if (inputId === "home-search") {
                            $suggestionsContainer.removeClass('show').hide();
                        } else {
                            // For header search, just hide normally
                            $suggestionsContainer.hide();
                        }
                        
                        // Check if any filters are active on the current page
                        const departmentFilter = $("#department-filter");
                        const branchFilter = $("#branch-filter");
                        
                        if (departmentFilter.length && branchFilter.length) {
                            const department = departmentFilter.val();
                            const branch = branchFilter.val();
                            
                            if (department || branch) {
                                fetchFilteredResults();
                            } else {
                                // Show static resources if on resources page
                                if ($("#static-resources").length) {
                                    $("#results-container").empty();
                                    $("#pageinit-container").empty();
                                    $("#static-resources").show();
                                }
                            }
                        }
                    }
                }, 300); // Debounce for better UX
            });
            
            // Hide suggestions when clicking outside
            $(document).on("click", function (event) {
                if (!$(event.target).closest(`#${inputId}, ${suggestionContainers[inputId]}`).length) {
                    if (inputId === "home-search") {
                        $suggestionsContainer.removeClass('show').hide();
                    } else {
                        // For header search, just hide normally
                        $suggestionsContainer.hide();
                    }
                    clearTimeout(homeDebounceTimer);
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
