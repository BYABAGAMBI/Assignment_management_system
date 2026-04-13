<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
$success_message = '';

// Handle teacher registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'register') {
    $teacher_name = trim($_POST['teacher_name'] ?? '');
    $teacher_username = trim($_POST['teacher_username'] ?? '');
    $teacher_password = trim($_POST['teacher_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // Validation
    if (empty($teacher_name) || empty($teacher_username) || empty($teacher_password)) {
        $error_message = 'All fields are required';
    } elseif (strlen($teacher_password) < 6) {
        $error_message = 'Password must be at least 6 characters long';
    } elseif ($teacher_password !== $confirm_password) {
        $error_message = 'Passwords do not match';
    } else {
        // Store teacher credentials in a simple file (for demo purposes)
        $teacher_data = [
            'name' => $teacher_name,
            'username' => $teacher_username,
            'password' => password_hash($teacher_password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // In a real application, you would store this in a database
        file_put_contents('teacher_credentials.json', json_encode($teacher_data));
        $success_message = 'Teacher account created successfully! You can now login.';
    }
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check if teacher credentials file exists
    if (file_exists('teacher_credentials.json')) {
        $teacher_data = json_decode(file_get_contents('teacher_credentials.json'), true);
        
        if ($username === $teacher_data['username'] && password_verify($password, $teacher_data['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['teacher_name'] = $teacher_data['name'];
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
            
            // Redirect to requested page or dashboard
            $redirect = $_GET['redirect'] ?? 'dashboard.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error_message = 'Invalid username or password';
        }
    } else {
        // Fallback to default credentials if no custom credentials exist
        if ($username === 'teacher' && $password === 'school123') {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['teacher_name'] = 'Default Teacher';
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
            
            $redirect = $_GET['redirect'] ?? 'dashboard.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error_message = 'Invalid username or password';
        }
    }
}

// Function to logout
function logout() {
    session_destroy();
    header('Location: teacher_login.php');
    exit;
}

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login System - School Assignment Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.6s ease-in;
        }

        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #e1e5e9;
        }

        .tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
        }

        .tab.active {
            color: #667eea;
            border-bottom: 3px solid #667eea;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .logo {
            font-size: 48px;
            margin-bottom: 20px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .error {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .info-box {
            background: rgba(102, 126, 234, 0.1);
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .info-box h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-box p {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo">? Teacher Portal</div>
        
        <div class="tabs">
            <button class="tab active" onclick="showTab('login')">Login</button>
            <button class="tab" onclick="showTab('register')">Register</button>
        </div>

        <!-- Login Tab -->
        <div id="login-tab" class="tab-content active">
            <h2>Teacher Login</h2>
            
            <?php if ($error_message): ?>
                <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="login">
                
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Login to System</button>
            </form>
            
            <div class="info-box">
                <h3>Default Credentials</h3>
                <p><strong>Username:</strong> teacher</p>
                <p><strong>Password:</strong> school123</p>
                <p>Or create your own account below!</p>
            </div>
        </div>

        <!-- Register Tab -->
        <div id="register-tab" class="tab-content">
            <h2>Create Teacher Account</h2>
            
            <?php if ($success_message): ?>
                <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <?php if ($error_message && !empty($_POST['teacher_name'])): ?>
                <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="action" value="register">
                
                <div class="form-group">
                    <label for="teacher_name">Full Name:</label>
                    <input type="text" id="teacher_name" name="teacher_name" required>
                </div>
                
                <div class="form-group">
                    <label for="teacher_username">Username:</label>
                    <input type="text" id="teacher_username" name="teacher_username" required>
                </div>
                
                <div class="form-group">
                    <label for="teacher_password">Password:</label>
                    <input type="password" id="teacher_password" name="teacher_password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
                
                <button type="submit" class="btn">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
