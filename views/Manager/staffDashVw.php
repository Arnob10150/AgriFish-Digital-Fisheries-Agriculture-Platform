<?php
session_start();

if(!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'fisherman' && $_SESSION['role'] != 'fish farmer')){
    header("Location: ../../../customer/views/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fisherman Dashboard</title>
    <link rel="stylesheet" href="../../style.css">
    

    </style>
</head>
<body>
<div class="dashboard">
    <h1><?php echo ucfirst($_SESSION['role']); ?> Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['user_name']; ?>!</p>

    <?php if($_SESSION['role'] == 'fish farmer'): ?>

      <div class="dashboard-content">
        <div class="dashboard-header">
          <div>
            <h1 class="dashboard-title">Farm Overview</h1>
            <p class="dashboard-subtitle">Real-time water quality and production metrics.</p>
          </div>
          <button class="add-pond-btn">+ Add New Pond</button>
        </div>


        <div class="sensor-grid">
          <div class="sensor-card">
            <div class="sensor-header">
              <div class="sensor-title">Dissolved Oxygen</div>
              <div class="sensor-icon">💨</div>
            </div>
            <div class="sensor-value">6.5 mg/L</div>
            <div class="sensor-subtitle">Optimal Range: 5-8 mg/L</div>
          </div>

          <div class="sensor-card">
            <div class="sensor-header">
              <div class="sensor-title">pH Level</div>
              <div class="sensor-icon">💧</div>
            </div>
            <div class="sensor-value">7.2</div>
            <div class="sensor-subtitle">Neutral - Perfect</div>
          </div>

          <div class="sensor-card">
            <div class="sensor-header">
              <div class="sensor-title">Temperature</div>
              <div class="sensor-icon">🌡️</div>
            </div>
            <div class="sensor-value">28°C</div>
            <div class="sensor-subtitle">Pond A - Surface</div>
          </div>

          <div class="sensor-card">
            <div class="sensor-header">
              <div class="sensor-title">Ammonia</div>
              <div class="sensor-icon">⚡</div>
            </div>
            <div class="sensor-value">0.02 ppm</div>
            <div class="sensor-subtitle">Warning at > 0.05</div>
          </div>
        </div>


        <div class="main-content-grid">

          <div class="data-table table-span-2">
            <div class="table-header">
              <h2 class="table-title">Pond Management</h2>
            </div>
            <div class="table-content">
              <div class="pond-list">
                <div class="pond-card">
                  <div class="pond-icon">🌱</div>
                  <div class="pond-info">
                    <h3>Pond #1 - Tilapia</h3>
                    <p>Stocked: 12 days ago • Est. Harvest: Nov 15</p>
                    <div class="pond-details">
                      <span>Feed: <strong>12kg/day</strong></span>
                      <span>Size: <strong>0.5 acres</strong></span>
                    </div>
                  </div>
                  <span class="pond-status">Healthy</span>
                </div>
                <div class="pond-card">
                  <div class="pond-icon">🌱</div>
                  <div class="pond-info">
                    <h3>Pond #2 - Rohu</h3>
                    <p>Stocked: 8 days ago • Est. Harvest: Nov 20</p>
                    <div class="pond-details">
                      <span>Feed: <strong>15kg/day</strong></span>
                      <span>Size: <strong>0.7 acres</strong></span>
                    </div>
                  </div>
                  <span class="pond-status">Healthy</span>
                </div>
                <div class="pond-card">
                  <div class="pond-icon">🌱</div>
                  <div class="pond-info">
                    <h3>Pond #3 - Catfish</h3>
                    <p>Stocked: 5 days ago • Est. Harvest: Nov 25</p>
                    <div class="pond-details">
                      <span>Feed: <strong>10kg/day</strong></span>
                      <span>Size: <strong>0.4 acres</strong></span>
                    </div>
                  </div>
                  <span class="pond-status">Healthy</span>
                </div>
              </div>
            </div>
          </div>


          <div class="farmers-assistant">
            <div class="assistant-header">
              <h2 class="assistant-title">Farmer's Assistant</h2>
            </div>
            <div class="assistant-content">
              <div class="assistant-tip">
                <h3>Feeding Schedule</h3>
                <p>Time for the afternoon feed in Pond #2.</p>
                <button class="assistant-btn">Mark Done</button>
              </div>
              <div class="assistant-market">
                <h3>Market Insight</h3>
                <p>Shrimp prices are up 5% this week due to high demand.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
 
      <div class="dashboard-content">
        <div class="dashboard-header">
          <div>
            <h1 class="dashboard-title">Good Morning, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>!</h1>
            <p class="dashboard-subtitle">Here's what's happening on the water today.</p>
          </div>
          <button class="sos-btn">🚨 SOS Signal</button>
        </div>


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

  
        <div class="main-content-grid">
 
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
                  <tr>
                    <td>Oct 24, 2024</td>
                    <td>Catfish</td>
                    <td>110 kg</td>
                    <td>$4.50</td>
                    <td class="text-right">$495.00</td>
                  </tr>
                  <tr>
                    <td>Oct 25, 2024</td>
                    <td>Shrimp</td>
                    <td>75 kg</td>
                    <td>$15.00</td>
                    <td class="text-right">$1,125.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

  
          <div class="weather-widget">
            <div class="weather-header">
              <div>
                <h3>Bay of Bengal</h3>
                <p>Chittagong Coast</p>
              </div>
              <div class="weather-icon-large">🌧️</div>
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
    <?php endif; ?>
</div>
</body>
</html>