<?php
// Set timezone and include necessary files
date_default_timezone_set("Asia/Phnom_Penh");
include_once('../database/dbconfig.php'); // Ensure correct path to your database config
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mini Mart Dashboard</title>

    <!-- Custom fonts for this template-->
    <?php include_once('page/layout/css.php') ?>
</head>

<body id="page-top">
<?php include_once('../language/lang.php') ?>


    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include_once('page/layout/leftSideBar.php') ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include_once('page/layout/header.php') ?>

                <?php
            // Fetch the correct page based on `pg` parameter
            if (isset($_GET['pg'])) {
                $pg = htmlspecialchars($_GET['pg'], ENT_QUOTES, 'UTF-8');
                $pgParts = explode('_', $pg);
                $folderName = !empty($pgParts) ? end($pgParts) : $pg;

                $filePath = "page/$folderName/$pg.php";

                if (file_exists($filePath)) {
                    include($filePath);
                } else {
                    // Log error (optional)
                    error_log("Page not found: " . $filePath);
                    require_once('page/layout/404.php');
                }
            } else {
                // Default to master page
                require_once("page/product/product.php");
            }
            ?>

            </div>
            <!-- Footer -->
            <?php include_once('page/layout/footer.php') ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php require_once('page/layout/js.php') ?>

</body>

</html>