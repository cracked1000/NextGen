<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Network</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #1a2a3a, #2d3748); 
            color: #ffffff; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; 
            padding: 0; 
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 20px; 
        }
        h1 { 
            text-align: center; 
            font-size: 2.5rem; 
            font-weight: 800; 
            margin-bottom: 1.5rem; 
            padding-top: 2rem; 
            background: linear-gradient(to right, #3b82f6, #8b5cf6, #ec4899); 
            -webkit-background-clip: text; 
            background-clip: text; 
            color: transparent; 
        }
        .form-container { 
            display: flex; 
            justify-content: center; 
            margin-bottom: 1.5rem; 
            position: relative; 
            z-index: 20; 
        }
        button { 
            padding: 0.75rem 1.5rem; 
            font-size: 1rem; 
            border-radius: 0.75rem; 
            border: none;
            transition: all 0.3s ease; 
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899); 
            color: #fff; 
            font-weight: 600; 
            cursor: pointer; 
            display: flex; 
            align-items: center;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        button:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.5); 
        }
        button:disabled { 
            cursor: not-allowed; 
            opacity: 0.6; 
        }
        button i { 
            margin-right: 0.5rem; 
        }
        .map-container { 
            position: relative; 
            margin: 2rem 0;
            padding: 1rem;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 1.5rem;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .map-glow {
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.4) 0%, rgba(59, 130, 246, 0) 70%);
            z-index: 1;
            border-radius: 50%;
            filter: blur(30px);
        }
        .map-glow-2 {
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.4) 0%, rgba(59, 130, 246, 0) 70%);
            z-index: 1;
            border-radius: 50%;
            filter: blur(30px);
        }
        #map { 
            height: 500px; 
            width: 100%; 
            border-radius: 1rem; 
            border: 1px solid rgba(255, 255, 255, 0.15); 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden; 
            position: relative;
            z-index: 2;
        }
        .map-controls {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            z-index: 10;
            display: flex;
            gap: 0.5rem;
        }
        .map-control-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            color: #3b82f6;
            border: none;
            transition: all 0.2s ease;
        }
        .map-control-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .table-container { 
            margin: 2rem 0; 
            overflow: hidden; 
            border-radius: 1rem; 
            background: rgba(255, 255, 255, 0.05); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            padding: 1rem; 
            text-align: left; 
        }
        th { 
            background: linear-gradient(to right, #3b82f6, #8b5cf6); 
            color: #ffffff; 
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }
        th::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
        }
        th:hover::after {
            animation: shine 1.5s infinite;
        }
        @keyframes shine {
            100% {
                transform: translateX(100%);
            }
        }
        td { 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
        }
        tr:hover { 
            background-color: rgba(255, 255, 255, 0.05); 
        }
        a { 
            color: #8b5cf6; 
            text-decoration: none; 
            display: inline-flex; 
            align-items: center; 
            transition: color 0.3s ease; 
        }
        a:hover { 
            color: #ec4899; 
        }
        a i { 
            margin-left: 0.5rem; 
        }
        .particles { 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            overflow: hidden; 
            z-index: 0; 
        }
        .particle { 
            position: absolute; 
            width: 3px; 
            height: 3px; 
            background-color: rgba(255, 255, 255, 0.3); 
            border-radius: 50%; 
            opacity: 0.3; 
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }
            50% {
                transform: scale(1.3);
                opacity: 0.5;
            }
            100% {
                transform: scale(1);
                opacity: 0.3;
            }
        }
        .hidden { 
            display: none; 
        }
        .page-section { 
            position: relative; 
            padding: 2rem 0; 
            z-index: 10; 
        }
        .section-title { 
            display: inline-block; 
            padding: 0.5rem 1rem; 
            font-size: 0.875rem; 
            font-weight: 600; 
            color: #ffffff; 
            background: linear-gradient(135deg, #3b82f6, #8b5cf6); 
            border-radius: 9999px; 
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }
        .text-center { 
            text-align: center; 
        }
        .text-2xl { 
            font-size: 1.5rem; 
            line-height: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .subtitle {
            font-size: 1rem;
            opacity: 0.8;
            max-width: 600px;
            margin: 0 auto 2rem auto;
        }
        .legend {
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 999;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            color: #333;
            font-size: 12px;
        }
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .legend-you {
            background: linear-gradient(to right, #3b82f6, #4f46e5);
            border: 2px solid white;
        }
        .legend-tech {
            background: linear-gradient(to right, #ef4444, #ec4899);
            border: 1px solid white;
        }
        .legend-shop {
            background: linear-gradient(to right, #10b981, #3b82f6);
            border: 1px solid white;
        }
    </style>
</head>
<body>
    @include('include.header')
    <div class="page-section">
        <div class="container">
            <div class="particles" id="particles"></div>

            <div class="text-center">
                <span class="section-title">LOCATE SERVICES</span>
                <h2 class="text-2xl">Find Nearby PC Building Shops</h2>
                <p class="subtitle">Discover the best PC building shops within {{ $maxDistance }} km of your location</p>
            </div>

            <div class="form-container">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <button id="fetchShopsBtn" type="button">
                    <i class="fas fa-search-location"></i>
                    Fetch Services Near You
                </button>
            </div>

            <div class="map-container">
                <div class="map-glow"></div>
                <div class="map-glow-2"></div>
                <div id="map"></div>
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color legend-you"></div>
                        <span>Your Location</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-tech"></div>
                        <span>Technicians</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-shop"></div>
                        <span>PC Shops</span>
                    </div>
                </div>
            </div>

            <div id="shopsTable" class="table-container hidden">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Distance (km)</th>
                            <th>Navigate</th>
                        </tr>
                    </thead>
                    <tbody id="shopsBody">
                        <!-- Populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Particle Animation
            const particles = document.getElementById('particles');
            for (let i = 0; i < 100; i++) {
                let particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.top = Math.random() * 100 + 'vh';
                let size = Math.random() * 5 + 1;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.opacity = Math.random() * 0.5 + 0.1;
                let animationDuration = Math.random() * 20 + 10;
                particle.style.animation = `pulse ${animationDuration}s infinite alternate`;
                particles.appendChild(particle);
            }

            let map;
            let userMarker;
            let techMarkers = [];
            let shopMarkers = [];
            let userLocation = [{{ $latitude ?? '6.9271' }}, {{ $longitude ?? '79.8612' }}]; // Default to Colombo

            // Geolocation with fallback and initialization
            function initializeLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const { latitude, longitude } = position.coords;
                            document.getElementById('latitude').value = latitude;
                            document.getElementById('longitude').value = longitude;
                            userLocation = [latitude, longitude];
                            console.log('Geolocation set:', latitude, longitude);
                            initializeMap();
                        },
                        (error) => {
                            console.error('Geolocation error:', error);
                            document.getElementById('latitude').value = userLocation[0];
                            document.getElementById('longitude').value = userLocation[1];
                            console.log('Using default location (Colombo):', userLocation);
                            initializeMap();
                            alert('Geolocation failed. Using default location (Colombo).');
                        }
                    );
                } else {
                    console.log('Geolocation not supported. Using default location.');
                    document.getElementById('latitude').value = userLocation[0];
                    document.getElementById('longitude').value = userLocation[1];
                    initializeMap();
                    alert('Geolocation not supported. Using default location (Colombo).');
                }
            }

            // Initialize map
            function initializeMap() {
                if (!map) {
                    map = L.map('map').setView(userLocation, 10);
                    
                    // Use a brighter map tile layer
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                        maxZoom: 19,
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors © <a href="https://carto.com/attributions">CARTO</a>'
                    }).addTo(map);
                } else {
                    map.setView(userLocation, 10);
                }

                if (userMarker) map.removeLayer(userMarker);
                const userIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background: linear-gradient(to right, #3b82f6, #4f46e5); width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px rgba(59, 130, 246, 0.7);"></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });
                userMarker = L.marker(userLocation, { icon: userIcon }).addTo(map)
                    .bindPopup('<div style="color: #333; font-weight: bold;">Your Location</div>').openPopup();

                addTechnicianMarkers();
            }

            function addTechnicianMarkers() {
                techMarkers.forEach(marker => map.removeLayer(marker));
                techMarkers = [];
                const techIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background: linear-gradient(to right, #ef4444, #ec4899); width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px rgba(239, 68, 68, 0.7);"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });
                const technicians = @json($technicians);
                technicians.forEach(tech => {
                    if (tech.latitude && tech.longitude) {
                        const position = [parseFloat(tech.latitude), parseFloat(tech.longitude)];
                        const marker = L.marker(position, { icon: techIcon }).addTo(map);
                        marker.bindPopup(`
                            <div style="color: #333; padding: 5px;">
                                <h3 style="color: #ef4444; margin: 0 0 8px 0;">${tech.name}</h3>
                                <p style="margin: 5px 0;"><strong>District:</strong> ${tech.district}</p>
                                <p style="margin: 5px 0;"><strong>Town:</strong> ${tech.town}</p>
                                <p style="margin: 5px 0;"><strong>Contact:</strong> ${tech.contact_number}</p>
                                <a href="https://www.openstreetmap.org/directions?engine=graphhopper_car&route=${userLocation[0]}%2C${userLocation[1]}%3B${tech.latitude}%2C${tech.longitude}" target="_blank" style="color: #ec4899; font-weight: bold; display: inline-block; margin-top: 8px;">
                                    Get Directions <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        `);
                        techMarkers.push(marker);
                    }
                });
            }

            function addPcShopsMarkers(pcShops) {
                shopMarkers.forEach(marker => map.removeLayer(marker));
                shopMarkers = [];
                const shopIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background: linear-gradient(to right, #10b981, #3b82f6); width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px rgba(16, 185, 129, 0.7);"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });
                pcShops.forEach(shop => {
                    const position = [parseFloat(shop.latitude), parseFloat(shop.longitude)];
                    const marker = L.marker(position, { icon: shopIcon }).addTo(map);
                    marker.bindPopup(`
                        <div style="color: #333; padding: 5px;">
                            <h3 style="color: #10b981; margin: 0 0 8px 0;">${shop.name}</h3>
                            <p style="margin: 5px 0;"><strong>Address:</strong> ${shop.address}</p>
                            <p style="margin: 5px 0;"><strong>Distance:</strong> ${shop.distance} km</p>
                            <a href="https://www.openstreetmap.org/directions?engine=graphhopper_car&route=${userLocation[0]}%2C${userLocation[1]}%3B${shop.latitude}%2C${shop.longitude}" target="_blank" style="color: #10b981; font-weight: bold; display: inline-block; margin-top: 8px;">
                                Get Directions <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    `);
                    shopMarkers.push(marker);
                });
            }

            function updateShopsTable(pcShops) {
                const tbody = document.getElementById('shopsBody');
                tbody.innerHTML = '';
                if (pcShops.length > 0) {
                    pcShops.forEach(shop => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><span style="font-weight: 600;">${shop.name}</span></td>
                            <td>${shop.address}</td>
                            <td>${shop.distance} km</td>
                            <td><a href="https://www.openstreetmap.org/directions?engine=graphhopper_car&route=${userLocation[0]}%2C${userLocation[1]}%3B${shop.latitude}%2C${shop.longitude}" target="_blank" class="nav-link">Navigate <i class="fas fa-directions"></i></a></td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="4" style="text-align: center; padding: 20px;">No PC building shops found within {{ $maxDistance }} km.</td>`;
                    tbody.appendChild(row);
                }
                document.getElementById('shopsTable').classList.remove('hidden');
            }

            // Button click event
            const fetchButton = document.getElementById('fetchShopsBtn');
            fetchButton.addEventListener('click', function() {
                alert('Button clicked!'); // Immediate feedback
                console.log('Button clicked!');
                const latitude = document.getElementById('latitude').value;
                const longitude = document.getElementById('longitude').value;
                console.log('Coordinates:', { latitude, longitude });

                if (latitude && longitude) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                    this.disabled = true;

                    $.ajax({
                        url: '{{ route("fetch.nearby.shops") }}',
                        method: 'GET',
                        data: { latitude: latitude, longitude: longitude },
                        success: function(response) {
                            console.log('AJAX Success:', response);
                            if (response.pcShops) {
                                addPcShopsMarkers(response.pcShops);
                                updateShopsTable(response.pcShops);
                            } else if (response.error) {
                                alert('Error: ' + response.error);
                            }
                            fetchButton.innerHTML = '<i class="fas fa-search-location"></i> Fetch Services Near You';
                            fetchButton.disabled = false;
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', xhr.responseText);
                            alert('Failed to fetch shops: ' + (xhr.responseJSON?.error || error));
                            fetchButton.innerHTML = '<i class="fas fa-search-location"></i> Fetch Services Near You';
                            fetchButton.disabled = false;
                        }
                    });
                } else {
                    alert('Location data is missing. Please allow location access and try again.');
                }
            });

            // Initialize location and map
            initializeLocation();
        });
    </script>
</body>
</html>