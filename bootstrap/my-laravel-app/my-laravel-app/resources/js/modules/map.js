// resources/js/modules/map.js
import L from "leaflet";
import "leaflet-routing-machine";
import "leaflet-control-geocoder";

// Export variables for use in other modules
export let map;
export let currentLocation = null;
export let currentMarker = null;
export let routeLayer = null;

/**
 * Initialize the map and set up current location marker
 */
export function initMap() {
  // Initialize map (default: Manila)
  map = L.map("map").setView([14.5995, 120.9842], 13);

  // Add OpenStreetMap tiles
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
  }).addTo(map);

  // Add geocoder/search control
  L.Control.geocoder({ defaultMarkGeocode: true }).addTo(map);

  // Get current location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      position => {
        const { latitude: lat, longitude: lng } = position.coords;
        currentLocation = [lat, lng];

        map.setView(currentLocation, 15);

        // Marker for current location
        currentMarker = L.marker(currentLocation)
          .addTo(map)
          .bindPopup("You are here")
          .openPopup();

        // Reverse geocode to display location in "From" input
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
          .then(res => res.json())
          .then(data => {
            if (data?.address) {
              const addr = data.address;
              const barangay = addr.suburb || addr.village || addr.hamlet || addr.neighbourhood;
              const municipality = addr.town || addr.municipality || addr.city_district;
              const city = addr.city;

              const clean = [barangay, municipality, city].filter(Boolean).join(", ");
              const fromInput = document.getElementById("fromPlace");
              if (fromInput) {
                fromInput.value = clean;
                currentMarker.bindPopup(clean).openPopup();
              }
            }
          })
          .catch(() => {
            document.getElementById("fromPlace").value = `${lat}, ${lng}`;
          });
      },
      () => {
        alert("Unable to access your location. Showing default location.");
      },
      { enableHighAccuracy: true }
    );
  } else {
    alert("Geolocation is not supported by your browser.");
  }
}

/**
 * Start live tracking the rider's location and optionally send to server
 */
export function startRiderTracking(sendToServer = false) {
  if (!navigator.geolocation) return;

  navigator.geolocation.watchPosition(position => {
    const { latitude: lat, longitude: lng } = position.coords;
    currentLocation = [lat, lng];

    // Update current marker
    if (!currentMarker) {
      currentMarker = L.marker(currentLocation).addTo(map);
    } else {
      currentMarker.setLatLng(currentLocation);
    }

    map.setView(currentLocation, 15);

    if (sendToServer) {
  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

  if (csrfToken) {
    fetch("/api/rider/location", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken
      },
      body: JSON.stringify({ lat, lng })
    }).catch(err => console.error("Error sending location:", err));
  } else {
    console.warn("No CSRF token found — skipping location send");
  }
}

  }, error => {
    console.warn("Live tracking failed:", error);
  }, { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 });
}

/**
 * Set a route from current location to destination
 */
export function calculateRoute(to) {
  if (!currentLocation) {
    alert("Current location not available yet.");
    return;
  }
  if (!to) {
    alert("Please enter a destination.");
    return;
  }

  const loading = document.getElementById("loadingSpinner");
  const routeInfo = document.getElementById("routeInfo");

  if (loading) loading.style.display = "block";
  if (routeInfo) {
    routeInfo.classList.add("d-none");
    routeInfo.innerHTML = "";
  }

  // Fetch destination coordinates
  fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(to)}`)
    .then(res => res.json())
    .then(result => {
      if (!result?.length) {
        alert("Destination not found.");
        if (loading) loading.style.display = "none";
        return;
      }

      const toCoords = [result[0].lat, result[0].lon];

      // Remove previous route if exists
      if (routeLayer) routeLayer.remove();

      routeLayer = L.Routing.control({
        waypoints: [
          L.latLng(currentLocation[0], currentLocation[1]),
          L.latLng(toCoords[0], toCoords[1])
        ],
        routeWhileDragging: false,
        addWaypoints: false,
        createMarker: (i, wp) => L.marker(wp.latLng)
      }).addTo(map);

      if (loading) loading.style.display = "none";
      if (routeInfo) {
        routeInfo.classList.remove("d-none");
        routeInfo.innerHTML = `<strong>Route set from:</strong> current location<br><strong>to:</strong> ${to}`;
      }
    })
    .catch(err => {
      console.error(err);
      alert("Error calculating route.");
      if (loading) loading.style.display = "none";
    });

    
}
