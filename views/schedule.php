    <?php 
//ensure the person is logged in
    if(!isset($_SESSION['access_token'])){
        ?>
        <script type='text/javascript'>
            let $message = "Login Required";
            alert($message);
            window.location.href='index.php';
        </script>
        <?php
    }
    
    ?>    
    
    
    <div class="row">

        <div class="container col-9 offset-1">
            <div class="row">
                <div class="col-5">
                    <h1>Employees</h1>
                    <div id="employees" class="participantList employees col-12">
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-5">
                    <h1>Participants</h1>
                    <div id="participants" class="participantList col-12">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="ScheduleModal" tabindex="-1" role="dialog" aria-labelledby="ScheduleModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6" >
                            <p>participants</p>
                            <ul id="modalParticipants">
                            </ul>
                        </div>
                        <div class="col-6" >
                            <div class="row">
                                <label for="modalStartTime">Starting Time:</label>
                                <div id="modalStartTime">

                                </div>
                                <label for="modalEndTime">End Time:</label>
                                <div id="modalEndTime">

                                </div>
                            </div>
                            <div class="row">
                                <p>options</p>
                                <div class="row">
                                    <label for="notifyParticipants" class="col-10">Notify participants</label>
                                    <input type="checkbox" id="notifyParticipants" class="col-2" name="notifyParticipants">
                                </div>
                                <div class="row">
                                    <label for="scheduleParticipants" class="col-10">Schedule participants</label>
                                    <input type="checkbox" id="scheduleParticipants" class="col-2" name="scheduleParticipants">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <form method="POST">
                        <input type="submit" name="accept" class="btn btn-primary acceptMeeting" value="Accept">
                    </form>
                </div>
                </div>
            
        </div>
    </div>


<script>
$(document).ready(function(){


    /* Logic for populating list with employees */
        generateParticipantList("#employees")

    /* function for opening the modal, and transfering data to it */

    $("#CreateMeeting").click(function(){
        ScheduleModal("#ScheduleModal");
        

    });

    $(".close").click(function(){
        // fade the modal Out.
        $("#ScheduleModal").fadeOut();
    })

    // Logic for creating the meeting

    $(".acceptMeeting").click(function(event){
        event.preventDefault();
        participantEmailList = [];

        //Generate an array of the emails from the participants so we can notify them.
        $("#participants").children().each(function(i){

                // we get their name
                let employeeName = $(this).text();

                // the listId
                let employeeDataId = $(this).data('id');

                // the Email
                let employeeEmail = $(this).data('email');


                participantEmailList.push(employeeEmail);

        })

        console.log(participantEmailList);

         /* Get from elements values */
    

        let sendData = function() {
        $.post('google/createEvent.php', {
            data: participantEmailList
        }, function(response) {
            console.log(response);
        });
        }
        sendData();

        //Create a google event.

    });



});

    

        


</script>