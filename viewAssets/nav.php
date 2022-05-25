<?php
    // here we set the customerId to the session, in order to later use it in the jquery to check if the user is logged in.
    $customerId = '';
    if(isset($_SESSION['customerId'])){
        $customerId = $_SESSION['customerId'] ?? '';
    }

    if(isset($_SESSION['admin'])){
        $admin = $_SESSION['admin'] ?? '';
    }

?>


<nav id="mainNavigation">
    <ul>
        <li>
            <a href="?" class="<?=(!isset($_GET['page'])) ? "active" : "" ?>">Home</a>
        </li>
        <li>
            <a href="?view=artists" class="<?=(isset($_GET['page']) && $_GET['page'] == 'artists') ? "active" : "" ?>">Artists</a>
        </li>
        <li>
            <a href="?view=albums" class="<?=(isset($_GET['page']) && $_GET['page'] == 'albums') ? "active" : "" ?>">Albums</a>
        </li>
        <li>
            <a href="?view=tracks" class="<?=(isset($_GET['page']) && $_GET['page'] == 'tracks') ? "active" : "" ?>">Tracks</a>
        </li>
        <li>
            <button id="LoginButton" class="NavButton">Login</button>
            <button id="CartButton" class="NavButton">Cart (0)</button>
        </li>
    </ul>

    <form id="loginForm" method="POST">
        <fieldset>
            <h2>Login</h2>
            <label for="loginEmail">Email: </label>
            <input type="email" id="loginEmail" name="loginEmail" placeholder="Email">

            <label for="loginPassword">Password: </label>
            <input type="password" id="loginPassword" name="loginPassword" placeholder="Password">

            <input type="submit" id="loginSubmit" name="loginSubmit" value="Login">
            <a href="?view=registration">Register!</a>
        </fieldset>
    </form>
</nav>

<script>
    $(document).ready(function () {

        // here we set the customerId from the session in order to enable us to change the loginform
        let customerId = "<?php echo $customerId;?>";
        let admin = "<?php echo $admin;?>";

        console.log(customerId);
        if(customerId !== '') {
            $("#LoginButton").text('Profile');

            $("#loginForm").empty();

            // new loginform and data.
            $.get("api/customers/"+customerId, function(data) {
                let results = "";
                const loginForm = $('#loginForm');

                    results +=
                        "<p>Firstname: "+ data['FirstName'] +"</p>" +
                        "<p>Lastname:"+ data['LastName'] +"</p>" +
                        "<a href='?view=profile&id="+ data['CustomerId'] +"'>Profile</a>"+
                        "<input type=\"submit\" name=\"logout\" id=\"logout\" value=\"Logout\">";


                // Only append once, build the html above
                loginForm.html(results);

            })

        } else if (admin === '1'){
            sessionStorage.setItem('admin', '1');
            $("#LoginButton").text('Admin');

            $("#loginForm").empty();

            // new loginform and data.
            let results = "";
            const loginForm = $('#loginForm');

            results +=
                "<p>Administration</p>" +
                "<input type=\"submit\" name=\"logout\" id=\"logout\" value=\"Logout\">";


            // Only append once, build the html above
            loginForm.html(results);

        }



    });
    
    $("#LoginButton").click(function (){
        $("#loginForm").toggle();


    });
</script>