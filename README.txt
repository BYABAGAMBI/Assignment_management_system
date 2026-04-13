SCHOOL ASSIGNMENT MANAGEMENT SYSTEM
BIT 2206: Scripting Languages Examination
Mountains of the Moon University
Department of Computer Science

GROUP6 MEMBERS:
1.Byamukama Prince 2024/u/Mmu/BIt/00148 
2.Ruhunda Allan    2024/u/Mmu/BIt/01419
3.Kaddu Geofrey    2024/u/Mmu/BIt/001103
4.Byabagambi Allan  2024/u/Mmu/BIt/00131
5.Karungi Oliver Loy 2024/u/Mmu/BIt/00153
6.Muhulizi Daniel    2024/u/Mmu/BIt/00111
7.Karungi Kellen     2024/u/Mmu/BIt/01415
8.Murungi Susan      2024/u/Mmu/BIt/00110
9.Bright Daniels     2024/u/Mmu/BIt/00128
10.Kampline Ruth      2024/u/Mmu/BIt/00230
11.Kamakune Jacinta   2024/u/Mmu/BIt/00212
12.Erudu Ronald       2024/u/Mmu/BIt/00100
13.Komuhimbo Mable    2024/u/Mmu/BIt/00132
14.Agondeze David     2024/u/Mmu/BIt/00152

PROJECT OVERVIEW:
This is a School Assignment Management System built for teachers to manage student assignments.
The system includes client-side validation (JavaScript), server-side processing (PHP), and database storage (MySQL).

FILES DESCRIPTION:

1. login.php (NEW)
   - Teacher authentication page with modern design
   - Simple login form with username/password validation
   - Demo credentials displayed for easy access
   - Redirects to requested page after successful login
   - Features glassmorphism design and animations

2. auth_check.php (NEW)
   - Session management and authentication logic
   - Protects restricted pages from unauthorized access
   - Handles logout functionality
   - Redirects unauthenticated users to login page

3. dashboard.php (ENHANCED)
   - Main analytics and overview page for teachers
   - Real-time statistics (total assignments, average marks, etc.)
   - Interactive grade distribution chart
   - Subject performance metrics
   - Recent submissions display
   - Requires teacher authentication

4. submit_assignment.html
   - Main assignment submission form with JavaScript validation
   - Features dynamic subject filtering for sample assignments
   - Validates all form fields client-side before submission
   - Displays error messages next to invalid fields
   - Includes 5 hardcoded sample assignments with radio button filtering
   - Enhanced with modern styling and navigation

5. save_assignment.php (PROTECTED)
   - Handles form submission from submit_assignment.html
   - Performs server-side validation on all submitted data
   - Inserts valid assignments into MySQL database
   - Displays success/error messages and submitted details
   - Provides navigation links to other pages
   - Now requires teacher authentication to access

6. view_assignments.php (PROTECTED)
   - Displays all assignments from database in HTML table
   - Features search functionality by student name or subject
   - Shows assignment statistics (total count, average marks, subjects)
   - Color-coded marks display (green for 70+, yellow for 50-69, red for <50)
   - Includes responsive design for mobile devices
   - Now requires teacher authentication to access

7. setup.sql
   - MySQL script to create database and table structure
   - Includes constraints and indexes for data integrity
   - Contains 15 sample assignment records
   - Creates database: school_assignments_db
   - Creates table: assignments with proper column types and constraints

SYSTEM REQUIREMENTS:
- Web server (Apache/Nginx)
- PHP 7.0 or higher
- MySQL 5.7 or higher
- Modern web browser with JavaScript enabled

INSTALLATION INSTRUCTIONS:

Step 1: Database Setup
1. Open MySQL command line or phpMyAdmin
2. Run the setup.sql script:
   - In MySQL command line: mysql -u root -p < setup.sql
   - In phpMyAdmin: Import the setup.sql file
3. Verify database 'school_assignments_db' and table 'assignments' are created
4. Check that sample data (15 records) is inserted

Step 2: Web Server Setup
1. Place all files in your web server's document root (e.g., htdocs, www)
2. Ensure PHP is properly configured on your server
3. Make sure MySQL extension is enabled in PHP

Step 3: Configuration
1. Update database connection settings in save_assignment.php and view_assignments.php if needed:
   - $host: usually 'localhost'
   - $username: usually 'root'
   - $password: your MySQL password (default is empty)
   - $database: 'school_assignments_db'

