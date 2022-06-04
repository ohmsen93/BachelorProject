<script>
    $(document).ready(function () {
        
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

        let $_GET = getQueryParams(document.location.search);


        // Get the displays to populate the 

        // Get the rooms to populate the navigation

        let Model = $_GET['view']
        switch (Model){
            case 'rooms':
                let room = $_GET['room'];
                let tools = '<div class="tools">'+
                                '<a class="btn btn-primary bookRoom" href="?view=schedule&room='+room+'">Book Room</a>'+
                            '</div';

                $('#mainNavigation').append(tools)

                $.get("api/rooms", function(data){
                        //Create the list and rows
                    let result =    '<div class="row list-group">'+
                                        '<ul class="roomList ">';
                                        // Display Get results goes here
                        //Get the list elements
                    $.each(data, function(i, item){
                        console.log(item['id']);
                        console.log(item['name']);
                        result +=   "<li>"+
                                    "<a class='col-12 roomListItem list-group-item' href='?view=rooms&room="+item['id']+"' >"+item['name']+"</a>"+
                                    "</li>";
                    });
                        //Close the list and rows
                    result +=       '</ul>'+
                                    '</div>';

                    $('#mainNavigation').append(result);

                    });
                break;
            case 'schedule':
                let result =    '<form action="" class="form-group">'+
                                        '<label for="startTime">Starting time:</label>'+
                                        '<input type="datetime-local" class="form-control" name="startTime" placeholder="Start Time" id="startTime">'+
                                        '<label for="endTime">Ending time:</label>'+
                                        '<input type="datetime-local" class="form-control" name="endTime" Placeholder="End Time" id="endTime">'+
                                    '</form>'+
                                '<div class="tools">'+
                                '<a href="#" id="CreateMeeting" class="btn btn-primary" value="">Create Meeting</a>'+
                                '</div>';

                $('#mainNavigation').html(result);
                break;
        }

        
        $(".bookRoom").click(function(data){
            let sessionEmail = "<?php if(!isset($_SESSION['email'])){ echo "null"; }else{ echo "set";}?>";

                console.log(sessionEmail);

            if(sessionEmail == "null"){
                
                console.log("logging in");

                data.preventDefault();
                
                alert("login needed");
                window.location.href = "<?php echo $client->createAuthUrl(); ?>";


            } else {
                console.log("use logged in info");
            }



        });

        
    });


</script>