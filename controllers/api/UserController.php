<?php
class UserController extends BaseController {

    public function addNewUser($google_account_id, $firstName, $lastName, $email, $password)
    {
        /** Add a new user/sign-up */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';
        try
        {
            $userModel = new user();

            // Hash password
            $password = password_hash($password, PASSWORD_DEFAULT);

            $userModel->addNewuser(
                $google_account_id, $firstName, $lastName, $email, $password);


            $responseData = json_encode(array('userId' =>$userModel->pdo->lastInsertId()));
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function updateuser($id, $email, $firstName, $lastName, $password)
    {
        /** Update user */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $userModel = new user();
            // Hash password if a new one is provided
            if (!is_null($password))
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }

            $userModel = $userModel->updateuser($id, $email, $firstName, $lastName, $password);

            $responseData = json_encode(array('id' => $id, 'affectedRows' => $userModel));
        }
        catch (Error $e)
        {
            print_r($e);
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function validateUser($email, $password){
        /** validate a user */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try {
            $userModel = new user();

            $userModel->validateuser($email, $password);
        }
        catch (Error $e){
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function getUserById($id)
    {
        /** Function to get a user by id and provide it json-encoded. */
        // Initialize stuff
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try {
            // Model instance
            $userModel = new user();
            // Get user
            $user = $userModel->getUserById($id);
            // Return as json
            $responseData = json_encode($user);
        } catch (Error $e) { // Errors are not exceptions, but rather an issue in the code
            $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

}