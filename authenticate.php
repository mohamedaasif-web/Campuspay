<?php

require_once "config/session.php";
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    header("Location: login.php?error=empty");
    exit;
}

$sql = "SELECT * FROM admin WHERE username = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database query failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {

    $admin = mysqli_fetch_assoc($result);

    if ($password === $admin["password"]) {

        $_SESSION["admin_id"] = $admin["admin_id"];
        $_SESSION["username"] = $admin["username"];
        $_SESSION["full_name"] = $admin["full_name"];

        header("Location: dashboard.php");
        exit;
    } else {

        header("Location: login.php?error=invalid");
        exit;
    }
} else {

    header("Location: login.php?error=invalid");
    exit;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
