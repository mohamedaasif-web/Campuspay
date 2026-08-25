<?php

require_once "../config/session.php";
require_once "../config/database.php";

// Login check
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit;
}


// --------------------------------------------------
// SEARCH
// --------------------------------------------------

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";


// --------------------------------------------------
// SQL QUERY
// --------------------------------------------------

$sql = "
    SELECT
        s.student_id,
        s.reg_no,
        s.admission_no,
        s.student_name,
        s.gender,
        s.dob,
        s.mobile,
        s.email,
        s.address,
        s.department_id,
        s.course_id,
        s.year_id,
        s.semester,
        s.admission_date,
        s.student_photo,
        s.status,

        d.department_name AS department_name,
        c.course_name AS course_name,
        ay.academic_year AS academic_year

    FROM students s

    LEFT JOIN departments d
        ON s.department_id = d.department_id

    LEFT JOIN courses c
        ON s.course_id = c.course_id

    LEFT JOIN academic_years ay
        ON s.year_id = ay.year_id
";


// --------------------------------------------------
// SEARCH FILTER
// --------------------------------------------------

if ($search !== "") {

    $search = mysqli_real_escape_string($conn, $search);

    $sql .= "
        WHERE
            s.reg_no LIKE '%$search%'
            OR s.admission_no LIKE '%$search%'
            OR s.student_name LIKE '%$search%'
            OR s.mobile LIKE '%$search%'
            OR s.email LIKE '%$search%'
            OR d.department_name LIKE '%$search%'
            OR c.course_name LIKE '%$search%'
            OR ay.academic_year LIKE '%$search%'
    ";
}


// --------------------------------------------------
// ORDER
// --------------------------------------------------

$sql .= "
    ORDER BY s.student_id DESC
";


// --------------------------------------------------
// EXECUTE QUERY
// --------------------------------------------------

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}


// --------------------------------------------------
// TOTAL STUDENTS
// --------------------------------------------------

$total_students = mysqli_num_rows($result);

?>


<?php require_once "../includes/header.php"; ?>

<?php require_once "../includes/sidebar.php"; ?>

