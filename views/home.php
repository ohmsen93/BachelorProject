<?php

?>

<div class="row">
    <div class="roomList col-2">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="?view=1" class="nav-link" id="navRoom1" >Room 1</a>
            </li>
            <li class="nav-item">
                <a href="?view=2" class="nav-link" id="navRoom2" >Room 2</a>
            </li>
            <li class="nav-item">
                <a href="?view=3" class="nav-link" id="navRoom3" >Room 3</a>
            </li>
        </ul>
    </div>
    <div class="roomContent col-9 offset-1">
        <div class="logo">
            <img src="viewAssets/Logo.png" class="rounded mx-auto d-block" alt="">
        </div>
        <div id="room1" class="schedule">
            <!-- Room1 Google Schedule goes here -->
            <a href="?view=schedule&room=1">1</a>

        </div>
        <div id="room2" class="schedule">
            <!-- Room2 Google Schedule goes here -->
            <a href="?view=schedule&room=2">2</a>

        </div>
        <div id="room3" class="schedule">
            <!-- Room3 Google Schedule goes here -->
            <a href="?view=schedule&room=3">3</a>
        </div>
    </div>
</div>

<script>
        $("#navRoom1").click(function(event){
            event.preventDefault();
            console.log("navRoom1Press");
            $(".logo").hide();
            $("#room1").show();
            $("#room2").hide();
            $("#room3").hide();
        });

        $("#navRoom2").click(function(event){
            event.preventDefault();
            console.log("navRoom2Press");
            $(".logo").hide();
            $("#room1").hide();
            $("#room2").show();
            $("#room3").hide();
        });

        $("#navRoom3").click(function(event){
            event.preventDefault();
            console.log("navRoom3Press");
            $(".logo").hide();
            $("#room1").hide();
            $("#room2").hide();
            $("#room3").show();
        });

        /* Might need a back feature in the bottom of the room list, so we can show the logo again. */
</script>