<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin")
    {
        header("Location:../home.php");
        exit;
    }

    require_once __DIR__ . '/../../controllers/NoticeController.php';
    $noticeController = new NoticeController();
    $notices = $noticeController->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Management - DFAP</title>
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
            <a href="admin.php" class="nav-item">🏠 Dashboard</a>
            <a href="products.php" class="nav-item">📦 Products</a>
            <a href="#" class="nav-item active">📢 Notices</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <div id="message" class="alert" style="display: none; margin-bottom: 1rem;"></div>

        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Notice Management</h1>
                <p class="dashboard-subtitle">Send notices to customers, fishermen, and farmers</p>
            </div>
            <button class="btn-primary" onclick="showCreateForm()">+ Create Notice</button>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h2 class="table-title">All Notices</h2>
                <p class="table-subtitle">Manage system-wide announcements</p>
            </div>
            <div class="table-content">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Content</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($notices)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 2rem;">
                                No notices created yet.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($notices as $notice): ?>
                            <tr>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($notice['title']); ?></td>
                                <td>
                                    <span class="category-badge category-<?php echo htmlspecialchars($notice['category']); ?>">
                                        <?php echo ucfirst(htmlspecialchars($notice['category'])); ?>
                                    </span>
                                </td>
                                <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #64748b;">
                                    <?php echo htmlspecialchars(substr($notice['content'], 0, 100)); ?>...
                                </td>
                                <td><?php echo htmlspecialchars($notice['creator_name']); ?></td>
                                <td style="color: #64748b;"><?php echo date('M j, Y H:i', strtotime($notice['created_at'])); ?></td>
                                <td class="text-right">
                                    <button class="btn-outline" onclick="editNotice(<?php echo $notice['notice_id']; ?>, '<?php echo addslashes($notice['title']); ?>', '<?php echo addslashes($notice['content']); ?>', '<?php echo addslashes($notice['category']); ?>')">Edit</button>
                                    <button class="btn-danger" onclick="deleteNotice(<?php echo $notice['notice_id']; ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="noticeModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Create Notice</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="noticeForm">
                <input type="hidden" id="noticeId" name="notice_id">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="all">All Users</option>
                        <option value="customer">Customers</option>
                        <option value="fisherman">Fishermen</option>
                        <option value="farmer">Farmers</option>
                        <option value="admin">Admins</option>
                        <option value="government_ngo">Government/NGO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="6" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save Notice</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showCreateForm() {
            document.getElementById('modalTitle').textContent = 'Create Notice';
            document.getElementById('noticeId').value = '';
            document.getElementById('title').value = '';
            document.getElementById('category').value = 'all';
            document.getElementById('content').value = '';
            document.getElementById('noticeModal').style.display = 'flex';
        }

        function editNotice(id, title, content, category) {
            document.getElementById('modalTitle').textContent = 'Edit Notice';
            document.getElementById('noticeId').value = id;
            document.getElementById('title').value = title;
            document.getElementById('category').value = category || 'all';
            document.getElementById('content').value = content;
            document.getElementById('noticeModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('noticeModal').style.display = 'none';
        }

        function deleteNotice(id) {
            if (confirm('Are you sure you want to delete this notice?')) {
                fetch('../../controllers/NoticeController.php?action=delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'notice_id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    showMessage(data.success ? 'success' : 'error', data.message);
                    if (data.success) {
                        setTimeout(() => location.reload(), 1000);
                    }
                })
                .catch(error => {
                    showMessage('error', 'Failed to delete notice');
                });
            }
        }

        document.getElementById('noticeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = formData.get('notice_id') ? 'update' : 'create';

            fetch('../../controllers/NoticeController.php?action=' + action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showMessage(data.success ? 'success' : 'error', data.message);
                if (data.success) {
                    closeModal();
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => {
                showMessage('error', 'Failed to save notice');
            });
        });

        function showMessage(type, message) {
            const messageDiv = document.getElementById('message');
            messageDiv.className = `alert alert-${type}`;
            messageDiv.innerHTML = `<span class="alert-icon">${type === 'success' ? '✅' : '⚠️'}</span> ${message}`;
            messageDiv.style.display = 'flex';

            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }
    </script>

    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #1e293b;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #334155;
        }

        .modal-header h3 {
            margin: 0;
            color: white;
        }

        .modal-close {
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .form-group {
            padding: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #e2e8f0;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #334155;
            border-radius: 0.375rem;
            background: #0f172a;
            color: white;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-actions {
            padding: 1.5rem;
            border-top: 1px solid #334155;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

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

        .category-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .category-all {
            background: #e5e7eb;
            color: #374151;
        }

        .category-customer {
            background: #dbeafe;
            color: #1e40af;
        }

        .category-fisherman {
            background: #dbeafe;
            color: #1e40af;
        }

        .category-farmer {
            background: #dcfce7;
            color: #166534;
        }

        .category-admin {
            background: #fef3c7;
            color: #92400e;
        }

        .category-government_ngo {
            background: #fce7f3;
            color: #be185d;
        }
    </style>
</body>
</html>