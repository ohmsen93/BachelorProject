<?php

?>


    <div class="roomContent">
        <div class="logo">
            <img src="viewAssets/Logo.png" class="rounded mx-auto d-block" alt="">
        </div>

    </div>

<script>
    //Schedule

    $(document).ready(function(){

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


        let Model = $_GET['view'];
        let Id = $_GET['room'];

        // Get specific room based of id from navigation
                    
        $.get("api/"+Model+"/"+Id, function(data){
            let result = '';
            console.log(data);

            result = '<iframe class="schedule container-fluid" src="'+data['calendarURL']+'" frameborder="0"></iframe>'

            $(".roomContent").html(result);

        });





        // 




    });



        $("#navLinux").click(function(event){
            console.log("navLinuxPress");
            $(".logo").hide();
            $("#Linux").show();
            $("#Android").hide();
            $("#Fatboy").hide();
        });

        $("#navAndroid").click(function(event){
            console.log("navAndroidPress");
            $(".logo").hide();
            $("#Linux").hide();
            $("#Android").show();
            $("#Fatboy").hide();
        });

        $("#navFatboy").click(function(event){
            console.log("navFatboyPress");
            $(".logo").hide();
            $("#Linux").hide();
            $("#Android").hide();
            $("#Fatboy").show();
        });

        /* Might need a back feature in the bottom of the room list, so we can show the logo again. */
</script>