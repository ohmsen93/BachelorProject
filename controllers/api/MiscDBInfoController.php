<?php
class MiscDBInfoController extends BaseController {

    public function listRoles()
    {
        /** List all tracks */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            // Create a model instance
            $roleModel = new miscDBInfo();
            // into an array
            $roles = $roleModel->getRoles();
            // I want to return json, encoding my array
            $responseData = json_encode($roles);

        }
        catch (Error $e)
        { // Errors are not exceptions, but rather an issue in the code
            $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }
}