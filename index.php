<?php
// First of all we need an Session for the CMS Script!
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Second include the CMS System
include_once "src/cms.php";
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Florian Lämmlein">
    <title>CMS System</title>
    <!-- css stylesheet Daten und Icons -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/core/jquery-3.3.1.min.js"></script>
    <script src="js/core/popper.min.js"></script>
    <script src="js/core/bootstrap.min.js" ></script>
    <script src="js/plugins/bootstrap-notify.js"></script>
    <script src="js/plugins/moment.min.js"></script>
    <script src="js/cms.js" type="text/javascript"></script>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="#">CMS (Calendar Management System)</a>
</nav>


<main class="col-12 p-4">
    <div class="col-12">
        <div class="btn-group" role="group" aria-label="Basic example">
            <?php 
                // Get all tests from the test folder
                foreach (glob("test/*") as $dir) {
                    $dir = preg_split("/(_|.php)/", basename($dir));
                    echo '<a type="button" href="?test&id='.$dir[1].'" class="btn btn-secondary">'.$dir[0].' '.$dir[1].'</a>';
                }
            ?>
        </div>
        <?php
            // Check if the User has clicked an test
            if(isset($_GET["test"]) && isset($_GET["id"])) {
                // Check if the file is existing
                if(file_exists("test/test_".$_GET["id"].".php")) {
                    include "test/test_".$_GET["id"].".php";

                    // Set the current Year and Month
                    $year = isset($_GET["year"]) ? $_GET["year"] : date("Y");
                    $month = isset($_GET["month"]) ? $_GET["month"] : date("m");
                    
                    // Finaly show the CMS Calendar
                    $CT->showCalendar($year, $month);
                } else {
                    echo "<script>
                        $(document).ready(function() { cms.showNotification('4', 'top', 'right', 'Pleas check the Test folder the file you have taken does not exist.', 'fas fa-bell'); });
                    </script>";
                }
            }
        ?>
    </div>
</main>


<footer class="footer fixed-bottom text-center p-3" style="background-color: #e3f2fd;">
    © 2019 CMS Calendar Management System  BY "Florian Lämmlein"
</footer>


</body>
</html>