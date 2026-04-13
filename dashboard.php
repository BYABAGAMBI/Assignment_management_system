<?php
// Include authentication check
require_once 'auth_check.php';

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'school_assignments_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get dashboard statistics
$total_assignments = 0;
$average_marks = 0;
$subject_stats = array();
$recent_assignments = array();
$grade_distribution = array('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0);
$monthly_submissions = array();

// Total assignments and average marks
$result = $conn->query("SELECT COUNT(*) as total, AVG(marks) as avg_marks FROM assignments");
if ($result && $row = $result->fetch_assoc()) {
    $total_assignments = $row['total'];
    $average_marks = round($row['avg_marks'], 1);
}

// Subject statistics
$result = $conn->query("SELECT subject, COUNT(*) as count, AVG(marks) as avg_marks FROM assignments GROUP BY subject ORDER BY count DESC");
while ($result && $row = $result->fetch_assoc()) {
    $subject_stats[] = $row;
}

// Grade distribution
$result = $conn->query("SELECT marks FROM assignments");
while ($result && $row = $result->fetch_assoc()) {
    $marks = $row['marks'];
    if ($marks >= 80) $grade_distribution['A']++;
    elseif ($marks >= 70) $grade_distribution['B']++;
    elseif ($marks >= 60) $grade_distribution['C']++;
    elseif ($marks >= 50) $grade_distribution['D']++;
    else $grade_distribution['F']++;
}

// Recent assignments (last 5)
$result = $conn->query("SELECT * FROM assignments ORDER BY submitted_at DESC LIMIT 5");
while ($result && $row = $result->fetch_assoc()) {
    $recent_assignments[] = $row;
}

// Monthly submissions (last 6 months)
$result = $conn->query("SELECT DATE_FORMAT(submitted_at, '%Y-%m') as month, COUNT(*) as count FROM assignments WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY month ORDER BY month");
while ($result && $row = $result->fetch_assoc()) {
    $monthly_submissions[$row['month']] = $row['count'];
}

$conn->close();
?>

<?php
$page_title = 'Dashboard - School Assignment Management System';
$current_page = 'dashboard';
require_once 'navigation_template.php';
?>

<style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .welcome-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .welcome-title {
            font-size: 36px;
            margin-bottom: 15px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            color: #666;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
            color: white;
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
            border-left: 4px solid #fff;
        }

        .stat-card:hover .stat-number {
            color: white;
        }

        .stat-card:hover .stat-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .stat-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .chart-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 200px;
            margin-bottom: 10px;
        }

        .bar {
            width: 60px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 8px 8px 0 0;
            position: relative;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .bar:hover {
            transform: scaleY(1.05);
        }

        .bar-label {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: #666;
            white-space: nowrap;
        }

        .bar-value {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            font-weight: bold;
            color: #667eea;
        }

        .subject-list {
            list-style: none;
        }

        .subject-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 12px;
            transition: background 0.3s ease;
        }

        .subject-item:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .subject-name {
            font-weight: 600;
            color: #333;
        }

        .subject-stats {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .subject-count {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .subject-avg {
            color: #666;
            font-size: 14px;
        }

        .recent-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #333;
        }

        .recent-list {
            list-style: none;
        }

        .recent-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            margin-bottom: 15px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 12px;
            transition: background 0.3s ease;
        }

        .recent-item:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .recent-info {
            flex: 1;
        }

        .recent-student {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .recent-details {
            color: #666;
            font-size: 14px;
        }

        .recent-marks {
            font-size: 24px;
            font-weight: bold;
            margin-left: 20px;
        }

        .marks-excellent { color: #28a745; }
        .marks-good { color: #ffc107; }
        .marks-poor { color: #dc3545; }

        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 20px;
                box-shadow: 0 2px 20px rgba(0,0,0,0.1);
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

            .welcome-title {
                font-size: 28px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .charts-section {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
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

        <div class="container">
        <div class="welcome-section fade-in">
            <h1 class="welcome-title">Welcome to Assignment Management Dashboard</h1>
            <p class="welcome-subtitle">Monitor and manage student assignments with comprehensive analytics</p>
        </div>

        <div class="stats-grid fade-in">
            <a href="view_assignments.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number"><?php echo $total_assignments; ?></div>
                    <div class="stat-label">Total Assignments</div>
                </div>
            </a>
            <a href="#" onclick="showAverageMarksDetails()" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number"><?php echo $average_marks; ?>%</div>
                    <div class="stat-label">Average Marks</div>
                </div>
            </a>
            <a href="#" onclick="showSubjectsDetails()" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number"><?php echo count($subject_stats); ?></div>
                    <div class="stat-label">Subjects</div>
                </div>
            </a>
            <a href="#" onclick="showGradeADetails()" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number"><?php echo $grade_distribution['A']; ?></div>
                    <div class="stat-label">Grade A Students</div>
                </div>
            </a>
        </div>

        <div class="charts-section fade-in">
            <div class="chart-card">
                <h3 class="chart-title">Grade Distribution</h3>
                <div class="bar-chart">
                    <?php
                    $grades = ['A', 'B', 'C', 'D', 'F'];
                    $max_count = max($grade_distribution);
                    foreach ($grades as $grade) {
                        $count = $grade_distribution[$grade];
                        $height = $max_count > 0 ? ($count / $max_count) * 180 : 0;
                        $color = $grade == 'A' ? '#28a745' : ($grade == 'B' ? '#ffc107' : ($grade == 'C' ? '#17a2b8' : ($grade == 'D' ? '#fd7e14' : '#dc3545')));
                        echo "<div class='bar' style='height: {$height}px; background: {$color};'>
                                <span class='bar-value'>{$count}</span>
                                <span class='bar-label'>Grade {$grade}</span>
                              </div>";
                    }
                    ?>
                </div>
            </div>

            <div class="chart-card">
                <h3 class="chart-title">Subject Performance</h3>
                <ul class="subject-list">
                    <?php foreach ($subject_stats as $subject): ?>
                        <li class="subject-item">
                            <span class="subject-name"><?php echo htmlspecialchars($subject['subject']); ?></span>
                            <div class="subject-stats">
                                <span class="subject-count"><?php echo $subject['count']; ?> assignments</span>
                                <span class="subject-avg">Avg: <?php echo round($subject['avg_marks'], 1); ?>%</span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="recent-section fade-in">
            <h3 class="section-title">Recent Submissions</h3>
            <ul class="recent-list">
                <?php foreach ($recent_assignments as $assignment): ?>
                    <li class="recent-item">
                        <div class="recent-info">
                            <div class="recent-student"><?php echo htmlspecialchars($assignment['student_name']); ?></div>
                            <div class="recent-details">
                                <?php echo htmlspecialchars($assignment['subject']); ?> - <?php echo htmlspecialchars($assignment['assignment_title']); ?>
                                <br>
                                <small>Submitted: <?php echo date('M j, Y H:i', strtotime($assignment['submitted_at'])); ?></small>
                            </div>
                        </div>
                        <div class="recent-marks <?php 
                            echo $assignment['marks'] >= 70 ? 'marks-excellent' : 
                                 ($assignment['marks'] >= 50 ? 'marks-good' : 'marks-poor'); 
                        ?>">
                            <?php echo $assignment['marks']; ?>%
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="action-buttons fade-in">
            <a href="submit_assignment.html" class="btn btn-primary">
                 Add New Assignment
            </a>
            <a href="view_assignments.php" class="btn btn-secondary">
                 View All Assignments
            </a>
        </div>

    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Interactive bar chart
        document.querySelectorAll('.bar').forEach(bar => {
            bar.addEventListener('click', function() {
                const value = this.querySelector('.bar-value').textContent;
                const label = this.querySelector('.bar-label').textContent;
                
                // Create tooltip
                const tooltip = document.createElement('div');
                tooltip.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: rgba(0,0,0,0.8);
                    color: white;
                    padding: 20px;
                    border-radius: 10px;
                    z-index: 10000;
                    animation: fadeIn 0.3s ease;
                `;
                tooltip.innerHTML = `<strong>${label}</strong><br>${value} students`;
                document.body.appendChild(tooltip);
                
                setTimeout(() => {
                    tooltip.remove();
                }, 2000);
            });
        });
    </script>

<script>
    function showAverageMarksDetails() {
        alert('Average Marks: <?php echo $average_marks; ?>%\n\nThis shows the overall performance across all assignments.\n\nClick "View Assignments" to see detailed marks breakdown.');
    }
    
    function showSubjectsDetails() {
        alert('Total Subjects: <?php echo count($subject_stats); ?>\n\nSubjects covered:\n<?php 
        $subjects = array_keys($subject_stats);
        foreach ($subjects as $subject) {
            echo "- " . htmlspecialchars($subject) . "\n";
        }
        ?>\n\nClick "View Assignments" to see assignments by subject.');
    }
    
    function showGradeADetails() {
        alert('Grade A Students: <?php echo $grade_distribution['A']; ?>\n\nStudents achieving excellent performance (Grade A).\n\nGrade Distribution:\nA: <?php echo $grade_distribution['A']; ?> students\nB: <?php echo $grade_distribution['B']; ?> students\nC: <?php echo $grade_distribution['C']; ?> students\nD: <?php echo $grade_distribution['D']; ?> students\nF: <?php echo $grade_distribution['F']; ?> students\n\nClick "View Assignments" to see detailed grade information.');
    }
</script>

<?php require_once 'footer_template.php'; ?>
