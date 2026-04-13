<?php
// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_page == 'index') $current_page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Assignment Management System'; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('books.avif') center/cover no-repeat fixed;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            color: #333;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transform: translateX(-200px);
            transition: transform 0.3s ease;
        }

        .sidebar:hover, .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-toggle {
            position: fixed;
            left: 10px;
            top: 20px;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .sidebar-toggle:hover {
            background: #667eea;
            color: white;
            transform: scale(1.1);
        }

        .sidebar-trigger-zone {
            position: fixed;
            left: 0;
            top: 0;
            width: 50px;
            height: 100vh;
            z-index: 999;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 40px;
        }

        .main-content {
            flex: 1;
            margin-left: 50px;
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.05);
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 250px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-menu li {
            margin-bottom: 15px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 15px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-menu a:hover, .nav-menu a.active {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 250px;
                transform: translateX(-250px);
            }

            .sidebar:hover, .sidebar.show {
                transform: translateX(0);
            }

            .logo {
                font-size: 18px;
                margin-bottom: 20px;
                text-align: center;
            }

            .nav-menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .nav-menu li {
                margin-bottom: 0;
            }

            .nav-menu a {
                padding: 10px 15px;
                font-size: 14px;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex;
            }

            .sidebar-trigger-zone {
                width: 60px;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="sidebar-trigger-zone"></div>
    <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
    
    <aside class="sidebar" id="sidebar">
        <div class="logo">Assignment Management System</div>
        <nav>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="submit_assignment.html" class="<?php echo $current_page == 'submit_assignment' ? 'active' : ''; ?>">Submit Assignment</a></li>
                <li><a href="view_assignments.php" class="<?php echo $current_page == 'view_assignments' ? 'active' : ''; ?>">View Assignments</a></li>
                <li><a href="dashboard.php?logout=true" style="color: #e74c3c;">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content" id="mainContent">
        <div class="container">
