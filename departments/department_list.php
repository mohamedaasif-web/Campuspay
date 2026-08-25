<?php

require_once "../config/session.php";
require_once "../config/database.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}


/* =========================================================
   SAVE DEPARTMENT
========================================================= */

if (isset($_POST["save_department"])) {

    $department_name = trim($_POST["department_name"]);
    $hod_name = trim($_POST["hod_name"]);

    if ($department_name === "" || $hod_name === "") {

        $_SESSION["error"] = "All fields are required.";

        header("Location: department_list.php");
        exit();
    }


    /* Duplicate Check */

    $check_sql = "
        SELECT department_id
        FROM departments
        WHERE department_name = ?
    ";

    $check_stmt = mysqli_prepare($conn, $check_sql);

    mysqli_stmt_bind_param(
        $check_stmt,
        "s",
        $department_name
    );

    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {

        mysqli_stmt_close($check_stmt);

        $_SESSION["error"] = "Department already exists.";

        header("Location: department_list.php");
        exit();
    }

    mysqli_stmt_close($check_stmt);


    /* Insert */

    $sql = "
        INSERT INTO departments
        (department_name, hod_name)
        VALUES (?, ?)
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $department_name,
        $hod_name
    );

    if (mysqli_stmt_execute($stmt)) {

        $_SESSION["success"] =
            "Department added successfully.";
    } else {

        $_SESSION["error"] =
            "Failed to add department.";
    }

    mysqli_stmt_close($stmt);

    header("Location: department_list.php");
    exit();
}


/* =========================================================
   UPDATE DEPARTMENT
========================================================= */

if (isset($_POST["update_department"])) {

    $department_id =
        intval($_POST["department_id"]);

    $department_name =
        trim($_POST["department_name"]);

    $hod_name =
        trim($_POST["hod_name"]);


    if (
        $department_id <= 0 ||
        $department_name === "" ||
        $hod_name === ""
    ) {

        $_SESSION["error"] =
            "Invalid department details.";

        header("Location: department_list.php");
        exit();
    }


    /* Duplicate Check */

    $check_sql = "
        SELECT department_id
        FROM departments
        WHERE department_name = ?
        AND department_id != ?
    ";

    $check_stmt =
        mysqli_prepare($conn, $check_sql);

    mysqli_stmt_bind_param(
        $check_stmt,
        "si",
        $department_name,
        $department_id
    );

    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {

        mysqli_stmt_close($check_stmt);

        $_SESSION["error"] =
            "Department already exists.";

        header("Location: department_list.php");
        exit();
    }

    mysqli_stmt_close($check_stmt);


    /* Update */

    $sql = "
        UPDATE departments
        SET
            department_name = ?,
            hod_name = ?
        WHERE department_id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $department_name,
        $hod_name,
        $department_id
    );


    if (mysqli_stmt_execute($stmt)) {

        $_SESSION["success"] =
            "Department updated successfully.";
    } else {

        $_SESSION["error"] =
            "Failed to update department.";
    }

    mysqli_stmt_close($stmt);

    header("Location: department_list.php");
    exit();
}


/* =========================================================
   DELETE DEPARTMENT
========================================================= */

if (isset($_GET["delete"])) {

    $department_id =
        intval($_GET["delete"]);


    if ($department_id <= 0) {

        $_SESSION["error"] =
            "Invalid department.";

        header("Location: department_list.php");
        exit();
    }


    /* Check Student Relation */

    $check_sql = "
        SELECT COUNT(*) AS total
        FROM students
        WHERE department_id = ?
    ";

    $check_stmt =
        mysqli_prepare($conn, $check_sql);

    mysqli_stmt_bind_param(
        $check_stmt,
        "i",
        $department_id
    );

    mysqli_stmt_execute($check_stmt);

    $check_result =
        mysqli_stmt_get_result($check_stmt);

    $check_row =
        mysqli_fetch_assoc($check_result);

    mysqli_stmt_close($check_stmt);


    if ($check_row["total"] > 0) {

        $_SESSION["error"] =
            "This department cannot be deleted because students are assigned to it.";

        header("Location: department_list.php");
        exit();
    }


    /* Delete */

    $sql = "
        DELETE FROM departments
        WHERE department_id = ?
    ";

    $stmt =
        mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $department_id
    );


    if (mysqli_stmt_execute($stmt)) {

        $_SESSION["success"] =
            "Department deleted successfully.";
    } else {

        $_SESSION["error"] =
            "Failed to delete department.";
    }

    mysqli_stmt_close($stmt);

    header("Location: department_list.php");
    exit();
}


/* =========================================================
   GET DEPARTMENTS
========================================================= */

