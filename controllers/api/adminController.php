<?php
class AdminController extends BaseController {

    public function validateAdmin($password){
        /** validate a customer */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try {
            $adminModel = new admin();

            $adminModel->validateAdmin($password);
        }
        catch (Error $e){
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }
}