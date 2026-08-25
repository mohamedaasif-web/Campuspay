<?php

require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$student_id = (int)($_GET["id"] ?? 0);

if ($student_id <= 0) {
    header("Location: student_list.php");
    exit;
}

$sql = "SELECT 
            s.*,
            d.department_id,
            d.department_name,
            c.course_name,
            y.academic_year
        FROM students s
        LEFT JOIN departments d
            ON s.department_id = d.department_id
        
        LEFT JOIN courses c
            ON s.course_id = c.course_id
        LEFT JOIN academic_years y
            ON s.year_id = y.year_id
        WHERE s.student_id = ?
        LIMIT 1";

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Details | CampusPay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .container-box {
            max-width: 1000px;
            margin: 40px auto;
        }

        .student-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .student-header {
            background: #0d3b82;
            color: white;
            padding: 25px;
            border-radius: 18px 18px 0 0;
        }

        .student-photo {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
        }

        .detail-label {
            font-size: 13px;
            color: #777;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 600;
        }
    </style>

</head>

<body>

    <div class="container-box">

        <div class="mb-3">

            <a href="student_list.php"
                class="btn btn-outline-secondary">
                ← Back to Students
            </a>

            <a href="edit_student.php?id=<?= $student['student_id']; ?>"
                class="btn btn-primary">
                Edit Student
            </a>

        </div>


        <div class="card student-card">

            <div class="student-header">

                <div class="row align-items-center">

                    <div class="col-md-3 text-center">

                        <?php if (!empty($student["student_photo"])): ?>

                            <img
                                src="../uploads/students/<?= htmlspecialchars($student["student_photo"]); ?>"
                                class="student-photo"
                                alt="Student">

                        <?php else: ?>

                            <div class="student-photo bg-light text-dark
                                    d-flex align-items-center justify-content-center mx-auto">
                                No Photo
                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="col-md-9">

                        <h2 class="mb-1">
                            <?= htmlspecialchars($student["student_name"]); ?>
                        </h2>

                        <p class="mb-0">
                            Registration No:
                            <?= htmlspecialchars($student["reg_no"]); ?>
                        </p>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="detail-label">Student ID</div>
                        <div class="detail-value">
                            <?= $student["student_id"]; ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Admission Number</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["admission_no"]); ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Gender</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["gender"]); ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Date of Birth</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["dob"]); ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Mobile</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["mobile"]); ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["email"] ?? "-"); ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["department_id"] ?? "-"); ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="detail-label">Department Name</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["department_name"] ?? "-"); ?>
                        </div>
                    </div>



                    <div class="col-md-4">
                        <div class="detail-label">Academic Year</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["academic_year"] ?? "-"); ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Semester</div>
                        <div class="detail-value">
                            <?= $student["semester"]; ?>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="detail-label">Admission Date</div>
                        <div class="detail-value">
                            <?= htmlspecialchars($student["admission_date"]); ?>
                        </div>
                    </div>


                    <div class="col-md-4">

                        <div class="detail-label">Status</div>

                        <?php if ($student["status"] === "Active"): ?>

                            <span class="badge bg-success">
                                Active
                            </span>

                        <?php else: ?>

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                        <?php endif; ?>

                    </div>


                    <div class="col-12">

                        <div class="detail-label">
                            Address
                        </div>

                        <div class="detail-value">
                            <?= nl2br(htmlspecialchars($student["address"] ?? "-")); ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>