<?php
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location:../home.php");
    exit;
}

// Default weather data
$weatherData = [
    'temperature' => '--',
    'wind_speed' => '--',
    'weather_description' => 'Loading...',
    'humidity' => '--',
    'alerts' => [],
    'location' => 'Locating...'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast - DFAP</title>
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
            <div class="sidebar-subtitle"><?php echo ucfirst($_SESSION['role']); ?> Portal</div>
        </div>
        <nav class="sidebar-nav">
            <?php if($_SESSION['role'] == 'fisherman'): ?>
                <a href="fisherman.php" class="nav-item">🏠 Dashboard</a>
                <a href="sales.php" class="nav-item">💰 Sales</a>
                <a href="upload-product.php" class="nav-item">📦 My Products</a>
                <a href="notice.php" class="nav-item">📢 Notices</a>
                <a href="../profile.php" class="nav-item">👤 Profile</a>
                <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
            <?php else: ?>
                <!-- Fallback for other roles if they access this page -->
                <a href="<?php echo $_SESSION['role']; ?>.php" class="nav-item">🏠 Dashboard</a>
                <a href="../profile.php" class="nav-item">👤 Profile</a>
                <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Weather Forecast</h1>
                <p class="dashboard-subtitle">Real-time weather updates for safe operations</p>
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

    <script>
        // Global weather data
        let currentWeatherData = null;
        let userLocation = null;

        // Get user's current location
        function getUserLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('Geolocation is not supported by this browser'));
                    return;
                }

                const timeoutId = setTimeout(() => {
                    reject(new Error('Location request timed out'));
                }, 3000);

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        clearTimeout(timeoutId);
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    },
                    (error) => {
                        clearTimeout(timeoutId);
                        reject(error);
                    },
                    {
                        enableHighAccuracy: false,
                        timeout: 3000,
                        maximumAge: 300000
                    }
                );
            });
        }

        // Fetch weather data from Open-Meteo API
        async function fetchWeatherData(latitude, longitude) {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,wind_speed_10m,weather_code&hourly=temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code&forecast_days=1&timezone=Asia/Dhaka`;
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        }

        // Process weather data
        function processWeatherData(apiData, locationName = 'Your Location') {
            const current = apiData.current;
            const hourly = apiData.hourly;
            const weatherDesc = getWeatherDescription(current.weather_code);
            
            const alerts = [];
            if (hourly.wind_speed_10m) {
                const maxWind = Math.max(...hourly.wind_speed_10m);
                if (maxWind > 15) alerts.push(`Strong winds expected (up to ${Math.round(maxWind)} km/h)`);
            }

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
            const descriptions = {
                0: 'Clear sky', 1: 'Mainly clear', 2: 'Partly cloudy', 3: 'Overcast',
                45: 'Fog', 48: 'Depositing rime fog', 51: 'Drizzle', 61: 'Rain', 
                80: 'Showers', 95: 'Thunderstorm'
            };
            return descriptions[code] || 'Unknown';
        }

        function updateWeatherWidget(data) {
            document.getElementById('weather-location').textContent = data.location;
            
            const subtitle = document.getElementById('weather-subtitle');
            if (subtitle) {
                if (data.status === 'loading') {
                    subtitle.textContent = 'Loading weather data...';
                } else {
                    const options = { weekday: 'long', month: 'long', day: 'numeric' };
                    subtitle.textContent = new Date().toLocaleDateString('en-US', options);
                }
            }

            document.getElementById('weather-temp').innerHTML = data.temperature + (data.temperature !== '--' ? '°' : '');
            document.getElementById('weather-desc').textContent = data.weather_description;
            document.getElementById('weather-wind').textContent = data.wind_speed + (data.wind_speed !== '--' ? ' km/h' : '');
            document.getElementById('weather-humidity').textContent = data.humidity + (data.humidity !== '--' ? '%' : '');
            document.getElementById('weather-status').textContent = data.status === 'success' ? `Updated: ${data.last_updated}` : 'Loading...';

            const iconElement = document.getElementById('weather-main-icon');
            if (iconElement && data.weather_description) {
                let icon = '☀️';
                const desc = data.weather_description.toLowerCase();
                if (desc.includes('rain')) icon = '🌧️';
                else if (desc.includes('cloud')) icon = '☁️';
                else if (desc.includes('storm')) icon = '⛈️';
                iconElement.textContent = icon;
            }

            const alertsContainer = document.getElementById('weather-alerts');
            if (data.alerts && data.alerts.length > 0) {
                alertsContainer.innerHTML = data.alerts.map(alert => `<div class="weather-alert"><span>⚠️</span><p>${alert}</p></div>`).join('');
                alertsContainer.style.display = 'block';
            } else {
                alertsContainer.style.display = 'none';
            }
        }

        async function loadWeatherData() {
            updateWeatherWidget({ status: 'loading', temperature: '--', wind_speed: '--', humidity: '--', location: 'Locating...' });

            try {
                const location = await getUserLocation();
                
                let locationName = 'Device Location';
                try {
                    const geoRes = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${location.latitude}&longitude=${location.longitude}&localityLanguage=en`);
                    const geoData = await geoRes.json();
                    locationName = geoData.city || geoData.locality || 'Device Location';
                } catch (e) {}

                const apiData = await fetchWeatherData(location.latitude, location.longitude);
                const currentWeatherData = processWeatherData(apiData, locationName);
                updateWeatherWidget(currentWeatherData);

            } catch (error) {
                console.warn('Location failed, using default');
                try {
                    const apiData = await fetchWeatherData(22.3569, 91.7832);
                    const currentWeatherData = processWeatherData(apiData, 'Chittagong (Default)');
                    updateWeatherWidget(currentWeatherData);
                } catch (e) {
                    console.error('Weather failed');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', loadWeatherData);
    </script>
</body>
</html>