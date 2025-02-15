/**
 * handle-search-request.js
 * -------------------------
 * This file handles AJAX requests and live search functionality for:
 *   • The Resource Search page.
 *   • Home and Header search suggestions.
 *
 * Features include:
 *   - Displaying search results (with pagination) using your existing card design.
 *   - Live search (with debouncing) and filtered results.
 *   - Fetching search suggestions for both resource search and home/header search.
 *   - Updating URL query parameters and search header text.
 *
 * When a suggestion item is clicked the search input is filled with the suggestion text and
 * a search is performed (or the user is redirected to the search results page).
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
    const $staticResources = $("#static-resources");
    let debounceTimer;
  
    // Clear search icon functionality (if it exists)
    const clearIcon = document.getElementById("clear-resource-search");
    if (clearIcon) {
      clearIcon.addEventListener("click", () => {
        displaySearchResults([]);
      });
    }
  
    /**
     * Display search results with pagination.
     * Expects a response with { data: [subject objects], pagination: "<HTML>" }.
     * Uses your card design for each result.
     */
    const displaySearchResults = (response) => {
      const data = response.data;
      const pagination = response.pagination;
      $resultsContainer.empty();
      $pageinitContainer.empty();
  
      if (data && data.length > 0) {
        // Hide static (fixed) resources when dynamic results are available.
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
        // No dynamic results – reveal static resources.
        $staticResources.show();
      }
    };
  
    /**
     * AJAX helper: Fetch search suggestions.
     * This function is used by both resource search and home/header searches.
     * @param {string} query - The search term.
     * @returns {JQuery.jqXHR} jQuery AJAX promise.
     */
    function getSearchSuggestions(query) {
      return $.ajax({
        url: "/resources/search",
        type: "GET",
        data: { query },
        dataType: "json",
      });
    }
    // Expose globally so the home/header suggestions can use it.
    window.getSearchSuggestions = getSearchSuggestions;
  
    /**
     * Fetch live search results and display them.
     * @param {string} query - The search term.
     * @param {string} [url="/resources/search"] - Optional URL (for pagination).
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
     * Fetch filtered results based on selected filter options.
     */
    const fetchFilteredResults = () => {
      const department = $departmentFilter.val();
      const branch = $branchFilter.val();
      const sort = $sortFilter.val();
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
  
              // When clicked: fill the input with the suggestion and perform a live search.
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
  
    // Resource search input: Live search with debouncing.
    $resourceSearch.on("input", function () {
      clearTimeout(debounceTimer);
      const query = $(this).val().trim();
      debounceTimer = setTimeout(() => {
        if (query) {
          fetchResourceSearchSuggestions(query);
          fetchLiveSearchResults(query);
        } else {
          $resourceSuggestionsContainer.empty();
          $resultsContainer.empty();
          $staticResources.show();
        }
      }, 300);
    });
  
    // Handle resource search form submission.
    $("#search-form").on("submit", function (e) {
      e.preventDefault();
      const query = $resourceSearch.val().trim();
      if (query) {
        fetchLiveSearchResults(query);
      }
    });
  
    // Trigger filtering when any filter option changes.
    $departmentFilter.add($branchFilter).add($sortFilter).on("change", fetchFilteredResults);
  
    // Hide suggestions when clicking outside the resource search/suggestion areas.
    $(document).on("click", function (event) {
      if (!$(event.target).closest("#resource-search, #resource-suggestions-container").length) {
        hideSuggestions();
        clearTimeout(debounceTimer);
      }
    });
  
    // Update URL query parameter and search header text as the user types in the resource search.
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
  
    // Delegate pagination link clicks (applies to search and filter results).
    $(document).on("click", ".pagination a", function (e) {
      e.preventDefault();
      const url = $(this).attr("href");
      const query = $resourceSearch.val().trim();
      if (url) {
        fetchLiveSearchResults(query, url);
      }
    });
  
    // Global function to hide all suggestion containers.
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
  
    // Loop through each search input (if present) and bind events.
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
  
                    // On click: fill the search input, clear suggestions, and perform a search.
                    $suggestionItem.on("click", () => {
                      $searchInput.val(subjectName);
                      $suggestionsContainer.empty();
                      window.location.href = `/resources/search?query=${encodeURIComponent(
                        subjectName
                      )}`;
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
            $suggestionsContainer.empty();
          }
        });
      }
    });
  
    // If either the home or header search exists, handle form submission.
    if ($("#home-search").length || $("#header-search").length) {
      $("#search-form").on("submit", function (e) {
        e.preventDefault();
        const query =
          $("#home-search").val().trim() || $("#header-search").val().trim();
        if (query) {
          window.location.href = `/resources/search?query=${encodeURIComponent(
            query
          )}`;
        }
      });
    }
  });
  