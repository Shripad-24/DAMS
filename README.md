# 📚 DAMS — Departmental Academics Management System

A role-based academic management web application built with **PHP** and **MySQL**. DAMS enables departments to manage students, faculty, subjects, assignments, submissions, and results through an intuitive, Bootstrap-powered interface.

---

## ✨ Features

### 🔐 Authentication & Authorization
- Session-based login system with role-based access control
- Three user roles: **Admin**, **Faculty**, and **Student**
- Role-specific dashboards and navigation

### 👨‍💼 Admin Capabilities
- **Dashboard** — Overview stats (students, faculty, subjects, assignments) with recent activity
- **Manage Students** — Add new students with auto-generated login credentials
- **Manage Faculty** — Add new faculty members with auto-generated login credentials
- **Manage Subjects** — Create subjects and assign faculty
- **Manage Results** — Record and view marks for all students
- **Manage Assignments** — Create assignments, view all, and manage submissions

### 👩‍🏫 Faculty Capabilities
- **Dashboard** — View assigned subjects and pending submissions with quick-action buttons
- **Students** — Add and view students
- **Subjects** — View assigned subjects
- **Results** — Add and view student marks
- **Assignments** — Create assignments, view all, and approve/manage submissions

### 🎓 Student Capabilities
- **Dashboard** — View latest assignments and recent marks at a glance
- **Assignments** — Browse all assignments in a card-based layout
- **Submit Assignments** — Upload files (PDF, ZIP, DOC, DOCX, PPT, PPTX — max 10 MB)
- **Results** — View personal semester-wise marks and academic performance

---

## 🛠️ Tech Stack

| Layer       | Technology                            |
| ----------- | ------------------------------------- |
| **Backend** | PHP (vanilla, no framework)           |
| **Database**| MySQL (`mysqli` extension)            |
| **Frontend**| HTML5, Bootstrap 5.3.2 (CDN)          |
| **Server**  | XAMPP (Apache + MySQL)                |
| **Auth**    | PHP Sessions (`$_SESSION`)            |

---

## 📁 Project Structure

```
dams/
├── index.php                    # Entry point — redirects to login page
├── sql_queries.sql              # Database schema + sample seed data
├── README.md
│
├── config/
│   └── db.php                   # MySQL database connection
│
├── includes/
│   ├── header.php               # HTML head with Bootstrap CSS
│   ├── navbar.php               # Role-aware responsive navigation bar
│   └── footer.php               # Bootstrap JS bundle + closing tags
│
├── pages/
│   ├── login.php                # Login form and authentication logic
│   ├── logout.php               # Session destroy and redirect
│   ├── dashboard.php            # Generic dashboard (summary counts)
│   ├── dashboard_admin.php      # Admin-specific dashboard
│   ├── dashboard_faculty.php    # Faculty-specific dashboard
│   ├── dashboard_student.php    # Student-specific dashboard
│   │
│   ├── students/
│   │   ├── add.php              # Add student + create login
│   │   └── view.php             # View all students
│   │
│   ├── faculty/
│   │   ├── add.php              # Add faculty + create login
│   │   └── view.php             # View all faculty
│   │
│   ├── subjects/
│   │   ├── add.php              # Add subject with faculty assignment
│   │   └── view.php             # View all subjects
│   │
│   ├── results/
│   │   ├── add.php              # Record marks for a student
│   │   ├── view.php             # View all results (admin/faculty)
│   │   └── student_result.php   # Student views own results
│   │
│   └── assignments/
│       ├── add.php              # Create an assignment
│       ├── view.php             # View all assignments (admin/faculty)
│       ├── student_view.php     # Student views assignments (cards)
│       ├── submit.php           # Student uploads assignment file
│       └── submissions.php      # Manage & approve submissions
│
├── assets/
│   ├── css/
│   │   └── style.css            # Custom styles
│   └── js/
│       └── main.js              # Custom scripts (placeholder)
│
└── uploads/
    └── assignments/             # Uploaded assignment files
```

---

## 🗄️ Database Schema

**Database:** `dams_db`

| Table           | Description                                      |
| --------------- | ------------------------------------------------ |
| `users`         | Login credentials (username, password, role)      |
| `students`      | Student records (name, roll number, course)       |
| `faculty`       | Faculty records (name, department, username)      |
| `subjects`      | Subjects with assigned faculty                    |
| `results`       | Student marks per subject and semester            |
| `assignments`   | Assignments with title, description, and due date |
| `submissions`   | Student file submissions with approval status     |

### Entity Relationships

```
users ──────┬──── students    (username = roll)
            └──── faculty     (username = faculty.username)

faculty ────────── subjects   (faculty_id → faculty.id)

subjects ───┬──── results     (subject_id → subjects.id)
            └──── assignments (subject_id → subjects.id)

students ───┬──── results     (student_id → students.id)
            └──── submissions (student_id → students.id)

assignments ────── submissions (assignment_id → assignments.id)
```

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL)
- A web browser

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/Shripad-24/Depertmental--Academics-Management-System.git dams
   ```

2. **Move to XAMPP's web directory**

   Copy or clone the project into your XAMPP `htdocs` folder:

   ```
   C:\xampp\htdocs\dams\
   ```

3. **Start XAMPP services**

   Open XAMPP Control Panel and start **Apache** and **MySQL**.

4. **Create the database**

   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Import the `sql_queries.sql` file — this will create the `dams_db` database, all tables, and seed sample data

5. **Configure the database connection** (if needed)

   Edit `config/db.php` to match your MySQL credentials:

   ```php
   $conn = new mysqli("127.0.0.1", "root", "root", "dams_db");
   ```

   > The default credentials are `root` / `root`. Update them if your XAMPP uses different credentials.

6. **Access the application**

   Open your browser and go to:

   ```
   http://localhost/dams/
   ```

---

## 🔑 Default Login Credentials

The SQL seed data includes these pre-configured accounts:

| Role      | Username   | Password   |
| --------- | ---------- | ---------- |
| Admin     | `admin`    | `admin123` |
| Faculty   | `faculty1` | `fac123`   |
| Faculty   | `faculty2` | `fac123`   |
| Student   | `2301`     | `stud123`  |
| Student   | `2302`     | `stud123`  |
| Student   | `2303`     | `stud123`  |

---

## ⚠️ Important Notes

- **Passwords** are stored in **plain text** in this version. For production use, implement password hashing (e.g., `password_hash()` / `password_verify()`).
- **Student linkage** — A student's `username` in the `users` table must match their `roll` number in the `students` table for dashboards and results to function correctly.
- **Faculty linkage** — A faculty member's `username` in the `users` table must match the `username` column in the `faculty` table.
- This project is intended for **educational/demo purposes**.

---

## 📄 License

This project is open source and available for educational use.

---

<p align="center">
  Made with ❤️ for academic management
</p>
