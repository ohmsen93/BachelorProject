<?php 

?>

<div class="container col-12">
    <div class="roomContent">
        
    </div>
</div>

<script>
    // Code to allow for Get variables in JS
    function getQueryParams(qs) {
        qs = qs.split("+").join(" ");
        var params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

        while (tokens = re.exec(qs)) {
            params[decodeURIComponent(tokens[1])]
                = decodeURIComponent(tokens[2]);
        }

        return params;
    }

    var $_GET = getQueryParams(document.location.search);
    // check if the document is ready
    $(document).ready(function(){
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
                    let id = data['id'];


                    let payload = {"name":name}
                    
                    $.ajax({
                        type: 'PUT',
                        url: 'api/'+Model+'/'+Room,
                        contentType: 'application/json',
                        data: JSON.stringify(payload), // access in body
                    }).done(function () {
                        console.log('SUCCESS');
                    })

                    window.location.href = "index.php?view=rooms";
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

                    let payload = {"name":name}
                    
                    $.post("api/"+Model, {
                        "name": name
                    }, function (return_data) {
                        alert(return_data);
                    });

                    window.location.href = "index.php?view=rooms";
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
                                    "<a class='col-11' href='?view=rooms&room="+item['id']+"' >"+item['name']+"</a>"+
                                    "<button class='col-1 btn btn-danger roomDelete' data-id='"+item['id']+"'>Delete</button>"+
                                    "</div>"+
                                    "</li>";
                    });
                        //Close the list and rows
                    result +=       '</ul>'+
                                    '</div>'+
                                    '<div class="row">'+
                                    '<a href="?view=rooms&create=true" class="btn btn-lg btn-primary col-2 offset-5">Create</a>'+
                                    '</div>';

                    $('.roomContent').append(result);

                        // Implement Delete functionality on the Delete buttons
                    $(".roomDelete").click(function () {

                        delId = $(this).attr("data-id");

                        $.ajax({
                            type: 'DELETE',
                            url: 'api/rooms/' + delId,
                        }).done(function () {
                            // make a messaging feedback system if theres time.
                            console.log('room Deleted on id:' + delId);
                            window.location.href = "index.php?view=rooms";
                        });
                    });
                });
            };
        };
    });

</script>