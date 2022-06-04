    <?php 

    
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
                    <button type="button" class="btn btn-primary acceptMeeting">Accept</button>
                </div>
                </div>
            
        </div>
    </div>


    <script>
        $(document).ready(function(){


        /* Logic for populating list with employees */

        $.get("api/users", function(data){
            //Create the list and rows
            let result =    '';
            let listId = 0;
                // user Get results goes here
                    //Get the list elements
                    $.each(data, function(i, item){
                        console.log(item);

                        result += '<div class="employee col-12" data-id="'+listId+'">'+item['firstName']+' '+item['lastName']+'</div>';
                        listId++;
                    });

            // append exising users to employee list
            $('#employees').append(result);
        
        
            /* Logic for moving form one list to another */
            $(".employee").each(function(){
                // First we define the Div as This, so we can work with it later.
                console.log("1");
                let $this = $(this);
                // Then we make a functionality when we click the thing we want to interact with.
                $this.click(function(event){
                    // as we end up with more each functionality ive decided to rename the "outerThis" employee.
                    let employee = $this;
                    // here we find the parent, so we can check the class in order to figure out which list the employee is within.
                    let parent = $this.closest('.participantList');
                    // here we define dataVal from the data-id tag on the object.
                    let dataVal = $this.data('id');

                    // Now we check if the object is within the employees list.
                    if(parent.hasClass('employees')){
                        // then we check if the destination list is empty.
                        if($("#participants").children().length == 0){
                            // if it is empty, we simply append the object.
                            $("#participants").append(employee);
                        } else {
                            // if the destination is not empty, we will have to iterate through it in order to find the correct placement.
                            $("#participants").children().each(function(i){
                                // here we get the id of the child.
                                let thisVal = +$(this).data('id');
                                // then we check if the data value is greater than the child's value and then inserts before.
                                if(dataVal < thisVal){
                                    console.log('insert here');
                                    employee.insertBefore(this);
                                    // we return false to break the loop
                                    return false;
                                // if the element is last or satisfies the codition for insertion
                                } else if ($(this).next().length == 0 || (dataVal <= thisVal && thisVal > +$(this).next().data('sort'))) {
                                    employee.insertAfter(this);
                                    // we return false to break the loop
                                    return false;
                                }
                            });
                        }


                    } else {
                        
                        console.log('move '+dataVal+' to employees');
                        console.log("employees has "+$("#employees").children().length);

                        if($("#employees").children().length == 0){
                            $("#employees").append(employee);
                        } else {
                            $("#employees").children().each(function(i){
                                let thisVal = +$(this).data('id');
                                let $innerThis = this;


                                if(dataVal < thisVal){
                                    console.log('insert here');
                                    employee.insertBefore(this);
                                    return false;
                                } else if ($(this).next().length == 0 || (dataVal <= thisVal && thisVal > +$(this).next().data('sort'))) {
                                    employee.insertAfter(this);
                                    return false;
                                }
                            });
                        };
                    };
                });            
            });

        
        /* Logic something like Jquery, Create array from Participant Div, and then work from that */
        // first we create an function when we click CreateMeeting
        
        /* Modal Show for meeting onclick for input submit "create meeting" */
            $("#CreateMeeting").click(function(){

                let $modalParticipantList = '';

                // then we take the children from the participants div
                $("#participants").children().each(function(i){
                    // we make a list of the participants, first we make a variable to store them in.
                    console.log($(this));
                    // we get their name
                    let employeeName = $(this).text();

                    // the listId
                    let employeeDataId = $(this).data('id');

                    // here we can add additional info if it is needed later

                    $modalParticipantList += "<li><p>"+employeeName+"</p><p>"+employeeDataId+"</p></li>";

                });

                $("#modalStartTime").append($("#startTime").val());
                $("#modalEndTime").append($("#endTime").val());


                // add the list elements to the ul.
                $("#modalParticipants").html($modalParticipantList);

                // fade the modal in.
                $("#ScheduleModal").fadeIn();

            });



        // Logic for creating the meeting

        // to create a meeting we need the google access info first.
        // we can get that from the user that is logged in,
        
            /*
            todo:

            check if it is possible to create a meeting with the token information from the user that is logged in.         
            
            */


        // Logic for informing the participants



        });

        $(".close").click(function(){
                $("#ScheduleModal").fadeOut();
            });


            $(".acceptMeeting").click(function(data){
                console.log("acceptMeeting");

                let $calendarId = 'html24.net_3u70aooh2tn8j6cschgu2em0v0@group.calendar.google.com';
                let $summary = 'This is the test summary';
                let $all_day = '0';
                let $event_time = ['start_time' = "2022-06-03T01:14",'end_time' = "2022-06-03T02:14"];
                let $event_timezone = '';
                let $access_token = '';

                CreateCalendarEvent($calendarId, $summary, $all_day, $event_time, $event_timezone, $access_token);

                
            });

            function CreateCalendarEvent($calendarId, $summary, $all_day, $event_time, $event_timezone, $access_token){

                $url = '';
                $url_events = 'https://www.googleapis.com/calendar/v3/calendars/'+$calendarId+'/events';

                let payload = ['summary' = $summary];
                if($all_day == 1) {
                    payload['start'] = array('date' = $event_time['event_date']);
                    payload['end'] = array('date' = $event_time['event_date']);
                }
                else {
                    payload['start'] = array('dateTime' = $event_time['start_time'], 'timeZone' = $event_timezone);
                    payload['end'] = array('dateTime' = $event_time['end_time'], 'timeZone' = $event_timezone);
                }

                console.log(payload);

                /*
                $.post($url_events, $payload, function(return_data){
                    alert(return_data);
                });
                */
            }
    });
    
    </script>