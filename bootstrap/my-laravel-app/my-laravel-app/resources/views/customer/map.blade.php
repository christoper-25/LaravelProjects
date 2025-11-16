<!-- resources/views/customer/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }
        h3 { text-align: center; background: #333; color: #fff; padding: 10px; margin: 0; }
        #map { height: calc(100vh - 60px); width: 100%; }
    </style>
</head>
<body>
    <h3>Order Tracking (Order ID: {{ $orderId }})</h3>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const orderId = {{ $orderId }};
        const riderId = {{ $riderId }}; // Rider assigned to this order

        let map = L.map('map').setView([14.5995, 120.9842], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let customerMarker, riderMarker;

        // 1️⃣ Show customer location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const latlng = [pos.coords.latitude, pos.coords.longitude];
                customerMarker = L.marker(latlng).addTo(map).bindPopup("You are here").openPopup();
                map.setView(latlng, 14);
            }, () => {
                alert("Could not detect your location.");
            }, { enableHighAccuracy: true });
        }

        // 2️⃣ Function to update rider location
        async function updateRiderLocation() {
            try {
                const res = await fetch(`/customer/rider-location/${riderId}`);
                if (!res.ok) return;
                const data = await res.json();
                if (data.latitude && data.longitude) {
                    const latlng = [data.latitude, data.longitude];
                    if (!riderMarker) {
                        riderMarker = L.marker(latlng, {
                            icon: L.icon({
                                iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                                iconSize: [30,30]
                            })
                        }).addTo(map).bindPopup("Delivery Rider").openPopup();
                    } else {
                        riderMarker.setLatLng(latlng);
                    }
                }
            } catch(err) {
                console.error("Error fetching rider location:", err);
            }
        }

        // Update rider location every 5 seconds
        setInterval(updateRiderLocation, 5000);
        updateRiderLocation();
    </script>
</body>
</html>
