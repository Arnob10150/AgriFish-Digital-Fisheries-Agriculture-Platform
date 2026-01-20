<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "farmer")
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fish Farmer Dashboard - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle">Farmer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">🏠 Farm Overview</a>
            <a href="#" class="nav-item">🐟 Products</a>
            <a href="upload-product.php" class="nav-item">📦 My Products</a>
            <a href="#" class="nav-item">👨‍🔬 Expert Advice</a>
            <a href="#" class="nav-item">📜 Grants</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Farm Overview</h1>
                <p class="dashboard-subtitle">Real-time water quality and production metrics</p>
            </div>
            <button class="add-pond-btn">+ Add New Pond</button>
        </div>

        <!-- Notices -->
        <?php if (!empty($notices)): ?>
        <div class="notices-section">
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

        <!-- Sensor Cards -->
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

        <!-- Main Content Grid -->
        <div class="main-content-grid">
            <!-- Pond Management -->
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
                    </div>
                </div>
            </div>

            <!-- Farmer's Assistant -->
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
    </style>
</body>
</html>