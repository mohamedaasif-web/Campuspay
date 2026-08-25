<?php

require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($conn) || !($conn instanceof mysqli)) {
    $host = defined("DB_HOST") ? DB_HOST : (getenv("DB_HOST") ?: "localhost");
    $user = defined("DB_USER") ? DB_USER : (defined("DB_USERNAME") ? DB_USERNAME : (getenv("DB_USER") ?: (getenv("DB_USERNAME") ?: "root")));
    $pass = defined("DB_PASS") ? DB_PASS : (defined("DB_PASSWORD") ? DB_PASSWORD : (getenv("DB_PASS") ?: (getenv("DB_PASSWORD") ?: "")));
    $name = defined("DB_NAME") ? DB_NAME : (defined("DB_DATABASE") ? DB_DATABASE : (getenv("DB_NAME") ?: (getenv("DB_DATABASE") ?: "fee_payment_record_system")));

    $conn = mysqli_connect($host, $user, $pass, $name);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
}   

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: add_student.php");
    exit;
}


// Get form values
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


// Basic validation
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


$duplicate_sql = "SELECT student_id FROM students
                  WHERE reg_no = ? OR admission_no = ? OR mobile = ?
                  LIMIT 1";
$duplicate_stmt = mysqli_prepare($conn, $duplicate_sql);

if (!$duplicate_stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($duplicate_stmt, "sss", $reg_no, $admission_no, $mobile);
mysqli_stmt_execute($duplicate_stmt);
mysqli_stmt_store_result($duplicate_stmt);

if (mysqli_stmt_num_rows($duplicate_stmt) > 0) {
    mysqli_stmt_close($duplicate_stmt);
    die("A student with this Registration Number, Admission Number, or Mobile Number already exists.");
}

mysqli_stmt_close($duplicate_stmt);


// Student photo
$student_photo = null;

if (
    isset($_FILES["student_photo"]) &&
    $_FILES["student_photo"]["error"] === UPLOAD_ERR_OK
) {

    $upload_dir = "../uploads/students/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES["student_photo"]["name"]);

    $target_file = $upload_dir . $file_name;

    $allowed_types = ["jpg", "jpeg", "png", "webp"];

    $extension = strtolower(
        pathinfo($_FILES["student_photo"]["name"], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, $allowed_types)) {
        die("Invalid image format.");
    }

    if (!move_uploaded_file($_FILES["student_photo"]["tmp_name"], $target_file)) {
        die("Failed to upload student photo.");
    }

    $student_photo = $file_name;
}


// Insert query
$sql = "INSERT INTO students
        (
            reg_no,
            admission_no,
            student_name,
            gender,
            dob,
            mobile,
            email,
            address,
            department_id,
            course_id,
            year_id,
            semester,
            admission_date,
            student_photo,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "ssssssssiiissss",
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
    $status
);


try {
    $saved = mysqli_stmt_execute($stmt);
} catch (mysqli_sql_exception $exception) {
    mysqli_stmt_close($stmt);

    if ($exception->getCode() === 1062) {
        die("A student with this Registration Number, Admission Number, or Mobile Number already exists.");
    }

    die("Failed to save student.");
}

if ($saved) {

    mysqli_stmt_close($stmt);

    header("Location: student_list.php?success=added");
    exit;
} else {

    die("Failed to save student: " . mysqli_stmt_error($stmt));
}
