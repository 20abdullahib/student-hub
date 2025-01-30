$(document).ready(function() {
    const searchInput = $('#resource-search');
    const searchSuggestionsContainer = $('#resource-suggestions-container');
    const liveSearchResultsContainer = $('#results-container'); // Unified container for results
    const filterForm = $('#filter-form');

    // Function to handle live search results
    function fetchLiveSearchResults(query) {
        $.ajax({
            url: '/resources/search',  // Adjust route if needed
            type: 'GET',
            data: { query: query },
            success: function(data) {
                displaySearchResults(data);  // Display the results
            },
            error: function() {
                liveSearchResultsContainer.html('<p>Error fetching search results</p>');
            }
        });
    }

    // Function to handle live filter results
    function fetchFilteredResults() {
        const department = $('#department-filter').val();
        const branch = $('#branch-filter').val();
        const sort = $('#sort-filter').val();

        $.ajax({
            url: '/resources/filter',  // Adjust route for filtering
            type: 'GET',
            data: {
                department: department,
                branch: branch,
                sort: sort
            },
            success: function(data) {
                displaySearchResults(data);  // Display the results
            },
            error: function() {
                liveSearchResultsContainer.html('<p>Error fetching filtered results</p>');
            }
        });
    }

    // Unified function to display results in the container
    function displaySearchResults(data) {
        liveSearchResultsContainer.empty(); // Clear previous results
        if (data.length > 0) {
            data.forEach(subject => {
                const resultItem = `<div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title">${subject.title}</h5>
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
                                </div>`;
                liveSearchResultsContainer.append(resultItem);
            });
        } else {
            liveSearchResultsContainer.html('<p>No results found</p>');
        }
    }

    // Function to fetch suggestions as user types
    function fetchSearchSuggestions(query) {
        $.ajax({
            url: '/resources/suggestions',
            type: 'GET',
            data: { query: query },
            success: function(data) {
                searchSuggestionsContainer.empty(); // Clear previous suggestions
                if (data.length > 0) {
                    data.forEach(subject => {
                        const suggestionItem = $('<div class="suggestion-item p-2 bg-light border"></div>');
                        suggestionItem.html(`<strong>${subject.title}</strong> (${subject.code})`);
                        searchSuggestionsContainer.append(suggestionItem);

                        // Handle click on suggestion
                        suggestionItem.on('click', function() {
                            // Put clicked suggestion into the input field
                            searchInput.val(subject.title);
                            // Hide the suggestions container
                            searchSuggestionsContainer.empty();
                            // Trigger live search with the selected suggestion
                            fetchLiveSearchResults(subject.title);
                        });
                    });
                } else {
                    searchSuggestionsContainer.html('<p>No suggestions found</p>');
                }
            },
            error: function() {
                searchSuggestionsContainer.html('<p>Error fetching suggestions</p>');
            }
        });
    }

    // Handle live search input
    searchInput.on('input', function() {
        const query = searchInput.val();
        if (query.length > 0) {
            fetchSearchSuggestions(query);  // Fetch suggestions
            fetchLiveSearchResults(query);  // Fetch live search results
        } else {
            searchSuggestionsContainer.empty();  // Clear suggestions
            liveSearchResultsContainer.empty();  // Clear results
        }
    });

    // Handle form submission (in case user submits via button)
    $('#search-form').on('submit', function() {
        const query = searchInput.val();
        if (query) {
            fetchLiveSearchResults(query);
        }
    });

    // Handle live filter changes
    $('#department-filter, #branch-filter, #sort-filter').on('change', function() {
        fetchFilteredResults();  // Trigger live filter whenever a filter option changes
    });

       // Hide suggestions when clicking outside of the input or suggestion containers
       $(document).on('click', function(event) {
        if (!$(event.target).closest('#resource-search, #resource-suggestions-container, #home-search, #home-suggestions-container').length) {
            hideSuggestions();  // Clear suggestions if click is outside
        }
    });

});
// Function to hide suggestions for both resource and home suggestions
function hideSuggestions() {
    $('#resource-suggestions-container').empty();
    $('#home-suggestions-container').empty();
}



document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('resource-search');
    const searchHeader = document.getElementById('search-header');
    const searchQuerySpan = document.getElementById('search-query');
    const noResultsQuerySpan = document.getElementById('search-query-no-results');

    // Update the URL and search query text as the user types
    searchInput.addEventListener('input', function() {
        const query = searchInput.value;
        const newUrl = new URL(window.location);

        if (query) {
            // Update the URL to include ?query= if there is a query
            newUrl.searchParams.set('query', query);
            window.history.replaceState(null, '', newUrl);

            // Update the search query text in the header
            searchHeader.style.display = "block";
            searchQuerySpan.textContent = query;
            noResultsQuerySpan.textContent = query;
        } else {
            // Remove the query parameter from the URL if the input is empty
            newUrl.searchParams.delete('query');
            window.history.replaceState(null, '', newUrl);

            // Hide the search header if the query is empty
            searchHeader.style.display = "none";
        }
    });
});
