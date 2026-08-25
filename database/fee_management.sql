-- =====================================================================
-- Fee Payment Record System - Production Database
-- College : M.S.P.V.L Polytechnic College, Pavoorchatram
-- Stack   : HTML5, CSS3, Bootstrap 5, JavaScript | PHP 8 | MySQL 8
-- Engine  : InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- Normal Form: 3NF
-- Ready to import directly into phpMyAdmin
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

-- =====================================================================
-- 1. DATABASE
-- =====================================================================
DROP DATABASE IF EXISTS fee_payment_system;
CREATE DATABASE fee_payment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fee_payment_system;

-- =====================================================================
-- 2. TABLE STRUCTURE
-- =====================================================================

-- ---------------------------------------------------------------------
-- Table: admin
-- ---------------------------------------------------------------------
CREATE TABLE admin (
    admin_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT 'default_admin.png',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: departments
-- ---------------------------------------------------------------------
CREATE TABLE departments (
    department_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL UNIQUE,
    hod_name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: courses
-- ---------------------------------------------------------------------
CREATE TABLE courses (
    course_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id INT UNSIGNED NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    duration VARCHAR(20) NOT NULL DEFAULT '3 Years',
    CONSTRAINT fk_courses_department FOREIGN KEY (department_id)
        REFERENCES departments(department_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_courses_department (department_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: academic_years
-- ---------------------------------------------------------------------
CREATE TABLE academic_years (
    year_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    academic_year VARCHAR(20) NOT NULL UNIQUE,
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: students
-- ---------------------------------------------------------------------
CREATE TABLE students (
    student_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reg_no VARCHAR(30) NOT NULL UNIQUE,
    admission_no VARCHAR(30) NOT NULL UNIQUE,
    student_name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL DEFAULT 'Male',
    dob DATE DEFAULT NULL,
    mobile VARCHAR(15) NOT NULL UNIQUE,
    email VARCHAR(100) DEFAULT NULL UNIQUE,
    address TEXT DEFAULT NULL,
    department_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    year_id INT UNSIGNED NOT NULL,
    semester TINYINT UNSIGNED NOT NULL DEFAULT 1,
    admission_date DATE NOT NULL,
    student_photo VARCHAR(255) DEFAULT 'default_student.png',
    status ENUM('Active','Inactive','Graduated') NOT NULL DEFAULT 'Active',
    CONSTRAINT fk_students_department FOREIGN KEY (department_id)
        REFERENCES departments(department_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_students_course FOREIGN KEY (course_id)
        REFERENCES courses(course_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_students_year FOREIGN KEY (year_id)
        REFERENCES academic_years(year_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_students_department (department_id),
    INDEX idx_students_course (course_id),
    INDEX idx_students_year (year_id),
    INDEX idx_students_name (student_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: fee_structure
-- ---------------------------------------------------------------------
CREATE TABLE fee_structure (
    structure_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    semester TINYINT UNSIGNED NOT NULL,
    tuition_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    exam_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    lab_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    library_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    other_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    CONSTRAINT fk_feestructure_course FOREIGN KEY (course_id)
        REFERENCES courses(course_id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_course_semester (course_id, semester),
    INDEX idx_feestructure_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: fee_payments
-- ---------------------------------------------------------------------
CREATE TABLE fee_payments (
    payment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    structure_id INT UNSIGNED NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    balance DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    payment_mode ENUM('Cash','UPI','Debit Card','Credit Card') NOT NULL DEFAULT 'Cash',
    payment_status ENUM('Paid','Partial','Pending') NOT NULL DEFAULT 'Pending',
    payment_date DATE NOT NULL,
    transaction_id VARCHAR(50) DEFAULT NULL UNIQUE,
    remarks VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_feepayments_student FOREIGN KEY (student_id)
        REFERENCES students(student_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_feepayments_structure FOREIGN KEY (structure_id)
        REFERENCES fee_structure(structure_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_feepayments_student (student_id),
    INDEX idx_feepayments_structure (structure_id),
    INDEX idx_feepayments_status (payment_status),
    INDEX idx_feepayments_date (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: payment_history
-- ---------------------------------------------------------------------
CREATE TABLE payment_history (
    history_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_id INT UNSIGNED NOT NULL,
    old_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    new_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    updated_by INT UNSIGNED NOT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_paymenthistory_payment FOREIGN KEY (payment_id)
        REFERENCES fee_payments(payment_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_paymenthistory_admin FOREIGN KEY (updated_by)
        REFERENCES admin(admin_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_paymenthistory_payment (payment_id),
    INDEX idx_paymenthistory_admin (updated_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: receipts
-- ---------------------------------------------------------------------
CREATE TABLE receipts (
    receipt_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_id INT UNSIGNED NOT NULL,
    receipt_no VARCHAR(30) NOT NULL UNIQUE,
    issue_date DATE NOT NULL,
    printed_by INT UNSIGNED NOT NULL,
    CONSTRAINT fk_receipts_payment FOREIGN KEY (payment_id)
        REFERENCES fee_payments(payment_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_receipts_admin FOREIGN KEY (printed_by)
        REFERENCES admin(admin_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_receipts_payment (payment_id),
    INDEX idx_receipts_admin (printed_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: reports
-- ---------------------------------------------------------------------
CREATE TABLE reports (
    report_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_name VARCHAR(150) NOT NULL,
    report_type ENUM('Pending Fee','Collection','Department-wise','Course-wise','Custom') NOT NULL DEFAULT 'Custom',
    generated_by INT UNSIGNED NOT NULL,
    generated_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reports_admin FOREIGN KEY (generated_by)
        REFERENCES admin(admin_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_reports_admin (generated_by),
    INDEX idx_reports_type (report_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: settings
-- ---------------------------------------------------------------------
CREATE TABLE settings (
    setting_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    college_name VARCHAR(200) NOT NULL,
    college_logo VARCHAR(255) DEFAULT 'logo.png',
    address TEXT DEFAULT NULL,
    phone VARCHAR(15) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    website VARCHAR(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: activity_logs
-- ---------------------------------------------------------------------
CREATE TABLE activity_logs (
    log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNSIGNED NOT NULL,
    activity VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    login_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_activitylogs_admin FOREIGN KEY (admin_id)
        REFERENCES admin(admin_id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_activitylogs_admin (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 3. SAMPLE DATA
-- =====================================================================

-- ---------------------------------------------------------------------
-- Data: admin  (1 record) | TEST CREDENTIALS - Username: admin | Password: admin123
-- NOTE: For production, use bcrypt hashed passwords with password_hash() function
-- ---------------------------------------------------------------------
INSERT INTO admin (admin_id, username, password, full_name, email, phone, profile_image) VALUES
(1, 'admin', 'admin123', 'Rajesh Kumar', 'admin@mspvl.edu.in', '9876543210', 'admin.png');

-- ---------------------------------------------------------------------
-- Data: departments (5 records)
-- ---------------------------------------------------------------------
INSERT INTO departments (department_id, department_name, hod_name) VALUES
(1,'Computer Science Engineering','Dr. S. Muthukumar'),
(2,'Electrical and Electronics Engineering','Mr. K. Sivakumar'),
(3,'Electronics and Communication Engineering','Mrs. R. Kalaivani'),
(4,'Mechanical Engineering','Mr. P. Elangovan'),
(5,'Civil Engineering','Dr. M. Saravanan');

-- ---------------------------------------------------------------------
-- Data: courses (5 records)
-- ---------------------------------------------------------------------
INSERT INTO courses (course_id, department_id, course_name, duration) VALUES
(1,1,'Diploma in Computer Science Engineering','3 Years'),
(2,2,'Diploma in Electrical and Electronics Engineering','3 Years'),
(3,3,'Diploma in Electronics and Communication Engineering','3 Years'),
(4,4,'Diploma in Mechanical Engineering','3 Years'),
(5,5,'Diploma in Civil Engineering','3 Years');

-- ---------------------------------------------------------------------
-- Data: academic_years (3 records)
-- ---------------------------------------------------------------------
INSERT INTO academic_years (year_id, academic_year, status) VALUES
(1,'2023-2024','Inactive'),
(2,'2024-2025','Inactive'),
(3,'2025-2026','Active');

-- ---------------------------------------------------------------------
-- Data: students (30 records)
-- ---------------------------------------------------------------------
INSERT INTO students (student_id, reg_no, admission_no, student_name, gender, dob, mobile, email, address, department_id, course_id, year_id, semester, admission_date, status) VALUES
(1,'MSPVL23CS101','ADM20231001','Arun Kumar','Male','2007-05-10','9336696312','arun.kumar1@gmail.com','115, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',1,1,1,6,'2023-06-19','Graduated'),
(2,'MSPVL23CS102','ADM20231002','Karthik Raja','Male','2005-01-14','9914763202','karthik.raja2@gmail.com','41, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',2,2,2,1,'2023-06-23','Active'),
(3,'MSPVL23CS103','ADM20231003','Vignesh S','Male','2007-05-16','9465341213','vignesh.s3@gmail.com','72, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',3,3,3,1,'2023-06-05','Active'),
(4,'MSPVL23CS104','ADM20231004','Praveen Kumar','Male','2006-03-17','9919795579','praveen.kumar4@gmail.com','87, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',4,4,1,6,'2023-06-04','Active'),
(5,'MSPVL23CS105','ADM20231005','Dinesh Babu','Male','2007-02-18','9203848421','dinesh.babu5@gmail.com','92, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',5,5,2,3,'2023-06-28','Active'),
(6,'MSPVL23CS106','ADM20231006','Suresh Kannan','Male','2006-12-06','9748245888','suresh.kannan6@gmail.com','68, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',1,1,3,2,'2023-06-26','Active'),
(7,'MSPVL23CS107','ADM20231007','Manikandan R','Male','2005-03-30','9883543540','manikandan.r7@gmail.com','118, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',2,2,1,2,'2023-06-18','Active'),
(8,'MSPVL23CS108','ADM20231008','Balaji M','Male','2005-09-13','9506448196','balaji.m8@gmail.com','21, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',3,3,2,2,'2023-06-18','Active'),
(9,'MSPVL23CS109','ADM20231009','Gokul Prasath','Male','2006-08-24','9990566476','gokul.prasath9@gmail.com','161, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',4,4,3,6,'2023-06-20','Graduated'),
(10,'MSPVL23CS110','ADM20231010','Sathish Kumar','Male','2008-03-28','9306468299','sathish.kumar10@gmail.com','181, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',5,5,1,1,'2023-06-03','Active'),
(11,'MSPVL23CS111','ADM20231011','Naveen Raj','Male','2005-04-04','9810026086','naveen.raj11@gmail.com','59, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',1,1,2,6,'2023-06-25','Graduated'),
(12,'MSPVL23CS112','ADM20231012','Ajith Kumar','Male','2005-06-13','9349957310','ajith.kumar12@gmail.com','26, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',2,2,3,6,'2023-06-13','Graduated'),
(13,'MSPVL23CS113','ADM20231013','Prakash V','Male','2007-07-18','9782560971','prakash.v13@gmail.com','94, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',3,3,1,5,'2023-06-06','Active'),
(14,'MSPVL23CS114','ADM20231014','Ramkumar S','Male','2007-01-29','9481469012','ramkumar.s14@gmail.com','54, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',4,4,2,1,'2023-06-22','Active'),
(15,'MSPVL23CS115','ADM20231015','Vinoth Kumar','Male','2006-07-01','9853573823','vinoth.kumar15@gmail.com','175, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',5,5,3,5,'2023-06-21','Active'),
(16,'MSPVL23CS116','ADM20231016','Priya Dharshini','Female','2005-05-27','9754049436','priya.dharshini16@gmail.com','163, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',1,1,1,4,'2023-06-06','Active'),
(17,'MSPVL23CS117','ADM20231017','Divya Bharathi','Female','2007-12-30','9882893941','divya.bharathi17@gmail.com','63, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',2,2,2,1,'2023-06-06','Active'),
(18,'MSPVL23CS118','ADM20231018','Keerthana S','Female','2007-08-05','9507437181','keerthana.s18@gmail.com','70, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',3,3,3,1,'2023-06-21','Active'),
(19,'MSPVL23CS119','ADM20231019','Swathi Lakshmi','Female','2008-11-10','9698020238','swathi.lakshmi19@gmail.com','57, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',4,4,1,1,'2023-06-22','Active'),
(20,'MSPVL23CS120','ADM20231020','Aishwarya R','Female','2006-10-27','9924970419','aishwarya.r20@gmail.com','199, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',5,5,2,2,'2023-06-02','Active'),
(21,'MSPVL23CS121','ADM20231021','Meena Kumari','Female','2006-04-15','9982403818','meena.kumari21@gmail.com','9, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',1,1,3,2,'2023-06-26','Active'),
(22,'MSPVL23CS122','ADM20231022','Nandhini M','Female','2006-10-09','9530747414','nandhini.m22@gmail.com','69, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',2,2,1,5,'2023-06-03','Active'),
(23,'MSPVL23CS123','ADM20231023','Kavya Shree','Female','2006-03-09','9709004943','kavya.shree23@gmail.com','184, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',3,3,2,5,'2023-06-11','Active'),
(24,'MSPVL23CS124','ADM20231024','Deepika P','Female','2006-03-12','9803771909','deepika.p24@gmail.com','128, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',4,4,3,1,'2023-06-13','Active'),
(25,'MSPVL23CS125','ADM20231025','Bhavani R','Female','2008-08-09','9592688426','bhavani.r25@gmail.com','37, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',5,5,1,5,'2023-06-09','Active'),
(26,'MSPVL23CS126','ADM20231026','Sowmiya K','Female','2005-10-13','9364814270','sowmiya.k26@gmail.com','191, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',1,1,2,2,'2023-06-18','Active'),
(27,'MSPVL23CS127','ADM20231027','Yamuna S','Female','2008-01-09','9382116655','yamuna.s27@gmail.com','192, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',2,2,3,6,'2023-06-19','Graduated'),
(28,'MSPVL23CS128','ADM20231028','Revathi M','Female','2008-04-10','9528853029','revathi.m28@gmail.com','93, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',3,3,1,6,'2023-06-08','Active'),
(29,'MSPVL23CS129','ADM20231029','Anitha V','Female','2007-11-10','9629908599','anitha.v29@gmail.com','24, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',4,4,2,6,'2023-06-25','Active'),
(30,'MSPVL23CS130','ADM20231030','Pavithra S','Female','2005-08-13','9264112119','pavithra.s30@gmail.com','161, Main Street, Pavoorchatram, Tenkasi District, Tamil Nadu',5,5,3,5,'2023-06-06','Active');

-- ---------------------------------------------------------------------
-- Data: fee_structure (5 courses x 6 semesters = 30 records)
-- ---------------------------------------------------------------------
INSERT INTO fee_structure (structure_id, course_id, semester, tuition_fee, exam_fee, lab_fee, library_fee, other_fee, total_fee) VALUES
(1,1,1,8250.00,800.00,1200.00,500.00,300.00,11050.00),
(2,1,2,8500.00,800.00,1200.00,500.00,300.00,11300.00),
(3,1,3,8750.00,800.00,1200.00,500.00,300.00,11550.00),
(4,1,4,9000.00,800.00,1200.00,500.00,300.00,11800.00),
(5,1,5,9250.00,800.00,1200.00,500.00,300.00,12050.00),
(6,1,6,9500.00,800.00,1200.00,500.00,300.00,12300.00),
(7,2,1,8250.00,800.00,1200.00,500.00,300.00,11050.00),
(8,2,2,8500.00,800.00,1200.00,500.00,300.00,11300.00),
(9,2,3,8750.00,800.00,1200.00,500.00,300.00,11550.00),
(10,2,4,9000.00,800.00,1200.00,500.00,300.00,11800.00),
(11,2,5,9250.00,800.00,1200.00,500.00,300.00,12050.00),
(12,2,6,9500.00,800.00,1200.00,500.00,300.00,12300.00),
(13,3,1,8250.00,800.00,1200.00,500.00,300.00,11050.00),
(14,3,2,8500.00,800.00,1200.00,500.00,300.00,11300.00),
(15,3,3,8750.00,800.00,1200.00,500.00,300.00,11550.00),
(16,3,4,9000.00,800.00,1200.00,500.00,300.00,11800.00),
(17,3,5,9250.00,800.00,1200.00,500.00,300.00,12050.00),
(18,3,6,9500.00,800.00,1200.00,500.00,300.00,12300.00),
(19,4,1,8250.00,800.00,1200.00,500.00,300.00,11050.00),
(20,4,2,8500.00,800.00,1200.00,500.00,300.00,11300.00),
(21,4,3,8750.00,800.00,1200.00,500.00,300.00,11550.00),
(22,4,4,9000.00,800.00,1200.00,500.00,300.00,11800.00),
(23,4,5,9250.00,800.00,1200.00,500.00,300.00,12050.00),
(24,4,6,9500.00,800.00,1200.00,500.00,300.00,12300.00),
(25,5,1,8250.00,800.00,1200.00,500.00,300.00,11050.00),
(26,5,2,8500.00,800.00,1200.00,500.00,300.00,11300.00),
(27,5,3,8750.00,800.00,1200.00,500.00,300.00,11550.00),
(28,5,4,9000.00,800.00,1200.00,500.00,300.00,11800.00),
(29,5,5,9250.00,800.00,1200.00,500.00,300.00,12050.00),
(30,5,6,9500.00,800.00,1200.00,500.00,300.00,12300.00);

-- ---------------------------------------------------------------------
-- Data: fee_payments (50 records)
-- ---------------------------------------------------------------------
INSERT INTO fee_payments (payment_id, student_id, structure_id, amount_paid, balance, payment_mode, payment_status, payment_date, transaction_id, remarks) VALUES
(1,1,6,0,12300,'Cash','Pending','2025-05-15',NULL,'Payment pending'),
(2,2,7,7607.17,3442.83,'Cash','Partial','2025-06-22','TXN2025002','Partial payment'),
(3,3,13,6712.21,4337.79,'Debit Card','Partial','2025-04-06','TXN2025003','Partial payment'),
(4,4,24,0,12300,'Cash','Pending','2025-11-09',NULL,'Payment pending'),
(5,5,27,11550,0,'Cash','Paid','2025-08-27','TXN2025005','Full payment received'),
(6,6,2,11300,0,'UPI','Paid','2025-02-18','TXN2025006','Full payment received'),
(7,7,8,11300,0,'Debit Card','Paid','2025-01-12','TXN2025007','Full payment received'),
(8,8,14,4472.32,6827.68,'UPI','Partial','2025-06-03','TXN2025008','Partial payment'),
(9,9,24,0,12300,'Cash','Pending','2025-10-25',NULL,'Payment pending'),
(10,10,25,11050,0,'UPI','Paid','2025-10-06','TXN2025010','Full payment received'),
(11,11,6,6286.16,6013.84,'Credit Card','Partial','2025-10-25','TXN2025011','Partial payment'),
(12,12,12,12300,0,'Debit Card','Paid','2025-03-15','TXN2025012','Full payment received'),
(13,13,17,0,12050,'Cash','Pending','2025-01-11',NULL,'Payment pending'),
(14,14,19,11050,0,'UPI','Paid','2025-06-03','TXN2025014','Full payment received'),
(15,15,29,12050,0,'UPI','Paid','2025-01-28','TXN2025015','Full payment received'),
(16,16,4,3874.45,7925.55,'UPI','Partial','2025-04-07','TXN2025016','Partial payment'),
(17,17,7,11050,0,'Credit Card','Paid','2025-04-26','TXN2025017','Full payment received'),
(18,18,13,0,11050,'UPI','Pending','2025-04-12',NULL,'Payment pending'),
(19,19,19,0,11050,'Credit Card','Pending','2025-01-22',NULL,'Payment pending'),
(20,20,26,11300,0,'Cash','Paid','2025-03-26','TXN2025020','Full payment received'),
(21,21,2,11300,0,'UPI','Paid','2025-05-15','TXN2025021','Full payment received'),
(22,22,11,12050,0,'Credit Card','Paid','2025-04-08','TXN2025022','Full payment received'),
(23,23,17,12050,0,'Credit Card','Paid','2025-12-18','TXN2025023','Full payment received'),
(24,24,19,11050,0,'Cash','Paid','2025-10-27','TXN2025024','Full payment received'),
(25,25,29,12050,0,'Cash','Paid','2025-12-08','TXN2025025','Full payment received'),
(26,26,2,11300,0,'Credit Card','Paid','2025-02-28','TXN2025026','Full payment received'),
(27,27,12,0,12300,'Cash','Pending','2025-01-13',NULL,'Payment pending'),
(28,28,18,8248.47,4051.53,'Credit Card','Partial','2025-05-22','TXN2025028','Partial payment'),
(29,29,24,0,12300,'UPI','Pending','2025-02-02',NULL,'Payment pending'),
(30,30,29,12050,0,'Debit Card','Paid','2025-05-16','TXN2025030','Full payment received'),
(31,1,6,12300,0,'Cash','Paid','2025-06-28','TXN2025031','Full payment received'),
(32,2,7,11050,0,'Cash','Paid','2025-11-28','TXN2025032','Full payment received'),
(33,3,13,11050,0,'Credit Card','Paid','2025-05-08','TXN2025033','Full payment received'),
(34,4,24,12300,0,'Cash','Paid','2025-05-19','TXN2025034','Full payment received'),
(35,5,27,7783.34,3766.66,'UPI','Partial','2025-08-08','TXN2025035','Partial payment'),
(36,6,2,5178.95,6121.05,'Debit Card','Partial','2025-01-01','TXN2025036','Partial payment'),
(37,7,8,0,11300,'Cash','Pending','2025-02-17',NULL,'Payment pending'),
(38,8,14,3988.7,7311.3,'Debit Card','Partial','2025-07-12','TXN2025038','Partial payment'),
(39,9,24,4466.11,7833.89,'Debit Card','Partial','2025-12-21','TXN2025039','Partial payment'),
(40,10,25,11050,0,'Debit Card','Paid','2025-06-05','TXN2025040','Full payment received'),
(41,11,6,4257.91,8042.09,'Cash','Partial','2025-07-09','TXN2025041','Partial payment'),
(42,12,12,6665.76,5634.24,'Debit Card','Partial','2025-03-17','TXN2025042','Partial payment'),
(43,13,17,0,12050,'Debit Card','Pending','2025-12-02',NULL,'Payment pending'),
(44,14,19,11050,0,'Credit Card','Paid','2025-06-01','TXN2025044','Full payment received'),
(45,15,29,7331.79,4718.21,'Debit Card','Partial','2025-04-18','TXN2025045','Partial payment'),
(46,16,4,0,11800,'Cash','Pending','2025-02-18',NULL,'Payment pending'),
(47,17,7,11050,0,'Debit Card','Paid','2025-07-14','TXN2025047','Full payment received'),
(48,18,13,11050,0,'Cash','Paid','2025-01-12','TXN2025048','Full payment received'),
(49,19,19,11050,0,'UPI','Paid','2025-08-25','TXN2025049','Full payment received'),
(50,20,26,0,11300,'UPI','Pending','2025-07-28',NULL,'Payment pending');

-- ---------------------------------------------------------------------
-- Data: payment_history (50 records)
-- ---------------------------------------------------------------------
INSERT INTO payment_history (history_id, payment_id, old_amount, new_amount, updated_by, updated_at) VALUES
(1,1,0,0,1,'2025-03-14 09:11:00'),
(2,2,6002.32,7607.17,1,'2025-06-26 15:51:00'),
(3,3,5207.35,6712.21,1,'2025-12-26 12:17:00'),
(4,4,0,0,1,'2025-12-04 15:55:00'),
(5,5,10991.91,11550,1,'2025-08-08 12:52:00'),
(6,6,9422.32,11300,1,'2025-06-10 12:14:00'),
(7,7,10764.5,11300,1,'2025-04-13 14:17:00'),
(8,8,2675.79,4472.32,1,'2025-05-12 17:25:00'),
(9,9,0,0,1,'2025-09-11 09:07:00'),
(10,10,9234.44,11050,1,'2025-05-06 13:02:00'),
(11,11,5623.56,6286.16,1,'2025-07-12 14:27:00'),
(12,12,10890.69,12300,1,'2025-09-04 15:57:00'),
(13,13,0,0,1,'2025-05-02 15:00:00'),
(14,14,9770.12,11050,1,'2025-09-22 12:23:00'),
(15,15,10903.05,12050,1,'2025-11-11 14:42:00'),
(16,16,2102.43,3874.45,1,'2025-12-10 17:19:00'),
(17,17,9549.66,11050,1,'2025-06-13 13:35:00'),
(18,18,0,0,1,'2025-07-22 15:43:00'),
(19,19,0,0,1,'2025-03-20 13:25:00'),
(20,20,9978.09,11300,1,'2025-01-10 13:13:00'),
(21,21,10155.17,11300,1,'2025-10-20 14:29:00'),
(22,22,10887.32,12050,1,'2025-11-07 17:30:00'),
(23,23,10359.4,12050,1,'2025-12-06 10:18:00'),
(24,24,9776.82,11050,1,'2025-11-20 14:05:00'),
(25,25,10322.36,12050,1,'2025-04-22 13:14:00'),
(26,26,9590.16,11300,1,'2025-03-01 09:15:00'),
(27,27,0,0,1,'2025-10-28 10:29:00'),
(28,28,7126.81,8248.47,1,'2025-11-19 12:45:00'),
(29,29,0,0,1,'2025-08-13 12:09:00'),
(30,30,10565.91,12050,1,'2025-01-25 10:49:00'),
(31,31,11162.28,12300,1,'2025-03-26 17:29:00'),
(32,32,10474.67,11050,1,'2025-04-28 10:29:00'),
(33,33,10349.99,11050,1,'2025-08-22 17:35:00'),
(34,34,10906.82,12300,1,'2025-08-20 17:27:00'),
(35,35,6037.49,7783.34,1,'2025-09-15 11:47:00'),
(36,36,3386.71,5178.95,1,'2025-08-09 12:53:00'),
(37,37,0,0,1,'2025-09-16 12:17:00'),
(38,38,2828.89,3988.7,1,'2025-12-10 12:17:00'),
(39,39,3462.34,4466.11,1,'2025-09-03 11:09:00'),
(40,40,10203.11,11050,1,'2025-12-05 12:04:00'),
(41,41,3135.61,4257.91,1,'2025-06-18 16:26:00'),
(42,42,6072.36,6665.76,1,'2025-07-13 09:54:00'),
(43,43,0,0,1,'2025-10-13 16:00:00'),
(44,44,9135.89,11050,1,'2025-05-25 15:54:00'),
(45,45,5494.33,7331.79,1,'2025-07-18 17:51:00'),
(46,46,0,0,1,'2025-04-16 12:17:00'),
(47,47,9896.25,11050,1,'2025-01-13 14:42:00'),
(48,48,9531.29,11050,1,'2025-07-24 11:53:00'),
(49,49,9848.91,11050,1,'2025-03-20 17:01:00'),
(50,50,0,0,1,'2025-10-19 09:05:00');

-- ---------------------------------------------------------------------
-- Data: receipts (50 records)
-- ---------------------------------------------------------------------
INSERT INTO receipts (receipt_id, payment_id, receipt_no, issue_date, printed_by) VALUES
(1,1,'RCPT/2025/1001','2025-11-14',1),
(2,2,'RCPT/2025/1002','2025-03-28',1),
(3,3,'RCPT/2025/1003','2025-08-06',1),
(4,4,'RCPT/2025/1004','2025-01-09',1),
(5,5,'RCPT/2025/1005','2025-07-11',1),
(6,6,'RCPT/2025/1006','2025-04-15',1),
(7,7,'RCPT/2025/1007','2025-06-11',1),
(8,8,'RCPT/2025/1008','2025-07-09',1),
(9,9,'RCPT/2025/1009','2025-07-09',1),
(10,10,'RCPT/2025/1010','2025-02-16',1),
(11,11,'RCPT/2025/1011','2025-01-24',1),
(12,12,'RCPT/2025/1012','2025-09-02',1),
(13,13,'RCPT/2025/1013','2025-06-08',1),
(14,14,'RCPT/2025/1014','2025-11-03',1),
(15,15,'RCPT/2025/1015','2025-11-02',1),
(16,16,'RCPT/2025/1016','2025-01-08',1),
(17,17,'RCPT/2025/1017','2025-04-27',1),
(18,18,'RCPT/2025/1018','2025-01-20',1),
(19,19,'RCPT/2025/1019','2025-03-08',1),
(20,20,'RCPT/2025/1020','2025-03-16',1),
(21,21,'RCPT/2025/1021','2025-11-04',1),
(22,22,'RCPT/2025/1022','2025-10-07',1),
(23,23,'RCPT/2025/1023','2025-08-23',1),
(24,24,'RCPT/2025/1024','2025-05-25',1),
(25,25,'RCPT/2025/1025','2025-06-06',1),
(26,26,'RCPT/2025/1026','2025-10-20',1),
(27,27,'RCPT/2025/1027','2025-12-23',1),
(28,28,'RCPT/2025/1028','2025-02-25',1),
(29,29,'RCPT/2025/1029','2025-03-10',1),
(30,30,'RCPT/2025/1030','2025-02-19',1),
(31,31,'RCPT/2025/1031','2025-01-10',1),
(32,32,'RCPT/2025/1032','2025-10-22',1),
(33,33,'RCPT/2025/1033','2025-07-13',1),
(34,34,'RCPT/2025/1034','2025-12-07',1),
(35,35,'RCPT/2025/1035','2025-02-19',1),
(36,36,'RCPT/2025/1036','2025-12-27',1),
(37,37,'RCPT/2025/1037','2025-11-08',1),
(38,38,'RCPT/2025/1038','2025-02-23',1),
(39,39,'RCPT/2025/1039','2025-05-28',1),
(40,40,'RCPT/2025/1040','2025-11-20',1),
(41,41,'RCPT/2025/1041','2025-02-26',1),
(42,42,'RCPT/2025/1042','2025-10-26',1),
(43,43,'RCPT/2025/1043','2025-01-12',1),
(44,44,'RCPT/2025/1044','2025-09-14',1),
(45,45,'RCPT/2025/1045','2025-11-12',1),
(46,46,'RCPT/2025/1046','2025-02-17',1),
(47,47,'RCPT/2025/1047','2025-11-11',1),
(48,48,'RCPT/2025/1048','2025-01-28',1),
(49,49,'RCPT/2025/1049','2025-07-27',1),
(50,50,'RCPT/2025/1050','2025-08-04',1);

-- ---------------------------------------------------------------------
-- Data: settings (1 record)
-- ---------------------------------------------------------------------
INSERT INTO settings (setting_id, college_name, college_logo, address, phone, email, website) VALUES
(1, 'M.S.P.V.L Polytechnic College', 'mspvl_logo.png', 'Pavoorchatram, Tenkasi District, Tamil Nadu - 627808', '04633-260000', 'info@mspvl.edu.in', 'www.mspvlpoly.edu.in');

-- ---------------------------------------------------------------------
-- Data: activity_logs (20 records)
-- ---------------------------------------------------------------------
INSERT INTO activity_logs (log_id, admin_id, activity, ip_address, login_time) VALUES
(1,1,'Deleted student record','192.168.1.247','2025-06-21 15:45:00'),
(2,1,'Updated fee payment','192.168.1.113','2025-03-24 16:41:00'),
(3,1,'Printed receipt','192.168.1.159','2025-09-25 15:29:00'),
(4,1,'Deleted student record','192.168.1.213','2025-12-19 12:20:00'),
(5,1,'Generated pending fee report','192.168.1.214','2025-02-09 15:15:00'),
(6,1,'Generated collection report','192.168.1.147','2025-10-22 14:21:00'),
(7,1,'Logged in to dashboard','192.168.1.128','2025-06-06 15:13:00'),
(8,1,'Updated student profile','192.168.1.206','2025-05-11 12:56:00'),
(9,1,'Viewed department-wise report','192.168.1.181','2025-05-18 08:33:00'),
(10,1,'Generated pending fee report','192.168.1.23','2025-04-24 14:31:00'),
(11,1,'Changed password','192.168.1.196','2025-04-23 15:41:00'),
(12,1,'Logged out','192.168.1.127','2025-08-26 08:05:00'),
(13,1,'Printed receipt','192.168.1.58','2025-07-23 11:19:00'),
(14,1,'Added fee structure','192.168.1.150','2025-06-16 16:33:00'),
(15,1,'Updated student profile','192.168.1.110','2025-12-18 13:22:00'),
(16,1,'Logged out','192.168.1.118','2025-05-10 12:14:00'),
(17,1,'Added new student record','192.168.1.186','2025-04-11 09:47:00'),
(18,1,'Changed password','192.168.1.245','2025-12-06 11:13:00'),
(19,1,'Logged out','192.168.1.125','2025-05-24 17:48:00'),
(20,1,'Changed password','192.168.1.154','2025-05-04 11:18:00');

-- ---------------------------------------------------------------------
-- Data: reports (10 records)
-- ---------------------------------------------------------------------
INSERT INTO reports (report_id, report_name, report_type, generated_by, generated_on) VALUES
(1,'Pending Fee Report - June 2025','Pending Fee',1,'2025-04-12 11:19:00'),
(2,'Fee Collection Report - Q1 2025','Collection',1,'2025-01-23 17:08:00'),
(3,'Department-wise Report - CSE','Department-wise',1,'2025-05-02 09:35:00'),
(4,'Department-wise Report - EEE','Department-wise',1,'2025-05-23 11:40:00'),
(5,'Department-wise Report - ECE','Department-wise',1,'2025-08-04 09:36:00'),
(6,'Department-wise Report - Mechanical','Department-wise',1,'2025-05-16 16:28:00'),
(7,'Department-wise Report - Civil','Department-wise',1,'2025-06-06 09:16:00'),
(8,'Course-wise Fee Summary 2025','Course-wise',1,'2025-08-04 10:25:00'),
(9,'Monthly Collection Report - July 2025','Collection',1,'2025-08-03 18:40:00'),
(10,'Custom Fee Analysis Report','Custom',1,'2025-11-02 11:09:00');

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- END OF SCRIPT
-- =====================================================================