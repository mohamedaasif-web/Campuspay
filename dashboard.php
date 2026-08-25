<?php

require_once "config/session.php";
require_once "config/database.php";
require_once "config/config.php";


// =========================================
// LOGIN CHECK
// =========================================

if (!isset($_SESSION["admin_id"])) {

    header("Location: login.php");

    exit;
}


// =========================================
// ADMIN DETAILS
// =========================================

$admin_name =
    $_SESSION["full_name"]
    ?? $_SESSION["username"]
    ?? "Admin";


// =========================================
// TOTAL STUDENTS
// =========================================

$student_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM students"
);

$student_data =
    mysqli_fetch_assoc($student_result);

$total_students =
    (int) $student_data["total"];


// =========================================
// TOTAL DEPARTMENTS
// =========================================

$department_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM departments"
);

$department_data =
    mysqli_fetch_assoc($department_result);

$total_departments =
    (int) $department_data["total"];


// =========================================
// TOTAL COURSES
// =========================================

$course_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM courses"
);

$course_data =
    mysqli_fetch_assoc($course_result);

$total_courses =
    (int) $course_data["total"];


// =========================================
// TOTAL FEE COLLECTION
// =========================================

$collection_result = mysqli_query(
    $conn,
    "SELECT
        COALESCE(SUM(amount_paid), 0) AS total
     FROM fee_payments"
);

$collection_data =
    mysqli_fetch_assoc($collection_result);

$total_collection =
    (float) $collection_data["total"];


// =========================================
// PENDING FEE
// =========================================

$pending_result = mysqli_query(
    $conn,
    "SELECT
        COALESCE(SUM(balance), 0) AS total
     FROM fee_payments
     WHERE balance > 0"
);

$pending_data =
    mysqli_fetch_assoc($pending_result);

$total_pending =
    (float) $pending_data["total"];


// =========================================
// RECENT PAYMENTS
// =========================================

$recent_query = mysqli_query(
    $conn,
    "SELECT
        fp.payment_id,
        fp.student_id,
        fp.amount_paid,
        fp.balance,
        fp.payment_status,
        fp.payment_date,

        s.reg_no,
        s.student_name,

        d.department_name,

        r.receipt_no

     FROM fee_payments fp

     LEFT JOIN students s
        ON fp.student_id = s.student_id

     LEFT JOIN departments d
        ON s.department_id = d.department_id

     LEFT JOIN receipts r
        ON fp.payment_id = r.payment_id

     ORDER BY fp.payment_id DESC

     LIMIT 5"
);

?>

<?php require_once "includes/header.php"; ?>

<?php require_once "includes/sidebar.php"; ?>


