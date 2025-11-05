document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map').setView([14.5995, 120.9842], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const riderMarker = L.marker([14.5995, 120.9842]).addTo(map)
        .bindPopup("🚴 You are here (default Manila)")
        .openPopup();

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((pos) => {
            const { latitude, longitude } = pos.coords;
            map.setView([latitude, longitude], 14);
            riderMarker.setLatLng([latitude, longitude]).bindPopup("📍 Your current location").openPopup();
        }, () => {
            console.log("Geolocation failed. Showing default location.");
        });
    }

    // Add search bar
    if (L.Control.Geocoder) {
        L.Control.geocoder({
            defaultMarkGeocode: false
        })
        .on('markgeocode', function (e) {
            const center = e.geocode.center;
            L.marker(center).addTo(map).bindPopup(e.geocode.name).openPopup();
            map.setView(center, 14);
        })
        .addTo(map);
    }

    let routeControl;

    window.calculateRoute = function () {
        const from = document.getElementById("fromPlace").value.trim();
        const to = document.getElementById("toPlace").value.trim();

        if (!from || !to) {
            alert("Please enter both locations!");
            return;
        }

        document.getElementById("loadingSpinner").style.display = "block";
        document.getElementById("routeInfo").classList.add("d-none");

        setTimeout(() => {
            document.getElementById("loadingSpinner").style.display = "none";

            if (routeControl) map.removeControl(routeControl);

            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(14.5995, 120.9842), // Start
                    L.latLng(14.6760, 121.0437)  // Example destination
                ],
                routeWhileDragging: true,
                lineOptions: {
                    styles: [{ color: '#dc3545', opacity: 0.8, weight: 5 }]
                }
            }).addTo(map);

            document.getElementById("routeInfo").classList.remove("d-none");
            document.getElementById("routeInfo").innerText =
                `Route successfully set from "${from}" to "${to}"`;
        }, 1500);
    };
});