<main class="main-content">

    <?php require_once "../includes/navbar.php"; ?>


    <section class="student-page">

        <div class="container-fluid">


            <!-- ==========================================
                 PAGE HEADER
            =========================================== -->

            <div class="student-page-header">

                <div class="student-heading-left">

                    <div class="student-heading-icon">
                        👥
                    </div>

                    <div>

                        <h1>
                            Students
                        </h1>

                        <p>
                            Manage student records and information
                        </p>

                    </div>

                </div>


                <div>

                    <a
                        href="add_student.php"
                        class="btn btn-primary add-student-btn">
                        <span>+</span>
                        Add Student
                    </a>

                </div>

            </div>


            <!-- ==========================================
                 SEARCH + SUMMARY
            =========================================== -->

            <div class="student-toolbar">

                <form
                    method="GET"
                    action="student_list.php"
                    class="student-search-form">

                    <div class="student-search-box">

                        <span class="search-icon">
                            🔍
                        </span>

                        <input
                            type="text"
                            name="search"
                            value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Search by reg no, admission no, name, mobile...">

                        <?php if ($search !== ""): ?>

                            <a
                                href="student_list.php"
                                class="clear-search"
                                title="Clear Search">
                                ×
                            </a>

                        <?php endif; ?>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary search-btn">
                        Search
                    </button>

                </form>


                <div class="student-count-box">

                    <span>
                        Total Students
                    </span>

                    <strong>
                        <?php echo $total_students; ?>
                    </strong>

                </div>

            </div>


            <!-- ==========================================
                 STUDENT TABLE
            =========================================== -->

            <div class="student-card">

                <div class="student-card-header">

                    <div>

                        <h5>
                            Student Records
                        </h5>

                        <small>
                            Complete student information
                        </small>

                    </div>

                </div>


                <div class="student-table-wrapper">

                    <table class="student-table">

                        <thead>

                            <tr>

                                <!-- ALL DATABASE FIELDS -->

                                <th>Student ID</th>

                                <th>Reg No</th>

                                <th>Admission No</th>

                                <th>Student Photo</th>

                                <th>Student Name</th>

                                <th>Gender</th>

                                <th>DOB</th>

                                <th>Mobile</th>

                                <th>Email</th>

                                <th>Address</th>

                                <th>Department ID</th>

                                <th>Department</th>

                                <th>Year ID</th>

                                <th>Academic Year</th>

                                <th>Semester</th>

                                <th>Admission Date</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php if ($total_students > 0): ?>

                                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                    <tr>


                                        <!-- Student ID -->

                                        <td>

                                            <span class="id-badge">

                                                <?php
                                                echo htmlspecialchars(
                                                    $row["student_id"]
                                                );
                                                ?>

                                            </span>

                                        </td>


                                        <!-- Reg No -->

                                        <td>

                                            <strong>

                                                <?php
                                                echo htmlspecialchars(
                                                    $row["reg_no"]
                                                );
                                                ?>

                                            </strong>

                                        </td>


                                        <!-- Admission No -->

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $row["admission_no"]
                                            );
                                            ?>

                                        </td>


                                        <!-- Student Photo -->

                                        <td>

                                            <?php

                                            $photo =
                                                trim(
                                                    $row["student_photo"]
                                                        ?? ""
                                                );

                                            if (
                                                $photo !== "" &&
                                                file_exists(
                                                    "../uploads/students/" . $photo
                                                )
                                            ) {

                                            ?>

                                                <img
                                                    src="../uploads/students/<?php echo htmlspecialchars($photo); ?>"
                                                    alt="Student"
                                                    class="student-photo">

                                            <?php

                                            } else {

                                                $gender =
                                                    strtolower(
                                                        $student["gender"] ?? ""
                                                    );

                                                if ($gender === "female") {

                                                    $default_photo =
                                                        "female-avatar.png";
                                                } else {

                                                    $default_photo =
                                                        "male-avatar.png";
                                                }

                                            ?>

                                                <img
                                                    src="../assets/images/students/<?php echo $default_photo; ?>"
                                                    alt="Student"
                                                    class="student-photo">

                                            <?php
                                            }

                                            ?>

                                        </td>


                                        <!-- Student Name -->

                                        <td>

                                            <div class="student-name-cell">

                                                <strong>

                                                    <?php
                                                    echo htmlspecialchars(
                                                        $row["student_name"]
                                                    );
                                                    ?>

                                                </strong>

                                                <small>

                                                    <?php
                                                    echo htmlspecialchars(
                                                        $row["reg_no"]
                                                    );
                                                    ?>

                                                </small>

                                            </div>

                                        </td>


                                        <!-- Gender -->

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $row["gender"]
                                            );
                                            ?>

                                        </td>


                                        <!-- DOB -->

                                        <td>

                                            <?php

                                            echo !empty($row["dob"])
                                                ? date(
                                                    "d-m-Y",
                                                    strtotime(
                                                        $row["dob"]
                                                    )
                                                )
                                                : "-";

                                            ?>

                                        </td>


                                        <!-- Mobile -->

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $row["mobile"]
                                            );
                                            ?>

                                        </td>


                                        <!-- Email -->

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $row["email"]
                                            );
                                            ?>

                                        </td>


                                        <!-- Address -->

                                        <td>

                                            <div class="address-cell">

                                                <?php
                                                echo htmlspecialchars(
                                                    $row["address"]
                                                );
                                                ?>

                                            </div>

                                        </td>


                                        <!-- Department ID -->

                                        <td>

                                            <span class="id-badge">

                                                <?php
                                                echo htmlspecialchars(
                                                    $row["department_id"]
                                                );
                                                ?>

                                            </span>

                                        </td>


                                        <!-- Department Name -->

                                        <td>
                                            <?php echo htmlspecialchars($row['department_name']); ?>
                                        </td>



                                        <!-- Year ID -->

                                        <td>

                                            <span class="id-badge">

                                                <?php
                                                echo htmlspecialchars(
                                                    $row["year_id"]
                                                );
                                                ?>

                                            </span>

                                        </td>


                                        <!-- Academic Year -->

                                        <td>

                                            <?php echo htmlspecialchars($row['academic_year']); ?>


                                        </td>


                                        <!-- Semester -->

                                        <td>

                                            <span class="semester-badge">

                                                Sem
                                                <?php
                                                echo htmlspecialchars(
                                                    $row["semester"]
                                                );
                                                ?>

                                            </span>

                                        </td>


                                        <!-- Admission Date -->

                                        <td>

                                            <?php

                                            echo !empty($row["admission_date"])
                                                ? date(
                                                    "d-m-Y",
                                                    strtotime(
                                                        $row["admission_date"]
                                                    )
                                                )
                                                : "-";

                                            ?>

                                        </td>


                                        <!-- Status -->

                                        <td>

                                            <?php

                                            $status =
                                                strtolower(
                                                    $student["status"] ?? ""
                                                );

                                            if (
                                                $status === "active"
                                            ) {

                                                echo
                                                '<span class="status-active">
                                                Active
                                            </span>';
                                            } else {

                                                echo
                                                '<span class="status-inactive">
                                                ' .
                                                    htmlspecialchars(
                                                        $row["status"]
                                                    ) .
                                                    '</span>';
                                            }

                                            ?>

                                        </td>


                                        <!-- Action -->

                                        <td>

                                            <div class="student-actions">

                                                <a
                                                    href="student_details.php?id=<?php echo $row["student_id"]; ?>"
                                                    class="action-btn view"
                                                    title="View Student">
                                                    View
                                                </a>

                                                <a
                                                    href="edit_student.php?id=<?php echo $row["student_id"]; ?>"
                                                    class="action-btn edit"
                                                    title="Edit Student">
                                                    Edit
                                                </a>

                                                <a href="delete_student.php?id=<?= (int)$row['student_id'] ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this student?');">
                                                    Delete
                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>

                                    <td
                                        colspan="20"
                                        class="empty-student">

                                        <div class="empty-icon">
                                            👥
                                        </div>

                                        <h5>
                                            No students found
                                        </h5>

                                        <p>
                                            Try another search or add a new student.
                                        </p>

                                        <a
                                            href="add_student.php"
                                            class="btn btn-primary">
                                            + Add Student
                                        </a>

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

</main>


<?php require_once "../includes/footer.php"; ?>