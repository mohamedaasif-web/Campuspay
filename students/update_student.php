<?php

require_once "../config/session.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: student_list.php");
    exit;
}


$student_id      = (int)($_POST["student_id"] ?? 0);
$reg_no          = trim($_POST["reg_no"] ?? "");
$admission_no    = trim($_POST["admission_no"] ?? "");
$student_name    = trim($_POST["student_name"] ?? "");
$gender          = trim($_POST["gender"] ?? "");
$dob             = $_POST["dob"] ?? "";
$mobile          = trim($_POST["mobile"] ?? "");
$email           = trim($_POST["email"] ?? "");
$address         = trim($_POST["address"] ?? "");
$department_id   = (int)($_POST["department_id"] ?? 0);
$course_id       = (int)($_POST["course_id"] ?? 0);
$year_id         = (int)($_POST["year_id"] ?? 0);
$semester        = (int)($_POST["semester"] ?? 0);
$admission_date  = $_POST["admission_date"] ?? "";
$status          = $_POST["status"] ?? "Active";


if ($student_id <= 0) {
    die("Invalid student ID.");
}


if (
    $reg_no === "" ||
    $admission_no === "" ||
    $student_name === "" ||
    $gender === "" ||
    $dob === "" ||
    $mobile === "" ||
    $department_id <= 0 ||
    $course_id <= 0 ||
    $year_id <= 0 ||
    $semester <= 0 ||
    $admission_date === ""
) {
    die("Please fill all required fields.");
}


$duplicate_sql = "SELECT student_id FROM students WHERE (reg_no = ? OR admission_no = ?) AND student_id != ? LIMIT 1";
$duplicate_stmt = mysqli_prepare($conn, $duplicate_sql);

if (!$duplicate_stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($duplicate_stmt, "ssi", $reg_no, $admission_no, $student_id);
mysqli_stmt_execute($duplicate_stmt);
mysqli_stmt_store_result($duplicate_stmt);

if (mysqli_stmt_num_rows($duplicate_stmt) > 0) {
    mysqli_stmt_close($duplicate_stmt);
    die("A student with this Registration Number or Admission Number already exists.");
}

mysqli_stmt_close($duplicate_stmt);


// Get old photo
$old_sql = "SELECT student_photo
            FROM students
            WHERE student_id = ?
            LIMIT 1";

$old_stmt = mysqli_prepare($conn, $old_sql);

mysqli_stmt_bind_param($old_stmt, "i", $student_id);
mysqli_stmt_execute($old_stmt);

$old_result = mysqli_stmt_get_result($old_stmt);
$old_student = mysqli_fetch_assoc($old_result);

$student_photo = $old_student["student_photo"] ?? null;


// New photo
if (
    isset($_FILES["student_photo"]) &&
    $_FILES["student_photo"]["error"] === UPLOAD_ERR_OK
) {

    $upload_dir = "../uploads/students/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }


    $extension = strtolower(
        pathinfo($_FILES["student_photo"]["name"], PATHINFO_EXTENSION)
    );


    $allowed_types = ["jpg", "jpeg", "png", "webp"];


    if (!in_array($extension, $allowed_types)) {
        die("Invalid image format.");
    }


    $new_file_name = time() . "_" . basename(
        $_FILES["student_photo"]["name"]
    );


    $target_file = $upload_dir . $new_file_name;


    if (!move_uploaded_file(
        $_FILES["student_photo"]["tmp_name"],
        $target_file
    )) {
        die("Failed to upload new photo.");
    }


    // Delete old photo
    if (!empty($student_photo)) {

        $old_file = $upload_dir . $student_photo;

        if (file_exists($old_file)) {
            unlink($old_file);
        }
    }


    $student_photo = $new_file_name;
}


// Update
$sql = "UPDATE students SET
            reg_no = ?,
            admission_no = ?,
            student_name = ?,
            gender = ?,
            dob = ?,
            mobile = ?,
            email = ?,
            address = ?,
            department_id = ?,
            course_id = ?,
            year_id = ?,
            semester = ?,
            admission_date = ?,
            student_photo = ?,
            status = ?
        WHERE student_id = ?";


$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "ssssssssiiissssi",
    $reg_no,
    $admission_no,
    $student_name,
    $gender,
    $dob,
    $mobile,
    $email,
    $address,
    $department_id,
    $course_id,
    $year_id,
    $semester,
    $admission_date,
    $student_photo,
    $status,
    $student_id
);


if (mysqli_stmt_execute($stmt)) {

    header("Location: student_list.php?success=updated");
    exit;
} else {

    die("Update failed: " . mysqli_stmt_error($stmt));
}
