import "./bootstrap";
import { toggleLike } from "./ajax";

window.toggleLike = toggleLike;

// Toggle navigation menu on mobile
document.getElementById("headerToggler").addEventListener("click", function () {
    document.getElementById("headerMenu").classList.toggle("active");
});

// Toggle dropdown menu
document.getElementById("userDropdown").addEventListener("click", function (e) {
    e.stopPropagation();
    document.getElementById("userDropdownMenu").classList.toggle("show");
});

// Close dropdown when clicking outside
document.addEventListener("click", function () {
    const dropdownMenu = document.getElementById("userDropdownMenu");
    if (dropdownMenu.classList.contains("show")) {
        dropdownMenu.classList.remove("show");
    }
});
