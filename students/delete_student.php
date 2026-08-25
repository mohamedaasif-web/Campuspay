<?php

require_once "../config/session.php";
require_once "../config/database.php";
require_once "../config/config.php";

if (!isset($conn) || !$conn) {
    $dbFile = __DIR__ . "/../config/database.php";
    if (file_exists($dbFile)) {
        require_once $dbFile;
    }
}

if (!isset($conn) || !$conn) {
    die("Database connection not available.");
}

// Check student ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: student_list.php?error=invalid_id");
    exit;
}

$student_id = (int) $_GET['id'];

// Check whether student exists
$check_sql = "SELECT student_id FROM students WHERE student_id = ? LIMIT 1";

$check_stmt = mysqli_prepare($conn, $check_sql);

if (!$check_stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($check_stmt, "i", $student_id);
mysqli_stmt_execute($check_stmt);

$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) === 0) {
    mysqli_stmt_close($check_stmt);

    header("Location: student_list.php?error=not_found");
    exit;
}

mysqli_stmt_close($check_stmt);


// Delete student
$sql = "DELETE FROM students WHERE student_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $student_id);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: student_list.php?success=deleted");
    exit;
} else {

    $error = mysqli_error($conn);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    die("Unable to delete student: " . $error);
}
