<?php
// If the user has clicked on 'Logout', the session is destroyed
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');


    // If the user has filled the login fields, the authentication process is launched
} elseif (isset($_POST['loginEmail'])) {

    $userValidation = true;

    $email = $_POST['loginEmail'] ?? '';
    $password = $_POST['loginPassword'] ?? '';

    $user = new user();
    $validuser = $user->validateUser($email, $password);



    if ($validuser) {


        $_SESSION['id'] = $user->id;
        $_SESSION['firstName'] = $user->firstName;
        $_SESSION['lastName'] = $user->lastName;
        $_SESSION['email'] = $email;


    }
} else if(isset($_POST['adminPassword'])){

    $adminValidation = true;

    $password = $_POST['adminPassword'] ?? '';

    $admin = new admin();
    $validAdmin = $admin->validateAdmin($password);

    if ($validAdmin) {

        $_SESSION['admin'] = true;

    }

}



