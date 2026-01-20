<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Role - DFAP</title>
    <link rel="stylesheet" href="views/User/Css/style.css">
    <link rel="stylesheet" href="views/User/Css/login.css">
</head>
<body>
    <div class="role-selection-page">
        <div class="role-selection-container">
            <div class="role-selection-header">
                <div class="welcome-icon">
                    <span>🎉</span>
                </div>
                <h1 class="role-selection-title">Welcome to DFAP!</h1>
                <p class="role-selection-subtitle">Please complete your profile to get started</p>
                <div class="progress-indicator">
                    <div class="progress-step active">1</div>
                    <div class="progress-line"></div>
                    <div class="progress-step active">2</div>
                </div>
                <p class="progress-text">Step 2 of 2: Choose your role</p>
            </div>

            <div class="role-selection-content">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">⚠️</span>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="/save-profile" id="roleForm">

                    <div class="role-grid">
                        <div class="role-card" data-role="customer">
                            <input type="radio" id="customer" name="role" value="customer" required>
                            <label for="customer" class="role-card-content">
                                <div class="role-icon">🛒</div>
                                <div class="role-info">
                                    <h3 class="role-title">Customer</h3>
                                    <p class="role-description">Buy fresh fish directly from fishermen and farmers</p>
                                </div>
                                <div class="role-checkmark">✓</div>
                            </label>
                        </div>

                        <div class="role-card" data-role="fisherman">
                            <input type="radio" id="fisherman" name="role" value="fisherman" required>
                            <label for="fisherman" class="role-card-content">
                                <div class="role-icon">🎣</div>
                                <div class="role-info">
                                    <h3 class="role-title">Fisher Man</h3>
                                    <p class="role-description">Sell your catch, get weather alerts, and safety features</p>
                                </div>
                                <div class="role-checkmark">✓</div>
                            </label>
                        </div>

                        <div class="role-card" data-role="farmer">
                            <input type="radio" id="farmer" name="role" value="farmer" required>
                            <label for="farmer" class="role-card-content">
                                <div class="role-icon">
                                    <img src="/DFAP/storage/resources/images/icon/icon.png" alt="Fish Farmer" class="role-icon-img">
                                </div>
                                <div class="role-info">
                                    <h3 class="role-title">Fish Farmer</h3>
                                    <p class="role-description">Monitor water quality, get expert advice, manage aquaculture</p>
                                </div>
                                <div class="role-checkmark">✓</div>
                            </label>
                        </div>


                        <div class="role-card admin-card" data-role="admin">
                            <input type="radio" id="admin" name="role" value="admin" required>
                            <label for="admin" class="role-card-content">
                                <div class="role-icon">👨‍💼</div>
                                <div class="role-info">
                                    <h3 class="role-title">Admin</h3>
                                    <p class="role-description">Manage platform, verify users, ensure system integrity</p>
                                </div>
                                <div class="role-checkmark">✓</div>
                            </label>
                        </div>
                    </div>


                    <div class="profile-section">
                        <h3 class="profile-section-title">Profile Information</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" id="full_name" name="full_name" class="form-input"
                                       placeholder="Enter your full name" required maxlength="100">
                            </div>

                            <div class="form-group">
                                <label for="location" class="form-label">Location</label>
                                <select id="location" name="location" class="form-input">
                                    <option value="">Select your location</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Chattogram">Chattogram</option>
                                    <option value="Cox's Bazar">Cox's Bazar</option>
                                    <option value="Barisal">Barisal</option>
                                    <option value="Mymensingh">Mymensingh</option>
                                    <option value="Khulna">Khulna</option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                    <option value="Rangpur">Rangpur</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="language" class="form-label">Preferred Language</label>
                            <select id="language" name="language" class="form-input">
                                <option value="bengali">বাংলা (Bengali)</option>
                                <option value="english">English</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="history.back()">
                            ← Back
                        </button>
                        <button type="submit" class="btn-primary" id="submitBtn">
                            Complete Registration <span class="arrow-icon">→</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
 
        const roleCards = document.querySelectorAll('.role-card');

        roleCards.forEach(card => {
            card.addEventListener('click', function() {
    
                roleCards.forEach(c => c.classList.remove('active'));

    
                this.classList.add('active');

   
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;

           
                radio.dispatchEvent(new Event('change'));
            });
        });


        document.getElementById('roleForm').addEventListener('submit', function(e) {
            const roleSelected = document.querySelector('input[name="role"]:checked');
            const fullName = document.getElementById('full_name').value.trim();

            if (!roleSelected) {
                e.preventDefault();
                showError('Please select a role.');
                return false;
            }

            if (!fullName) {
                e.preventDefault();
                showError('Please enter your full name.');
                document.getElementById('full_name').focus();
                return false;
            }

           
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = 'Creating Account... <span class="spinner">⟳</span>';
        });

        
        document.addEventListener('DOMContentLoaded', function() {
            const firstRole = document.querySelector('.role-card');
            if (firstRole) {
                firstRole.click();
            }
        });

        
        function showError(message) {
            const existing = document.querySelector('.alert-error');
            if (existing) existing.remove();

            const alert = document.createElement('div');
            alert.className = 'alert alert-error';
            alert.innerHTML = `<span class="alert-icon">⚠️</span> ${message}`;
            document.querySelector('.role-selection-content').prepend(alert);

            setTimeout(() => alert.remove(), 5000);
        }
    </script>

    <style>
        .role-selection-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .role-selection-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            overflow: hidden;
        }

        .role-selection-header {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .welcome-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .role-selection-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .role-selection-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .progress-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .progress-step {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .progress-step.active {
            background: white;
            color: #1e88e5;
        }

        .progress-line {
            width: 4rem;
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
            margin: 0 1rem;
        }

        .progress-text {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .role-selection-content {
            padding: 2rem;
        }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .role-card {
            position: relative;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }

        .role-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .role-card.active {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .role-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-card-content {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            cursor: pointer;
        }

        .role-icon {
            font-size: 2.5rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .role-info {
            flex: 1;
        }

        .role-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .role-description {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.4;
        }

        .role-checkmark {
            font-size: 1.5rem;
            color: #3b82f6;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .role-card.active .role-checkmark {
            opacity: 1;
        }

        .admin-card {
            grid-column: span 2;
        }

        .profile-section {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
        }

        .profile-section-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-secondary {
            padding: 0.75rem 1.5rem;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-primary {
            padding: 0.75rem 2rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        
        @media (max-width: 768px) {
            .role-grid {
                grid-template-columns: 1fr;
            }

            .admin-card {
                grid-column: span 1;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .role-selection-title {
                font-size: 2rem;
            }
        }
    </style>
</body>
</html>