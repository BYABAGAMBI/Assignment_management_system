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

// Initialize variables and error messages
$student_name = $student_id = $subject = $assignment_title = $due_date = $marks_obtained = $remarks = '';
$errors = array();

// Server-side validation when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate Student Name
    if (empty($_POST['student_name'])) {
        $errors['student_name'] = "Student name is required";
    } else {
        $student_name = trim($_POST['student_name']);
        if (strlen($student_name) < 3) {
            $errors['student_name'] = "Student name must be at least 3 characters";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $student_name)) {
            $errors['student_name'] = "Student name must contain letters only";
        }
    }
    
    // Validate Student ID
    if (empty($_POST['student_id'])) {
        $errors['student_id'] = "Student ID is required";
    } else {
        $student_id = trim($_POST['student_id']);
        if (!preg_match("/^\d{8}$/", $student_id)) {
            $errors['student_id'] = "Student ID must be exactly 8 digits";
        }
    }
    
    // Validate Subject
    if (empty($_POST['subject'])) {
        $errors['subject'] = "Please select a subject";
    } else {
        $subject = $_POST['subject'];
        $valid_subjects = array('Mathematics', 'English', 'Science', 'History', 'Computer Science');
        if (!in_array($subject, $valid_subjects)) {
            $errors['subject'] = "Invalid subject selected";
        }
    }
    
    // Validate Assignment Title
    if (empty($_POST['assignment_title'])) {
        $errors['assignment_title'] = "Assignment title is required";
    } else {
        $assignment_title = trim($_POST['assignment_title']);
        if (strlen($assignment_title) < 5) {
            $errors['assignment_title'] = "Assignment title must be at least 5 characters";
        }
    }
    
    // Validate Due Date
    if (empty($_POST['due_date'])) {
        $errors['due_date'] = "Due date is required";
    } else {
        $due_date = $_POST['due_date'];
        $selected_date = new DateTime($due_date);
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        
        if ($selected_date < $today) {
            $errors['due_date'] = "Due date cannot be in the past";
        }
    }
    
    // Validate Marks Obtained
    if (empty($_POST['marks_obtained'])) {
        $errors['marks_obtained'] = "Marks obtained is required";
    } else {
        $marks_obtained = $_POST['marks_obtained'];
        if (!is_numeric($marks_obtained) || $marks_obtained < 0 || $marks_obtained > 100) {
            $errors['marks_obtained'] = "Marks must be between 0 and 100";
        }
    }
    
    // Remarks (optional - no validation needed)
    $remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';
    
    // If there are no errors, insert into database
    if (empty($errors)) {
        
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO assignments (student_name, student_id, subject, assignment_title, due_date, marks, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("sssssds", $student_name, $student_id, $subject, $assignment_title, $due_date, $marks_obtained, $remarks);
        
        // Execute the statement
        if ($stmt->execute()) {
            $success_message = "Assignment submitted successfully!";
            $stmt->close();
        } else {
            $error_message = "Error: " . $stmt->error;
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Assignment - School Management System</title>
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
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .nav-menu a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .nav-menu a:hover, .nav-menu a.active {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .main-container {
            max-width: 800px;
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
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border: none;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.2);
        }
        .error {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            padding: 20px;
            border: none;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.2);
        }
        .field-error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 8px;
            display: block;
            font-weight: 500;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        input[type="text"], input[type="date"], input[type="number"], select, textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        input:focus, select:focus, textarea:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        .submit-btn {
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
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .details {
            background: rgba(102, 126, 234, 0.05);
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
            border: 2px solid rgba(102, 126, 234, 0.1);
        }
        .details h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
            text-align: center;
        }
        .details p {
            margin: 12px 0;
            color: #555;
            padding: 10px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
        }
        .details strong {
            color: #667eea;
            font-weight: 600;
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
        .primary-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
        }
        .primary-btn:hover {
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .secondary-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid #667eea;
        }
        .secondary-btn:hover {
            background: #667eea;
            color: white;
        }
    @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .main-container {
                margin: 20px auto;
            }

            .container {
                padding: 25px;
            }

            h1 {
                font-size: 28px;
            }

            .navigation {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .navigation a {
                width: 100%;
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
    <header class="header">
        <div class="header-content">
            <div class="logo"> Assignment Management System</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="submit_assignment.html">Submit Assignment</a></li>
                    <li><a href="view_assignments.php">View Assignments</a></li>
                    <li><a href="save_assignment.php?logout=true" style="color: #e74c3c;"> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="main-container">
        <div class="container fade-in">
        <h1>Assignment Submission Result</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            
            <div class="details">
                <h3>Submitted Assignment Details:</h3>
                <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></p>
                <p><strong>Subject:</strong> <?php echo htmlspecialchars($subject); ?></p>
                <p><strong>Assignment Title:</strong> <?php echo htmlspecialchars($assignment_title); ?></p>
                <p><strong>Due Date:</strong> <?php echo htmlspecialchars($due_date); ?></p>
                <p><strong>Marks Obtained:</strong> <?php echo htmlspecialchars($marks_obtained); ?>/100</p>
                <?php if (!empty($remarks)): ?>
                    <p><strong>Remarks:</strong> <?php echo htmlspecialchars($remarks); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="navigation">
                <a href="submit_assignment.html" class="secondary-btn"> Submit Another Assignment</a>
                <a href="view_assignments.php" class="primary-btn"> View All Assignments</a>
                <a href="dashboard.php" class="secondary-btn"> Dashboard</a>
            </div>
            
        <?php elseif (isset($error_message)): ?>
            <div class="error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <div class="navigation">
                <a href="submit_assignment.html" class="secondary-btn">🔙 Go Back to Form</a>
                <a href="dashboard.php" class="primary-btn"> Dashboard</a>
            </div>
            
        <?php elseif (!empty($errors)): ?>
            <div class="error">
                Please correct the following errors:
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="student_name">Student Name:</label>
                    <input type="text" id="student_name" name="student_name" value="<?php echo htmlspecialchars($student_name); ?>">
                    <?php if (isset($errors['student_name'])): ?>
                        <span class="field-error"><?php echo htmlspecialchars($errors['student_name']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="student_id">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                    <?php if (isset($errors['student_id'])): ?>
                        <span class="field-error"><?php echo htmlspecialchars($errors['student_id']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <select id="subject" name="subject">
                        <option value="">-- Select Subject --</option>
                        <option value="Mathematics" <?php echo ($subject == 'Mathematics') ? 'selected' : ''; ?>>Mathematics</option>
                        <option value="English" <?php echo ($subject == 'English') ? 'selected' : ''; ?>>English</option>
                        <option value="Science" <?php echo ($subject == 'Science') ? 'selected' : ''; ?>>Science</option>
                        <option value="History" <?php echo ($subject == 'History') ? 'selected' : ''; ?>>History</option>
                        <option value="Computer Science" <?php echo ($subject == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                    </select>
                    <?php if (isset($errors['subject'])): ?>
                        <span class="field-error"><?php echo htmlspecialchars($errors['subject']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="assignment_title">Assignment Title:</label>
                    <input type="text" id="assignment_title" name="assignment_title" value="<?php echo htmlspecialchars($assignment_title); ?>">
                    <?php if (isset($errors['assignment_title'])): ?>
                        <span class="field-error"><?php echo htmlspecialchars($errors['assignment_title']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="due_date">Due Date:</label>
                    <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>">
                    <?php if (isset($errors['due_date'])): ?>
                        <span class="field-error"><?php echo htmlspecialchars($errors['due_date']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="marks_obtained">Marks Obtained:</label>
                    <input type="number" id="marks_obtained" name="marks_obtained" value="<?php echo htmlspecialchars($marks_obtained); ?>" min="0" max="100">
                    <?php if (isset($errors['marks_obtained'])): ?>
                        <span class="field-error"><?php echo htmlspecialchars($errors['marks_obtained']); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks:</label>
                    <textarea id="remarks" name="remarks" rows="4"><?php echo htmlspecialchars($remarks); ?></textarea>
                </div>

                <button type="submit" class="submit-btn">Submit Assignment</button>
            </form>
            
        <?php else: ?>
            <div class="error">
                No data submitted. Please go back and fill out the form.
            </div>
            <div class="navigation">
                <a href="submit_assignment.html" class="secondary-btn"> Go Back to Form</a>
                <a href="dashboard.php" class="primary-btn"> Dashboard</a>
            </div>
        <?php endif; ?>
        </div>
    </div>
</body>
</html>
