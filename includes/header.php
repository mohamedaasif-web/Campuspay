<?php

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="description"
        content="CampusPay - M.S.P.V.L Polytechnic College">

    <title>CampusPay | College Fee Management Portal</title>

    <link rel="icon" href="<?php echo BASE_URL; ?>/assets/images/logo.png" type="image/x-icon">

    <!-- Offline Bootstrap -->
    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/bootstrap/css/bootstrap.min.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Main CSS -->
    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/style.css">

    <!-- Dashboard CSS -->
    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/dashboard.css">

    <link
        rel="stylesheet"
        href="<?php echo BASE_URL; ?>/assets/css/student.css">


</head>

<body>

    <div id="pageOverlay"></div>