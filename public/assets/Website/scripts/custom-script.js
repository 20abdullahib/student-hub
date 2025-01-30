// // Function to activate the current page link
// document.addEventListener("DOMContentLoaded", function () {
//     // Get the current path of the page (e.g., /home, /contact-us)
//     var currentPath = window.location.pathname;

//     // Map each path to the corresponding link element's ID
//     var pageLinks = {
//         "/": "home-link",
//         "/about-teem": "about-link",
//         "/contact-us": "contact-link",
//     };

//     // Find the matching link based on the current path
//     var activeLinkId = pageLinks[currentPath];

//     // Remove the active class from all navigation links
//     var navLinks = document.querySelectorAll(".nav li");
//     navLinks.forEach(function (link) {
//         link.classList.remove("active");
//     });

//     // Add the active class to the current page's link if it exists
//     if (activeLinkId) {
//         document.getElementById(activeLinkId).classList.add("active");
//     }
// });

// Function to activate the current page link
document.addEventListener("DOMContentLoaded", function () {
    // Get the current path of the page (e.g., /home, /contact-us/1)
    var currentPath = window.location.pathname;

    // Map each base path to the corresponding link element's ID
    var pageLinks = [
        { path: /^\/$/, id: "home-link" }, // Home page
        { path: /^\/about-teem/, id: "about-link" }, // About Team
        { path: /^\/contact-us/, id: "contact-link" }, // Contact Us
        { path: /^\/resources/, id: "resources-link" }, // Contact Us
    ];

    // Remove the active class from all navigation links
    var navLinks = document.querySelectorAll(".nav li");
    navLinks.forEach(function (link) {
        link.classList.remove("active");
    });

    // Loop through the pageLinks array and check for matching path using regex
    pageLinks.forEach(function (link) {
        if (link.path.test(currentPath)) {
            // Add the active class to the matched link
            document.getElementById(link.id).classList.add("active");
        }
    });
});

