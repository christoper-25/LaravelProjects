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

// Marker for rider's current location
let currentMarker;

// Try to get current location
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            map.setView([lat, lng], 15);

            currentMarker = L.marker([lat, lng]).addTo(map)
                .bindPopup("You are here")
                .openPopup();

            // Optional: pre-fill "From" input with current location coordinates
            document.getElementById('fromPlace').value = `${lat}, ${lng}`;
        },
        function () {
            alert("Geolocation failed. Showing default location.");
        }
    );
} else {
    alert("Geolocation is not supported by your browser.");
}

// ================================
// 🚚 ROUTE CALCULATION
// ================================
let routeLayer;

window.calculateRoute = function () {
    const from = document.getElementById('fromPlace').value;
    const to = document.getElementById('toPlace').value;

    if (!from || !to) {
        alert("Please enter both starting point and destination.");
        return;
    }

    const loading = document.getElementById('loadingSpinner');
    loading.style.display = 'block';

    const routeInfo = document.getElementById('routeInfo');
    routeInfo.classList.add('d-none');
    routeInfo.innerHTML = '';

    const fetchFrom = isNaN(from.split(",")[0])
        ? fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(from)}`).then(res => res.json())
        : Promise.resolve([{ lat: from.split(",")[0], lon: from.split(",")[1] }]);

    const fetchTo = isNaN(to.split(",")[0])
        ? fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(to)}`).then(res => res.json())
        : Promise.resolve([{ lat: to.split(",")[0], lon: to.split(",")[1] }]);

    Promise.all([fetchFrom, fetchTo]).then(results => {
        const fromCoords = results[0][0] ? [results[0][0].lat, results[0][0].lon] : null;
        const toCoords = results[1][0] ? [results[1][0].lat, results[1][0].lon] : null;

        if (!fromCoords || !toCoords) {
            alert("Could not find one or both locations.");
            loading.style.display = 'none';
            return;
        }

        if (routeLayer) {
            map.removeControl(routeLayer);
        }

        routeLayer = L.Routing.control({
            waypoints: [
                L.latLng(fromCoords[0], fromCoords[1]),
                L.latLng(toCoords[0], toCoords[1])
            ],
            routeWhileDragging: true,
            showAlternatives: false,
            createMarker: function (i, wp) {
                return L.marker(wp.latLng);
            }
        }).addTo(map);

        loading.style.display = 'none';
        routeInfo.classList.remove('d-none');
        routeInfo.innerHTML = `<strong>Route set from:</strong> ${from} <br><strong>to:</strong> ${to}`;
    }).catch(err => {
        console.error(err);
        alert("Error calculating route.");
        loading.style.display = 'none';
    });
};

// ================================
// 🔒 LOGOUT CONFIRMATION
// ================================
document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
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
                    logoutForm.submit();
                }
            });
        });
    }
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
});