<main class="main-content">

    <?php require_once "includes/navbar.php"; ?>


    <section class="dashboard-container">


        <!-- =====================================
             HEADING
        ====================================== -->

        <div class="dashboard-heading reveal">

            <h1>
                Dashboard
            </h1>

            <p>
                Welcome back,
                <?php
                echo htmlspecialchars($admin_name);
                ?>!
                Here's a quick overview of your fee records.
            </p>

        </div>


        <!-- =====================================
             SUMMARY CARDS
        ====================================== -->

        <div class="row g-4">


            <!-- Students -->

            <div class="col-xl col-md-6 reveal">

                <div
                    class="summary-card card-blue">

                    <div class="card-icon-box">
                        👥
                    </div>

                    <div class="summary-content">

                        <h6>
                            Total Students
                        </h6>

                        <h2>
                            <?php
                            echo number_format(
                                $total_students
                            );
                            ?>
                        </h2>

                        <a
                            href="<?php echo BASE_URL; ?>/students/student_list.php"
                            class="summary-link">
                            View Students →
                        </a>

                    </div>

                </div>

            </div>


            <!-- Departments -->

            <div class="col-xl col-md-6 reveal">

                <div
                    class="summary-card card-green">

                    <div class="card-icon-box">
                        ▦
                    </div>

                    <div class="summary-content">

                        <h6>
                            Total Departments
                        </h6>

                        <h2>
                            <?php
                            echo number_format(
                                $total_departments
                            );
                            ?>
                        </h2>

                        <a
                            href="<?php echo BASE_URL; ?>/departments/department_list.php"
                            class="summary-link">
                            View Departments →
                        </a>

                    </div>

                </div>

            </div>


            <!-- Courses -->

            <div class="col-xl col-md-6 reveal">

                <div
                    class="summary-card card-orange">

                    <div class="card-icon-box">
                        📚
                    </div>

                    <div class="summary-content">

                        <h6>
                            Total Courses
                        </h6>

                        <h2>
                            <?php
                            echo number_format(
                                $total_courses
                            );
                            ?>
                        </h2>

                        <a
                            href="<?php echo BASE_URL; ?>/courses/course_list.php"
                            class="summary-link">
                            View Courses →
                        </a>

                    </div>

                </div>

            </div>


            <!-- Collection -->

            <div class="col-xl col-md-6 reveal">

                <div
                    class="summary-card card-purple">

                    <div class="card-icon-box">
                        ₹
                    </div>

                    <div class="summary-content">

                        <h6>
                            Total Fee Collected
                        </h6>

                        <h2>

                            ₹
                            <?php
                            echo number_format(
                                $total_collection,
                                0
                            );
                            ?>

                        </h2>

                        <a
                            href="<?php echo BASE_URL; ?>/fees/fee_history.php"
                            class="summary-link">
                            View Payments →
                        </a>

                    </div>

                </div>

            </div>


            <!-- Pending -->

            <div class="col-xl col-md-6 reveal">

                <div
                    class="summary-card card-red">

                    <div class="card-icon-box">
                        !
                    </div>

                    <div class="summary-content">

                        <h6>
                            Pending Fee
                        </h6>

                        <h2>

                            ₹
                            <?php
                            echo number_format(
                                $total_pending,
                                0
                            );
                            ?>

                        </h2>

                        <a
                            href="<?php echo BASE_URL; ?>/reports/pending_fee_report.php"
                            class="summary-link">
                            View Pending →
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================
             QUICK ACTION + RECENT PAYMENTS
        ====================================== -->

        <div class="row g-4 mt-1">


            <!-- Quick Actions -->

            <div class="col-lg-5 reveal">

                <div class="dashboard-panel">

                    <div class="panel-title">

                        <div>

                            <h5>
                                Quick Actions
                            </h5>

                            <p>
                                Frequently used actions
                            </p>

                        </div>

                    </div>


                    <a
                        href="<?php echo BASE_URL; ?>/students/add_student.php"
                        class="quick-action">

                        <div class="quick-action-left">

                            <div class="quick-icon">
                                +
                            </div>

                            <span>
                                Add New Student
                            </span>

                        </div>

                        <span class="quick-arrow">
                            →
                        </span>

                    </a>


                    <a
                        href="<?php echo BASE_URL; ?>/fees/fee_payment.php"
                        class="quick-action">

                        <div class="quick-action-left">

                            <div class="quick-icon">
                                ₹
                            </div>

                            <span>
                                Add Fee Payment
                            </span>

                        </div>

                        <span class="quick-arrow">
                            →
                        </span>

                    </a>


                    <a
                        href="<?php echo BASE_URL; ?>/fees/fee_history.php"
                        class="quick-action">

                        <div class="quick-action-left">

                            <div class="quick-icon">
                                ↺
                            </div>

                            <span>
                                Fee History
                            </span>

                        </div>

                        <span class="quick-arrow">
                            →
                        </span>

                    </a>


                    <a
                        href="<?php echo BASE_URL; ?>/receipts/receipt_list.php"
                        class="quick-action">

                        <div class="quick-action-left">

                            <div class="quick-icon">
                                ▤
                            </div>

                            <span>
                                Generate Receipt
                            </span>

                        </div>

                        <span class="quick-arrow">
                            →
                        </span>

                    </a>


                    <a
                        href="<?php echo BASE_URL; ?>/reports/report_dashboard.php"
                        class="quick-action">

                        <div class="quick-action-left">

                            <div class="quick-icon">
                                ▥
                            </div>

                            <span>
                                View Reports
                            </span>

                        </div>

                        <span class="quick-arrow">
                            →
                        </span>

                    </a>

                </div>

            </div>


            <!-- Recent Payments -->

            <div class="col-lg-7 reveal">

                <div class="dashboard-panel">

                    <div class="panel-title">

                        <div>

                            <h5>
                                Recent Fee Payments
                            </h5>

                            <p>
                                Latest payment records
                            </p>

                        </div>

                        <a
                            href="<?php echo BASE_URL; ?>/fees/fee_history.php"
                            class="view-all-link">
                            View All
                        </a>

                    </div>


                    <div class="table-wrapper">

                        <table
                            class="table dashboard-table mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Receipt
                                    </th>

                                    <th>
                                        Student
                                    </th>

                                    <th>
                                        Department
                                    </th>

                                    <th>
                                        Amount
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php

                                if (
                                    $recent_query &&
                                    mysqli_num_rows(
                                        $recent_query
                                    ) > 0
                                ):

                                ?>

                                    <?php
                                    while (
                                        $payment =
                                        mysqli_fetch_assoc(
                                            $recent_query
                                        )
                                    ):
                                    ?>

                                        <?php

                                        $status =
                                            strtolower(
                                                $payment["payment_status"]
                                                    ?? "paid"
                                            );

                                        ?>

                                        <tr>

                                            <td>
                                                <?php

                                                echo htmlspecialchars(
                                                    $payment["receipt_no"]
                                                        ?? "-"
                                                );

                                                ?>
                                            </td>


                                            <td>

                                                <strong>
                                                    <?php

                                                    echo htmlspecialchars(
                                                        $payment["student_name"]
                                                            ?? "Unknown"
                                                    );

                                                    ?>
                                                </strong>

                                                <div
                                                    class="small text-muted">
                                                    <?php

                                                    echo htmlspecialchars(
                                                        $payment["reg_no"]
                                                            ?? "-"
                                                    );

                                                    ?>
                                                </div>

                                            </td>


                                            <td>

                                                <?php

                                                echo htmlspecialchars(
                                                    $payment["department_name"]
                                                        ?? "-"
                                                );

                                                ?>

                                            </td>


                                            <td>

                                                ₹
                                                <?php

                                                echo number_format(
                                                    (float)
                                                    $payment["amount_paid"],
                                                    0
                                                );

                                                ?>

                                            </td>


                                            <td>

                                                <?php

                                                if (
                                                    $status === "paid"
                                                ) {

                                                    echo
                                                    '<span class="status-paid">
                                                    Paid
                                                </span>';
                                                } elseif (
                                                    $status === "partial"
                                                ) {

                                                    echo
                                                    '<span class="status-partial">
                                                    Partial
                                                </span>';
                                                } else {

                                                    echo
                                                    '<span class="status-pending">
                                                    Pending
                                                </span>';
                                                }

                                                ?>

                                            </td>

                                        </tr>

                                    <?php
                                    endwhile;
                                    ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center text-muted py-4">
                                            No payment records available.
                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================
             NOTIFICATIONS
        ====================================== -->

        <div class="row mt-1">

            <div class="col-12 reveal">

                <div class="dashboard-panel">

                    <div class="panel-title">

                        <div>

                            <h5>
                                Notifications
                            </h5>

                            <p>
                                Important college updates
                            </p>

                        </div>

                    </div>


                    <div class="notification-item">

                        <div class="notification-icon">
                            !
                        </div>

                        <div class="notification-text">

                            <strong>
                                Fee payment reminder
                            </strong>

                            <span>
                                Students with pending fees should complete
                                payment before the due date.
                            </span>

                        </div>

                        <div class="notification-date">
                            Today
                        </div>

                    </div>


                    <div class="notification-item">

                        <div class="notification-icon">
                            i
                        </div>

                        <div class="notification-text">

                            <strong>
                                Student records
                            </strong>

                            <span>
                                Make sure all student information is
                                updated correctly.
                            </span>

                        </div>

                        <div class="notification-date">
                            Today
                        </div>

                    </div>


                    <div class="notification-item">

                        <div class="notification-icon">
                            ₹
                        </div>

                        <div class="notification-text">

                            <strong>
                                Payment records
                            </strong>

                            <span>
                                Verify recent payments and receipts before
                                closing the day.
                            </span>

                        </div>

                        <div class="notification-date">
                            Today
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>


<?php require_once "includes/footer.php"; ?>