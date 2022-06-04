<?php 

?>

<div class="container col-12">
    <div class="userContent">
        
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
        let User = $_GET['user'];
        let Create = $_GET['create'];

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

                            window.location.href = "index.php?view=users";

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
                                                        "<a class='col-9 list-group-item' href='?view=users&user="+item['email']+"' >"+item['firstName']+" "+item['lastName']+"</a>"+
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
                                url: 'api/users/' + delId,
                            }).done(function () {
                                // make a messaging feedback system if theres time.
                                console.log('user Deleted on id:' + delId);
                                window.location.href = "index.php?view=users";
                            });
                        });
                });
        };
    });

</script>