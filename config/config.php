<?php
// this is a configuration for the web application to reliably require and include the files i need

define("ROOT_PATH", __DIR__ . "/../");

// require the files needed for the project to run

// Here we require the database information
require_once ROOT_PATH . "/config/db_connection.php";

// include the base controller file
require_once ROOT_PATH . "/controllers/api/BaseController.php";

// include the controller files
require_once ROOT_PATH . "/controllers/api/BaseController.php";
require_once ROOT_PATH . "/controllers/api/UserController.php";
require_once ROOT_PATH . "/controllers/api/RoomController.php";
require_once ROOT_PATH . "/controllers/api/MeetingController.php";


// include the use model file
require_once ROOT_PATH . "/models/admin.php";
require_once ROOT_PATH . "/models/user.php";
require_once ROOT_PATH . "/models/room.php";
require_once ROOT_PATH . "/models/meeting.php";


