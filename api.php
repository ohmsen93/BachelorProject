<?php
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
    case 'rooms':
        require ROOT_PATH . "Controllers/Api/UserController.php";
        $objController = new UserController();

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
                $objController->addRoom(htmlspecialchars($_POST['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            }
        }
        // Here we target specific Rooms through the url, First we check if the url count is 3, as in the tags after the root folder, so in this case api/Rooms/$id, would put the count at 3, and $url[2] would target the id, hence why we use that for the getRoomsById.
        if (count($url) === 3 && $requestMethod === 'GET') {$objController->getRoomById($url[2]);}
        if (count($url) === 3 && $requestMethod === 'DELETE') {$objController->deleteRoom($url[2]);}
        if (count($url) === 3 && $requestMethod === 'PUT')
        {
            $_PUT = json_decode(file_get_contents("php://input"), true);
            var_dump($_PUT);
            $objController->updateRoom($url[2], $_PUT['name']);
        }

        else {http_response_code(404); echo 'Not found';}
        break;

    case 'meetings':
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

    case 'users':
        require ROOT_PATH . "Controllers/Api/UserController.php";
        $objController = new UserController();

        if (count($url) === 2 && $requestMethod === 'POST' &&
            isset($_POST['firstName']) && isset($_POST['lastName']) &&
            isset($_POST['password']) && isset($_POST['email']))
        {
            $objController->addNewUser(
                $_POST['google_account_id'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password']);
        }

        if (count($url) === 3 && $requestMethod === 'GET') {$objController->getUserById($url[2]);}
        if (count($url) === 3 && $requestMethod === 'POST' &&
            isset($_POST['firstName']) && isset($_POST['lastName'])
            && isset($_POST['email']))
        {
            $objController->updateUser($url[2],
                $_POST['firstName'], $_POST['lastName'], $_POST['password']);
        }

        else {http_response_code(404);echo 'Not found';}
        break;


    default:
        http_response_code(404);
        echo 'Not found';
}