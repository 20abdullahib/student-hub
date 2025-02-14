// ============================================
// Typed.js Initialization for Homepage
// ============================================
document.addEventListener("DOMContentLoaded", () => {
    if (window.location.pathname === "/") {
        if (typeof Typed !== "undefined") {
            new Typed("#typed-strings", {
                strings: ["Support You", "Shorten Time", "Gain Skills", "Have Fun"],
                typeSpeed: 100, // milliseconds per character
                backSpeed: 50, // milliseconds per character
                backDelay: 1000, // delay before backspacing
                startDelay: 500, // delay before typing starts
                loop: true, // loop typing effect
                showCursor: true, // display the blinking cursor
            });
        }
    }
});