<?php session_start(); if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){ header("Location: ../home.php"); exit; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Console - DFAP</title>
    <link rel="stylesheet" href="css/Dashboard_style.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/DFAP/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle">Admin Console</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">Dashboard</a>
            <a href="#" class="nav-item">User Management</a>
            <a href="#" class="nav-item">Verifications</a>
            <a href="#" class="nav-item">Reports</a>
            <a href="../../?logout=1" class="nav-item">Logout</a>
        </nav>
    </div>


    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Admin Console</h1>
                <p class="dashboard-subtitle">System health and user management.</p>
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
                <div class="stat-value">45</div>
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
                <h2 class="table-title">Pending Verifications</h2>
            </div>
            <div class="table-content">
                <table>
                    <thead>
                        <tr>
                            <th>User Details</th>
                            <th>Role</th>
                            <th>Location</th>
                            <th>Applied</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div class="user-avatar">R</div>
                                    <span style="font-weight: 500; color: #1e293b;">Rahim Uddin</span>
                                </div>
                            </td>
                            <td><span class="role-badge">Fisherman</span></td>
                            <td style="color: #64748b;">Cox's Bazar</td>
                            <td style="color: #64748b;">2 mins ago</td>
                            <td class="text-right">
                                <button class="btn-outline">Reject</button>
                                <button class="btn-primary">Approve</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div class="user-avatar">K</div>
                                    <span style="font-weight: 500; color: #1e293b;">Karim Farms Ltd</span>
                                </div>
                            </td>
                            <td><span class="role-badge">Farmer</span></td>
                            <td style="color: #64748b;">Khulna</td>
                            <td style="color: #64748b;">15 mins ago</td>
                            <td class="text-right">
                                <button class="btn-outline">Reject</button>
                                <button class="btn-primary">Approve</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>