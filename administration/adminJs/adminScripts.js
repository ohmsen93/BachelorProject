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

function generateAdminDisplayCrud(){
            
    
    let $_GET = getQueryParams(document.location.search);

            // get the model from view
            let Model = $_GET['view'];
            let Display = $_GET['display'];
            let Create = $_GET['create'];

            console.log($_GET);
    
            if(typeof(Display) != "undefined" && Display !== null){
                // get the specific display
                $.get("api/"+Model+"/"+Display, function(data){
                    // if the specific Display exists, display a form so we can update it.
                    let result =    '<div class="form-group">'+
                                        '<div class="row">'+
                                            '<label for="displayName">Display Name</label>'+
                                        '</div>'+
                                        '<div class="row">'+
                                            '<input type="text" name="displayName" id="displayName" class="form-control" placeholder="Display Name" aria-describedby="helpId" value="'+data['name']+'">'+
                                            '<small id="helpId" class="text-muted">the name of the display</small>'+
                                        '</div>'+
                                        '<div class="row">'+
                                            '<button class="btn btn-lg btn-warning" id="updateDisplay" value="">Update</button>'+
                                        '</div>'+
                                    '</div>';
    
                    $('.displayContent').append(result);
    
                        // if update is pressed we need to post the variables so we update the database.
                    $('#updateDisplay').click(function(){
                        let name = $("#displayName").val();
                        let id = data['id'];
    
    
                        let payload = {"name":name}
                        
                        $.ajax({
                            type: 'PUT',
                            url: 'api/'+Model+'/'+Display,
                            contentType: 'application/json',
                            data: JSON.stringify(payload), // access in body
                        }).done(function () {
                            console.log('SUCCESS');
                        })
    
                        window.location.href = "index.php?view=display";
                    });
                });
    
            
            } else {
                // if is create make a create new form, else display list
                if(typeof(Create) != "undefined" && Create !== null){
                    // CREATE NEW DISPLAY
                    let result =    '<div class="form-group">'+
                                        '<div class="row">'+
                                            '<label for="displayName">Display Name</label>'+
                                        '</div>'+
                                        '<div class="row">'+
                                            '<input type="text" name="displayName" id="displayName" class="form-control" placeholder="Display Name" aria-describedby="helpId">'+
                                            '<small id="helpId" class="text-muted">the name of the display</small>'+
                                        '</div>'+
                                        '<div class="row">'+
                                            '<button class="btn btn-lg btn-primary" id="createDisplay" value="">Create Display</button>'+
                                        '</div>'+
                                    '</div>';
    
                    $('.displayContent').append(result);
    
                        // if Create Display is pressed we need to post the variables to the database.
                    $('#createDisplay').click(function(){
                        let name = $("#displayName").val();
    
                        let payload = {"name":name}
                        
                        $.post("api/"+Model, {
                            "name": name
                        }, function (return_data) {
                            alert(return_data);
                        });
    
                        window.location.href = "index.php?view=display";
                    });
                } else {
                        // if the specific Display is not set, generate a list of the existing displays.
                        $.get("api/"+Model, function(data){
                            //Create the list and rows
                        let result =    '<div class="row">'+
                                            '<ul class="displayList list-group" style="margin:15px">';
                                            // Display Get results goes here
                            //Get the list elements
                        $.each(data, function(i, item){
                            console.log(item['id']);
                            console.log(item['name']);
                            result +=   "<li class='list-group-item col-12'>"+
                                        "<div class='row'>"+
                                        "<a class='col-11' href='?view=display&display="+item['id']+"' >"+item['name']+"</a>"+
                                        "<button class='col-1 btn btn-danger displayDelete' data-id='"+item['id']+"'>Delete</button>"+
                                        "</div>"+
                                        "</li>";
                        });
                            //Close the list and rows
                        result +=       '</ul>'+
                                        '</div>'+
                                        '<div class="row">'+
                                        '<a href="?view=display&create=true" class="btn btn-lg btn-primary col-2 offset-5">Create</a>'+
                                        '</div>';
    
                        $('.displayContent').append(result);
    
                            // Implement Delete functionality on the Delete buttons
                        $(".displayDelete").click(function () {
    
                            delId = $(this).attr("data-id");
    
                            $.ajax({
                                type: 'DELETE',
                                url: 'api/displays/' + delId,
                            }).done(function () {
                                // make a messaging feedback system if theres time.
                                console.log('Display Deleted on id:' + delId);
                                window.location.href = "index.php?view=displays";
                            });
                        });
                    });
                };
            };
}

