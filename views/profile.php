<?php
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - DFAP</title>
    <link rel="stylesheet" href="User/Css/style.css">
    <link rel="stylesheet" href="User/Css/dashboard.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle"><?php echo ucfirst($_SESSION['role']); ?> Portal</div>
        </div>
        <nav class="sidebar-nav">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="Admin/admin.php" class="nav-item">🏠 Dashboard</a>
                <a href="products.php" class="nav-item">📦 Products</a>
                <a href="#" class="nav-item">👥 User Management</a>
                <a href="#" class="nav-item">✅ Verification</a>
                <a href="#" class="nav-item">📊 Reports</a>
                <a href="#" class="nav-item">⚙️ System</a>
                <a href="#" class="nav-item active">👤 Profile</a>
                <a href="../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif ($_SESSION['role'] == 'customer'): ?>
                <a href="User/customer.php" class="nav-item">🏠 Marketplace</a>
                <a href="#" class="nav-item">📦 My Orders</a>
                <a href="#" class="nav-item">💬 Messages</a>
                <a href="#" class="nav-item">❤️ Wishlist</a>
                <a href="#" class="nav-item active">👤 Profile</a>
                <a href="../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif ($_SESSION['role'] == 'farmer' || $_SESSION['role'] == 'fisherman'): ?>
                <a href="User/<?php echo $_SESSION['role']; ?>.php" class="nav-item">🏠 Dashboard</a>
                <a href="#" class="nav-item">📊 Sensors</a>
                <a href="#" class="nav-item">🐟 Products</a>
                <a href="User/upload-product.php" class="nav-item">📦 My Products</a>
                <a href="#" class="nav-item">👨‍🔬 Expert Advice</a>
                <a href="#" class="nav-item">📜 Grants</a>
                <a href="#" class="nav-item active">👤 Profile</a>
                <a href="../?logout=1" class="nav-item">🚪 Logout</a>
            <?php endif; ?>
        </nav>
    </div>


    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Profile Settings</h1>
                <p class="dashboard-subtitle">Manage your account information</p>
            </div>
        </div>


        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
            </div>
            <h2 class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
            <p class="profile-role"><?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?> Account</p>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h2 class="table-title">Account Information</h2>
            </div>
            <div class="table-content">
                <table>
                    <tbody>
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td><?php echo htmlspecialchars($_SESSION['user_name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td><?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Location:</strong></td>
                            <td><?php echo htmlspecialchars($_SESSION['location'] ?? 'Dhaka'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Language:</strong></td>
                            <td><?php echo htmlspecialchars($_SESSION['language'] ?? 'English'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Login Time:</strong></td>
                            <td><?php echo date('Y-m-d H:i:s', $_SESSION['login_time']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            border-radius: 0.75rem;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar {
            margin-bottom: 1rem;
        }

        .avatar-circle {
            width: 6rem;
            height: 6rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0 auto;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .profile-name {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .profile-role {
            font-size: 1rem;
            opacity: 0.9;
        }
    </style>
</body>
</html>