Step 4: Access the System
1. Open your web browser
2. Navigate to: http://localhost/submit_assignment.html
3. Start using the system!

FEATURES DEMONSTRATION:

Client-Side Features (JavaScript):
- Form validation with real-time error messages
- Dynamic subject filtering with radio buttons
- Input field restrictions (letters only for name, digits for ID)
- Date validation (no past dates)
- Marks range validation (0-100)

Server-Side Features (PHP):
- Data validation and sanitization
- Database insertion with prepared statements
- Search functionality with SQL LIKE queries
- Session management ready for login extension
- Error handling and user feedback

Database Features (MySQL):
- Structured data storage with constraints
- Auto-incrementing primary keys
- Timestamp tracking for submissions
- Indexes for improved query performance
- Data integrity checks

TESTING INSTRUCTIONS:

1. Form Validation Testing:
   - Try submitting empty fields (should show errors)
   - Enter invalid student name (less than 3 chars or numbers)
   - Enter invalid student ID (not 8 digits)
   - Select past date (should show error)
   - Enter marks outside 0-100 range

2. Dynamic Filter Testing:
   - Click "Show All" radio button (should show all 5 samples)
   - Click "Show Mathematics" (should show only Math assignments)
   - Click "Show Science" (should show only Science assignments)

3. Database Operations:
   - Submit a valid assignment and check if it appears in view_assignments.php
   - Search by student name (partial matches should work)
   - Search by subject name
   - Verify statistics are calculated correctly

4. Authentication System (BONUS CHALLENGE):
   - Try accessing dashboard.php without login (should redirect to login.php)
   - Try accessing save_assignment.php without login (should redirect to login.php)
   - Try accessing view_assignments.php without login (should redirect to login.php)
   - Login with credentials: username: teacher, password: school123
   - Verify logout functionality works correctly
   - Test redirect after login to originally requested page

5. Error Handling:
   - Test with invalid data to see server-side validation
   - Test database connection errors (if applicable)

PRESENTATION TIPS:
1. Demonstrate all validation features with invalid inputs
2. Show the dynamic subject filtering in action
3. Submit a real assignment and show it appearing in the view page
4. Demonstrate the search functionality
5. Explain the database structure and sample data
6. Discuss challenges faced during development

COMMON CHALLENGES AND SOLUTIONS:
Challenge 1: JavaScript validation vs PHP validation
Solution: Implemented both client-side for user experience and server-side for security

Challenge 2: Database connection issues
Solution: Added proper error handling and connection checks

Challenge 3: Form data persistence on validation errors
Solution: Used PHP to repopulate form fields with submitted values

Challenge 4: Responsive design for mobile devices
Solution: Added CSS media queries and flexible layouts

Challenge 5: Session management and authentication
Solution: Implemented PHP sessions with proper security checks and redirects

BONUS CHALLENGE COMPLETED (+2 Extra Marks):
✅ Teacher Login System Successfully Implemented:
1. ✅ Created login.php with modern glassmorphism design
2. ✅ Implemented PHP sessions for authentication
3. ✅ Added session checks to save_assignment.php and view_assignments.php
4. ✅ Created logout functionality with session destruction
5. ✅ Added auth_check.php for centralized authentication logic
6. ✅ Protected dashboard.php with authentication
7. ✅ Updated navigation to show login/logout states
8. ✅ Added redirect functionality to original requested page after login

Login Credentials:
- Username: teacher
- Password: school123

Features:
- Secure session-based authentication
- Automatic redirect to login for unauthorized access
- Smart redirect to original page after successful login
- Logout functionality on all protected pages
- Modern UI with glassmorphism effects

SUPPORT:
If you encounter any issues:
1. Check PHP error logs
2. Verify database connection details
3. Ensure all files are in the correct directory
4. Test database setup with setup.sql script

SUBMISSION CHECKLIST:
☑ login.php (authentication page with modern design)
☑ auth_check.php (session management and protection)
☑ dashboard.php (enhanced with analytics and authentication)
☑ submit_assignment.html (with validation and filtering)
☑ save_assignment.php (protected with authentication)
☑ view_assignments.php (protected with authentication)
☑ setup.sql (database structure and sample data)
☑ README.txt (updated with complete instructions)
☑ All files tested and working
☑ Bonus challenge completed (+2 extra marks)
☑ Presentation prepared (10-12 minutes)

