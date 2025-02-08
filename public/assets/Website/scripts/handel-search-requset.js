// jQuery code for live search, filtering, and suggestions
$(function () {
    // Cache frequently used selectors
    const $searchInput = $('#resource-search');
    const $suggestionsContainer = $('#resource-suggestions-container');
    const $resultsContainer = $('#results-container');
    const $filterForm = $('#filter-form');
  
    /**
     * Displays search/filter results.
     * Uses a fallback for the subject name if undefined.
     * @param {Array} data - Array of subject objects.
     */
    const displaySearchResults = (data) => {
      $resultsContainer.empty();
  
      if (data.length > 0) {
        data.forEach((subject) => {
          // Use a fallback if subject.name is undefined or falsy
          const subjectName = subject.name || 'No Name Provided';
  
          const resultItem = `
            <div class="col-md-4 mb-4">
              <div class="card h-100">
                <div class="card-header">
                  <h5 class="card-title">${subjectName}</h5>
                </div>
                <div class="card-body px-0">
                  <div class="embed-responsive embed-responsive-16by9 mb-3 px-1">
                    <i class="bi bi-folder-fill text-warning" style="font-size: 6rem; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"></i>
                  </div>
                  <div class="px-3">
                    <div class="tags-container mb-3">
                      <a href="#" class="btn btn-outline-primary btn-sm me-1 mb-1" style="pointer-events: none;">tags</a>
                    </div>
                    <button class="btn btn-primary position-relative see-details" data-storage-path="${subject.storage_path}">
                      <i class="bi bi-info-circle"></i> See Details
                    </button>
                  </div>
                </div>
              </div>
            </div>
          `;
          $resultsContainer.append(resultItem);
        });
      } else {
        $resultsContainer.html('<p>No results found</p>');
      }
    };
  
    /**
     * Fetches live search results based on the query.
     * @param {string} query - The search term.
     */
    const fetchLiveSearchResults = (query) => {
      $.ajax({
        url: '/resources/search', // Adjust route if needed
        type: 'GET',
        data: { query },
        success: displaySearchResults,
        error: () => {
          $resultsContainer.html('<p>Error fetching search results</p>');
        },
      });
    };
  
    /**
     * Fetches filtered results.
     */
    const fetchFilteredResults = () => {
      const department = $('#department-filter').val();
      const branch = $('#branch-filter').val();
      const sort = $('#sort-filter').val();
  
      $.ajax({
        url: '/resources/filter', // Adjust route if needed
        type: 'GET',
        data: { department, branch, sort },
        success: displaySearchResults,
        error: () => {
          $resultsContainer.html('<p>Error fetching filtered results</p>');
        },
      });
    };
  
    /**
     * Fetches search suggestions as the user types.
     * @param {string} query - The search term.
     */
    const fetchSearchSuggestions = (query) => {
      $.ajax({
        url: '/resources/suggestions',
        type: 'GET',
        data: { query },
        success: (data) => {
          $suggestionsContainer.empty();
  
          if (data.length > 0) {
            data.forEach((subject) => {
              // Use fallback for the subject name if undefined
              const subjectName = subject.name || 'No Name Provided';
              const $suggestionItem = $(
                '<div class="suggestion-item p-2 bg-light border"></div>'
              );
              $suggestionItem.html(`<strong>${subjectName}</strong> (${subject.code})`);
              $suggestionsContainer.append($suggestionItem);
  
              // Click on a suggestion fills the search input and triggers a search
              $suggestionItem.on('click', () => {
                $searchInput.val(subjectName);
                $suggestionsContainer.empty();
                fetchLiveSearchResults(subjectName);
              });
            });
          } else {
            $suggestionsContainer.html('<p>No suggestions found</p>');
          }
        },
        error: () => {
          $suggestionsContainer.html('<p>Error fetching suggestions</p>');
        },
      });
    };
  
    // --- Event Handlers ---
  
    // Live search: fetch suggestions and search results on input
    $searchInput.on('input', function () {
      const query = $(this).val();
      if (query.length > 0) {
        fetchSearchSuggestions(query);
        fetchLiveSearchResults(query);
      } else {
        $suggestionsContainer.empty();
        $resultsContainer.empty();
      }
    });
  
    // Handle search form submission
    $('#search-form').on('submit', function (e) {
      e.preventDefault();
      const query = $searchInput.val();
      if (query) {
        fetchLiveSearchResults(query);
      }
    });
  
    // Trigger filtering when any filter option changes
    $('#department-filter, #branch-filter, #sort-filter').on('change', () => {
      fetchFilteredResults();
    });
  
    // Hide suggestions when clicking outside the search or suggestion areas
    $(document).on('click', function (event) {
      if (
        !$(event.target).closest(
          '#resource-search, #resource-suggestions-container, #home-search, #home-suggestions-container'
        ).length
      ) {
        hideSuggestions();
      }
    });
  });
  
  /**
   * Hides search suggestions for both resource and home suggestions.
   */
  function hideSuggestions() {
    $('#resource-suggestions-container').empty();
    $('#home-suggestions-container').empty();
  }
  
  // Plain JavaScript code for updating the URL and search header based on the input
  document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('resource-search');
    const searchHeader = document.getElementById('search-header');
    const searchQuerySpan = document.getElementById('search-query');
    const noResultsQuerySpan = document.getElementById('search-query-no-results');
  
    if (!searchInput) {
      console.warn('Search input element with id "resource-search" not found.');
      return;
    }
  
    // Update URL query parameter and header text as the user types
    searchInput.addEventListener('input', () => {
      const query = searchInput.value;
      const newUrl = new URL(window.location);
  
      if (query) {
        newUrl.searchParams.set('query', query);
        window.history.replaceState(null, '', newUrl);
  
        searchHeader.style.display = 'block';
        searchQuerySpan.textContent = query;
        noResultsQuerySpan.textContent = query;
      } else {
        newUrl.searchParams.delete('query');
        window.history.replaceState(null, '', newUrl);
  
        searchHeader.style.display = 'none';
      }
    });
  });
  