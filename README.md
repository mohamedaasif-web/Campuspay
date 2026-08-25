# CampusPay – Fee Payment Management System

> A simple, responsive and user-friendly web application for managing students, academic details, fee structures, payments, receipts and reports.

## 📌 Project Overview

**CampusPay** is an academic fee payment management system designed for educational institutions.

It provides a centralized platform to manage:

- Student information
- Departments
- Courses
- Academic Years
- Fee Structures
- Fee Payments
- Payment History
- Receipts
- Reports
- Settings
- Administrator authentication

The project is developed as an **intermediate-level Full Stack Web Development academic project**.

## 🎯 Objectives

1. Digitize student and fee records.
2. Reduce manual paperwork.
3. Maintain organized student information.
4. Manage department, course and academic-year information.
5. Maintain fee structures.
6. Record student payments accurately.
7. Track payment history.
8. Provide printable payment receipts.
9. Generate useful fee-related reports.
10. Provide a simple responsive administration interface.

## ✨ Main Features

### 🔐 Admin Authentication
- Administrator login
- Username/password authentication
- Session-based access control
- Protected pages
- Logout

### 📊 Dashboard
- Total Students
- Total Departments
- Total Courses
- Total Fee Structures
- Total Payments
- Pending Fees
- Recent Payments
- Quick Actions

### 👨‍🎓 Students
Manage:
- Student ID
- Register Number
- Admission Number
- Student Name
- Gender
- Date of Birth
- Mobile
- Email
- Address
- Department
- Course
- Academic Year
- Semester
- Admission Date
- Student Photo
- Status

Operations: **Add, View, Search, Edit, Delete**

### 🏢 Departments
Manage academic departments.

Operations: **Add, View, Search, Edit, Delete**

### 📚 Courses
Manage courses and their departments.

Operations: **Add, View, Search, Edit, Delete**

### 📅 Academic Years
Manage years such as:

```text
2024-2025
2025-2026
2026-2027
```

### 💰 Fee Structure
Manage fees by:
- Department
- Course
- Academic Year
- Semester
- Tuition Fee
- Other Fees
- Total Fee

### 💳 Fee Payment
Record:
- Student
- Fee Structure
- Payment Date
- Amount
- Payment Method
- Transaction Reference
- Remarks

Payment methods may include Cash, UPI, Bank Transfer, Card and Other.

### 📜 Fee History
View previous payments with receipt number, student, date, amount, method, reference and status.

### 🧾 Receipt
Manage printable receipts containing:
- Receipt Number
- Student details
- Department
- Course
- Academic Year
- Semester
- Payment Date
- Paid Amount
- Payment Method
- Transaction Reference

### 📊 Reports
Possible reports:
- Student Fee Report
- Payment Report
- Pending Fee Report
- Department-wise Report
- Course-wise Report
- Academic Year Report
- Date-wise Payment Report

### ⚙️ Settings
Manage:
- Institution Name
- Address
- Contact Number
- Email
- Receipt Settings
- System Preferences

## 🛠️ Technology Stack

### Frontend
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Bootstrap Icons

### Backend
- PHP 8+
- MySQL / MariaDB

### Development
- XAMPP
- Apache
- MySQL
- phpMyAdmin
- Visual Studio Code

### Version Control
- Git
- GitHub

## 🗄️ Database

Database name:

```text
fee_payment_system
```

Main tables:

```text
admin
departments
courses
academic_years
students
fee_structure
fee_payments
payment_history
receipts
reports
settings
activity_logs
```

### Basic Relationship

```text
Departments
     │
     └── Courses
           │
           └── Students
                 │
                 ├── Fee Payments
                 ├── Payment History
                 └── Receipts

Academic Years
     │
     └── Students / Fee Structure
```

## 📁 Project Structure

```text
CampusPay/
│
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── dashboard.css
│   │   └── responsive.css
│   ├── js/
│   │   ├── main.js
│   │   └── validation.js
│   └── images/
│
├── config/
│   ├── database.php
│   └── session.php
│
├── includes/
│   ├── header.php
│   ├── navbar.php
│   ├── sidebar.php
│   └── footer.php
│
├── students/
│   ├── student_list.php
│   ├── add_student.php
│   ├── view_student.php
│   ├── edit_student.php
│   ├── delete_student.php
│   └── save_student.php
│
├── department/
│   ├── department_list.php
│   ├── add_department.php
│   ├── edit_department.php
│   ├── delete_department.php
│   └── save_department.php
│
├── course/
│   ├── course_list.php
│   ├── add_course.php
│   ├── edit_course.php
│   ├── delete_course.php
│   └── save_course.php
│
├── academic_year/
│   ├── academic_year_list.php
│   ├── add_academic_year.php
│   ├── edit_academic_year.php
│   ├── delete_academic_year.php
│   └── save_academic_year.php
│
├── fee_structure/
│   ├── fee_structure_list.php
│   ├── add_fee_structure.php
│   ├── edit_fee_structure.php
│   ├── delete_fee_structure.php
│   └── save_fee_structure.php
│
├── fee_payment/
│   ├── fee_payment.php
│   └── save_payment.php
│
├── fee_history/
│   └── fee_history.php
│
├── receipt/
│   ├── receipt.php
│   └── generate_receipt.php
│
├── reports/
│   ├── reports.php
│   └── payment_report.php
│
├── settings/
│   └── settings.php
│
├── uploads/
│   └── students/
│
├── database/
│   └── fee_payment_system.sql
│
├── login.php
├── authenticate.php
├── dashboard.php
├── logout.php
└── README.md
```

