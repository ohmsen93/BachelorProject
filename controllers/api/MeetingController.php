<?php
class MeetingController extends BaseController {

    public function listMeetings()
    {
        /** List all tracks */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            // Create a model instance
            $meetingModel = new meeting();
            // into an array
            $meetings = $meetingModel->getMeetings();
            // I want to return json, encoding my array
            $responseData = json_encode($meetings);

        }
        catch (Error $e)
        { // Errors are not exceptions, but rather an issue in the code
            $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function getMeetingById($id)
    {
        /** Get an meeting by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $meetingModel = new meeting();
            $meeting = $meetingModel->getMeetingById($id);
            $responseData = json_encode($meeting);
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function addMeeting($fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description)
    {
        /** Add an meeting by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $meetingModel = new meeting();
            $meetingModel->addMeeting($fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description);
            $responseData = json_encode('Created meeting with id: ' . $meetingModel->pdo->lastInsertId());
        }
        catch (Error $e)
        {
            $errorMsg = 'Unsupported method';
            $errorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    public function deleteMeeting($id)
    {
        /** Delete an meeting by id */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $meetingModel = new meeting();
            $meetingModel = $meetingModel->deleteMeeting($id);
            $responseData = json_encode('Deleted ' . $meetingModel . ' meeting!');
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
                $responseData = json_encode('meeting cannot be deleted due to historical reasons: '.$pdoEx);
            }
            else
            {
                $errorMsg = 'Fatal error!';
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);

    }

    public function updateMeeting($id, $fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description)
    {
        /** Update an meeting */
        $errorHeader = '';
        $errorMsg = '';
        $responseData = '';

        try
        {
            $meetingModel = new meeting();
            $meetingModel = $meetingModel->updateMeeting($id, $fk_user_id, $fk_room_id, $title, $start_time, $end_time, $description);
            $responseData = json_encode('Updated ' . $meetingModel . ' meeting');
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
                $responseData = json_encode('Certain fields of meeting cannot be updated');
            }
            else
            {
                $errorMsg = 'Fatal error!';
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);
    }

    // public function searchmeeting($searchText)
    // {
    //     /** List search-matched meetings */
    //     $errorHeader = '';
    //     $errorMsg = '';
    //     $responseData = '';

    //     try
    //     {
    //         $meetingModel = new meeting();
    //         $meetings = $meetingModel->searchmeeting($searchText);
    //         $responseData = json_encode($meetings);

    //     }
    //     catch (Error $e)
    //     { // Errors are not exceptions, but rather an issue in the code
    //         $errorMsg = 'Software first-aid needed!: ' . $e->getMessage();
    //         $errorHeader = 'HTTP/1.1 500 Internal Server Error';
    //     }
    //     $this->check_for_error_msg($errorMsg, $responseData, $errorHeader, $this);

    // }

}