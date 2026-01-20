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
    <link rel="stylesheet" href="Css/farmer.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/DFAP/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle">Farmer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="farmer.php" class="nav-item active">🏠 Farm Overview</a>
            <a href="upload-product.php" class="nav-item">📦 My Products</a>
            <a href="sales.php" class="nav-item">💰 Sales</a>
            <a href="notice.php" class="nav-item">📢 Notices</a>
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
            <button class="add-pond-btn" onclick="openPondModal('add')">+ Add New Pond</button>
        </div>

        <!-- Notices -->
        <?php if (!empty($notices)): ?>
        <div class="notices-section" id="notices">
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

        <!-- Main Content Grid -->
        <div class="main-content-grid">
            <!-- Pond Management -->
            <div class="data-table table-span-2">
                <div class="table-header">
                    <h2 class="table-title">Pond Management</h2>
                </div>
                <div class="table-content">
                    <div class="pond-list">
                        <div class="pond-card" data-pond-id="1">
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
                            <div class="pond-actions">
                                <button class="pond-action-btn edit-btn" onclick="openPondModal('edit', 1)">✏️</button>
                                <button class="pond-action-btn delete-btn" onclick="deletePond(1)">🗑️</button>
                            </div>
                            <div class="pond-tasks">
                                <h4>Daily Tasks</h4>
                                <div class="task-item"><input type="checkbox" id="task-feed-morning-1" onchange="updateProgress(1)"><label for="task-feed-morning-1">Morning Feed</label></div>
                                <div class="task-item"><input type="checkbox" id="task-water-check-1" onchange="updateProgress(1)"><label for="task-water-check-1">Check Water Quality</label></div>
                                <div class="task-item"><input type="checkbox" id="task-feed-afternoon-1" onchange="updateProgress(1)"><label for="task-feed-afternoon-1">Afternoon Feed</label></div>
                                <div class="add-task-form">
                                    <input type="text" id="add-task-input-1" class="add-task-input" placeholder="Add a new task..." onkeydown="if(event.key==='Enter'){addTask(1); event.preventDefault();}">
                                    <button type="button" class="add-task-btn" onclick="addTask(1)">+</button>
                                </div>
                                <div class="task-progress">
                                    <div class="progress-bar-container"><div class="progress-bar" id="progress-bar-1"></div></div>
                                    <span id="progress-text-1">0/3 Completed</span>
                                </div>
                            </div>
                        </div>
                        <div class="pond-card" data-pond-id="2">
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
                            <div class="pond-actions">
                                <button class="pond-action-btn edit-btn" onclick="openPondModal('edit', 2)">✏️</button>
                                <button class="pond-action-btn delete-btn" onclick="deletePond(2)">🗑️</button>
                            </div>
                            <div class="pond-tasks">
                                <h4>Daily Tasks</h4>
                                <div class="task-item"><input type="checkbox" id="task-feed-morning-2" onchange="updateProgress(2)"><label for="task-feed-morning-2">Morning Feed</label></div>
                                <div class="task-item"><input type="checkbox" id="task-water-check-2" onchange="updateProgress(2)"><label for="task-water-check-2">Check Water Quality</label></div>
                                <div class="task-item"><input type="checkbox" id="task-feed-afternoon-2" onchange="updateProgress(2)"><label for="task-feed-afternoon-2">Afternoon Feed</label></div>
                                <div class="add-task-form">
                                    <input type="text" id="add-task-input-2" class="add-task-input" placeholder="Add a new task..." onkeydown="if(event.key==='Enter'){addTask(2); event.preventDefault();}">
                                    <button type="button" class="add-task-btn" onclick="addTask(2)">+</button>
                                </div>
                                <div class="task-progress">
                                    <div class="progress-bar-container"><div class="progress-bar" id="progress-bar-2"></div></div>
                                    <span id="progress-text-2">0/3 Completed</span>
                                </div>
                            </div>
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

    <!-- Add/Edit Pond Modal -->
    <div id="pondModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="pondModalTitle">Add New Pond</h3>
                <button class="modal-close" onclick="closePondModal()">×</button>
            </div>
            <div class="modal-body">
                <form id="pondForm" onsubmit="savePond(event)">
                    <input type="hidden" id="pondId">
                    <div class="form-group">
                        <label class="form-label">Pond Name</label>
                        <input type="text" id="pondName" class="form-input" required placeholder="e.g. Pond #3">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fish Type</label>
                        <input type="text" id="fishType" class="form-input" required placeholder="e.g. Pangas">
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex:1">
                            <label class="form-label">Stock Date</label>
                            <input type="text" id="stockDate" class="form-input" placeholder="e.g. 5 days ago">
                        </div>
                        <div class="form-group" style="flex:1">
                            <label class="form-label">Est. Harvest</label>
                            <input type="text" id="harvestDate" class="form-input" placeholder="e.g. Dec 01">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex:1">
                            <label class="form-label">Feed (kg/day)</label>
                            <input type="text" id="feedAmount" class="form-input" placeholder="e.g. 10kg/day">
                        </div>
                        <div class="form-group" style="flex:1">
                            <label class="form-label">Size (acres)</label>
                            <input type="text" id="pondSize" class="form-input" placeholder="e.g. 0.8 acres">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="pondStatus" class="form-input">
                            <option value="Healthy">Healthy</option>
                            <option value="Attention">Attention Needed</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-outline" onclick="closePondModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Save Pond</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Modal Functions
        function openPondModal(mode, pondId = null) {
            const modal = document.getElementById('pondModal');
            const title = document.getElementById('pondModalTitle');
            const form = document.getElementById('pondForm');
            
            form.reset();
            
            if (mode === 'edit' && pondId) {
                title.textContent = 'Edit Pond';
                document.getElementById('pondId').value = pondId;
                
                // Populate data from existing card
                const card = document.querySelector(`.pond-card[data-pond-id="${pondId}"]`);
                const nameText = card.querySelector('h3').textContent; // "Pond #1 - Tilapia"
                const parts = nameText.split(' - ');
                
                document.getElementById('pondName').value = parts[0];
                document.getElementById('fishType').value = parts[1] || '';
                
                const infoText = card.querySelector('.pond-info p').textContent; // "Stocked: 12 days ago • Est. Harvest: Nov 15"
                const infoParts = infoText.split(' • ');
                document.getElementById('stockDate').value = infoParts[0].replace('Stocked: ', '');
                document.getElementById('harvestDate').value = infoParts[1].replace('Est. Harvest: ', '');
                
                const details = card.querySelectorAll('.pond-details span strong');
                document.getElementById('feedAmount').value = details[0].textContent;
                document.getElementById('pondSize').value = details[1].textContent;
                
                document.getElementById('pondStatus').value = card.querySelector('.pond-status').textContent;
            } else {
                title.textContent = 'Add New Pond';
                document.getElementById('pondId').value = '';
            }
            
            modal.style.display = 'flex';
        }

        function closePondModal() {
            document.getElementById('pondModal').style.display = 'none';
        }

        function savePond(e) {
            e.preventDefault();
            const id = document.getElementById('pondId').value;
            const name = document.getElementById('pondName').value;
            const fish = document.getElementById('fishType').value;
            const stock = document.getElementById('stockDate').value;
            const harvest = document.getElementById('harvestDate').value;
            const feed = document.getElementById('feedAmount').value;
            const size = document.getElementById('pondSize').value;
            const status = document.getElementById('pondStatus').value;

            if (id) {
                // Edit existing
                const card = document.querySelector(`.pond-card[data-pond-id="${id}"]`);
                card.querySelector('h3').textContent = `${name} - ${fish}`;
                card.querySelector('.pond-info p').textContent = `Stocked: ${stock} • Est. Harvest: ${harvest}`;
                const details = card.querySelectorAll('.pond-details span strong');
                details[0].textContent = feed;
                details[1].textContent = size;
                card.querySelector('.pond-status').textContent = status;
            } else {
                // Add new
                const newId = Date.now();
                const pondList = document.querySelector('.pond-list');
                const newCardHTML = `
                    <div class="pond-card" data-pond-id="${newId}">
                        <div class="pond-icon">🌱</div>
                        <div class="pond-info">
                            <h3>${name} - ${fish}</h3>
                            <p>Stocked: ${stock} • Est. Harvest: ${harvest}</p>
                            <div class="pond-details">
                                <span>Feed: <strong>${feed}</strong></span>
                                <span>Size: <strong>${size}</strong></span>
                            </div>
                        </div>
                        <span class="pond-status">${status}</span>
                        <div class="pond-actions">
                            <button class="pond-action-btn edit-btn" onclick="openPondModal('edit', ${newId})">✏️</button>
                            <button class="pond-action-btn delete-btn" onclick="deletePond(${newId})">🗑️</button>
                        </div>
                        <div class="pond-tasks">
                            <h4>Daily Tasks</h4>
                            <div class="add-task-form">
                                <input type="text" id="add-task-input-${newId}" class="add-task-input" placeholder="Add a new task..." onkeydown="if(event.key==='Enter'){addTask(${newId}); event.preventDefault();}">
                                <button type="button" class="add-task-btn" onclick="addTask(${newId})">+</button>
                            </div>
                            <div class="task-progress">
                                <div class="progress-bar-container"><div class="progress-bar" id="progress-bar-${newId}"></div></div>
                                <span id="progress-text-${newId}">0/0 Completed</span>
                            </div>
                        </div>
                    </div>
                `;
                pondList.insertAdjacentHTML('beforeend', newCardHTML);
            }
            closePondModal();
        }

        function deletePond(id) {
            if(confirm('Are you sure you want to delete this pond?')) {
                const card = document.querySelector(`.pond-card[data-pond-id="${id}"]`);
                if(card) card.remove();
            }
        }

        function addTask(pondId) {
            const input = document.getElementById(`add-task-input-${pondId}`);
            const taskText = input.value.trim();

            if (taskText === '') {
                alert('Please enter a task.');
                input.focus();
                return;
            }

            const taskListContainer = input.closest('.pond-tasks');
            const addTaskForm = input.closest('.add-task-form');
            
            const taskItem = document.createElement('div');
            taskItem.className = 'task-item';
            const taskId = `task-custom-${pondId}-${Date.now()}`;
            const sanitizedTaskText = taskText.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            taskItem.innerHTML = `<input type="checkbox" id="${taskId}" onchange="updateProgress(${pondId})"><label for="${taskId}">${sanitizedTaskText}</label>`;

            // Insert the new task before the "add task" form
            taskListContainer.insertBefore(taskItem, addTaskForm);

            input.value = '';
            updateProgress(pondId);
        }

        function updateProgress(pondId) {
            const tasks = document.querySelectorAll(`.pond-card[data-pond-id="${pondId}"] .task-item input[type="checkbox"]`);
            const progressBar = document.getElementById(`progress-bar-${pondId}`);
            const progressText = document.getElementById(`progress-text-${pondId}`);
            
            if (!tasks.length || !progressBar || !progressText) return;

            const totalTasks = tasks.length;
            let completedTasks = 0;
            tasks.forEach(task => {
                if (task.checked) {
                    completedTasks++;
                }
            });
            
            const percentage = totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0;
            
            progressBar.style.width = percentage + '%';
            progressText.textContent = `${completedTasks}/${totalTasks} Completed`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize progress for all ponds on the page
            const pondCards = document.querySelectorAll('.pond-card');
            pondCards.forEach(card => {
                const pondId = card.dataset.pondId;
                if (pondId) {
                    updateProgress(pondId);
                }
            });
        });
    </script>
</body>
</html>