//  JavaScript to handle modal
function openModal() {
    document.getElementById("myModal").style.display = "block";
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// random Animation
// document.addEventListener('DOMContentLoaded', () => {
//     const marqueeElements = document.querySelectorAll('.animate-marquee');

//     marqueeElements.forEach((element) => {
//         const randomDuration = Math.random() * (150 - 50) + 50; // Generate random duration between 50s and 150s
//         // const randomDuration = Math.random() * (50 - 40) + 40; // Generate random duration between 40s and 50s
//         element.style.animationDuration = `${randomDuration}s`;
//     });
// });

/*
document.addEventListener('DOMContentLoaded', () => {
    const marqueeElements = document.querySelectorAll('.animate-marquee');

    let baseDuration = 50; // Base duration (in seconds)

    marqueeElements.forEach((element, index) => {
        // Generate random difference within 20s range for each element
        const randomDifference = Math.random() * 20; // Random value between 0 and 20 seconds
        const duration = baseDuration + randomDifference + (index * 10); // Slight increase per element

        element.style.animationDuration = `${duration}s`; // Set animation duration
    });
});
*/

document.addEventListener('DOMContentLoaded', () => {
    const marqueeElements = document.querySelectorAll('.animate-marquee');

    let baseDuration = 30; // Base duration (in seconds)
    let constantDifference = 5; // Constant difference between elements

    marqueeElements.forEach((element, index) => {
        const duration = baseDuration + ((index % 5) * constantDifference); // Reset after every 5 elements

        element.style.animationDuration = `${duration}s`; // Set animation duration
    });
});

// JavaScript to handle the search suggestions
// $(document).ready(function() {
//     const searchInput = $('#search');
//     const searchSuggestions = $('#search-suggestions');

//     // Fetch suggestions when the user types in the input
//     searchInput.on('input', function() {
//         const query = searchInput.val();

//         if (query.length > 0) { // Only search if input is longer than 2 characters
//             $.ajax({
//                 url: '/resources/suggestions',
//                 type: 'GET',
//                 data: { query: query },
//                 success: function(data) {
//                     searchSuggestions.empty(); // Clear previous suggestions
//                     if (data.length > 0) {
//                         data.forEach(subject => {
//                             const suggestionItem = $('<div class="suggestion-item p-2 bg-light border"></div>');
//                             suggestionItem.html(`<strong>${subject.title}</strong> (${subject.code})`);
//                             searchSuggestions.append(suggestionItem);

//                             // Redirect to search results when clicked
//                             suggestionItem.on('click', function() {
//                                 // Redirect to the search results page with the clicked subject's title
//                                 window.location.href = `/resources/search?query=${encodeURIComponent(subject.title)}`;
//                             });
//                         });
//                     } else {
//                         searchSuggestions.html('<p>No suggestions found</p>');
//                     }
//                 },
//                 error: function() {
//                     searchSuggestions.html('<p>Error fetching suggestions</p>');
//                 }
//             });
//         } else {
//             searchSuggestions.empty(); // Clear suggestions if input is too short
//         }
//     });

//     // Handle form submission
//     $('#search-form').on('submit', function() {
//         const query = searchInput.val();
//         if (query) {
//             window.location.href = `/resources/search?query=${encodeURIComponent(query)}`;
//         }
//     });
// });

$(document).ready(function() {
    // Array of input IDs you want to apply the live search to
    const searchInputs = ['home-search', 'header-search'
        // ,'resource-search'
    ]; // Add as many input IDs as needed
    const suggestionContainers = {
        'home-search': '#home-suggestions-container',      // Associated suggestion container for 'search' input
        'header-search': '#header-suggestions-container',
        // 'resource-search':'#resource-suggestions-container' // Associated suggestion container for 'new-search' input
        // Add more key-value pairs for additional inputs and their suggestion containers
    };

    searchInputs.forEach(inputId => {
        const searchInput = $(`#${inputId}`); // Select input by its ID
        const searchSuggestions = $(suggestionContainers[inputId]); // Get the corresponding suggestion container

        // Fetch suggestions when the user types in the input
        searchInput.on('input', function() {
            const query = searchInput.val();

            if (query.length >= 1) { // Only search if input is not empty
                $.ajax({
                    url: '/resources/suggestions',
                    type: 'GET',
                    data: { query: query },
                    success: function(data) {
                        searchSuggestions.empty(); // Clear previous suggestions
                        if (data.length > 0) {
                            data.forEach(subject => {
                                const suggestionItem = $('<div class="suggestion-item p-2 bg-light border"></div>');
                                suggestionItem.html(`<strong>${subject.title}</strong> (${subject.code})`);
                                searchSuggestions.append(suggestionItem);

                                // Redirect to search results when clicked
                                suggestionItem.on('click', function() {
                                    window.location.href = `/resources/search?query=${encodeURIComponent(subject.title)}`;
                                });
                            });
                        } else {
                            searchSuggestions.html('<p>No suggestions found</p>');
                        }
                    },
                    error: function() {
                        searchSuggestions.html('<p>Error fetching suggestions</p>');
                    }
                });
            } else {
                searchSuggestions.empty(); // Clear suggestions if input is empty
            }
        });
    });

    // Handle form submission for all search forms
    $('#search-form').on('submit', function() {
        const query = $(`#${searchInputs[0]}`).val(); // Example for first input
        if (query) {
            window.location.href = `/resources/search?query=${encodeURIComponent(query)}`;
        }
    });
});






// // JavaScript to handle the search results 

// $(document).ready(function() {
//     const searchInput = $('#resource-search');
//     const searchSuggestionsContainer = $('#resource-suggestions-container');
//     const liveSearchResultsContainer = $('#live-search-results');

//     // Function to fetch and display live search results
//     function liveSearchResults(query) {
//         $.ajax({
//             url: '/resources/search',  // Reuse your search route here for live search results
//             type: 'GET',
//             data: { query: query },
//             success: function(data) {
//                 liveSearchResultsContainer.empty(); // Clear previous search results
//                 if (data.length > 0) {
//                     data.forEach(subject => {
//                         const resultItem = $('<div class="result-item p-2 border-bottom"></div>');
//                         resultItem.html(`<strong>${subject.title}</strong> (${subject.code})`);
//                         liveSearchResultsContainer.append(resultItem);
//                     });
//                 } else {
//                     liveSearchResultsContainer.html('<p>No matching results found</p>');
//                 }
//             },
//             error: function() {
//                 liveSearchResultsContainer.html('<p>Error fetching search results</p>');
//             }
//         });
//     }

//     // Fetch suggestions and live search results as the user types
//     searchInput.on('input', function() {
//         const query = searchInput.val();

//         if (query.length > 0) {
//             // Fetch suggestions
//             $.ajax({
//                 url: '/resources/suggestions',  // Route to get suggestions
//                 type: 'GET',
//                 data: { query: query },
//                 success: function(data) {
//                     searchSuggestionsContainer.empty(); // Clear previous suggestions
//                     if (data.length > 0) {
//                         data.forEach(subject => {
//                             const suggestionItem = $('<div class="suggestion-item p-2 bg-light border"></div>');
//                             suggestionItem.html(`<strong>${subject.title}</strong> (${subject.code})`);
//                             searchSuggestionsContainer.append(suggestionItem);

//                             // Handle click on suggestion
//                             suggestionItem.on('click', function() {
//                                 // Put clicked suggestion into the input field
//                                 searchInput.val(subject.title);

//                                 // Hide the suggestions container
//                                 searchSuggestionsContainer.empty();

//                                 // Trigger live search with the selected suggestion
//                                 liveSearchResults(subject.title);
//                             });
//                         });
//                     } else {
//                         searchSuggestionsContainer.html('<p>No suggestions found</p>');
//                     }
//                 },
//                 error: function() {
//                     searchSuggestionsContainer.html('<p>Error fetching suggestions</p>');
//                 }
//             });

//             // Trigger live search results as the user types
//             liveSearchResults(query);

//         } else {
//             // Clear suggestions and search results if input is empty
//             searchSuggestionsContainer.empty();
//             liveSearchResultsContainer.empty();
//         }
//     });

//     // Handle form submission (in case the user submits the form instead of clicking)
//     $('#search-form').on('submit', function() {
//         const query = searchInput.val();
//         if (query) {
//             liveSearchResults(query);
//         }
//     });
// });


// // JavaScript to handle the live filter

// $(document).ready(function() {
//     // Trigger live filter whenever a filter option changes
//     $('#department-filter, #branch-filter, #sort-filter').on('change', function() {
//         filterData();
//     });

//     function filterData() {
//         const department = $('#department-filter').val();
//         const branch = $('#branch-filter').val();
//         const sort = $('#sort-filter').val();

//         $.ajax({
//             url: '/resources/filter', // You need to define this route in your Laravel app
//             type: 'GET',
//             data: {
//                 department: department,
//                 branch: branch,
//                 sort: sort
//             },
//             success: function(data) {
//                 $('#results-container').html(''); // Clear previous results

//                 if (data.length > 0) {
//                     data.forEach(item => {
//                         const resultItem = `<div class="result-item p-3 mb-3 border">
//                                                 <h5>${item.title}</h5>
//                                                 <p>${item.description}</p>
//                                             </div>`;
//                         $('#results-container').append(resultItem);
//                     });
//                 } else {
//                     $('#results-container').html('<p>No results found</p>');
//                 }
//             },
//             error: function() {
//                 $('#results-container').html('<p>Error fetching data</p>');
//             }
//         });
//     }
// });





// typed.js for the homepage
var typed = new Typed("#typed-strings", {
    strings: ["Support You", "Shorten Time", "Gain Skills", "Have Fun"], // List of words to type
    typeSpeed: 100, // Typing speed in milliseconds
    backSpeed: 50, // Backspacing speed in milliseconds
    backDelay: 1000, // Delay between typing a word and backspacing
    startDelay: 500, // Delay before typing starts
    loop: true, // Loop the typing effect
    showCursor: true, // Show the cursor after the typed text
});