$sql = "
    SELECT
        department_id,
        department_name,
        hod_name
    FROM departments
    ORDER BY department_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {

    die("Database Error: " .
        mysqli_error($conn));
}


/* =========================================================
   EXISTING HEADER
========================================================= */

require_once "../includes/sidebar.php"; 
require_once "../includes/navbar.php"; 
require_once "../includes/header.php"; 


?>

<!-- =======================================================
     EXISTING SIDEBAR
======================================================= -->



<!-- =======================================================
     MAIN CONTENT
======================================================= -->

<main class="main-content">

    <div class="container-fluid py-4">


        <!-- PAGE HEADER -->

        <div
            class="d-flex
                   justify-content-between
                   align-items-center
                   flex-wrap
                   gap-3
                   mb-4">

            <div>

                <h4 class="fw-bold mb-1">

                    <i
                        class="bi bi-building
                               text-primary
                               me-2">
                    </i>

                    Departments

                </h4>

                <p class="text-muted mb-0">

                    Manage departments and HOD details.

                </p>

            </div>


            <!-- ADD BUTTON -->

            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addDepartmentModal">

                <i class="bi bi-plus-lg me-1"></i>

                Add Department

            </button>

        </div>


        <!-- =================================================
             ALERT
        ================================================== -->

        <?php if (isset($_SESSION["success"])): ?>

            <div
                class="alert
                       alert-success
                       alert-dismissible
                       fade show">

                <i class="bi bi-check-circle-fill me-2"></i>

                <?= htmlspecialchars(
                    $_SESSION["success"]
                ) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

            <?php unset($_SESSION["success"]); ?>

        <?php endif; ?>


        <?php if (isset($_SESSION["error"])): ?>

            <div
                class="alert
                       alert-danger
                       alert-dismissible
                       fade show">

                <i
                    class="bi
                           bi-exclamation-triangle-fill
                           me-2">
                </i>

                <?= htmlspecialchars(
                    $_SESSION["error"]
                ) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

            <?php unset($_SESSION["error"]); ?>

        <?php endif; ?>


        <!-- =================================================
             DEPARTMENT CARD
        ================================================== -->

        <div class="card border-0 shadow-sm">


            <!-- CARD HEADER -->

            <div
                class="card-header
                       bg-white
                       border-0
                       py-3">

                <div
                    class="d-flex
                           justify-content-between
                           align-items-center">

                    <h5 class="fw-semibold mb-0">

                        Department List

                    </h5>


                    <span
                        class="badge
                               text-bg-primary">

                        <?= mysqli_num_rows($result) ?>

                        Departments

                    </span>

                </div>

            </div>


            <!-- TABLE -->

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table
                               table-hover
                               align-middle
                               mb-0">

                        <thead
                            class="table-light">

                            <tr>

                                <th class="px-4">
                                    S.No
                                </th>

                                <th>
                                    Department Name
                                </th>

                                <th>
                                    HOD Name
                                </th>

                                <th
                                    class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php

                            $serial = 1;

                            if (
                                mysqli_num_rows($result)
                                > 0
                            ):

                                while (
                                    $row =
                                    mysqli_fetch_assoc($result)
                                ):

                            ?>

                                    <tr>


                                        <!-- S.NO -->

                                        <td class="px-4 fw-semibold">

                                            <?= $serial++ ?>

                                        </td>


                                        <!-- DEPARTMENT -->

                                        <td>

                                            <div
                                                class="d-flex
                                               align-items-center">

                                                <div
                                                    class="rounded-circle
                                                   bg-primary-subtle
                                                   text-primary
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   me-3"
                                                    style="
                                                width:42px;
                                                height:42px;
                                            ">

                                                    <i
                                                        class="bi
                                                       bi-building">
                                                    </i>

                                                </div>


                                                <div>

                                                    <div
                                                        class="fw-semibold">

                                                        <?= htmlspecialchars(
                                                            $row["department_name"]
                                                        ) ?>

                                                    </div>

                                                    <small
                                                        class="text-muted">

                                                        Department ID:
                                                        <?= $row["department_id"] ?>

                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- HOD -->

                                        <td>

                                            <i
                                                class="bi
                                               bi-person-badge
                                               text-secondary
                                               me-1">
                                            </i>

                                            <?= htmlspecialchars(
                                                $row["hod_name"]
                                            ) ?>

                                        </td>


                                        <!-- ACTION -->

                                        <td>

                                            <div
                                                class="d-flex
                                               justify-content-center
                                               gap-2">


                                                <!-- EDIT -->

                                                <button
                                                    type="button"
                                                    class="btn
                                                   btn-sm
                                                   btn-outline-warning"

                                                    data-bs-toggle="modal"

                                                    data-bs-target="#editDepartmentModal"

                                                    data-id="<?=
                                                                $row["department_id"]
                                                                ?>"

                                                    data-name="<?=
                                                                htmlspecialchars(
                                                                    $row["department_name"],
                                                                    ENT_QUOTES
                                                                )
                                                                ?>"

                                                    data-hod="<?=
                                                                htmlspecialchars(
                                                                    $row["hod_name"],
                                                                    ENT_QUOTES
                                                                )
                                                                ?>">

                                                    <i
                                                        class="bi
                                                       bi-pencil-square">
                                                    </i>

                                                    <span
                                                        class="d-none d-md-inline">
                                                        Edit
                                                    </span>

                                                </button>


                                                <!-- DELETE -->

                                                <a
                                                    href="
                                                department_list.php?delete=<?=
                                                                            $row["department_id"]
                                                                            ?>"

                                                    class="btn
                                                   btn-sm
                                                   btn-outline-danger"

                                                    onclick="
                                                return confirm(
                                                    'Are you sure you want to delete this department?'
                                                );
                                            ">

                                                    <i
                                                        class="bi
                                                       bi-trash3">
                                                    </i>

                                                    <span
                                                        class="d-none d-md-inline">
                                                        Delete
                                                    </span>

                                                </a>


                                            </div>

                                        </td>

                                    </tr>


                                <?php

                                endwhile;

                            else:

                                ?>


                                <!-- EMPTY -->

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center py-5">

                                        <i
                                            class="bi
                                               bi-building
                                               display-5
                                               text-muted">
                                        </i>

                                        <h6
                                            class="fw-semibold mt-3">

                                            No Departments Found

                                        </h6>

                                        <p
                                            class="text-muted">

                                            Add your first department.

                                        </p>

                                    </td>

                                </tr>


                            <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>

        </div>


    </div>

