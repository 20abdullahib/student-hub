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
 *   • If the search input is empty and both the department and branch filters are not set,
 *     a full (non-AJAX) request is made to the backend search route so that all subjects are returned.
 *
 * Backend Expectation:
 *   The backend search function checks if the request expects JSON (AJAX) or a normal page load.
 *   When no query or filters are provided, the backend returns all subjects.
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
      // Clear suggestions and trigger a full reload to display all subjects.
      window.location.href = "/resources/search";
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
      // No dynamic results – trigger a full page reload (or show static content).
      window.location.href = "/resources/search";
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
   * If no department and branch are selected, a full page request is made.
   */
  const fetchFilteredResults = () => {
    const department = $departmentFilter.val();
    const branch = $branchFilter.val();
    const sort = $sortFilter.val();

    // If both department and branch are empty, perform a full request to return all subjects.
    if (!department && !branch) {
      window.location.href = "/resources/search";
      return;
    }

    $.ajax({
      url: "/resources/filter",
      type: "GET",
      data: { department, branch, sort },
      dataType: "json",
      success: displaySearchResults,
      error: () => {
        $resultsContainer.html("<p>Error fetching filtered results</p>");
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
          $resourceSuggestionsContainer.html("<p>No suggestions found</p>");
        }
      })
      .fail(() => {
        $resourceSuggestionsContainer.html("<p>Error fetching suggestions</p>");
      });
  };

  /**
   * Resource search input event: live search with debouncing.
   * If the input is empty (and therefore no query is provided), a normal page reload is triggered.
   */
  $resourceSearch.on("input", function () {
    clearTimeout(debounceTimer);
    const query = $(this).val().trim();

    debounceTimer = setTimeout(() => {
      if (query) {
        fetchResourceSearchSuggestions(query);
        fetchLiveSearchResults(query);
      } else {
        // If search input is empty, perform a normal (non-AJAX) request to load all subjects.
        window.location.href = "/resources/search";
      }
    }, 300); // 300ms debounce delay.
  });

  /**
   * Handle resource search form submission.
   * If no search query and no department/branch filters are selected,
   * the form is allowed to submit normally so that the backend returns all subjects.
   */
  $("#search-form").on("submit", function (e) {
    const query = $resourceSearch.val().trim();
    const department = $departmentFilter.val();
    const branch = $branchFilter.val();

    // If query is empty and no filters are selected, allow normal submission.
    if (!query && !department && !branch) {
      return;
    }
    e.preventDefault();
    fetchLiveSearchResults(query);
  });

  // Trigger filtering when any filter option changes.
  $departmentFilter.add($branchFilter).add($sortFilter).on("change", fetchFilteredResults);

  /**
   * Hide suggestions and cancel any pending debounce when clicking outside search areas.
   */
  $(document).on("click", function (event) {
    if (!$(event.target).closest("#resource-search, #resource-suggestions-container").length) {
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
    const noResultsQuerySpan = document.getElementById("search-query-no-results");

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
                    $suggestionsContainer.empty();
                    window.location.href = `/resources/search?query=${encodeURIComponent(subjectName)}`;
                  });
                });
              } else {
                $suggestionsContainer.html("<p>No suggestions found</p>");
              }
            })
            .fail(() => {
              $suggestionsContainer.html("<p>Error fetching suggestions</p>");
            });
        } else {
          // If the search input is cleared, perform a normal request to display all subjects.
          $suggestionsContainer.empty();
          window.location.href = "/resources/search";
        }
      });
    }
  });

  /**
   * Handle form submission for Home/Header search inputs.
   * If no query is provided, a full page reload is triggered.
   */
  if ($("#home-search").length || $("#header-search").length) {
    $("#search-form").on("submit", function (e) {
      e.preventDefault();
      const query = $("#home-search").val().trim() || $("#header-search").val().trim();
      if (query) {
        window.location.href = `/resources/search?query=${encodeURIComponent(query)}`;
      } else {
        window.location.href = "/resources/search";
      }
    });
  }
});
