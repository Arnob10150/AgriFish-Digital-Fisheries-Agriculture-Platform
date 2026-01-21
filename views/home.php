<?php
session_start();

function redirectToRoleDashboard($role) {
    $redirect_path = "";
    if ($role == 'admin') {
        $redirect_path = "Admin/admin.php";
    } elseif ($role == 'customer') {
        $redirect_path = "User/customer.php";
    } else {
        $redirect_path = "User/{$role}.php";
    }
    header("Location: {$redirect_path}");
    exit;
}

$email = "";
$err_email = "";
$password = "";
$err_password = "";
$err_invalid = "";
$has_error = false;


$role = "";

$full_name = "";
$reg_email = "";
$reg_password = "";
$confirm_password = "";
$phone_number = "";
$nid = "";
$field_errors = [];

if (isset($_POST['submit'])) {

    if (empty($_POST['email'])) {
        $err_email = "*Email Required";
        $has_error = true;
    } else {
        $email = $_POST['email'];
    }
    if (empty($_POST['password'])) {
        $err_password = "*Password Required";
        $has_error = true;
    } else {
        $password = $_POST['password'];
    }

    if (!$has_error) {

        $demoUsers = [
            'customer@dfap.com' => ['password' => 'customer123', 'role' => 'customer', 'name' => 'Customer User', 'email' => 'customer@dfap.com', 'profile_picture' => null],
            'fisherman@dfap.com' => ['password' => 'fisherman123', 'role' => 'fisherman', 'name' => 'Fisherman User', 'email' => 'fisherman@dfap.com', 'profile_picture' => null],
            'farmer@dfap.com' => ['password' => 'farmer123', 'role' => 'farmer', 'name' => 'Fish Farmer User', 'email' => 'farmer@dfap.com', 'profile_picture' => null],
            'admin@dfap.com' => ['password' => 'admin123', 'role' => 'admin', 'name' => 'Admin User', 'email' => 'admin@dfap.com', 'profile_picture' => null],
        ];

        if (isset($demoUsers[$email]) && $demoUsers[$email]['password'] === $password) {

            $user = $demoUsers[$email];
            // Use fixed IDs to match database
            $fixedIds = [
                'admin@dfap.com' => 1,
                'farmer@dfap.com' => 5,
                'fisherman@dfap.com' => 4,
                'customer@dfap.com' => 3
            ];
            $_SESSION['user_id'] = $fixedIds[$email] ?? rand(1000, 9999);
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = '+880' . rand(100000000, 999999999);
            $_SESSION['user_nid'] = rand(100000000000, 999999999999);
            $_SESSION['role'] = $user['role'];
            $_SESSION['location'] = 'Dhaka';
            $_SESSION['language'] = 'english';
            $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
            $_SESSION['login_time'] = time();

            redirectToRoleDashboard($user['role']);
        } else {

            try {
                require_once '../models/User.php';
                $userModel = new User();
                $user = $userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {

                    if ($user['is_verified'] == 0) {
                        $err_invalid = "Account pending admin verification. Please wait for approval.";
                        $email = "";
                        $password = "";
                    } elseif ($user['account_status'] !== 'active') {
                        $err_invalid = "Account is {$user['account_status']}. Please contact admin.";
                        $email = "";
                        $password = "";
                    } else {

                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['user_name'] = $user['full_name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_phone'] = $user['mobile_number'];
                        $_SESSION['user_nid'] = $user['nid'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['location'] = $user['location'];
                        $_SESSION['language'] = $user['language_preference'];
                        $_SESSION['profile_picture'] = $user['profile_picture'];
                        $_SESSION['login_time'] = time();

                        redirectToRoleDashboard($user['role']);
                    }
                } else {

                    if (isset($_SESSION['recent_signup_email']) && $_SESSION['recent_signup_email'] === $email) {
                        $err_invalid = "Account pending admin verification. Please wait for approval.";
                    } else {
                        $err_invalid = "Invalid Email or Password";
                    }
                    $email = "";
                    $password = "";
                }
            } catch (Exception $e) {

                if (isset($_SESSION['recent_signup_email']) && $_SESSION['recent_signup_email'] === $email) {
                    $err_invalid = "Account pending admin verification. Please wait for approval.";
                } else {
                    $err_invalid = "Invalid Email or Password";
                }
                $email = "";
                $password = "";
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - DFAP</title>
    <link rel="stylesheet" href="User/Css/style.css">
    <link rel="stylesheet" href="User/Css/login.css">
</head>
<body>
    <div class="auth-grid">

        <div class="hero-section">
            <div class="slideshow-background">
                <!-- Slideshow images from your directory -->
                <div class="slide" style="background-image: url('../storage/resources/images/home/1.jpg');"></div>
                <div class="slide" style="background-image: url('../storage/resources/images/home/2.jpg');"></div>
                <div class="slide" style="background-image: url('../storage/resources/images/home/3.jpeg');"></div>
                <div class="slide" style="background-image: url('../storage/resources/images/home/4.jpg');"></div>
                <div class="slide" style="background-image: url('../storage/resources/images/home/5.jpg');"></div>
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-pattern-1"></div>
            <div class="hero-pattern-2"></div>
            <div class="hero-content">
                <div class="hero-icon">
                    <img src="../storage/resources/images/icon/icon.png" alt="DFAP" class="logo-icon">
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

   
        <div class="auth-section">
            <div class="auth-container">
                <div class="auth-header">
                    <h2 class="auth-title">Welcome to Agrifish</h2>
                    <p class="auth-subtitle">Sign in with your email and password</p>
                </div>

                <div class="auth-card">
                    <div class="auth-form">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-error">
                                <span class="alert-icon">⚠️</span>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <span class="alert-icon">✅</span>
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>


                        <div id="login-form" class="auth-form-content">
                            <form method="post" action="" id="loginForm">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-wrapper">
                                        <input type="email" id="email" name="email" placeholder="user@example.com"
                                                class="form-input" value="<?php echo $email; ?>" required autocomplete="email">
                                        <span class="input-icon">📧</span>
                                    </div>
                                    <span style="color:red"><?php echo $err_email; ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-wrapper">
                                        <input type="password" id="password" name="password" placeholder="Enter password"
                                                class="form-input" value="<?php echo $password; ?>" required autocomplete="current-password">
                                        <span class="input-icon">🔒</span>
                                    </div>
                                    <span style="color:red"><?php echo $err_password; ?></span>
                                </div>

                                <button type="submit" class="auth-button" name="submit" id="submitBtn">
                                    Login <span class="arrow-icon">→</span>
                                </button>
                                <span style="color:red"><?php echo $err_invalid; ?></span>
                            </form>
                        </div>


                        <div id="register-form" class="auth-form-content" style="display: none;">
                            <form method="post" action="" id="registerForm">
                                <div class="form-group">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <div class="input-wrapper">
                                        <input type="text" id="full_name" name="full_name" placeholder="Enter your full name"
                                                class="form-input" value="<?php echo $full_name; ?>">
                                        <span class="input-icon">👤</span>
                                    </div>
                                    <span id="name_Err" class="error-span" style="color:red;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <div class="input-wrapper">
                                        <input type="text" id="phone_number" name="phone_number" placeholder="+880 1XXX-XXXXXX"
                                                class="form-input" value="<?php echo $phone_number; ?>">
                                        <span class="input-icon">📱</span>
                                    </div>
                                    <span id="phone_Err" class="error-span" style="color:red;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="nid" class="form-label">National ID (NID)</label>
                                    <div class="input-wrapper">
                                        <input type="text" id="nid" name="nid" placeholder="Enter NID Number"
                                                class="form-input" value="<?php echo $nid; ?>">
                                        <span class="input-icon">🆔</span>
                                    </div>
                                    <span id="nid_Err" class="error-span" style="color:red;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="reg_email" class="form-label">Email</label>
                                    <div class="input-wrapper">
                                        <input type="email" id="reg_email" name="reg_email" placeholder="user@example.com"
                                                class="form-input" value="<?php echo $reg_email; ?>" autocomplete="email">
                                        <span class="input-icon">📧</span>
                                    </div>
                                    <span id="email_Err" class="error-span" style="color:red;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="reg_password" class="form-label">Password</label>
                                    <div class="input-wrapper">
                                        <input type="password" id="reg_password" name="reg_password" placeholder="Enter password"
                                                class="form-input" value="<?php echo $reg_password; ?>" autocomplete="new-password">
                                        <span class="input-icon">🔒</span>
                                    </div>
                                    <span id="password_Err" class="error-span" style="color:red;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-wrapper">
                                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password"
                                                class="form-input">
                                        <span class="input-icon">🔒</span>
                                    </div>
                                    <span id="confirm_password_Err" class="error-span" style="color:red;"></span>
                                </div>

                                <div class="form-group">
                                    <label for="role" class="form-label">Role</label>
                                    <div class="input-wrapper">
                                        <select id="role" name="role" class="form-input">
                                            <option value="">Select your role</option>
                                            <option value="customer" <?php echo ($role == 'customer') ? 'selected' : ''; ?>>Customer</option>
                                            <option value="fisherman" <?php echo ($role == 'fisherman') ? 'selected' : ''; ?>>Fisherman</option>
                                            <option value="farmer" <?php echo ($role == 'farmer') ? 'selected' : ''; ?>>Fish Farmer</option>
                                        </select>
                                        <span class="input-icon">👤</span>
                                    </div>
                                    <span id="role_Err" class="error-span" style="color:red;"></span>
                                </div>

                                <button type="submit" class="auth-button" name="register" id="registerBtn">
                                    Sign Up <span class="arrow-icon">→</span>
                                </button>
                            </form>
                        </div>

                        <div class="auth-footer">
                            <p class="auth-link">
                                <span id="auth-link-text">New to DFAP? <a href="#" onclick="toggleForm()">Sign Up</a></span> | <a href="#" onclick="showDemo()">View Demo</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div id="demoModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>DFAP Demo</h3>
                <button class="modal-close" onclick="closeDemo()">×</button>
            </div>
            <div class="modal-body">
                <p>Use these demo credentials to explore different user roles:</p>
                <div class="demo-numbers">
                    <div class="demo-item">
                        <strong>Customer:</strong> customer@dfap.com / customer123
                    </div>
                    <div class="demo-item">
                        <strong>Fisherman:</strong> fisherman@dfap.com / fisherman123
                    </div>
                    <div class="demo-item">
                        <strong>Fish Farmer:</strong> farmer@dfap.com / farmer123
                    </div>
                    <div class="demo-item">
                        <strong>Admin:</strong> admin@dfap.com / admin123
                    </div>
                </div>
                <p><em>Note: OTP will be displayed in the browser console for demo purposes.</em></p>
            </div>
        </div>
    </div>


    <div id="signupModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Registration Submitted</h3>
            </div>
            <div class="modal-body">
                <p><?php echo $_SESSION['signup_message'] ?? 'Please wait for admin verification.'; ?></p>
                <p>You will be notified once your account is approved.</p>
                <button onclick="closeSignupModal()" class="btn-primary" style="margin-top: 1rem;">OK</button>
            </div>
        </div>
    </div>


    <script>
 
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Please enter both email and password.');
                return false;
            }


            const btn = document.getElementById('submitBtn');
            btn.innerHTML = 'Logging in... <span class="spinner">⟳</span>';
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('registerBtn');
            btn.innerHTML = 'Signing up... <span class="spinner">⟳</span>';
            fetch('ajax_register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.error-span').forEach(span => span.textContent = '');
                if (data.success) {
                    document.getElementById('signupModal').style.display = 'block';
                    this.reset();
                } else if (data.errors) {
                    for (const [key, value] of Object.entries(data.errors)) {
                        const span = document.getElementById(key);
                        if (span) span.textContent = value;
                    }
                }
                btn.innerHTML = 'Sign Up <span class="arrow-icon">→</span>';
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = 'Sign Up <span class="arrow-icon">→</span>';
            });
        });


        function showDemo() {
            document.getElementById('demoModal').style.display = 'block';
        }

        function closeDemo() {
            document.getElementById('demoModal').style.display = 'none';
        }

   
        let isLogin = true;
        function toggleForm() {
            isLogin = !isLogin;
            if (isLogin) {
                document.getElementById('login-form').style.display = 'block';
                document.getElementById('register-form').style.display = 'none';
                document.getElementById('auth-link-text').innerHTML = 'New to DFAP? <a href="#" onclick="toggleForm()">Sign Up</a>';
            } else {
                document.getElementById('login-form').style.display = 'none';
                document.getElementById('register-form').style.display = 'block';
                document.getElementById('auth-link-text').innerHTML = 'Already have an account? <a href="#" onclick="toggleForm()">Login</a>';
            }
        }


        document.querySelectorAll('.demo-item').forEach(item => {
            item.addEventListener('click', function() {
                const text = this.textContent;
                const emailMatch = text.match(/(\w+@\w+\.\w+)/);
                const passMatch = text.match(/\/ (\w+)/);
                if (emailMatch && passMatch) {
                    document.getElementById('email').value = emailMatch[1];
                    document.getElementById('password').value = passMatch[1];
                    closeDemo();
                }
            });
        });


        window.onclick = function(event) {
            const modal = document.getElementById('demoModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }


        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'register') {
            toggleForm();
        }
        if (urlParams.get('signup') === '1') {
            document.getElementById('signupModal').style.display = 'block';
        }

        function closeSignupModal() {
            document.getElementById('signupModal').style.display = 'none';
            <?php unset($_SESSION['signup_complete'], $_SESSION['signup_message']); ?>
        }
    </script>

    <style>
        /* Slideshow Styles */
        .hero-section {
            position: relative;
            overflow: hidden;
            /* Ensure background color doesn't hide slides if they take a moment to load */
            background-color: hsl(222, 47%, 11%); 
        }

        .slideshow-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0; /* Behind everything */
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            animation: slideAnimation 25s infinite;
        }

        /* Animation for 5 slides */
        .slide:nth-child(1) { animation-delay: 0s; }
        .slide:nth-child(2) { animation-delay: 5s; }
        .slide:nth-child(3) { animation-delay: 10s; }
        .slide:nth-child(4) { animation-delay: 15s; }
        .slide:nth-child(5) { animation-delay: 20s; }

        @keyframes slideAnimation {
            0% { opacity: 0; }
            4% { opacity: 1; } /* Fade-in for 1s */
            20% { opacity: 1; } /* Stay visible for 4s */
            24% { opacity: 0; } /* Fade-out for 1s */
            100% { opacity: 0; }
        }

        /* Ensure content sits on top */
        .hero-overlay { z-index: 1; }
        .hero-pattern-1, .hero-pattern-2 { z-index: 2; }
        .hero-content { z-index: 10; }
        
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

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        .form-help {
            display: block;
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .input-prefix {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #374151;
            font-weight: 500;
            z-index: 1;
        }

        .form-input {
            padding-left: 3.5rem !important;
        }

        .language-selector {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        .language-selector select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
        }

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 15% auto;
            padding: 0;
            border-radius: 0.75rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .demo-numbers {
            margin: 1rem 0;
        }

        .demo-item {
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .demo-item:hover {
            background: #e2e8f0;
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

    </style>
</body>
</html>
