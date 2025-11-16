// resources/js/rider-dashboard.js
import $ from "jquery";
window.$ = window.jQuery = $;

// Import map functions and modules
import { initMap, startRiderTracking, calculateRoute } from "./modules/map.js";
import "./modules/logout.js";
import "./modules/theme.js";
import "./modules/fullscreen.js";
import "./modules/tabs.js";

// Initialize map
initMap();

// Start live tracking (set to false if you don’t want auto sending to server yet)
startRiderTracking(false);

// Route calculation
document.getElementById("calculateRouteBtn")?.addEventListener("click", () => {
    const from = document.getElementById("fromPlace").value || null;
    const to = document.getElementById("toPlace").value;
    calculateRoute(to, from); // from is optional
});

document.addEventListener('DOMContentLoaded', function () {
    const toInput = document.getElementById('toPlace');
    const calculateBtn = document.getElementById('calculateRouteBtn');

    document.querySelectorAll('.set-destination-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const address = this.dataset.address;

            if (toInput) {
                toInput.value = address; // Fill "To:" field
            }

            // Optional: Automatically calculate route
            if (calculateBtn) {
                calculateBtn.click();
            }

            // Optional: Switch to dashboard tab to see map
            const dashboardTab = document.querySelector('button[data-bs-target="#dashboard"]');
            if (dashboardTab) dashboardTab.click();
        });
    });
});

