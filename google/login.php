<?php
    // init configuration

use Google\Service\Calendar\Calendar;



function getClient(){
    $clientID = '939294856842-2sibjb44s44cf7l1k57rmg0vd4klasb2.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'http://localhost/BachelorProject/FinalProject/index.php';

    // create Client Request to access Google API
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

    // if session is not set, auth, else grab info from session
    if(isset($_SESSION['email'])){
        print_r($_SESSION);
        echo "<a href='views/logout.php' class='btn btn-lg btn-danger'>Logout</a>";
        if($_SESSION['role_id'] == 2){
            echo "<a href='administration/index.php' class='btn btn-lg btn-secondary'>administration</a>";
        }
    } else {

        // authenticate code from Google OAuth Flow
        if (isset($_GET['code'])) {

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        print_r($token);

        $client->setAccessToken($token['access_token']);

        // get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email =  $google_account_info->email;
        $name =  $google_account_info->name;
            

        // make a curl request to the api to check the users for the specific user
            $emailCheck = json_encode(array(
                "email" => $email
            ));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://localhost/BachelorProject/FinalProject/api/users");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $emailCheck);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            # Return response instead of printing.
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            # Send request.
            $result = curl_exec($ch);
            curl_close($ch);

            // if the user doesnt exist Register the user
            if($result === 'false'){
                $registerInfo = json_encode(array(
                    "firstName" => $google_account_info->givenName,
                    "lastName" => $google_account_info->familyName,
                    "email" => $email,
                    "token" => $token
                ));


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://localhost/BachelorProject/FinalProject/api/users/create");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $registerInfo);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                # Return response instead of printing.
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                # Send request.
                $result = curl_exec($ch);
                curl_close($ch);


                // make a curl request to grab the newly created user
                $emailCheck = json_encode(array(
                    "email" => $email
                ));

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://localhost/BachelorProject/FinalProject/api/user");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $emailCheck);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                # Return response instead of printing.
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                # Send request.
                $result = curl_exec($ch);
                curl_close($ch);

                //set the session for the user
                $profile = json_decode($result);

                $_SESSION['firstName'] = $profile->firstName;
                $_SESSION['lastName'] = $profile->lastName;
                $_SESSION['email'] = $profile->email;
                $_SESSION['google_account_id'] = $profile->google_account_id;
                $_SESSION['role_id'] = $profile->fk_role_id;

                print_r($_SESSION);

                //set the header so we remove the code
                header('Location: ' . $_SERVER['PHP_SELF']);

            // Else grab the user information
            } else {

                $profile = json_decode($result);

                $_SESSION['firstName'] = $profile->firstName;
                $_SESSION['lastName'] = $profile->lastName;
                $_SESSION['email'] = $profile->email;
                $_SESSION['google_account_id'] = $profile->google_account_id;
                $_SESSION['role_id'] = $profile->fk_role_id;

                print_r($_SESSION);
                //header('Location: ' . $_SERVER['PHP_SELF']);

            }
                
        // now you can use this profile info to create account in your website and make user logged in.
        } else {

        echo "<a href='".$client->createAuthUrl()."' class='btn btn-lg btn-success loginBtn'>Google Login</a>";
        }
    }

    return $client;
}

// We now have a getClient function, This allows us to call it in other places such as below, to access google data such as profile information or the personal calendar.

$client = getClient();


$service = new Google_Service_Calendar($client);

 
// Print the next 10 events on the user's calendar.
$calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);
$events = $results->getItems();

if (empty($events)) {
    print "No upcoming events found.\n";
} else {
    print "Upcoming events:\n";
    foreach ($events as $event) {
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        printf("%s (%s)\n", $event->getSummary(), $start);
    }
}





    

?>
   
   <script>


   </script>