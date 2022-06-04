<?php
class DisplayController extends BaseController {

    public function listDisplays()
    {
        /** List all tracks */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            // Create a model instance
            $displayModel = new display();
            // into an array
            $displays = $displayModel->getDisplays();
            // I want to return json, encoDing my array
            $responseData = json_encode($displays);

        }
        catch (Error $e)
        { // Errors are not exceptions, but rather an issue in the code
            $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function getDisplayById($id)
    {
        /** Get an display by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $displayModel = new display();
            $display = $displayModel->getDisplayById($id);
            $responseData = json_encode($display);
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function addDisplay($name)
    {
        /** Add an display by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $displayModel = new display();
            $displayModel->addDisplay($name);
            $responseData = json_encode('Created display with id: ' . $displayModel->pdo->lastInsertId());
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function deleteDisplay($id)
    {
        /** Delete an display by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $displayModel = new display();
            $displayModel = $displayModel->deleteDisplay($id);
            $responseData = json_encode('Deleted ' . $displayModel . ' display!');
        }
        catch (Error $e )
        {
            echo $e;
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        catch (PDOException $pdoEx)
        {
            if ($pdoEx->getCode() === '23000')
            {
                $responseData = json_encode('display cannot be deleted due to historical reasons: '.$pdoEx);
            }
            else
            {
                $errorMsg = 'Fatal error!';
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);

    }

    public function updateDisplay($id, $name)
    {
        /** Update an display */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $displayModel = new display();
            $displayModel = $displayModel->updateDisplay($id, $name);
            $responseData = json_encode('Updated ' . $displayModel . ' display');
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
                $responseData = json_encode('Certain fields of display cannot be updated');
            }
            else
            {
                $errorMsg = 'Fatal error!';
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    // public function searchdisplay($searchText)
    // {
    //     /** List search-matched displays */
    //     $errorHeader = '';
    //     $errorMsg = '';
    //     $responseData = '';

    //     try
    //     {
    //         $displayModel = new display();
    //         $displays = $displayModel->searchdisplay($searchText);
    //         $responseData = json_encode($displays);

    //     }
    //     catch (Error $e)
    //     { // Errors are not exceptions, but rather an issue in the code
    //         $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
    //         $errorHeader = 'HTTP/1.1 500 Internal Server Error';
    //     }
    //     $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);

    // }

}