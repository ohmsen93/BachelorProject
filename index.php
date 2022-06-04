<?php
session_start();
require __DIR__ . "/config/config.php";
require __DIR__ . "/vendor/autoload.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$view = $_GET['view'] ?? '';

$admin = $_SESSION['admin'] ?? '';

/*
if ((isset($uri[2]) && $uri[2] != 'customer') || !isset($uri[4])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
*/



### login setup ###


### page setup ###



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="css/stylesheet.css">


    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <!-- <script src="js/scripts.js" defer></script> Scripts -->




    <!-- Page information -->

    <title>Twentyfour Booking</title>
</head>
<header class='container-fluid'>
    <?php include "viewAssets/header.php"; ?>
</header>
<body>

<div class="container-fluid">
    <div class="row">
        <nav id="mainNavigation" class="col-2">
        <?php include "viewAssets/nav.php"; ?>

        </nav>
        <div id="mainContent" class="col-10">
            <?php
                switch ($view){
                    case "rooms":
                        include "views/room.php";
                        break;

                    case "registration":
                        include "views/registration.php";
                        break;

                    case "schedule":
                        include "views/schedule.php";
                        break;

                    default:
                        include "views/home.php";
                }

            ?>
        </div>
    </div>
</div>


    
<footer class='container-fluid'>
    <?php include "viewAssets/footer.php"; ?>
</footer>



    <script>

    </script>


</body>

</html>
