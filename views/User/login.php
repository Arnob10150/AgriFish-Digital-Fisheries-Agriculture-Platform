<?php
// include("../includes/db_connect.php"); // Commented out for dummy mode
session_start();
$error = '';
if(isset($_SESSION['error'])){ $error = $_SESSION['error']; unset($_SESSION['error']); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DFAP</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
<div class="auth-grid">
    <!-- Left: Brand / Hero -->
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-pattern-1"></div>
        <div class="hero-pattern-2"></div>
        <div class="hero-content">
            <div class="hero-icon">
                <span class="fish-icon">🐟</span>
            </div>
            <h1 class="hero-title">
                Digital Fisheries & <br/>
                <span class="hero-subtitle">Agriculture Platform</span>
            </h1>
            <p class="hero-description">
                Empowering fishermen, farmers, and consumers with a unified digital ecosystem for sustainable growth.
            </p>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3 class="stat-number">10k+</h3>
                    <p class="stat-label">Active Farmers</p>
                </div>
                <div class="stat-card">
                    <h3 class="stat-number">$2M+</h3>
                    <p class="stat-label">Trade Volume</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Auth Form -->
    <div class="auth-section">
        <div class="auth-container">
            <div class="auth-header">
                <h2 class="auth-title">Welcome Back</h2>
                <p class="auth-subtitle">Sign in to access your dashboard</p>
            </div>

            <div class="auth-card">
                <div class="auth-form">
                    <?php if($error != ''){ echo '<div class="error">'.htmlspecialchars($error).'</div>'; } ?>
                    <form method="post" action="../controllers/login_process.php">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" placeholder="your@email.com" class="form-input" required>
                                <span class="input-icon">📧</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password" placeholder="Password" class="form-input" required>
                                <span class="input-icon">🔒</span>
                            </div>
                        </div>
                        <button type="submit" name="login" class="auth-button">
                            Login <span class="arrow-icon">→</span>
                        </button>
                    </form>
                    <p class="auth-link">No account? <a href="../login.php?tab=register">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>