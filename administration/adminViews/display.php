<?php 

?>

<div class="container col-12">
    <div class="displayContent">
        
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
        let Display = $_GET['display'];
        let Create = $_GET['create'];

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

                    window.location.href = "index.php?view=displays";
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

                    window.location.href = "index.php?view=displays";
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
                                    "<a class='col-11' href='?view=displays&display="+item['id']+"' >"+item['name']+"</a>"+
                                    "<button class='col-1 btn btn-danger displayDelete' data-id='"+item['id']+"'>Delete</button>"+
                                    "</div>"+
                                    "</li>";
                    });
                        //Close the list and rows
                    result +=       '</ul>'+
                                    '</div>'+
                                    '<div class="row">'+
                                    '<a href="?view=displays&create=true" class="btn btn-lg btn-primary col-2 offset-5">Create</a>'+
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
    });

</script>