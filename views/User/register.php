<?php
include("../includes/db_connect.php");
session_start();
$error = '';
if(isset($_SESSION['reg_error'])){ $error = $_SESSION['reg_error']; unset($_SESSION['reg_error']); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DFAP</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
<div class="auth-section" style="min-height: 100vh;">
    <div class="auth-container">
        <div class="auth-header">
            <h2 class="auth-title">Create Account</h2>
            <p class="auth-subtitle">Join the Digital Fisheries & Agriculture Platform</p>
        </div>

        <div class="auth-card">
            <div class="auth-form">
                <?php if($error != ''){ echo '<div class="error">'.htmlspecialchars($error).'</div>'; } ?>
                <form method="post" action="../controllers/register_process.php">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" placeholder="Your full name" class="form-input" required>
                            <span class="input-icon">👤</span>
                        </div>
                    </div>
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
                            <input type="password" id="password" name="password" placeholder="Password (min 6)" minlength="6" class="form-input" required>
                            <span class="input-icon">🔒</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" placeholder="Your address" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-input" required>
                            <option value="">Select Role</option>
                            <option value="buyer">Buyer</option>
                            <option value="fish farmer">Fish Farmer</option>
                            <option value="fisherman">Fisherman</option>
                            <option value="government ngo">Government NGO</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="register" class="auth-button">
                        Create Account <span class="arrow-icon">→</span>
                    </button>
                </form>
                <p class="auth-link">Already have account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>