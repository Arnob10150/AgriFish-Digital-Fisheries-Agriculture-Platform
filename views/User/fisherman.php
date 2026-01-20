<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "fisherman")
    {
        header("Location:../home.php");
        exit;
    }

    // Load notices
    try {
        require_once __DIR__ . '/../../controllers/NoticeController.php';
        $noticeController = new NoticeController();
        $notices = $noticeController->getAll();
    } catch (Exception $e) {
        $notices = [];
    }

    // Default weather data (will be updated by JavaScript)
    $weatherData = [
        'temperature' => 24,
        'wind_speed' => 12,
        'weather_description' => 'Please allow location access for accurate weather',
        'humidity' => 75,
        'alerts' => [],
        'location' => 'Requesting location permission...'
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fisherman Dashboard - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/DFAP/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle">Fisherman Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="fisherman.php" class="nav-item active">🏠 Dashboard</a>
            <a href="sales.php" class="nav-item">💰 Sales</a>
            <a href="upload-product.php" class="nav-item">📦 My Products</a>
            <a href="notice.php" class="nav-item">📢 Notices</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Good Morning, <?php echo isset($_SESSION['user_name']) ? explode(' ', $_SESSION['user_name'])[0] : 'Fisherman'; ?>!</h1>
                <p class="dashboard-subtitle">Here's what's happening on the water today</p>
            </div>
        </div>

        <!-- Notices -->
        <?php if (!empty($notices)): ?>
        <div class="notices-section" id="notices">
            <h2 class="section-title">📢 Important Notices</h2>
            <div class="notices-container">
                <?php foreach ($notices as $notice): ?>
                <div class="notice-card">
                    <div class="notice-header">
                        <h3 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h3>
                        <span class="notice-date"><?php echo date('M j, Y', strtotime($notice['created_at'])); ?></span>
                    </div>
                    <div class="notice-content">
                        <?php echo nl2br(htmlspecialchars($notice['content'])); ?>
                    </div>
                    <div class="notice-footer">
                        <span class="notice-author">By: <?php echo htmlspecialchars($notice['creator_name']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stats Grid -->
        <div class="sensor-grid">
            <div class="sensor-card">
                <div class="sensor-header">
                    <div class="sensor-title">Total Catch</div>
                    <div class="sensor-icon blue">⚖️</div>
                </div>
                <div class="sensor-value">1,240 kg</div>
                <div class="sensor-subtitle">+12% from last week</div>
            </div>

            <div class="sensor-card">
                <div class="sensor-header">
                    <div class="sensor-title">Earnings</div>
                    <div class="sensor-icon green">💰</div>
                </div>
                <div class="sensor-value">৳4,250</div>
                <div class="sensor-subtitle">+8% from last week</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="main-content-grid">
            <!-- Recent Catches Table -->
            <div class="data-table table-span-2">
                <div class="table-header">
                    <h2 class="table-title">Recent Catches</h2>
                    <button class="btn-primary" onclick="openCatchModal()">+ Add Catch</button>
                </div>
                <div class="table-content">
                    <table id="catches-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Weight</th>
                                <th>Price/kg</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-id="1">
                                <td>Oct 21, 2024</td>
                                <td>Hilsa Fish</td>
                                <td>120 kg</td>
                                <td>৳387</td>
                                <td class="text-right">৳46,440</td>
                                <td class="text-right">
                                    <button class="btn-outline" onclick="editCatch(this)">Edit</button>
                                    <button class="btn-danger" onclick="deleteCatch(this)">Delete</button>
                                </td>
                            </tr>
                            <tr data-id="2">
                                <td>Oct 22, 2024</td>
                                <td>Tiger Prawns</td>
                                <td>85 kg</td>
                                <td>৳1530</td>
                                <td class="text-right">৳130,050</td>
                                <td class="text-right">
                                    <button class="btn-outline" onclick="editCatch(this)">Edit</button>
                                    <button class="btn-danger" onclick="deleteCatch(this)">Delete</button>
                                </td>
                            </tr>
                            <tr data-id="3">
                                <td>Oct 23, 2024</td>
                                <td>Rui Fish</td>
                                <td>95 kg</td>
                                <td>৳530</td>
                                <td class="text-right">৳50,350</td>
                                <td class="text-right">
                                    <button class="btn-outline" onclick="editCatch(this)">Edit</button>
                                    <button class="btn-danger" onclick="deleteCatch(this)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Weather Widget -->
            <div class="weather-widget" id="weather">
                <div class="weather-header">
                    <div>
                        <h3 id="weather-location"><?php echo htmlspecialchars($weatherData['location']); ?></h3>
                        <p id="weather-subtitle">Loading weather data...</p>
                    </div>
                    <div class="weather-icon" id="weather-main-icon">⏳</div>
                </div>
                <div class="weather-temp">
                    <h2 id="weather-temp"><?php echo $weatherData['temperature']; ?>°</h2>
                    <p id="weather-desc"><?php echo htmlspecialchars($weatherData['weather_description']); ?></p>
                </div>
                <div class="weather-details">
                    <div class="weather-detail">
                        <p>Wind</p>
                        <p id="weather-wind"><?php echo $weatherData['wind_speed']; ?> km/h</p>
                    </div>
                    <div class="weather-detail">
                        <p>Humidity</p>
                        <p id="weather-humidity"><?php echo $weatherData['humidity']; ?>%</p>
                    </div>
                    <div class="weather-detail">
                        <p>Status</p>
                        <p id="weather-status">Loading</p>
                    </div>
                </div>
                <div id="weather-alerts" style="display: none;">
                    <!-- Alerts will be inserted here by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit Catch -->
    <div id="catch-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="catch-modal-title">Add Catch</h3>
                <button class="modal-close" onclick="closeCatchModal()">&times;</button>
            </div>
            <form id="catch-form">
                <input type="hidden" id="catch-id">
                <div class="form-group">
                    <label for="catch-date">Date</label>
                    <input type="date" id="catch-date" required>
                </div>
                <div class="form-group">
                    <label for="catch-type">Type</label>
                    <input type="text" id="catch-type" required>
                </div>
                <div class="form-group">
                    <label for="catch-weight">Weight (kg)</label>
                    <input type="number" id="catch-weight" required>
                </div>
                <div class="form-group">
                    <label for="catch-price">Price per kg (৳)</label>
                    <input type="number" id="catch-price" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-outline" onclick="closeCatchModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>


    <style>
        .notices-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
        }

        .notices-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notice-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1.5rem;
            transition: all 0.2s ease;
        }

        .notice-card:hover {
            border-color: #475569;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .notice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .notice-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            margin: 0;
        }

        .notice-date {
            font-size: 0.875rem;
            color: #64748b;
        }

        .notice-content {
            color: #e2e8f0;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .notice-footer {
            border-top: 1px solid #334155;
            padding-top: 0.75rem;
        }

        .notice-author {
            font-size: 0.875rem;
            color: #64748b;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #1e293b; /* Dark background for modal */
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #334155;
        }

        .modal-header h3 {
            margin: 0;
            color: white;
        }

        .modal-close {
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.5rem;
            cursor: pointer;
        }

        #catch-form {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #e2e8f0;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #334155;
            border-radius: 0.375rem;
            background: #0f172a;
            color: white;
        }

        .form-actions {
            padding-top: 1rem;
            border-top: 1px solid #334155;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }
    </style>

    <script>
        // --- CATCH CRUD SCRIPT ---
        const catchModal = document.getElementById('catch-modal');
        const catchModalTitle = document.getElementById('catch-modal-title');
        const catchForm = document.getElementById('catch-form');
        const catchesTableBody = document.querySelector('#catches-table tbody');
        let nextCatchId = 4; // To simulate auto-incrementing IDs for new entries

        function openCatchModal(title = 'Add Catch') {
            catchForm.reset();
            document.getElementById('catch-id').value = '';
            catchModalTitle.textContent = title;
            catchModal.style.display = 'flex';
        }

        function closeCatchModal() {
            catchModal.style.display = 'none';
        }

        function editCatch(button) {
            openCatchModal('Edit Catch');
            const row = button.closest('tr');
            const dateStr = row.cells[0].textContent;
            const type = row.cells[1].textContent;
            const weight = parseFloat(row.cells[2].textContent);
            const price = parseFloat(row.cells[3].textContent.replace('৳', ''));

            const date = new Date(dateStr);
            const year = date.getFullYear();
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const day = ('0' + date.getDate()).slice(-2);
            
            document.getElementById('catch-id').value = row.dataset.id;
            document.getElementById('catch-date').value = `${year}-${month}-${day}`;
            document.getElementById('catch-type').value = type;
            document.getElementById('catch-weight').value = weight;
            document.getElementById('catch-price').value = price;
        }

        function deleteCatch(button) {
            if (confirm('Are you sure you want to delete this catch?')) {
                button.closest('tr').remove();
            }
        }

        catchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('catch-id').value;
            const date = new Date(document.getElementById('catch-date').value);
            const type = document.getElementById('catch-type').value;
            const weight = parseFloat(document.getElementById('catch-weight').value);
            const price = parseFloat(document.getElementById('catch-price').value);

            const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const totalAmount = (weight * price).toLocaleString('en-IN', { style: 'currency', currency: 'BDT', minimumFractionDigits: 2 }).replace('BDT', '৳');

            if (id) {
                // Update
                const row = document.querySelector(`#catches-table tr[data-id='${id}']`);
                row.cells[0].textContent = formattedDate;
                row.cells[1].textContent = type;
                row.cells[2].textContent = `${weight} kg`;
                row.cells[3].textContent = `৳${price}`;
                row.cells[4].textContent = totalAmount;
            } else {
                // Add
                const newRow = catchesTableBody.insertRow();
                newRow.dataset.id = nextCatchId++;
                newRow.innerHTML = `
                    <td>${formattedDate}</td>
                    <td>${type}</td>
                    <td>${weight} kg</td>
                    <td>৳${price}</td>
                    <td class="text-right">${totalAmount}</td>
                    <td class="text-right">
                        <button class="btn-outline" onclick="editCatch(this)">Edit</button>
                        <button class="btn-danger" onclick="deleteCatch(this)">Delete</button>
                    </td>
                `;
            }
            closeCatchModal();
        });


        // --- WEATHER SCRIPT ---
        let currentWeatherData = null;
        let userLocation = null;

        function getUserLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    return reject(new Error('Geolocation is not supported'));
                }
                const timeoutId = setTimeout(() => reject(new Error('Location request timed out')), 3000);
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        clearTimeout(timeoutId);
                        resolve({ latitude: position.coords.latitude, longitude: position.coords.longitude });
                    },
                    (error) => {
                        clearTimeout(timeoutId);
                        reject(error);
                    },
                    { enableHighAccuracy: false, timeout: 3000, maximumAge: 300000 }
                );
            });
        }

        async function fetchWeatherData(latitude, longitude) {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,wind_speed_10m,weather_code&hourly=temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code&forecast_days=1&timezone=Asia/Dhaka`;
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        }

        function processWeatherData(apiData, locationName = 'Your Location') {
            const current = apiData.current;
            const hourly = apiData.hourly;
            const weatherDesc = getWeatherDescription(current.weather_code);
            const alerts = checkForAlerts(hourly);

            return {
                location: locationName,
                temperature: Math.round(current.temperature_2m),
                wind_speed: Math.round(current.wind_speed_10m),
                weather_description: weatherDesc,
                humidity: hourly.relative_humidity_2m ? Math.round(hourly.relative_humidity_2m[0]) : 75,
                alerts: alerts,
                last_updated: new Date().toLocaleTimeString(),
                status: 'success'
            };
        }
        
        function getWeatherDescription(code) {
            const descriptions = { 0: 'Clear sky', 1: 'Mainly clear', 2: 'Partly cloudy', 3: 'Overcast', 45: 'Fog', 48: 'Depositing rime fog', 51: 'Light drizzle', 53: 'Moderate drizzle', 55: 'Dense drizzle', 61: 'Slight rain', 63: 'Moderate rain', 65: 'Heavy rain', 71: 'Slight snow', 73: 'Moderate snow', 75: 'Heavy snow', 77: 'Snow grains', 80: 'Slight rain showers', 81: 'Moderate rain showers', 82: 'Violent rain showers', 85: 'Slight snow showers', 86: 'Heavy snow showers', 95: 'Thunderstorm', 96: 'Thunderstorm with slight hail', 99: 'Thunderstorm with heavy hail' };
            return descriptions[code] || 'Unknown';
        }

        function checkForAlerts(hourly) {
            const alerts = [];
            if (hourly.wind_speed_10m) {
                if (Math.max(...hourly.wind_speed_10m) > 15) alerts.push(`Strong winds expected (up to ${Math.round(Math.max(...hourly.wind_speed_10m))} km/h)`);
            }
            if (hourly.weather_code && hourly.weather_code.some(code => [95, 96, 99, 65, 82, 86].includes(code))) {
                alerts.push('Severe weather conditions expected');
            }
            return alerts;
        }

        function updateWeatherDisplay(weatherData) {
            if (weatherData.status === 'loading') {
                updateWeatherWidget({ location: 'Loading...', temperature: '--', weather_description: 'Fetching weather data...', wind_speed: '--', humidity: '--', alerts: [], status: 'loading' });
                return;
            }
            updateWeatherWidget(weatherData);
        }

        function updateWeatherWidget(data) {
            document.getElementById('weather-location').textContent = data.location;
            const subtitle = document.getElementById('weather-subtitle');
            if (subtitle) {
                subtitle.textContent = data.status === 'loading' ? 'Loading weather data...' : new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
            }
            document.getElementById('weather-temp').innerHTML = data.temperature + (data.temperature !== '--' ? '°' : '');
            document.getElementById('weather-desc').textContent = data.weather_description;
            document.getElementById('weather-wind').textContent = data.wind_speed + (data.wind_speed !== '--' ? ' km/h' : '');
            document.getElementById('weather-humidity').textContent = data.humidity + (data.humidity !== '--' ? '%' : '');
            document.getElementById('weather-status').textContent = data.status === 'success' ? `Updated: ${data.last_updated}` : (data.status === 'error' ? 'Error' : 'Loading...');

            const iconElement = document.getElementById('weather-main-icon');
            if (iconElement) {
                let icon = '⏳';
                if (data.weather_description && !data.weather_description.includes('Loading')) {
                    const desc = data.weather_description.toLowerCase();
                    if (desc.includes('rain')) icon = '🌧️'; else if (desc.includes('cloud')) icon = '☁️'; else if (desc.includes('storm') || desc.includes('thunder')) icon = '⛈️'; else if (desc.includes('snow')) icon = '❄️'; else if (desc.includes('fog')) icon = '🌫️'; else icon = '☀️';
                }
                iconElement.textContent = icon;
            }

            const alertsContainer = document.getElementById('weather-alerts');
            alertsContainer.innerHTML = data.alerts && data.alerts.length > 0 ? data.alerts.map(alert => `<div class="weather-alert"><span>⚠️</span><p>${alert}</p></div>`).join('') : '';
            alertsContainer.style.display = data.alerts && data.alerts.length > 0 ? 'block' : 'none';
        }

        async function loadWeatherData() {
            updateLocationDisplay('Getting your location...');
            updateWeatherDisplay({ status: 'loading' });
            try {
                const location = await getUserLocation();
                let locationName = 'Device Location';
                try {
                    const geoRes = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${location.latitude}&longitude=${location.longitude}&localityLanguage=en`);
                    const geoData = await geoRes.json();
                    locationName = geoData.city || geoData.locality || 'Device Location';
                } catch (e) { console.warn('Reverse geocoding failed'); }
                const apiData = await fetchWeatherData(location.latitude, location.longitude);
                currentWeatherData = processWeatherData(apiData, locationName);
            } catch (locationError) {
                updateLocationDisplay('Using Default Location');
                try {
                    const apiData = await fetchWeatherData(22.3569, 91.7832);
                    currentWeatherData = processWeatherData(apiData, 'Chittagong Coast (Default)');
                } catch (apiError) {
                    currentWeatherData = { location: 'Weather Service Unavailable', temperature: 25, wind_speed: 10, weather_description: 'Unable to load data', humidity: 70, alerts: [], status: 'error' };
                }
            }
            updateWeatherDisplay(currentWeatherData);
        }

        function updateLocationDisplay(message) {
            const locationElement = document.querySelector('#weather-location');
            if (locationElement) locationElement.textContent = message;
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadWeatherData();
            setInterval(loadWeatherData, 1800000);
            
            window.onclick = function(event) {
                if (event.target == catchModal) {
                    closeCatchModal();
                }
            }
        });
    </script>
</body>
</html>
