<?php

use SebastianBergmann\Environment\Console;

require __DIR__ . "/config/config.php";



$url = strtok($_SERVER['REQUEST_URI'], "?"); // clean url, removing query parameters

// Make trailing slashes redundant
if (substr($url, strlen($url) - 1) == '/') {
    $url = substr($url, 0, strlen($url) - 1);
}

// Clean url of local project name e.g. start from api/ -- makes it deployable elsewhere
$url = substr($url, strpos($url, "api"));

// Split the url to get something meaningful to work with
$url = explode('/', urldecode($url));

// Request method is used to determine correct action
$requestMethod = $_SERVER['REQUEST_METHOD'];
// Index 1 is going to be the "model" in plural; for instance tracks, Users, etc.
switch ($url[1] ?? null) {
    case 'display':
        require ROOT_PATH . "Controllers/Api/DisplayController.php";
        $objController = new DisplayController();

        // if count is 2, as in tags after the project root, this being api/users, api is on index 0, displays on index 1, if we were to include another tag the count would be 3 and it would skip the if function below.
        if(count($url) === 2){
            // i check the requestMethod through the use of $_SERVER['REQUEST_METHOD'], it is an easy way of getting "post, put, get, delete" for a switch later
            if($requestMethod === 'GET'){
                // then we include a get for a search query, and enable the search function if we are searching, or just list the displays if not.
                if(isset($_GET['search'])){
                    // htmlspecialchars are utilized here as it takes an input from the url string, it is to prevent code injection.
                    //$objController->searchdisplay(htmlspecialchars($_GET['search']));
                } else {
                    // here we call the list displays function in our displaysController in order to list our displays.
                    $objController->listDisplays();
                }
            }
            // if the requestmethod is post, and $_POST name isset input it into the database through adddisplay().
            if ($requestMethod === 'POST' && isset($_POST['name']))
            {
                $objController->addDisplay(htmlspecialchars($_POST['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            }
        }
        // Here we target specific displays through the url, First we check if the url count is 3, as in the tags after the root folder, so in this case api/displays/$id, would put the count at 3, and $url[2] would target the id, hence why we use that for the getdisplaysById.
        if (count($url) === 3 && $requestMethod === 'GET') {$objController->getDisplayById($url[2]);}
        if (count($url) === 3 && $requestMethod === 'DELETE') {$objController->deleteDisplay($url[2]);}
        if (count($url) === 3 && $requestMethod === 'PUT')
        {
            $_PUT = json_decode(file_get_contents("php://input"), true);
            var_dump($_PUT);
            $objController->updateDisplay($url[2], $_PUT['name']);
        }

        else {http_response_code(404); echo 'Not found';}
        break;
    case 'room':
        require ROOT_PATH . "Controllers/Api/RoomController.php";
        $objController = new RoomController();

        // if count is 2, as in tags after the project root, this being api/users, api is on index 0, Rooms on index 1, if we were to include another tag the count would be 3 and it would skip the if function below.
        if(count($url) === 2){
            // i check the requestMethod through the use of $_SERVER['REQUEST_METHOD'], it is an easy way of getting "post, put, get, delete" for a switch later
            if($requestMethod === 'GET'){
                // then we include a get for a search query, and enable the search function if we are searching, or just list the Rooms if not.
                if(isset($_GET['search'])){
                    // htmlspecialchars are utilized here as it takes an input from the url string, it is to prevent code injection.
                    //$objController->searchRoom(htmlspecialchars($_GET['search']));
                } else {
                    // here we call the list Rooms function in our RoomsController in order to list our Rooms.
                    $objController->listRooms();
                }
            }
            // if the requestmethod is post, and $_POST name isset input it into the database through addRoom().
            if ($requestMethod === 'POST' && isset($_POST['name']))
            {
                $objController->addRoom(htmlspecialchars($_POST['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), htmlspecialchars($_POST['calendarId'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), htmlspecialchars($_POST['calendarUrl'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            }
        }
        // Here we target specific Rooms through the url, First we check if the url count is 3, as in the tags after the root folder, so in this case api/Rooms/$id, would put the count at 3, and $url[2] would target the id, hence why we use that for the getRoomsById.
        if (count($url) === 3 && $requestMethod === 'GET') {$objController->getRoomById($url[2]);}
        if (count($url) === 3 && $requestMethod === 'DELETE') {$objController->deleteRoom($url[2]);}
        if (count($url) === 3 && $requestMethod === 'PUT')
        {
            $_PUT = json_decode(file_get_contents("php://input"), true);
            var_dump($_PUT);
            $objController->updateRoom($url[2], htmlspecialchars($_PUT['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), htmlspecialchars($_PUT['calendarId'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), htmlspecialchars($_PUT['calendarUrl'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        else {http_response_code(404); echo 'Not found';}
        break;

    case 'meeting':
        require ROOT_PATH . "/Controllers/Api/MeetingController.php";
        $objController = new MeetingController();

        if (count($url) === 2)
        {
            if ($requestMethod === 'GET')
            {
                if (isset($_GET['search']))
                {
                    //$objController->searchMeeting(htmlspecialchars($_GET["search"]));

                }
                else {$objController->listMeetings();}
            }

            if ($requestMethod === 'POST' && isset($_POST['fk_user_id']) && isset($_POST['fk_room_id']) && isset($_POST['title']) && isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['description']))
            {
                $objController->addMeeting($_POST['fk_user_id'], $_POST['fk_room_id'], $_POST['title'], $_POST['start_time'], $_POST['end_time'], $_POST['description']);
            }

        }

        if (count($url) === 3 && $requestMethod === 'GET') {$objController->getMeetingById($url[2]);}
        if (count($url) === 3 && $requestMethod === 'DELETE') {$objController->deleteMeeting($url[2]);}
        if (count($url) === 3 && $requestMethod === 'PUT')
        {
            $_PUT = json_decode(file_get_contents("php://input"), true);
            $objController->updateMeeting($url[2], $_POST['fk_user_id'], $_POST['fk_room_id'], $_POST['title'], $_POST['start_time'], $_POST['end_time'], $_POST['description']);
        }

        else {http_response_code(404); echo 'Not found';}
        break;

    case 'user':
        require ROOT_PATH . "Controllers/Api/UserController.php";
        $objController = new UserController();

        if (count($url) === 2)
        {
            if ($requestMethod === 'GET')
            {
                if (isset($_GET['search']))
                {
                    //$objController->searchMeeting(htmlspecialchars($_GET["search"]));

                }
                else {$objController->listUsers();}
            }

        }

        // Creates a new user
        if (count($url) === 3 && $requestMethod === 'POST')
        {
            // Takes raw data from the request
            $json = file_get_contents('php://input');
            // Converts it into a PHP object
            $data = json_decode($json);
            // GET the email
            $firstName = $data->firstName;
            $lastName = $data->lastName;
            $email = $data->email;
            $oauth_user_id = $url[2];
            //$token = $data->token;

            print_r($oauth_user_id);
            
            $objController->addNewUser($firstName, $lastName, $email, $oauth_user_id);
        }

        

        if (count($url) === 3 && $requestMethod === 'GET')
        {
            $objController->getUserByOAuthId($url[2]);
        }

        if (count($url) === 3 && $requestMethod === 'DELETE') {$objController->deleteUser($url[2]);}
        if (count($url) === 2 && $requestMethod === 'PUT')
        {
            $_PUT = json_decode(file_get_contents("php://input"), true);
            $objController->updateUser($_PUT['email'], $_PUT['firstName'], $_PUT['lastName'], $_PUT['fk_role_id']);
        }

        else {http_response_code(404); echo 'Not found';}
        break;

    case "miscDBInfo":
        require ROOT_PATH . "Controllers/Api/MiscDBInfoController.php";
        $objController = new MiscDBInfoController();
        if (count($url) === 2)
        {
            if ($requestMethod === 'GET')
            {
                $objController->listRoles();
            }
        }
        break;

        default:
        http_response_code(404);
        echo 'Not found';

    }