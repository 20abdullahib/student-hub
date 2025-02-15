/**
 * custom-script.js
 * ----------------
 * This file contains general front-end functionalities unrelated to AJAX requests.
 * It manages:
 *   - Navigation highlighting for the current page.
 *   - Modal display handling.
 *   - Randomized animation durations for marquee elements.
 *   - Live search suggestions for home and header search fields that redirect to search results.
 */

document.addEventListener("DOMContentLoaded", () => {
    // ============================================
    // Activate Current Page Link
    // ============================================
    const currentPath = window.location.pathname;
    const pageLinks = [
        { path: /^\/$/, id: "home-link" },
        { path: /^\/about-teem/, id: "about-link" },
        { path: /^\/contact-us/, id: "contact-link" },
        { path: /^\/resources/, id: "resources-link" },
    ];

    // Remove "active" from all navigation links.
    document
        .querySelectorAll(".nav li")
        .forEach((link) => link.classList.remove("active"));

    // Activate any link whose regex matches the current path.
    pageLinks.forEach((link) => {
        if (link.path.test(currentPath)) {
            const activeEl = document.getElementById(link.id);
            if (activeEl) activeEl.classList.add("active");
        }
    });

    // ============================================
    // Modal Handling
    // ============================================
    window.openModal = () => {
        const modal = document.getElementById("myModal");
        if (modal) {
            modal.style.display = "block";
        }
    };

    window.closeModal = () => {
        const modal = document.getElementById("myModal");
        if (modal) {
            modal.style.display = "none";
        }
    };

    // ============================================
    // Random Animation for Marquee Elements
    // ============================================
    const marqueeElements = document.querySelectorAll(".animate-marquee");
    const baseDuration = 30; // seconds
    const constantDifference = 5; // seconds difference per element

    marqueeElements.forEach((element, index) => {
        const duration = baseDuration + (index % 5) * constantDifference;
        element.style.animationDuration = `${duration}s`;
    });
});



