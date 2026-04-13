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

// Initialize search variables
$search_term = '';
$where_clause = '';

// Handle search functionality
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = trim($_GET['search']);
    $search_term = $conn->real_escape_string($search_term);
    $where_clause = "WHERE student_name LIKE '%$search_term%' OR subject LIKE '%$search_term%'";
}

// Query to get assignments
$query = "SELECT * FROM assignments $where_clause ORDER BY submitted_at DESC";
$result = $conn->query($query);

$conn->close();
?>

<?php
$page_title = 'View Assignments - School Management System';
$current_page = 'view_assignments';
require_once 'navigation_template.php';
?>

<style>
    .main-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 40px;
            font-size: 32px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .search-container {
            margin-bottom: 40px;
            text-align: center;
        }
        .search-form {
            display: flex;
            justify-content: center;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .search-input {
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            width: 350px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        .search-input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        .search-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .clear-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            padding: 15px 30px;
            border: 2px solid #667eea;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .clear-btn:hover {
            background: #667eea;
            color: white;
        }
        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e1e5e9;
        }
        th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        tr:nth-child(even) {
            background-color: rgba(102, 126, 234, 0.05);
        }
        tr:hover {
            background-color: rgba(102, 126, 234, 0.1);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        .no-assignments {
            text-align: center;
            color: #666;
            font-size: 18px;
            padding: 60px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 15px;
            border: 2px dashed rgba(102, 126, 234, 0.3);
        }
        .navigation {
            text-align: center;
            margin-top: 40px;
        }
        .navigation a {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 12px;
            margin: 0 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .navigation a:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .add-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }
        .add-btn:hover {
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }
        .view-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid #667eea;
        }
        .view-btn:hover {
            background: #667eea;
            color: white;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 40px;
            text-align: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .stat-item {
            background: rgba(255, 255, 255, 0.8);
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            min-width: 200px;
        }
        .stat-item:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            color: #666;
            margin-top: 8px;
            font-weight: 500;
        }
        .search-info {
            text-align: center;
            margin-bottom: 30px;
            color: #666;
            font-style: italic;
            background: rgba(102, 126, 234, 0.05);
            padding: 15px;
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .main-container {
                margin: 20px auto;
            }

            .container {
                padding: 25px;
            }

            .search-form {
                flex-direction: column;
            }
            .search-input {
                width: 100%;
            }
            .stats {
                flex-direction: column;
                gap: 15px;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
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

<div class="main-container">
        <div class="container fade-in">
        <h1>View All Assignments</h1>
        
        <?php if ($search_term): ?>
            <div class="search-info">
                Showing results for: "<strong><?php echo htmlspecialchars($search_term); ?></strong>"
            </div>
        <?php endif; ?>
        
        <div class="search-container">
            <form method="GET" class="search-form">
                <input type="text" name="search" class="search-input" 
                       placeholder="Search by student name or subject..." 
                       value="<?php echo htmlspecialchars($search_term); ?>">
                <button type="submit" class="search-btn">Search</button>
                <?php if ($search_term): ?>
                    <a href="view_assignments.php" class="clear-btn">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php
            // Calculate statistics
            $total_assignments = $result->num_rows;
            $average_marks = 0;
            $subject_counts = array();
            
            $result->data_seek(0); // Reset result pointer
            $total_marks = 0;
            while ($row = $result->fetch_assoc()) {
                $total_marks += $row['marks'];
                $subject = $row['subject'];
                if (!isset($subject_counts[$subject])) {
                    $subject_counts[$subject] = 0;
                }
                $subject_counts[$subject]++;
            }
            $average_marks = $total_assignments > 0 ? round($total_marks / $total_assignments, 1) : 0;
            
            // Reset result pointer again for table display
            $result->data_seek(0);
            ?>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_assignments; ?></div>
                    <div class="stat-label">Total Assignments</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $average_marks; ?></div>
                    <div class="stat-label">Average Marks</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($subject_counts); ?></div>
                    <div class="stat-label">Subjects</div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Subject</th>
                            <th>Assignment Title</th>
                            <th>Due Date</th>
                            <th>Marks</th>
                            <th>Remarks</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $row_number = 1;
                        while ($row = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo $row_number; ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['assignment_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                                <td>
                                    <span style="color: <?php echo ($row['marks'] >= 70) ? '#28a745' : (($row['marks'] >= 50) ? '#ffc107' : '#dc3545'); ?>; font-weight: bold;">
                                        <?php echo htmlspecialchars($row['marks']); ?>/100
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['remarks'] ?: 'N/A'); ?></td>
                                <td><?php echo date('M j, Y H:i', strtotime($row['submitted_at'])); ?></td>
                            </tr>
                        <?php
                        $row_number++;
                        endwhile;
                        ?>
                    </tbody>
                </table>
            </div>
            
        <?php else: ?>
            <div class="no-assignments">
                <?php if ($search_term): ?>
                    No assignments found matching "<?php echo htmlspecialchars($search_term); ?>".
                <?php else: ?>
                    No assignments found in the database.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        </div>

        <div class="navigation">
            <a href="submit_assignment.html" class="add-btn"> Add New Assignment</a>
            <a href="view_assignments.php" class="view-btn"> Refresh List</a>
            <a href="dashboard.php" class="view-btn"> Dashboard</a>
        </div>
    </div>

    <script>
        // Auto-focus on search input when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.focus();
            }
        });

        // Clear search on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const searchInput = document.querySelector('.search-input');
                if (searchInput && searchInput.value) {
                    window.location.href = 'view_assignments.php';
                }
            }
        });
    </script>

<?php require_once 'footer_template.php'; ?>
