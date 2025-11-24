-------------------------------------------
-- DATABASE CREATION
-------------------------------------------
CREATE DATABASE IF NOT EXISTS dams_db;
USE dams_db;

-------------------------------------------
-- USERS TABLE (Admin, Faculty, Student Login)
-------------------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin','faculty','student') NOT NULL
);

-- SAMPLE LOGIN ACCOUNTS
INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('faculty1', 'fac123', 'faculty'),
('faculty2', 'fac123', 'faculty'),
('2301', 'stud123', 'student'),
('2302', 'stud123', 'student'),
('2303', 'stud123', 'student');

-------------------------------------------
-- STUDENTS TABLE
-------------------------------------------
DROP TABLE IF EXISTS students;
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    roll VARCHAR(50) UNIQUE NOT NULL,
    course VARCHAR(100) NOT NULL
);

-- SAMPLE STUDENTS
INSERT INTO students (name, roll, course) VALUES
('Amit Sharma', '2301', 'Computer Engineering'),
('Pooja Patil', '2302', 'Computer Engineering'),
('Rohan Deshmukh', '2303', 'Computer Engineering');

-------------------------------------------
-- FACULTY TABLE
-------------------------------------------
DROP TABLE IF EXISTS faculty;
CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE
);

-- SAMPLE FACULTY
INSERT INTO faculty (name, department, username) VALUES
('Dr. Kavita Joshi', 'Computer Engineering', 'faculty1'),
('Prof. Sandeep Rao', 'Computer Engineering', 'faculty2');

-------------------------------------------
-- SUBJECTS TABLE
-------------------------------------------
DROP TABLE IF EXISTS subjects;
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    faculty_id INT,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- SAMPLE SUBJECTS
INSERT INTO subjects (subject_name, faculty_id) VALUES
('Data Structures', 1),
('Computer Networks', 2),
('Operating Systems', 1);

-------------------------------------------
-- RESULTS TABLE
-------------------------------------------
DROP TABLE IF EXISTS results;
CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    marks INT NOT NULL,
    total_marks INT NOT NULL DEFAULT 100,
    semester VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- SAMPLE RESULTS
INSERT INTO results (student_id, subject_id, marks, total_marks, semester) VALUES
(1, 1, 45, 50, '3'),
(2, 2, 78, 100, '3'),
(3, 3, 60, 75, '3');

-------------------------------------------
-- ASSIGNMENTS TABLE
-------------------------------------------
DROP TABLE IF EXISTS assignments;
CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    subject_id INT,
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- SAMPLE ASSIGNMENTS
INSERT INTO assignments (title, description, subject_id, due_date) VALUES
('DS Assignment 1', 'Linked List Implementations', 1, '2025-02-20'),
('CN Assignment 1', 'Network Layer Protocols', 2, '2025-02-18');

-------------------------------------------
-- SUBMISSIONS TABLE
-------------------------------------------
DROP TABLE IF EXISTS submissions;
CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    file_path VARCHAR(255),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending','Approved') DEFAULT 'Pending',
    FOREIGN KEY (assignment_id) REFERENCES assignments(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
);

-- SAMPLE SUBMISSIONS
INSERT INTO submissions (assignment_id, student_id, file_path, status) VALUES
(1, 1, 'uploads/amit_ds.pdf', 'Pending'),
(1, 2, 'uploads/pooja_ds.pdf', 'Approved');