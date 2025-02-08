// ============================================
// Activate Current Page Link
// ============================================
document.addEventListener("DOMContentLoaded", () => {
    const currentPath = window.location.pathname;
    const pageLinks = [
      { path: /^\/$/, id: "home-link" },
      { path: /^\/about-teem/, id: "about-link" },
      { path: /^\/contact-us/, id: "contact-link" },
      { path: /^\/resources/, id: "resources-link" }
    ];
  
    // Remove "active" from all navigation links.
    document.querySelectorAll(".nav li").forEach(link => link.classList.remove("active"));
  
    // Activate any link whose regex matches the current path.
    pageLinks.forEach(link => {
      if (link.path.test(currentPath)) {
        const activeEl = document.getElementById(link.id);
        if (activeEl) activeEl.classList.add("active");
      }
    });
  });
  
  // ============================================
  // Modal Handling
  // ============================================
  const openModal = () => {
    const modal = document.getElementById("myModal");
    if (modal) {
      modal.style.display = "block";
    }
  };
  
  const closeModal = () => {
    const modal = document.getElementById("myModal");
    if (modal) {
      modal.style.display = "none";
    }
  };
  
  // ============================================
  // Random Animation for Marquee Elements
  // ============================================
  document.addEventListener("DOMContentLoaded", () => {
    const marqueeElements = document.querySelectorAll(".animate-marquee");
    const baseDuration = 30; // in seconds
    const constantDifference = 5; // seconds difference between each (resets after 5 elements)
  
    marqueeElements.forEach((element, index) => {
      const duration = baseDuration + ((index % 5) * constantDifference);
      element.style.animationDuration = `${duration}s`;
    });
  });
  
  // ============================================
  // Search Suggestions (jQuery)
  // ============================================
  $(document).ready(() => {
    // Define input IDs and their associated suggestion container selectors.
    const searchInputs = ["home-search", "header-search"];
    const suggestionContainers = {
      "home-search": "#home-suggestions-container",
      "header-search": "#header-suggestions-container"
    };
  
    // Loop over each search input to add the live search functionality.
    searchInputs.forEach(inputId => {
      const $searchInput = $(`#${inputId}`);
      const $suggestionsContainer = $(suggestionContainers[inputId]);
  
      $searchInput.on("input", () => {
        const query = $searchInput.val();
  
        if (query.length >= 1) {
          $.ajax({
            url: "/resources/suggestions",
            type: "GET",
            data: { query },
            success: data => {
              $suggestionsContainer.empty();
  
              if (data.length > 0) {
                data.forEach(subject => {
                  // Build suggestion item with subject name and code.
                  const $suggestionItem = $(
                    `<div class="suggestion-item p-2 bg-light border">
                        <strong>${subject.name}</strong> (${subject.code})
                     </div>`
                  );
                  $suggestionsContainer.append($suggestionItem);
  
                  // When the suggestion is clicked, redirect to search results.
                  $suggestionItem.on("click", () => {
                    window.location.href = `/resources/search?query=${encodeURIComponent(subject.name)}`;
                  });
                });
              } else {
                $suggestionsContainer.html("<p>No suggestions found</p>");
              }
            },
            error: () => {
              $suggestionsContainer.html("<p>Error fetching suggestions</p>");
            }
          });
        } else {
          $suggestionsContainer.empty();
        }
      });
    });
  
    // Handle search form submission.
    $("#search-form").on("submit", e => {
      e.preventDefault();
      // Using the first input value as an example.
      const query = $(`#${searchInputs[0]}`).val();
      if (query) {
        window.location.href = `/resources/search?query=${encodeURIComponent(query)}`;
      }
    });
  });
  
  // ============================================
  // Typed.js Initialization for Homepage
  // ============================================
  const typed = new Typed("#typed-strings", {
    strings: ["Support You", "Shorten Time", "Gain Skills", "Have Fun"],
    typeSpeed: 100,   // milliseconds per character
    backSpeed: 50,    // milliseconds per character
    backDelay: 1000,  // delay before backspacing
    startDelay: 500,  // delay before typing starts
    loop: true,       // loop typing effect
    showCursor: true  // display the blinking cursor
  });
  