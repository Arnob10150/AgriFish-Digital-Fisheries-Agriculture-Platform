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
            <div class="sidebar-logo">
                <img src="/AgriFish-Digital-Fisheries-Agriculture-Platform-main/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle"><?php echo ucfirst($_SESSION['role']); ?> Portal</div>
        </div>
        <nav class="sidebar-nav">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="Admin/admin.php" class="nav-item">🏠 Dashboard</a>
                <a href="Admin/products.php" class="nav-item">📦 Products</a>
                <a href="Admin/notices.php" class="nav-item">📢 Notices</a>
                <a href="#" class="nav-item active">👤 Profile</a>
                <a href="../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif ($_SESSION['role'] == 'customer'): ?>
                <a href="User/customer.php" class="nav-item">🏠 Marketplace</a>
                <a href="User/cart.php" class="nav-item">🛒 My Cart</a>
                <a href="User/orders.php" class="nav-item">📦 My Orders</a>
                <a href="User/notice.php" class="nav-item">📢 Notices</a>
                <a href="User/wishlist.php" class="nav-item">❤️ Wishlist</a>
                <a href="#" class="nav-item active">👤 Profile</a>
                <a href="../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif ($_SESSION['role'] == 'farmer'): ?>
                <a href="User/farmer.php" class="nav-item">🏠 Farm Overview</a>
                <a href="User/upload-product.php" class="nav-item">📦 My Products</a>
                <a href="User/sales.php" class="nav-item">💰 Sales</a>
                <a href="User/notice.php" class="nav-item">📢 Notices</a>
                <a href="#" class="nav-item active">👤 Profile</a>
                <a href="../?logout=1" class="nav-item">🚪 Logout</a>
            <?php elseif ($_SESSION['role'] == 'fisherman'): ?>
                <a href="User/fisherman.php" class="nav-item">🏠 Dashboard</a>
                <a href="User/sales.php" class="nav-item">💰 Sales</a>
                <a href="User/upload-product.php" class="nav-item">📦 My Products</a>
                <a href="User/notice.php" class="nav-item">📢 Notices</a>
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

        <?php if (isset($_SESSION['profile_update_success'])): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✅</span>
                <?php echo htmlspecialchars($_SESSION['profile_update_success']); unset($_SESSION['profile_update_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['profile_update_error'])): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <?php echo htmlspecialchars($_SESSION['profile_update_error']); unset($_SESSION['profile_update_error']); ?>
            </div>
        <?php endif; ?>

        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    <?php if (!empty($_SESSION['profile_picture'])): ?>
                        <img src="/AgriFish-Digital-Fisheries-Agriculture-Platform-main/storage/resources/images/profiles/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>"
                             alt="Profile Picture" class="avatar-image">
                    <?php else: ?>
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                    <?php endif; ?>
                </div>
                <button class="edit-avatar-btn" onclick="document.getElementById('profileForm').style.display='block'; document.getElementById('viewMode').style.display='none';">✏️ Edit Profile</button>
            </div>
            <h2 class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
            <p class="profile-role"><?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?> Account</p>
        </div>

        <!-- View Mode -->
        <div id="viewMode" class="data-table">
            <div class="table-header">
                <h2 class="table-title">Account Information</h2>
                <button class="btn-primary" onclick="document.getElementById('profileForm').style.display='block'; document.getElementById('viewMode').style.display='none';">Edit Profile</button>
            </div>
            <div class="table-content">
                <table>
                    <tbody>
                        <tr>
                            <td><strong>Full Name:</strong></td>
                            <td><?php echo htmlspecialchars($_SESSION['user_name']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Phone Number:</strong></td>
                            <td><?php echo htmlspecialchars($_SESSION['user_phone'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>National ID (NID):</strong></td>
                            <td><?php echo htmlspecialchars($_SESSION['user_nid'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td><?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Mode -->
        <div id="profileForm" class="data-table" style="display: none;">
            <div class="table-header">
                <h2 class="table-title">Edit Profile</h2>
            </div>
            <form method="post" action="../controllers/profile_update.php" enctype="multipart/form-data" class="profile-edit-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($_SESSION['location'] ?? 'Dhaka'); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email (Cannot be changed)</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" disabled readonly>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number (Cannot be changed)</label>
                        <input type="text" id="phone" value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>" disabled readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nid">National ID (NID) (Cannot be changed)</label>
                        <input type="text" id="nid" value="<?php echo htmlspecialchars($_SESSION['user_nid'] ?? ''); ?>" disabled readonly>
                    </div>

                    <div class="form-group">
                        <label for="role">Role (Cannot be changed)</label>
                        <input type="text" id="role" value="<?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?>" disabled readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                    <small class="form-hint">Upload a new profile picture (JPG, PNG, GIF - Max 5MB)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update" class="btn-primary">Save Changes</button>
                    <button type="button" class="btn-outline" onclick="document.getElementById('profileForm').style.display='none'; document.getElementById('viewMode').style.display='block';">Cancel</button>
                </div>
            </form>
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
            overflow: hidden;
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .edit-avatar-btn {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .edit-avatar-btn:hover {
            background: rgba(255, 255, 255, 0.3);
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

        .profile-edit-form {
            padding: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #334155;
            border-radius: 0.375rem;
            background: #0f172a;
            color: white;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-hint {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn-primary, .btn-outline {
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-outline {
            background: transparent;
            color: #64748b;
            border: 1px solid #334155;
        }

        .btn-outline:hover {
            background: #334155;
            color: white;
        }
    </style>
</body>
</html>
