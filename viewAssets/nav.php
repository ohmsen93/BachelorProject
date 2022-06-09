<div class="row list-group">
    <ul class="roomList">
        <li>
            <a class="col-12 list-group-item" href="?view=room">Booking</a>
        </li>
    </ul>
    <div class="tools">
        <a href="#" id="bookRoom" class="col-12 list-group-item">Book Room</a>
    </div>
</div>


<script type="text/javascript">

    let $_GET = getQueryParams(document.location.search);

    generateNavListElements('.roomList');

    generateViewTools('.tools');
  
    //Check if the session is set on the user
    let session = "<?php if(!isset($_SESSION['email'])){ echo "null"; }else{ echo "set";}?>";
    // generate the redirect url for google
    let redirectUrl = "<?php echo $client->createAuthUrl(); ?>";

    $("#bookRoom").click(function(){

            console.log("Regirecting to schedule");
            
            let scheduleUrl = '?view=schedule&schedule='+$_GET['room'];

            window.location.replace(scheduleUrl);


    });

</script>