> Adjust folder names if your actual implementation differs.

## 💻 Installation & Setup

### 1. Install XAMPP

Start:

```text
Apache
MySQL
```

### 2. Copy the Project

Place it inside:

```text
C:\xampp\htdocs\
```

Example:

```text
C:\xampp\htdocs\CampusPay\
```

### 3. Create Database

Open:

```text
http://localhost/phpmyadmin
```

Create:

```text
fee_payment_system
```

### 4. Import SQL

Select the database → **Import** → choose:

```text
database/fee_payment_system.sql
```

Then execute the import.

### 5. Configure Database

Open `config/database.php`:

```php
<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "fee_payment_system";

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
```

Change the values if your local MySQL setup is different.

## ▶️ Run the Project

Start Apache and MySQL, then open:

```text
http://localhost/CampusPay/
```

or:

```text
http://localhost/CampusPay/login.php
```

## 🔐 Security

Use secure password handling:

```php
$password_hash = password_hash($password, PASSWORD_DEFAULT);
```

Verify with:

```php
password_verify($password, $password_hash);
```

Also use:

- Prepared statements
- `htmlspecialchars()` for output
- Session checks
- Input validation
- Database constraints

Do not store real production passwords in source code or README files.

## 📱 Responsive Design

CampusPay is intended for:

- Desktop
- Laptop
- Tablet
- Mobile

Bootstrap responsive utilities are used for navigation, cards, forms and tables.

Use `.table-responsive` for wide tables.

## 🎨 UI / UX Principles

- Clean layout
- Simple navigation
- Clear page titles
- Consistent spacing
- Readable typography
- Professional tables
- Responsive forms
- Clear action buttons
- Confirmation before deletion
- Useful empty states
- Consistent icons
- Subtle animations
- No unnecessary visual complexity

## 🧪 Testing Checklist

### Authentication
- [ ] Valid login
- [ ] Invalid login
- [ ] Empty fields
- [ ] Logout
- [ ] Protected page access

### Students
- [ ] Add
- [ ] View
- [ ] Search
- [ ] Edit
- [ ] Delete
- [ ] Duplicate register number validation
- [ ] Duplicate mobile validation

### Departments
- [ ] Add
- [ ] View
- [ ] Search
- [ ] Edit
- [ ] Delete

### Courses
- [ ] Add
- [ ] View
- [ ] Edit
- [ ] Delete

### Academic Years
- [ ] Add
- [ ] View
- [ ] Edit
- [ ] Delete

### Fees
- [ ] Add fee structure
- [ ] Record payment
- [ ] View payment history
- [ ] Generate receipt
- [ ] Check pending fee

### Reports
- [ ] Payment report
- [ ] Pending fee report
- [ ] Student fee report
- [ ] Department report
- [ ] Date-wise report

## ⚠️ Common Problems

### MySQL Not Starting

Check:
- MySQL port
- Other MySQL/MariaDB services
- XAMPP Control Panel
- MySQL error log

Do not randomly delete files from:

```text
C:\xampp\mysql\data
```

as this can damage existing databases.

### Database Connection Error

Check `config/database.php`:

```text
Host: localhost
Username: root
Password: ""
Database: fee_payment_system
```

### Undefined Array Key

If you see:

```text
Undefined array key "department_name"
```

your query may not be returning the required joined field.

Example:

```sql
LEFT JOIN departments d
    ON s.department_id = d.department_id
```

and:

```sql
d.department_name AS department_name
```

Use the correct table and column names from your actual schema.

## 🚀 Future Improvements

- PDF receipt generation
- Excel/CSV export
- Advanced reports
- Email notifications
- SMS notifications
- Role-based access
- Multiple administrator accounts
- Online payment gateway
- Automatic fee reminders
- Student portal
- Database backup and restore
- Audit logs
- Advanced analytics

Only mark these as completed after they are actually implemented.

## 📌 Project Workflow

```text
Admin Login
     ↓
Dashboard
     ↓
Students
     ↓
Departments
     ↓
Courses
     ↓
Academic Year
     ↓
Fee Structure
     ↓
Fee Payment
     ↓
Fee History
     ↓
Receipt
     ↓
Reports
     ↓
Settings
     ↓
Logout
```

## 🧑‍💻 Project Information

**Project Name:** CampusPay – Fee Payment Management System

**Project Type:** Academic Full Stack Web Application

**Project Level:** Intermediate

**Backend:** PHP

**Database:** MySQL

**Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5

**Development Environment:** XAMPP

<<<<<<< HEAD
**Developer:** Aasif
=======
**Developer:** Mohamed Aasif
>>>>>>> 06bbb2f5fe7158b1d1aa983d8862a263280b6f41

## 📄 License

This project is developed for educational and academic purposes.

It can be modified and extended according to institutional requirements.

---

# CampusPay

**Fee Payment Management System**

*Simple. Organized. Efficient.*
