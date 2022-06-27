<?php
//require_once 'dbcontroller.php';

//Google API PHP Library includes
//require_once 'Google/Client.php';
//require_once 'Google/Service/Oauth2.php';

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
 $client_id = '';
 $client_secret = '';
 $redirect_uri = '';
 $simple_api_key = '';
 
//Create Client Request to access Google API
$client = new Google_Client();
$client->setApplicationName("PHP Google OAuth Login Twentyfour booking");
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->setDeveloperKey($simple_api_key);
$client->addScope("profile");
$client->addScope("https://www.googleapis.com/auth/userinfo.email");
$client->addScope("https://www.googleapis.com/auth/calendar");

//Send Client Request
$objOAuthService = new Google_Service_Oauth2($client);

//Logout
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
  $client->revokeToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect user back to page
}

//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

//Set Access Token to make Request
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
}

//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {
  $userData = $objOAuthService->userinfo->get();
  //print_r($userData);
  
  if(!empty($userData)) {


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/BachelorProject/FinalProject/api/user/".$userData->id);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);

    
    $existing_member = $result;

	  if($existing_member === 'false') {
      print_r("No User: " . $existing_member);
      // if the user doesnt exist Register the user
      $registerInfo = json_encode(array(
          "firstName" => $userData->givenName,
          "lastName" => $userData->familyName,
          "email" => $userData->email,
      ));

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "http://localhost/BachelorProject/FinalProject/api/user/".$userData->id);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $registerInfo);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      # Return response instead of printing.
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      # Send request.
      $result = curl_exec($ch);
      curl_close($ch);	  
    }
  }
  $_SESSION['access_token'] = $client->getAccessToken();
  
  } else {
    $authUrl = $client->createAuthUrl();
  }


//calendar stuff


//print_r($calendarClient);


$calendarId = 'html24.net_3u70aooh2tn8j6cschgu2em0v0@group.calendar.google.com';

function createGoogleCalendarEvent($client, $calendarId, $summary, $location, $description, $start, $end, $attendees){
  $service = new Google_Service_Calendar($client);

  $event = new Google_Service_Calendar_Event(array(
    'summary' => $summary,
    'location' => $location,
    'description' => $description,
    'start' => $start,
    'end' => $end,
    'attendees' => $attendees
  ));

  $event = $service->events->insert($calendarId, $event);
  printf('Event created: %s\n', $event->htmlLink);
};













require_once("viewlogin.php");

?>
