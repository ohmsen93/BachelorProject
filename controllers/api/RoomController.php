<?php
class RoomController extends BaseController {

    public function listRooms()
    {
        /** List all tracks */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            // Create a model instance
            $roomModel = new room();
            // into an array
            $rooms = $roomModel->getRooms();
            // I want to return json, encoding my array
            $responseData = json_encode($rooms);

        }
        catch (Error $e)
        { // Errors are not exceptions, but rather an issue in the code
            $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function getRoomById($id)
    {
        /** Get an room by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $roomModel = new room();
            $room = $roomModel->getRoomById($id);
            $responseData = json_encode($room);
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function addRoom($name)
    {
        /** Add an room by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $roomModel = new room();
            $roomModel->addroom($name);
            $responseData = json_encode('Created room with id: ' . $roomModel->pdo->lastInsertId());
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function deleteRoom($id)
    {
        /** Delete an room by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $roomModel = new room();
            $roomModel = $roomModel->deleteRoom($id);
            $responseData = json_encode('Deleted ' . $roomModel . ' room!');
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
                $responseData = json_encode('room cannot be deleted due to historical reasons: '.$pdoEx);
            }
            else
            {
                $errorMsg = 'Fatal error!';
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);

    }

    public function updateRoom($id, $name)
    {
        /** Update an room */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $roomModel = new room();
            $roomModel = $roomModel->updateRoom($id, $name);
            $responseData = json_encode('Updated ' . $roomModel . ' room');
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
                $responseData = json_encode('Certain fields of room cannot be updated');
            }
            else
            {
                $errorMsg = 'Fatal error!';
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    // public function searchroom($searchText)
    // {
    //     /** List search-matched rooms */
    //     $errorHeader = '';
    //     $errorMsg = '';
    //     $responseData = '';

    //     try
    //     {
    //         $roomModel = new room();
    //         $rooms = $roomModel->searchroom($searchText);
    //         $responseData = json_encode($rooms);

    //     }
    //     catch (Error $e)
    //     { // Errors are not exceptions, but rather an issue in the code
    //         $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
    //         $errorHeader = 'HTTP/1.1 500 Internal Server Error';
    //     }
    //     $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);

    // }

}