</main>



<!-- =======================================================
     ADD MODAL
======================================================= -->

<div
    class="modal fade"
    id="addDepartmentModal"
    tabindex="-1">

    <div
        class="modal-dialog
               modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title fw-bold">

                    <i
                        class="bi
                               bi-plus-circle
                               text-primary
                               me-2">
                    </i>

                    Add Department

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form method="POST">

                <div class="modal-body">


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Department Name

                        </label>

                        <input
                            type="text"
                            name="department_name"
                            class="form-control"
                            placeholder="Enter department name"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            HOD Name

                        </label>

                        <input
                            type="text"
                            name="hod_name"
                            class="form-control"
                            placeholder="Enter HOD name"
                            required>

                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button
                        type="submit"
                        name="save_department"
                        class="btn btn-primary">

                        <i
                            class="bi
                                   bi-check-lg
                                   me-1">
                        </i>

                        Save

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- =======================================================
     EDIT MODAL
======================================================= -->

<div
    class="modal fade"
    id="editDepartmentModal"
    tabindex="-1">

    <div
        class="modal-dialog
               modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title fw-bold">

                    <i
                        class="bi
                               bi-pencil-square
                               text-warning
                               me-2">
                    </i>

                    Edit Department

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form method="POST">

                <div class="modal-body">


                    <input
                        type="hidden"
                        name="department_id"
                        id="edit_department_id">


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Department Name

                        </label>

                        <input
                            type="text"
                            name="department_name"
                            id="edit_department_name"
                            class="form-control"
                            required>

                    </div>


                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            HOD Name

                        </label>

                        <input
                            type="text"
                            name="hod_name"
                            id="edit_hod_name"
                            class="form-control"
                            required>

                    </div>


                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button
                        type="submit"
                        name="update_department"
                        class="btn btn-warning">

                        <i
                            class="bi
                                   bi-arrow-repeat
                                   me-1">
                        </i>

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- =======================================================
     PAGE JAVASCRIPT
======================================================= -->

<script>
    const editModal =
        document.getElementById(
            "editDepartmentModal"
        );


    editModal.addEventListener(
        "show.bs.modal",
        function(event) {

            const button =
                event.relatedTarget;


            const id =
                button.getAttribute(
                    "data-id"
                );

            const name =
                button.getAttribute(
                    "data-name"
                );

            const hod =
                button.getAttribute(
                    "data-hod"
                );


            document.getElementById(
                "edit_department_id"
            ).value = id;


            document.getElementById(
                "edit_department_name"
            ).value = name;


            document.getElementById(
                "edit_hod_name"
            ).value = hod;

        }
    );


    setTimeout(function() {

        document
            .querySelectorAll(".alert")
            .forEach(function(alert) {

                bootstrap.Alert
                    .getOrCreateInstance(alert)
                    .close();

            });

    }, 4000);
</script>