function generateAdminRoomCrud(){
    let $_GET = getQueryParams(document.location.search);

    // get the model from view
    let Model = $_GET['view'];
    let Room = $_GET['room'];
    let Create = $_GET['create'];

    if(typeof(Room) != "undefined" && Room !== null){
        // get the specific room
        $.get("api/"+Model+"/"+Room, function(data){
            // if the specific room exists, room a form so we can update it.
            let result =    '<div class="form-group">'+
                                '<div class="row">'+
                                    '<label for="RoomName">Room Name</label>'+
                                '</div>'+
                                '<div class="row">'+
                                    '<input type="text" name="roomCalendarURL" id="roomCalendarURL" class="form-control" placeholder="Room CalendarURL" aria-describedby="helpId" value="'+data['calendarURL']+'">'+
                                    '<input type="text" name="roomCalendarID" id="roomCalendarID" class="form-control" placeholder="Room CalendarID" aria-describedby="helpId" value="'+data['calendarId']+'">'+
                                    '<input type="text" name="roomName" id="roomName" class="form-control" placeholder="Room Name" aria-describedby="helpId" value="'+data['name']+'">'+
                                    '<small id="helpId" class="text-muted">the name of the room</small>'+
                                '</div>'+
                                '<div class="row">'+
                                    '<button class="btn btn-lg btn-warning" id="updateRoom" value="">Update</button>'+
                                '</div>'+
                            '</div>';

            $('.roomContent').append(result);

                // if update is pressed we need to post the variables so we update the database.
            $('#updateRoom').click(function(){
                let name = $("#roomName").val();
                let calendarid = $("#roomCalendarID").val();
                let calendarurl = $("#roomCalendarURL").val();

                let id = data['id'];


                let payload = {
                    "name":name,
                    "calendarId":calendarid,
                    "calendarUrl":calendarurl
                }
                
                $.ajax({
                    type: 'PUT',
                    url: 'api/'+Model+'/'+Room,
                    contentType: 'application/json',
                    data: JSON.stringify(payload), // access in body
                }).done(function () {
                    console.log('SUCCESS');
                })

                window.location.href = "index.php?view=room";
            });
        });

    
    } else {
        // if is create make a create new form, else room list
        if(typeof(Create) != "undefined" && Create !== null){
            // CREATE NEW room
            let result =    '<div class="form-group">'+
                                '<div class="row">'+
                                    '<label for="roomName">Room Name</label>'+
                                '</div>'+
                                '<div class="row">'+
                                    '<input type="text" name="roomCalendarURL" id="roomCalendarURL" class="form-control" placeholder="Room CalendarURL" aria-describedby="helpId">'+
                                    '<input type="text" name="roomCalendarID" id="roomCalendarID" class="form-control" placeholder="Room CalendarID" aria-describedby="helpId">'+
                                    '<input type="text" name="roomName" id="roomName" class="form-control" placeholder="Room Name" aria-describedby="helpId">'+
                                    '<small id="helpId" class="text-muted">the name of the room</small>'+
                                '</div>'+
                                '<div class="row">'+
                                    '<button class="btn btn-lg btn-primary" id="createRoom" value="">Create room</button>'+
                                '</div>'+
                            '</div>';

            $('.roomContent').append(result);

                // if Create room is pressed we need to post the variables to the database.
            $('#createRoom').click(function(){
                let name = $("#roomName").val();
                let calendarid = $("#roomCalendarID").val();
                let calendarurl = $("#roomCalendarURL").val();

                console.log(calendarid+" "+calendarurl);

                $.post("api/"+Model, {
                    "name":name,
                    "calendarId":calendarid,
                    "calendarUrl":calendarurl
                }, function (return_data) {
                    alert(return_data);
                });


                //window.location.href = "index.php?view=room";
            });
        } else {
                // if the specific room is not set, generate a list of the existing rooms.
                $.get("api/"+Model, function(data){
                    //Create the list and rows
                let result =    '<div class="row">'+
                                    '<ul class="roomList list-group" style="margin:15px">';
                                    // room Get results goes here
                    //Get the list elements
                $.each(data, function(i, item){
                    console.log(item['id']);
                    console.log(item['name']);
                    result +=   "<li class='list-group-item col-12'>"+
                                "<div class='row'>"+
                                "<a class='col-11' href='?view=room&room="+item['id']+"' >"+item['name']+"</a>"+
                                "<button class='col-1 btn btn-danger roomDelete' data-id='"+item['id']+"'>Delete</button>"+
                                "</div>"+
                                "</li>";
                });
                    //Close the list and rows
                result +=       '</ul>'+
                                '</div>'+
                                '<div class="row">'+
                                '<a href="?view=room&create=true" class="btn btn-lg btn-primary col-2 offset-5">Create</a>'+
                                '</div>';

                $('.roomContent').append(result);

                    // Implement Delete functionality on the Delete buttons
                $(".roomDelete").click(function () {

                    delId = $(this).attr("data-id");

                    $.ajax({
                        type: 'DELETE',
                        url: 'api/room/' + delId,
                    }).done(function () {
                        // make a messaging feedback system if theres time.
                        console.log('room Deleted on id:' + delId);
                        window.location.href = "index.php?view=room";
                    });
                });
            });
        };
    };
}

