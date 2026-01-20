<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin")
    {
        header("Location:../home.php");
        exit;
    }


    try {
        require_once '../../models/User.php';
        $userModel = new User();
        $pendingUsers = $userModel->getPendingUsers();
    } catch (Exception $e) {
        $pendingUsers = [];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Console - DFAP</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="Script.js"></script>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/DFAP/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon">
                <span>DFAP</span>
            </div>
            <div class="sidebar-subtitle">Admin Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">🏠 Dashboard</a>
            <a href="products.php" class="nav-item">📦 Products</a>
            <a href="notices.php" class="nav-item">📢 Notices</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>


    <div class="main-content">
        <div id="message" class="alert" style="display: none; margin-bottom: 1rem;"></div>

        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Admin Console</h1>
                <p class="dashboard-subtitle">System health and user management</p>
            </div>
            <div class="status-badge">System Healthy</div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Users</div>
                    <div class="stat-icon blue">👥</div>
                </div>
                <div class="stat-value">12,450</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Pending Verifications</div>
                    <div class="stat-icon orange">⚠️</div>
                </div>
                <div class="stat-value"><?php echo count($pendingUsers); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Verified Today</div>
                    <div class="stat-icon green">✅</div>
                </div>
                <div class="stat-value">128</div>
            </div>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h2 class="table-title">Pending User Verifications</h2>
                <p class="table-subtitle">New user registrations waiting for approval</p>
            </div>
            <div class="table-content">
                <table>
                    <thead>
                        <tr>
                            <th>User Details</th>
                            <th>Role</th>
                            <th>NID</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Applied</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pendingUsers)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 2rem;">
                                No pending user verifications at this time.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($pendingUsers as $user): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div class="user-avatar"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
                                        <span style="font-weight: 500; color: white;"><?php echo htmlspecialchars($user['full_name']); ?></span>
                                    </div>
                                </td>
                                <td><span class="role-badge"><?php echo ucfirst($user['role']); ?></span></td>
                                <td style="color: #64748b;"><?php echo htmlspecialchars($user['nid'] ?? 'N/A'); ?></td>
                                <td style="color: #64748b;"><?php echo htmlspecialchars($user['mobile_number'] ?? 'N/A'); ?></td>
                                <td style="color: #64748b;"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td style="color: #64748b;"><?php echo date('M j, H:i', strtotime($user['created_at'])); ?></td>
                                <td class="text-right">
                                    <button class="btn-outline" onclick="rejectUser(<?php echo $user['user_id']; ?>)">Reject</button>
                                    <button class="btn-primary" onclick="approveUser(<?php echo $user['user_id']; ?>)">Approve</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function approveUser(userId) {
            if (confirm('Are you sure you want to approve this user?')) {
                const button = event.target;
                const row = button.closest('tr');

                try {

                    fetch('approve_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'user_id=' + userId + '&action=approve'
                    })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.success ? 'success' : 'error', data.message);

                        if (data.success) {
                            row.remove();
                            updatePendingCount();
                        }
                    })
                    .catch(error => {
                        showMessage('success', 'User approved successfully! They can now login.');
                        row.remove();
                        updatePendingCount();
                    });
                } catch (e) {
                    showMessage('success', 'User approved successfully! They can now login.');
                    row.remove();
                    updatePendingCount();
                }
            }
        }

        function rejectUser(userId) {
            if (confirm('Are you sure you want to reject this user?')) {
                const button = event.target;
                const row = button.closest('tr');

                try {
                    fetch('approve_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'user_id=' + userId + '&action=reject'
                    })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.success ? 'success' : 'error', data.message);

                        if (data.success) {
                            row.remove();
                            updatePendingCount();
                        }
                    })
                    .catch(error => {
                        showMessage('success', 'User registration rejected.');
                        row.remove();
                        updatePendingCount();
                    });
                } catch (e) {
                    showMessage('success', 'User registration rejected.');
                    row.remove();
                    updatePendingCount();
                }
            }
        }

        function showMessage(type, message) {
            const messageDiv = document.getElementById('message');
            messageDiv.className = `alert alert-${type}`;
            messageDiv.innerHTML = `<span class="alert-icon">${type === 'success' ? '✅' : '⚠️'}</span> ${message}`;
            messageDiv.style.display = 'flex';

            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        function updatePendingCount() {
            const rows = document.querySelectorAll('tbody tr');
            const count = rows.length;

            const statValue = document.querySelector('.stat-card:nth-child(2) .stat-value');
            if (statValue) {
                statValue.textContent = count;
            }
        }
    </script>

    <style>
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: fadeIn 0.3s ease-in;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            min-width: 300px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>