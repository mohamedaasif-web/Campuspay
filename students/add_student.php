<?php
require_once "../config/session.php";
require_once "../config/database.php";

// Check if connection exists
if (!isset($conn) || !$conn) {
    die("Database connection failed");
}

// Fetch departments
$department_sql = "SELECT department_id, department_name 
                   FROM departments 
                   ORDER BY department_name ASC";

$department_result = mysqli_query($conn, $department_sql);

// Fetch courses
$course_sql = "SELECT course_id, course_name 
               FROM courses 
               ORDER BY course_name ASC";

$course_result = mysqli_query($conn, $course_sql);

// Fetch academic years
$year_sql = "SELECT year_id, academic_year 
             FROM academic_years 
             ORDER BY academic_year DESC";

$year_result = mysqli_query($conn, $year_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Student | CampusPay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .page-wrapper {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: #0d3b82;
            color: white;
            border-radius: 16px 16px 0 0 !important;
            padding: 20px 25px;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-primary {
            background: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background: #0b5ed7;
        }
    </style>
</head>

<body>

    <div class="page-wrapper">

        <div class="mb-4">
            <a href="student_list.php" class="btn btn-outline-secondary">
                ← Back to Students
            </a>
        </div>

        <div class="card">

            <div class="card-header">
                <h3 class="mb-1">Add New Student</h3>
                <small>Enter student information</small>
            </div>

            <div class="card-body p-4">

                <form action="save_student.php" method="POST" enctype="multipart/form-data">

                    <div class="row g-3">

                        <!-- Registration Number -->
                        <div class="col-md-6">
                            <label class="form-label">Registration Number</label>
                            <input type="text"
                                name="reg_no"
                                class="form-control"
                                placeholder="Example: 2025CSE001"
                                required>
                        </div>

                        <!-- Admission Number -->
                        <div class="col-md-6">
                            <label class="form-label">Admission Number</label>
                            <input type="text"
                                name="admission_no"
                                class="form-control"
                                placeholder="Admission Number"
                                required>
                        </div>

                        <!-- Student Name -->
                        <div class="col-md-6">
                            <label class="form-label">Student Name</label>
                            <input type="text"
                                name="student_name"
                                class="form-control"
                                placeholder="Enter full name"
                                required>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>

                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- DOB -->
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>

                            <input type="date"
                                name="dob"
                                class="form-control"
                                required>
                        </div>

                        <!-- Mobile -->
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>

                            <input type="text"
                                name="mobile"
                                class="form-control"
                                maxlength="10"
                                placeholder="10 digit mobile number"
                                required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">Email</label>

                            <input type="email"
                                name="email"
                                class="form-control"
                                placeholder="student@email.com">
                        </div>

                        <!-- Department -->
                        <div class="col-md-6">
                            <label class="form-label">Department</label>

                            <select name="department_id" class="form-select" required>

                                <option value="">Select Department</option>

                                <?php while ($department = mysqli_fetch_assoc($department_result)): ?>

                                    <option value="<?= $department['department_id']; ?>">
                                        <?= htmlspecialchars($department['department_name']); ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>
                        </div>

                        <!-- Course -->
                        <div class="col-md-6">
                            <label class="form-label">Course</label>

                            <select name="course_id" class="form-select" required>

                                <option value="">Select Course</option>

                                <?php while ($course = mysqli_fetch_assoc($course_result)): ?>

                                    <option value="<?= $course['course_id']; ?>">
                                        <?= htmlspecialchars($course['course_name']); ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>
                        </div>

                        <!-- Academic Year -->
                        <div class="col-md-6">
                            <label class="form-label">Academic Year</label>

                            <select name="year_id" class="form-select" required>

                                <option value="">Select Academic Year</option>

                                <?php while ($year = mysqli_fetch_assoc($year_result)): ?>

                                    <option value="<?= $year['year_id']; ?>">
                                        <?= htmlspecialchars($year['academic_year']); ?>
                                    </option>

                                <?php endwhile; ?>

                            </select>
                        </div>

                        <!-- Semester -->
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>

                            <select name="semester" class="form-select" required>

                                <option value="">Select Semester</option>
                                <option value="1">1st Semester</option>
                                <option value="2">2nd Semester</option>
                                <option value="3">3rd Semester</option>
                                <option value="4">4th Semester</option>
                                <option value="5">5th Semester</option>
                                <option value="6">6th Semester</option>

                            </select>
                        </div>

                        <!-- Admission Date -->
                        <div class="col-md-6">
                            <label class="form-label">Admission Date</label>

                            <input type="date"
                                name="admission_date"
                                class="form-control"
                                required>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">

                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>

                            </select>
                        </div>

                        <!-- Address -->
                        <div class="col-12">
                            <label class="form-label">Address</label>

                            <textarea name="address"
                                class="form-control"
                                rows="3"
                                placeholder="Enter residential address"></textarea>
                        </div>

                        <!-- Photo -->
                        <div class="col-12">
                            <label class="form-label">Student Photo</label>

                            <input type="file"
                                name="student_photo"
                                class="form-control"
                                accept="image/*">
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="student_list.php"
                            class="btn btn-secondary">
                            Cancel
                        </a>

                        <button type="reset"
                            class="btn btn-outline-secondary">
                            Reset
                        </button>

                        <button type="submit"
                            class="btn btn-primary px-4">
                            Save Student
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>