function generateAdminUserCrud(){
    let $_GET = getQueryParams(document.location.search);

    // get the model from view
    let Model = $_GET['view'];
    let User = $_GET['user'];

    if(typeof(User) != "undefined" && User !== null){
        console.log(User);

        // get the specific user
        // $.post("api/"+Model, {
        //     "email": User
        // }, function(data){
            let formData = {email: User};

            $.ajax({
                url: "api/"+Model,
                type: "POST",
                data: JSON.stringify(formData),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(data, textStatus, jqXHR)
                {
                    console.log(data);
                    console.log(textStatus);
                    console.log(jqXHR);
                    //data - response from server

                    userEmail = data['email'];
                    userFirstName = data['firstName'];
                    userLastName = data['lastName'];
                    userRole = data['fk_role_id'];
                    

                    //Get misc data from DB
                    $.get("api/miscDBInfo", function(data){
                        //Generate the form
                        let result =    '<div class="form-group">'+
                                            '<div class="row">'+
                                                '<label for="userFirstName">First name:</label>'+
                                                '<input name="userFirstName" id="userFirstName" type="text" class="form-control" placeholder="First Name" value="'+userFirstName+'"></input>'+
                                                '<label for="userFirstName">Last name:</label>'+
                                                '<input name="userLastName" id="userLastName" type="text" class="form-control" placeholder="Last Name" value="'+userLastName+'"></input>'+
                                            '</div>'+
                                            '<div class="row">'+
                                                '<label for="userRole">User Role:</label>'+
                                            '</div>'+
                                            '<select  name="userRole" id="userRole" class="form-control" placeholder="User Role" aria-describedby="helpId"">';

                                            // Use the misc data from db to generate Role options
                                            $.each(data, function(i, item){
                                                // make it so that its the active role that is default
                                                let selected = '';
                                                if(item['id'] == userRole){
                                                    selected = 'selected';
                                                } else {
                                                    selected = '';
                                                }
                                                result += '<option value="'+item['id']+'" '+selected+'>'+item['role_name']+'</option>';
                                            });

                            result +=       '</select>'+
                                            '<small id="helpId" class="text-muted">the role of the user</small>'+
                                            '</div>'+
                                            '<div class="row">'+
                                                '<button class="btn btn-lg btn-warning" id="updateUser" value="">Update</button>'+
                                            '</div>'+
                                        '</div>';

                    $('.userContent').append(result);

                        // if update is pressed we need to post the variables so we update the database.
                    $('#updateUser').click(function(){
                        console.log("updateStart");

                        let firstName = $("#userFirstName").val();
                        let lastName = $("#userLastName").val();

                        let role_id = $("#userRole").val();


                        let payload = {
                            "email":userEmail,
                            "firstName":firstName,
                            "lastName":lastName,
                            "fk_role_id":role_id
                        }
                        
                        console.log(payload);

                        $.ajax({
                            type: 'PUT',
                            url: 'api/'+Model,
                            contentType: 'application/json',
                            data: JSON.stringify(payload), // access in body
                        }).done(function () {
                            console.log('SUCCESS');
                        })

                        window.location.href = "index.php?view=user";

                    });



                                        

                    });
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);

                }
            



                //if the specific user exists, user a form so we can update it.
            
        });

    
    } else {
                // if the specific user is not set, generate a list of the existing users.
                $.get("api/"+Model, function(data){
                
                    //Create the list and rows
                    let result =    '<div class="row">'+
                                        '<ul class="userList list-group" style="margin:15px">';
                // user Get results goes here
                    //Get the list elements
                    $.each(data, function(i, item){
                        console.log(item['id']);
                        console.log(item['name']);
                        let role = '';

                        if(item['fk_role_id'] == 2){
                            role = 'administrator';
                        } else {
                            role = 'employee';
                        }
                        
                        result +=           "<li class='list-group-item col-12'>"+
                                                "<div class='row'>"+
                                                    "<a class='col-9 list-group-item' href='?view=user&user="+item['email']+"' >"+item['firstName']+" "+item['lastName']+"</a>"+
                                                    "<div class='col-2 list-group-item'>"+role+"</div>"+
                                                    "<button class='col-1 btn btn-danger userDelete' data-id='"+item['email']+"'>Delete</button>"+
                                                "</div>"+
                                            "</li>";
                    });
                        //Close the list and rows
                    result +=           '</ul>'+
                                    '</div>';

                    $('.userContent').append(result);

                        // Implement Delete functionality on the Delete buttons
                    $(".userDelete").click(function () {

                        delId = $(this).attr("data-id");

                        $.ajax({
                            type: 'DELETE',
                            url: 'api/user/' + delId,
                        }).done(function () {
                            // make a messaging feedback system if theres time.
                            console.log('user Deleted on id:' + delId);
                            window.location.href = "index.php?view=user";
                        });
                    });
            });
    };
}