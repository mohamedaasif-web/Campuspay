document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const toggleButton = document.getElementById("sidebarToggle");
    const overlay = document.getElementById("pageOverlay");

    if (toggleButton && sidebar) {

        toggleButton.addEventListener("click", function () {

            sidebar.classList.toggle("open");

            if (overlay) {
                overlay.classList.toggle("show");
            }

        });

    }


    if (overlay) {

        overlay.addEventListener("click", function () {

            sidebar.classList.remove("open");

            overlay.classList.remove("show");

        });

    }


    // Scroll reveal animation

    const revealElements =
        document.querySelectorAll(".reveal");

    if ("IntersectionObserver" in window) {

        const observer =
            new IntersectionObserver(
                function (entries) {

                    entries.forEach(function (entry) {

                        if (entry.isIntersecting) {

                            entry.target.classList.add("show");

                            observer.unobserve(entry.target);

                        }

                    });

                },
                {
                    threshold: 0.12
                }
            );

        revealElements.forEach(function (element) {

            observer.observe(element);

        });

    } else {

        revealElements.forEach(function (element) {

            element.classList.add("show");

        });

    }


    // Close mobile sidebar when menu item clicked

    const sidebarLinks =
        document.querySelectorAll(".sidebar-link");

    sidebarLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            if (window.innerWidth <= 991) {

                sidebar.classList.remove("open");

                overlay.classList.remove("show");

            }

        });

    });

});