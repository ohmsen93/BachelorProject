
    // Code to allow for Get variables in JS
    function getQueryParams(qs) {
        qs = qs.split("+").join(" ");
        let params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

        while (tokens = re.exec(qs)) {
            params[decodeURIComponent(tokens[1])]
                = decodeURIComponent(tokens[2]);
        }
        return params;
    }

    function generateNavListElements($target){

        let $_GET = getQueryParams(document.location.search);

        let model = $_GET['view'];

        let listElements = "<li>"+
                            "<a class='col-12 list-group-item' href='?' >Home</a>"+
                            "</li>";

        // First we get the model for the list we want to generate
        $.get("api/"+model, function(data){
            //console.log(data);
            //then we generate the list elements based of the model
            $.each(data, function(i, item){
                //console.log(item['id']);
                //console.log(item['name']);
                listElements +=     "<li>"+
                                        "<a class='col-12 list-group-item' href='?view="+model+"&"+model+"="+item['id']+"' >"+item['name']+"</a>"+
                                    "</li>";
            });

            $($target).html(listElements);
            
            return true;
        });
    };

    function generateViewTools($target){
        
        let $_GET = getQueryParams(document.location.search);

        let view = $_GET['view'] ?? ''

        let generatedTools = $('.tools').html();
        
        // Basically an isset function checks if $_GET['view'] is set in the url
        if(typeof(view) != "undefined" && view !== null){
            let tools = $_GET[view];
            
            //Checks if the submodel is loaded in the url, so we can load the relevant tools.
            if(typeof(tools) != "undefined" && tools !== null){

                switch(view){
                    case 'schedule':
                        console.log('display Schedule tools');

                        generatedTools = '<form action="" class="form-group">'+
                                            '<label for="startTime">Starting time:</label>'+
                                            '<input type="datetime-local" class="form-control" name="startTime" placeholder="Start Time" id="startTime">'+
                                            '<label for="endTime">Ending time:</label>'+
                                            '<input type="datetime-local" class="form-control" name="endTime" Placeholder="End Time" id="endTime">'+
                                            '</br>'+
                                            '<a href="#" id="CreateMeeting" class="btn btn-primary" value="">Create Meeting</a>'+
                                        '</form>';
                        break;

                    default:
                        console.log('unknown sub-page');
                }

                $($target).html(generatedTools);
            } else {
                generatedTools = '';
                console.log("home Tools");
                $($target).html(generatedTools);
            }
        }
    }

    function generateParticipantList($target){
        console.log("gets function")
        $.get("api/user", function(data){
            //Create the list and rows
            let result =    '';
            let listId = 0;
                // user Get results goes here
                    //Get the list elements
                    $.each(data, function(i, item){
                        console.log(item);

                        result += '<div class="employee col-12" data-email="'+item['email']+'" data-id="'+listId+'">'+item['firstName']+' '+item['lastName']+'</div>';
                        listId++;
                    });

            // append exising users to employee list
            $($target).append(result);
        
        
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
        });
    }

    function ScheduleModal(){

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

    }

    //Loads a Schedule
    function loadSchedule($target){
        let $_GET = getQueryParams(document.location.search);


        let Model = $_GET['view'];
        let Id = $_GET['room'];

        // Basically an isset function checks if $_GET['view'] is set in the url
        if(typeof(Model) != "undefined" && Model !== null){

            //Checks if the submodel is loaded in the url, so we can load the relevant schedule.
            if(typeof(Id) != "undefined" && Id !== null){

                // Get specific room based of id from navigation
                    
                $.get("api/"+Model+"/"+Id, function(data){
                    let result = '';
                    console.log(data);

                    result = '<iframe class="schedule container-fluid" src="'+data['calendarURL']+'" frameborder="0"></iframe>'

                    $($target).html(result);

                });
            }
        }
    }

 
     



    
