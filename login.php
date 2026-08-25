<?php
require_once "config/session.php";

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | CampusPay</title>

    <!-- Offline Bootstrap -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/login.css">

    <link rel="icon" href="assets/images/profile.gif" type="image/x-icon">

</head>

<body>

    <div class="login-page">

        <div class="login-card">

            <!-- College Logo -->
            <div class="text-center mb-3">

                <img
                    src="assets/images/logo.png"
                    alt="College Logo"
                    class="college-logo">

            </div>

            <!-- Title -->
            <div class="text-center mb-4">

                <h4>CampusPay</h4>

                <p class="text-muted mb-0">
                    M.S.P.V.L Polytechnic College
                </p>

                <small class="text-muted">
                    Pavoorchatram
                </small>

            </div>

            <!-- Error Message -->
            <?php if (isset($_GET['error'])): ?>

                <div class="alert alert-danger">
                    Invalid username or password.
                </div>

            <?php endif; ?>

            <!-- Login Form -->
            <form action="authenticate.php" method="POST">

                <!-- Username -->
                <div class="mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Enter username"
                        required>

                </div>

                <!-- Password -->
                <div class="mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter password"
                        required>

                </div>

                <!-- Remember -->
                <div class="form-check mb-3">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="remember">

                    <label
                        class="form-check-label"
                        for="remember">
                        Remember me
                    </label>

                </div>

                <!-- Login -->
                <button
                    type="submit"
                    class="btn btn-primary w-100">
                    Login
                </button>

            </form>

            <div class="text-center mt-4">

                <small class="text-muted">
                    © <?php echo date("Y"); ?>
                    M.S.P.V.L Polytechnic College
                </small>

            </div>

        </div>

    </div>

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>