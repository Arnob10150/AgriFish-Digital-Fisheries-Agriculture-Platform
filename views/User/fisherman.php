<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "fisherman")
    {
        header("Location:../home.php");
        exit;
    }
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
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle">Fisherman Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">🏠 Dashboard</a>
            <a href="#" class="nav-item">📝 My Listings</a>
            <a href="#" class="nav-item">💰 Sales</a>
            <a href="upload-product.php" class="nav-item">📦 My Products</a>
            <a href="#" class="nav-item">🌊 Weather</a>
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
                <div class="sensor-value">$4,250</div>
                <div class="sensor-subtitle">+8% from last week</div>
            </div>

            <div class="sensor-card">
                <div class="sensor-header">
                    <div class="sensor-title">Active Trips</div>
                    <div class="sensor-icon purple">🚢</div>
                </div>
                <div class="sensor-value">3</div>
            </div>

            <div class="sensor-card">
                <div class="sensor-header">
                    <div class="sensor-title">Weather</div>
                    <div class="sensor-icon orange">🌧️</div>
                </div>
                <div class="sensor-value">24°C</div>
                <div class="sensor-subtitle">Light Rain Expected</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="main-content-grid">
            <!-- Recent Catches Table -->
            <div class="data-table table-span-2">
                <div class="table-header">
                    <h2 class="table-title">Recent Catches</h2>
                </div>
                <div class="table-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Weight</th>
                                <th>Price/kg</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Oct 21, 2024</td>
                                <td>Hilsa Fish</td>
                                <td>120 kg</td>
                                <td>$4.50</td>
                                <td class="text-right">$540.00</td>
                            </tr>
                            <tr>
                                <td>Oct 22, 2024</td>
                                <td>Tiger Prawns</td>
                                <td>85 kg</td>
                                <td>$18.00</td>
                                <td class="text-right">$1,530.00</td>
                            </tr>
                            <tr>
                                <td>Oct 23, 2024</td>
                                <td>Rui Fish</td>
                                <td>95 kg</td>
                                <td>$6.20</td>
                                <td class="text-right">$589.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Weather Widget -->
            <div class="weather-widget">
                <div class="weather-header">
                    <div>
                        <h3>Bay of Bengal</h3>
                        <p>Chittagong Coast</p>
                    </div>
                    <div class="weather-icon">🌧️</div>
                </div>
                <div class="weather-temp">
                    <h2>24°</h2>
                    <p>Light Showers</p>
                </div>
                <div class="weather-details">
                    <div class="weather-detail">
                        <p>Wind</p>
                        <p>12 km/h</p>
                    </div>
                    <div class="weather-detail">
                        <p>Humidity</p>
                        <p>78%</p>
                    </div>
                    <div class="weather-detail">
                        <p>Visibility</p>
                        <p>8 km</p>
                    </div>
                </div>
                <div class="weather-alert">
                    <span>⚠️</span>
                    <p>Strong winds expected at 4 PM</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>