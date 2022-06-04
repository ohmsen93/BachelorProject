<?php
class UserController extends BaseController {

    
    public function listUsers()
    {
        /** List all tracks */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            // Create a model instance
            $UserModel = new User();
            // into an array
            $Users = $UserModel->getUsers();
            // I want to return json, encoding my array
            $responseData = json_encode($Users);

        }
        catch (Error $e)
        { // Errors are not exceptions, but rather an issue in the code
            $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function addNewUser($firstName, $lastName, $email, $token)
    {
        /** Add a new user/sign-up */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $userModel = new user();

            $userModel->addNewUser($firstName, $lastName, $email, $token);

            $responseData = json_encode(array('message' => 'User Registered'));
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
        
    }

    
    public function updateUser($email, $firstName, $lastName, $role_id)
    {
        /** Update an User */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $UserModel = new User();
            $UserModel = $UserModel->updateUser($email, $firstName, $lastName, $role_id);
            $responseData = json_encode('Updated ' . $UserModel . ' User');
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        catch (PDOException $pdoEx)
        {
            if ($pdoEx->getCode() === '23000')
            {
                $responseData = json_encode('Certain fields of User cannot be updated');
            }
            else
            {
                $errorMsg = 'Fatal error!';
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function getUserByEmail($email)
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
            $user = $userModel->getUserByEmail($email);
            // Return as json
            $responseData = json_encode($user);
        } catch (Error $e) { // Errors are not exceptions, but rather an issue in the code
            $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

}