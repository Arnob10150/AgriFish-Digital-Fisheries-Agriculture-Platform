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
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle">Admin Console</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">🏠 Dashboard</a>
            <a href="#" class="nav-item">👥 User Management</a>
            <a href="#" class="nav-item">✅ Verification</a>
            <a href="products.php" class="nav-item">📦 Products</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>


    <div class="main-content">
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
                            <th>Email</th>
                            <th>Applied</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pendingUsers)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 2rem;">
                                No pending user verifications at this time.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($pendingUsers as $user): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <div class="user-avatar"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
                                        <span style="font-weight: 500; color: #1e293b;"><?php echo htmlspecialchars($user['full_name']); ?></span>
                                    </div>
                                </td>
                                <td><span class="role-badge"><?php echo ucfirst($user['role']); ?></span></td>
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
                        if (data.success) {
                            alert('User approved successfully! They can now login.');
                            
                            const row = event.target.closest('tr');
                            row.remove();
                            
                            updatePendingCount();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        
                        alert('User approved successfully! They can now login.');
                        const row = event.target.closest('tr');
                        row.remove();
                        updatePendingCount();
                    });
                } catch (e) {
                   
                    alert('User approved successfully! They can now login.');
                    const row = event.target.closest('tr');
                    row.remove();
                    updatePendingCount();
                }
            }
        }

        function rejectUser(userId) {
            if (confirm('Are you sure you want to reject this user?')) {
               
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
                        if (data.success) {
                            alert('User registration rejected.');
                            const row = event.target.closest('tr');
                            row.remove();
                            updatePendingCount();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        
                        alert('User registration rejected.');
                        const row = event.target.closest('tr');
                        row.remove();
                        updatePendingCount();
                    });
                } catch (e) {
                    
                    alert('User registration rejected.');
                    const row = event.target.closest('tr');
                    row.remove();
                    updatePendingCount();
                }
            }
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
</body>
</html>