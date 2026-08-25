<header class="top-navbar bg-white border-bottom shadow-sm sticky-top">

    <div class="container-fluid px-3 px-lg-4">

        <div class="d-flex align-items-center justify-content-between"
            style="height: 70px;">

            <!-- LEFT -->
            <div class="d-flex align-items-center gap-3">

                <!-- Sidebar Toggle -->
                <button
                    type="button"
                    id="sidebarToggle"
                    class="btn btn-light border-0 rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 42px; height: 42px;"
                    aria-label="Toggle navigation">

                    <i class="bi bi-list fs-4"></i>

                </button>


                <!-- System Name -->
                <div class="system-title fw-bold fs-5 text-dark">
                    CampusPay
                </div>

            </div>


            <!-- RIGHT -->
            <div class="d-flex align-items-center gap-3">

                <!-- Current Date -->
                <div class="d-none d-md-flex align-items-center gap-2 text-secondary">

                    <i class="bi bi-calendar3"></i>

                    <span class="small fw-medium">
                        <?php echo date("d M Y"); ?>
                    </span>

                </div>


                <!-- Divider -->
                <div class="vr d-none d-md-block"></div>


                <!-- Admin Profile -->
                <div class="d-flex align-items-center gap-2">

                    <!-- Avatar -->
                    <div
                        class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center"
                        style="width: 42px; height: 42px;">

                        A

                    </div>


                    <!-- Admin Details -->
                    <div class="d-none d-sm-block">

                        <div class="fw-semibold text-dark"
                            style="font-size: 14px;">

                            <?php
                            echo htmlspecialchars(
                                $_SESSION["username"] ?? "Admin"
                            );
                            ?>

                        </div>

                        <div class="text-secondary"
                            style="font-size: 12px;">

                            Administrator

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const sidebarToggle = document.getElementById("sidebarToggle");

        if (sidebarToggle) {

            sidebarToggle.addEventListener("click", function() {

                document.body.classList.toggle("sidebar-collapsed");

            });

        }

    });
</script>