<?php

require_once "../config/session.php";
require_once "../config/database.php";

$student_id = (int)($_GET["id"] ?? 0);

if ($student_id <= 0) {
    header("Location: student_list.php");
    exit;
}


// Student
$sql = "SELECT * FROM students WHERE student_id = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
    die("Student not found.");
}

$student = mysqli_fetch_assoc($result);


// Departments
$departments = mysqli_query(
    $conn,
    "SELECT department_id, department_name
     FROM departments
     ORDER BY department_name ASC"
);


// Courses
$courses = mysqli_query(
    $conn,
    "SELECT course_id, course_name
     FROM courses
     ORDER BY course_name ASC"
);


// Academic Years
$years = mysqli_query(
    $conn,
    "SELECT year_id, academic_year
     FROM academic_years
     ORDER BY academic_year DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Student | CampusPay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

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
    </style>

</head>

<body>

    <div class="page-wrapper">

        <div class="mb-4">

            <a href="student_list.php"
                class="btn btn-outline-secondary">
                ← Back
            </a>

        </div>


        <div class="card">

            <div class="card-header">

                <h3 class="mb-1">Edit Student</h3>

                <small>
                    Update student information
                </small>

            </div>


            <div class="card-body p-4">

                <form action="update_student.php"
                    method="POST"
                    enctype="multipart/form-data">

                    <input type="hidden"
                        name="student_id"
                        value="<?= $student['student_id']; ?>">


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Registration Number
                            </label>

                            <input type="text"
                                name="reg_no"
                                class="form-control"
                                value="<?= htmlspecialchars($student['reg_no']); ?>"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Admission Number
                            </label>

                            <input type="text"
                                name="admission_no"
                                class="form-control"
                                value="<?= htmlspecialchars($student['admission_no']); ?>"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Student Name
                            </label>

                            <input type="text"
                                name="student_name"
                                class="form-control"
                                value="<?= htmlspecialchars($student['student_name']); ?>"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Gender
                            </label>

                            <select name="gender"
                                class="form-select"
                                required>

                                <option value="">Select Gender</option>

                                <option value="Male"
                                    <?= $student['gender'] === 'Male' ? 'selected' : ''; ?>>
                                    Male
                                </option>

                                <option value="Female"
                                    <?= $student['gender'] === 'Female' ? 'selected' : ''; ?>>
                                    Female
                                </option>

                                <option value="Other"
                                    <?= $student['gender'] === 'Other' ? 'selected' : ''; ?>>
                                    Other
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Date of Birth
                            </label>

                            <input type="date"
                                name="dob"
                                class="form-control"
                                value="<?= htmlspecialchars($student['dob']); ?>"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Mobile
                            </label>

                            <input type="text"
                                name="mobile"
                                class="form-control"
                                value="<?= htmlspecialchars($student['mobile']); ?>"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($student['email'] ?? ''); ?>">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Department
                            </label>

                            <select name="department_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Select Department
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($departments)): ?>

                                    <option value="<?= $row['department_id']; ?>"
                                        <?= $student['department_id'] == $row['department_id'] ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['department_name']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Course
                            </label>

                            <select name="course_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Select Course
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($courses)): ?>

                                    <option value="<?= $row['course_id']; ?>"
                                        <?= $student['course_id'] == $row['course_id'] ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['course_name']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Academic Year
                            </label>

                            <select name="year_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Select Academic Year
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($years)): ?>

                                    <option value="<?= $row['year_id']; ?>"
                                        <?= $student['year_id'] == $row['year_id'] ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['academic_year']); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Semester
                            </label>

                            <select name="semester"
                                class="form-select"
                                required>

                                <?php for ($i = 1; $i <= 6; $i++): ?>

                                    <option value="<?= $i; ?>"
                                        <?= $student['semester'] == $i ? 'selected' : ''; ?>>

                                        <?= $i; ?><?= $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')); ?>
                                        Semester

                                    </option>

                                <?php endfor; ?>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Admission Date
                            </label>

                            <input type="date"
                                name="admission_date"
                                class="form-control"
                                value="<?= htmlspecialchars($student['admission_date']); ?>"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                class="form-select">

                                <option value="Active"
                                    <?= $student['status'] === 'Active' ? 'selected' : ''; ?>>
                                    Active
                                </option>

                                <option value="Inactive"
                                    <?= $student['status'] === 'Inactive' ? 'selected' : ''; ?>>
                                    Inactive
                                </option>

                            </select>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea name="address"
                                class="form-control"
                                rows="3"><?= htmlspecialchars($student['address'] ?? ''); ?></textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Change Student Photo
                            </label>

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

                        <button type="submit"
                            class="btn btn-primary px-4">
                            Update Student
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

</body>

</html>