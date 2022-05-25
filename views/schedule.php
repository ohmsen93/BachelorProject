<?php

?>


    <div class="row">
        <div class="container col-2">
            <form action="" class="form-group">
                <h1>Logged in as:</h1>

                <input type="datetime-local" class="form-control" name="startTime" placeholder="Start Time" id="">
                <input type="datetime-local" class="form-control" name="endTime" Placeholder="End Time" id="">

                <input type="submit" class="btn btn-primary" value="Create Meeting">
            </form>    


            
        </div>
        <div class="container col-9 offset-1">
            <div class="row">
                <div class="participantList col-5">
                    <div class="col-12">
                        <h1>Employees</h1>
                    </div>
                    <input class="participant col-12" type="checkbox" name="EmployeeName" id="" value="name">Name</input>
                    <input class="participant col-12" type="checkbox" name="EmployeeName" id="" value="name">Name</input>
                    <input class="participant col-12" type="checkbox" name="EmployeeName" id="" value="name">Name</input>
                </div>
                <div class="col-2"></div>
                <div class="participantList col-5">
                    <div class="col-12">
                            <h1>Participants</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* Logic for moving form one list to another */

        /* Logic something like Jquery, Create array from Participant Div, and then work from that */

        /* Modal Show for meeting onclick for input submit "create meeting" */
    </script>