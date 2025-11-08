import $ from 'jquery'

window.$ = $
window.jQuery = $;

// rider-dashboard.js

// ================================
// 🌍 MAP INITIALIZATION
// ================================

// Initialize map (default view: Manila)
let map = L.map('map').setView([14.5995, 120.9842], 13);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Add geocoder/search control
L.Control.geocoder({
  defaultMarkGeocode: true
}).addTo(map);

// ================================
// 📍 Current Location
// ================================
let currentMarker;
let currentLocation = null; // store lat/lng for route starting point
let routeLayer = null;      // only declared once ✅

// Try to get current location
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(
    function (position) {
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;

      currentLocation = [lat, lng];
      map.setView(currentLocation, 15);

      currentMarker = L.marker(currentLocation).addTo(map)
        .bindPopup("You are here")
        .openPopup();

      // Reverse Geocode for Barangay, Municipality, City
      fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
          if (data && data.address) {
            const addr = data.address;
            const barangay = addr.suburb || addr.village || addr.hamlet || addr.neighbourhood;
            const municipality = addr.town || addr.municipality || addr.city_district;
            const city = addr.city;

            const cleanLocation = [barangay, municipality, city].filter(Boolean).join(', ');
            document.getElementById('fromPlace').value = cleanLocation;
            currentMarker.bindPopup(cleanLocation || "You are here").openPopup();
          } else {
            document.getElementById('fromPlace').value = `${lat}, ${lng}`;
          }
        })
        .catch(() => {
          document.getElementById('fromPlace').value = `${lat}, ${lng}`;
        });
    },
    function () {
      alert("Geolocation failed. Showing default location.");
    }
  );
} else {
  alert("Geolocation is not supported by your browser.");
}

// ================================
// 🚚 ROUTE CALCULATION (auto start at current location)
// ================================
window.calculateRoute = function () {
  const to = document.getElementById('toPlace').value;

  if (!currentLocation) {
    alert("Current location not available yet. Please allow location access.");
    return;
  }

  if (!to) {
    alert("Please enter a destination.");
    return;
  }

  const loading = document.getElementById('loadingSpinner');
  const routeInfo = document.getElementById('routeInfo');
  loading.style.display = 'block';
  routeInfo.classList.add('d-none');
  routeInfo.innerHTML = '';

  // Fetch destination coordinates
  fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(to)}`)
    .then(res => res.json())
    .then(result => {
      if (!result || result.length === 0) {
        alert("Could not find destination.");
        loading.style.display = 'none';
        return;
      }

      const toCoords = [result[0].lat, result[0].lon];

      if (routeLayer) map.removeControl(routeLayer);

      routeLayer = L.Routing.control({
        waypoints: [
          L.latLng(currentLocation[0], currentLocation[1]), // 🟢 Start at rider's location
          L.latLng(toCoords[0], toCoords[1])               // 🔴 End at destination
        ],
        routeWhileDragging: false,
        showAlternatives: false,
        createMarker: function (i, wp) {
          return L.marker(wp.latLng);
        },
        addWaypoints: false // 🚫 Removes “Start / Via / End” inputs
      }).addTo(map);

      loading.style.display = 'none';
      routeInfo.classList.remove('d-none');
      routeInfo.innerHTML = `<strong>Route set from:</strong> Your current location <br><strong>to:</strong> ${to}`;
    })
    .catch(err => {
      console.error(err);
      alert("Error calculating route.");
      loading.style.display = 'none';
    });
};


document.addEventListener('click', function (e) {
  const btn = e.target.closest('.logout-btn');
  if (!btn) return;

  e.preventDefault();
  const form = btn.closest('form');
  if (!form) return;

  Swal.fire({
    title: "Are you sure?",
    text: "You will be logged out of your account.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, log out",
    background: "#111",
    color: "#fff",
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });
});






// 🌗 Theme Toggle Script
document.addEventListener("DOMContentLoaded", function() {
  const body = document.body;
  const toggleBtn = document.getElementById("themeToggle");
  const icon = document.getElementById("themeIcon");

  // Load saved theme from localStorage
  const savedTheme = localStorage.getItem("theme") || "light";
  body.classList.add(`${savedTheme}-mode`);
  icon.className = savedTheme === "dark" ? "bi bi-moon-fill" : "bi bi-sun-fill";

  // Toggle theme on click
  toggleBtn.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    body.classList.toggle("light-mode");

    const newTheme = body.classList.contains("dark-mode") ? "dark" : "light";
    icon.className = newTheme === "dark" ? "bi bi-moon-fill" : "bi bi-sun-fill";
    localStorage.setItem("theme", newTheme);
  });

  // ===== Get DOM Elements =====
const fullscreenBtn = document.getElementById("fullscreenBtn");
const mapContainer = document.getElementById("map"); // 🔹 Ito ang mismong mapa mo
const mainContainer = document.getElementById("mainContainer"); // optional kung meron


  // ===== Fullscreen Toggle =====
  if (fullscreenBtn && mapContainer) {
    fullscreenBtn.addEventListener("click", () => {
      mapContainer.classList.toggle("fullscreen-map");

      // If may main content, itago ito sa fullscreen
      if (mainContainer) {
        mainContainer.classList.toggle("d-none");
      }

      // Palitan icon kapag fullscreen / exit
      const iconEl = fullscreenBtn.querySelector("i");
      if (mapContainer.classList.contains("fullscreen-map")) {
        iconEl.classList.remove("bi-arrows-fullscreen");
        iconEl.classList.add("bi-fullscreen-exit");
      } else {
        iconEl.classList.remove("bi-fullscreen-exit");
        iconEl.classList.add("bi-arrows-fullscreen");
      }

      // Fix map rendering glitch
      setTimeout(() => {
        map.invalidateSize();
      }, 300);
    });
  }
});
  // Activate corresponding tab-pane when clicking bottom nav buttons
  document.querySelectorAll('.mobile-bottom-nav .nav-btn').forEach(button => {
    button.addEventListener('click', function () {
      // Remove active state from all buttons
      document.querySelectorAll('.mobile-bottom-nav .nav-btn').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');

      // Get target tab ID
      const targetId = this.getAttribute('data-bs-target');
      const targetTab = document.querySelector(targetId);

      if (targetTab) {
        // Hide all tab-panes
        document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('show', 'active'));

        // Show the selected one
        targetTab.classList.add('show', 'active');
      }

      // Optional smooth scroll to top (for mobile UX)
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });




