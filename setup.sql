-- School Assignment Management System Database Setup
-- BIT 2206: Scripting Languages Examination
-- Mountains of the Moon University

-- Create database
CREATE DATABASE IF NOT EXISTS school_assignments_db;

-- Use the database
USE school_assignments_db;

-- Create assignments table
CREATE TABLE IF NOT EXISTS assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_name VARCHAR(100) NOT NULL,
    student_id VARCHAR(8) NOT NULL,
    subject VARCHAR(30) NOT NULL,
    assignment_title VARCHAR(200) NOT NULL,
    due_date DATE NOT NULL,
    marks INT NOT NULL,
    remarks TEXT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Add constraints
    CONSTRAINT chk_marks CHECK (marks >= 0 AND marks <= 100),
    CONSTRAINT chk_student_id_length CHECK (CHAR_LENGTH(student_id) = 8),
    CONSTRAINT chk_student_id_digits CHECK (student_id REGEXP '^[0-9]{8}$')
);

-- Demo data removed - database will start empty

-- Create indexes for better performance
CREATE INDEX idx_student_name ON assignments(student_name);
CREATE INDEX idx_subject ON assignments(subject);
CREATE INDEX idx_due_date ON assignments(due_date);
CREATE INDEX idx_submitted_at ON assignments(submitted_at);

-- Display success message
SELECT 'Database setup completed successfully!' AS message;
SELECT 'Database is ready for use - no sample data included' AS status;
