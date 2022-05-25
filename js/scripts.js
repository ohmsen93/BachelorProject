// Setting cart array
let sessionArray = JSON.parse(sessionStorage.getItem('cartArr')) || [];
let adminAccess = JSON.parse(sessionStorage.getItem('admin')) || [];

console.log(adminAccess);



// sets the admin session to 0
$(document).ready(function(){
    $('#logout').click(function(){
        sessionStorage.setItem('admin', 0);

    })



    // Here we setup the search function with jQuery, it runs of a submit of our SearchBar Form.
    $("#CartButton").text("Cart (" + (sessionArray.length) + ")");

    // SEARCH FUNCTION
    $('#SearchBar').submit(function (event){
        event.preventDefault();
        let searchParams = new URLSearchParams(window.location.search);

        const Model =  searchParams.get('view')
        const Query =  $("#SearchQuery").val();
        const Method =  searchParams.get('method');
        const id = searchParams.get('id');




        if(Method === 'post'){
            // We check if adminAccess is enabled as Post for artist, album and track is not allowed for normal users.
            if(adminAccess === 1){
                $.get("api/" + Model, function(data){
                    let results = "";
                    let optionsArtist = "";
                    let optionsAlbum = "";
                    let optionsMediaType = "";
                    let optionsGenre = "";
                    const contentList = $('#contentList');
                    contentList.empty();

                    if(Model === 'artists') {

                        results +=
                            "<form id='ArtistForm' name='ArtistForm'>" +
                            "<input type='text' id='ArtistName' placeholder='Artist Name'/>" +
                            "<input type='submit' id='ArtistCreate' placeholder='Create Artist'/>" +
                            "</form>"
                        contentList.append(results);

                        $('#ArtistForm').submit(function (event) {
                            event.preventDefault();

                            let name = $("#ArtistName").val();


                            $.post("api/artists", {
                                "name": name
                            }, function (return_data) {
                                alert(return_data);
                            });
                        })

                    }

                    if (Model === 'albums'){

                            results +=
                                "<form id='AlbumForm' name='AlbumForm'>" +
                                "<input type='text' id='AlbumTitle' placeholder='Album Title'/>" +
                                "<select id='ArtistIdSelector'></select>" +
                                "<input type='submit' id='ArtistCreate' placeholder='Create Album'/>" +
                                "</form>"
                            contentList.append(results);
                        $.get("api/artists", function(artistdata){
                            $.each(artistdata, function (i, item) {

                                optionsArtist +=
                                    "<option value="+ item['ArtistId']+">"+ item['Name'] +"</option>"
                            })
                            $("#ArtistIdSelector").html(optionsArtist);

                        })

                        $('#AlbumForm').submit(function (event) {
                            event.preventDefault();

                            let title = $("#AlbumTitle").val();
                            let artistId = $("#ArtistIdSelector").val();


                            $.post("api/albums", {
                                "title": title,
                                "artistId":artistId
                            }, function (return_data) {
                                alert(return_data);
                            });
                        })
                    }

                    if (Model === 'tracks'){

                        results +=
                            "<form id='TrackForm' name='TrackForm'>" +
                            "<input type='text' id='TrackName' placeholder='Track Name'/>" +
                            "<select id='AlbumIdSelector'></select>" +
                            "<select id='MediaTypeIdSelector'></select>" +
                            "<select id='GenreIdSelector'></select>" +
                            "<input type='text' id='TrackComposer' placeholder='Composer'/>" +
                            "<input type='number' id='TrackMilliseconds' placeholder='Milliseconds'/>" +
                            "<input type='number' id='TrackBytes' placeholder='Bytes'/>" +
                            "<input type='number' id='TrackPrice' placeholder='Price'/>" +
                            "<input type='submit' id='TrackCreate' placeholder='Create Track'/>" +
                            "</form>"
                        contentList.append(results);

                        $.get("api/albums", function(albumdata){
                            $.each(albumdata, function (i, item) {

                                optionsAlbum +=
                                    "<option value="+ item['AlbumId']+">"+ item['Title'] +"</option>"
                            })
                            $("#AlbumIdSelector").html(optionsAlbum);
                        })

                        $.get("api/lookup/mediatypes", function(mediatypedata){
                            $.each(mediatypedata, function (i, item) {

                                optionsMediaType +=
                                    "<option value="+ item['MediaTypeId']+">"+ item['Name'] +"</option>"
                            })
                            $("#MediaTypeIdSelector").html(optionsMediaType);
                        })

                        $.get("api/lookup/genres", function(genredata){
                            $.each(genredata, function (i, item) {

                                optionsGenre +=
                                    "<option value="+ item['GenreId']+">"+ item['Name'] +"</option>"
                            })
                            $("#GenreIdSelector").html(optionsGenre);
                        })



                        $('#TrackForm').submit(function (event) {
                            event.preventDefault();

                            let TrackName = $("#TrackName").val();
                            let AlbumId = $("#AlbumIdSelector").val();
                            let MediaTypeId = $("#MediaTypeIdSelector").val();
                            let GenreId = $("#GenreIdSelector").val();
                            let Composer = $("#TrackComposer").val();
                            let Milliseconds = $("#TrackMilliseconds").val();
                            let Bytes = $("#TrackBytes").val();
                            let unitPrice = $("#TrackPrice").val();


                            $.post("api/tracks", {
                                "name": TrackName,
                                "albumId":AlbumId,
                                "mediaTypeId":MediaTypeId,
                                "genreId":GenreId,
                                "composer":Composer,
                                "milliseconds":Milliseconds,
                                'bytes':Bytes,
                                'unitPrice':unitPrice
                            }, function (return_data) {
                                alert(return_data);
                            });
                        })
                    }

                })
            } else {
                window.location.href = "index.php?"
            }



        } else if(Method === 'update' && typeof(id) != "undefined" && id !== null){

            if(adminAccess === 1){
                $.get("api/" + Model + "/" + id, function(data){
                    let optionsArtist = "";
                    let optionsAlbum = "";
                    let optionsMediaType = "";
                    let optionsGenre = "";
                    console.log('update Artists');

                    let results = "";
                    const contentList = $('#contentList');
                    contentList.empty();

                    if(Model === 'artists'){
                        results +=
                            "<form id='ArtistForm' name='ArtistForm' method='put'>" +
                            "<input type='text' id='ArtistName' placeholder='Artist Name'/>" +
                            "<input type='submit' id='ArtistCreate' placeholder='Create Artist'/>" +
                            "</form>"
                        contentList.append(results);

                        $('#ArtistName').val(data['Name']);

                        $('#ArtistForm').submit(function (event) {
                            event.preventDefault();

                            let name = $("#ArtistName").val();
                            let id = data['ArtistId'];


                            let payload = {"name":name}

                            $.ajax({
                                type: 'PUT',
                                url: 'api/artists/'+id,
                                contentType: 'application/json',
                                data: JSON.stringify(payload), // access in body
                            }).done(function () {
                                console.log('SUCCESS');
                            })

                        })
                    }


                    if (Model === 'albums'){
                        results +=
                            "<form id='AlbumForm' name='AlbumForm'>" +
                            "<input type='text' id='AlbumTitle' placeholder='Album Title'/>" +
                            "<select id='ArtistIdSelector'></select>" +
                            "<input type='submit' id='ArtistCreate' placeholder='Create Album'/>" +
                            "</form>"
                        contentList.append(results);

                        $.get("api/artists", function(artistdata){
                            $.each(artistdata, function (i, item) {

                                optionsArtist +=
                                    "<option value="+ item['ArtistId']+">"+ item['Name'] +"</option>"
                            })
                            $("#ArtistIdSelector").html(optionsArtist);
                        })

                        $('#AlbumTitle').val(data['Title']);
                        $('#ArtistIdSelector').val(data['ArtistId']);


                        $('#AlbumForm').submit(function (event) {
                            event.preventDefault();

                            let title = $("#AlbumTitle").val();
                            let artistId = $("#ArtistIdSelector").val();
                            let id = data['AlbumId'];

                            let payload = {"title":title,"artistId":artistId}

                            $.ajax({
                                type: 'PUT',
                                url: 'api/albums/'+id,
                                contentType: 'application/json',
                                data: JSON.stringify(payload), // access in body
                            }).done(function () {
                                console.log('SUCCESS');
                            })
                        })
                    }


                    if (Model === 'tracks'){
                        results +=
                            "<form id='TrackForm' name='TrackForm'>" +
                            "<input type='text' id='TrackName' placeholder='Track Name'/>" +
                            "<select id='AlbumIdSelector'></select>" +
                            "<select id='MediaTypeIdSelector'></select>" +
                            "<select id='GenreIdSelector'></select>" +
                            "<input type='text' id='TrackComposer' placeholder='Composer'/>" +
                            "<input type='number' id='TrackMilliseconds' placeholder='Milliseconds'/>" +
                            "<input type='number' id='TrackBytes' placeholder='Bytes'/>" +
                            "<input type='number' id='TrackPrice' placeholder='Price'/>" +
                            "<input type='submit' id='TrackCreate' placeholder='Create Track'/>" +
                            "</form>"
                        contentList.append(results);

                        $.get("api/albums", function(albumdata){
                            $.each(albumdata, function (i, item) {

                                optionsAlbum +=
                                    "<option value="+ item['AlbumId']+">"+ item['Title'] +"</option>"
                            })
                            $("#AlbumIdSelector").html(optionsAlbum);
                        })

                        $.get("api/lookup/mediatypes", function(mediatypedata){
                            $.each(mediatypedata, function (i, item) {

                                optionsMediaType +=
                                    "<option value="+ item['MediaTypeId']+">"+ item['Name'] +"</option>"
                            })
                            $("#MediaTypeIdSelector").html(optionsMediaType);
                        })

                        $.get("api/lookup/genres", function(genredata){
                            $.each(genredata, function (i, item) {

                                optionsGenre +=
                                    "<option value="+ item['GenreId']+">"+ item['Name'] +"</option>"
                            })
                            $("#GenreIdSelector").html(optionsGenre);
                        })

                        console.log(data);

                        $("#TrackName").val(data['TrackName']);
                        $("#AlbumIdSelector").val(data['AlbumId']);
                        $("#MediaTypeIdSelector").val(data['MediaTypeId']);
                        $("#GenreIdSelector").val(data['GenreId']);
                        $("#TrackComposer").val(data['Composer']);
                        $("#TrackMilliseconds").val(data['Milliseconds']);
                        $("#TrackBytes").val(data['Bytes']);
                        $("#TrackPrice").val(data['UnitPrice']);


                        $('#TrackForm').submit(function (event) {
                            event.preventDefault();

                            let TrackName = $("#TrackName").val();
                            let AlbumId = $("#AlbumIdSelector").val();
                            let MediaTypeId = $("#MediaTypeIdSelector").val();
                            let GenreId = $("#GenreIdSelector").val();
                            let Composer = $("#TrackComposer").val();
                            let Milliseconds = $("#TrackMilliseconds").val();
                            let Bytes = $("#TrackBytes").val();
                            let unitPrice = $("#TrackPrice").val();

                            let payload = {
                                "name": TrackName,
                                "albumId":AlbumId,
                                "mediaTypeId":MediaTypeId,
                                "genreId":GenreId,
                                "composer":Composer,
                                "milliseconds":Milliseconds,
                                'bytes':Bytes,
                                'unitPrice':unitPrice
                            }

                            $.ajax({
                                type: 'PUT',
                                url: 'api/tracks/'+id,
                                contentType: 'application/json',
                                data: JSON.stringify(payload), // access in body
                            }).done(function () {
                                console.log('SUCCESS');
                            })


                        })
                    }

                })
            } else {
                window.location.href = "index.php?"
            }

        } else {
            $.get("api/" + Model + "?search=" + Query, function(data){

                console.log('List Artists')

                let results = "";
                const contentList = $('#contentList');

                if (Model === 'artists'){
                    $.each(data, function (i, item) {

                        results +=
                            "<div class='card' id='" + item["ArtistId"] + "'>" +
                            "<h2>" + item["Name"] + "</h2>"

                        // Here we implement the delete links for Artists
                        if(adminAccess === 1){
                            results +=
                                "<a href='#' class='artistDelete' data-id='"+ item["ArtistId"] +"'>Delete</a>"
                        }


                        results +=
                            "</div>"

                    });
                    // Only append once, build the html above
                    contentList.html(results);

                    // here we implement the Delete functionality utilizing the delete links for artists.
                    if(adminAccess === 1) {
                        $(".artistDelete").click(function () {

                            delId = $(this).attr("data-id");
                            console.log(delId)

                            $.ajax({
                                type: 'DELETE',
                                url: 'api/artists/' + delId,
                            }).done(function () {
                                // make a messaging feedback system if theres time.
                                console.log('Artist Deleted on id:' + delId);
                            })
                        })
                    }
                }


                if (Model === 'albums'){
                    $.each(data, function (i, item) {

                        results +=
                            "<div class='card' id='" + item["AlbumId"] + "'>" +
                            "<h2>" + item["Title"] + "</h2>"

                        // Here we implement the delete links for Albums
                        if(adminAccess === 1){
                            results +=
                                "<a href='#' class='albumDelete' data-id='"+ item["AlbumId"] +"'>Delete</a>"
                        }
                        results +=
                            "</div>"

                    });
                    // Only append once, build the html above
                    contentList.html(results);

                    // here we implement the Delete functionality utilizing the delete links for albums.
                    // might need more due to no cascade in db.
                    if(adminAccess === 1) {
                        $(".albumDelete").click(function () {

                            delId = $(this).attr("data-id");
                            console.log(delId)

                            $.ajax({
                                type: 'DELETE',
                                url: 'api/albums/' + delId,
                            }).done(function () {
                                // make a messaging feedback system if theres time.
                                console.log('Album Deleted on id:' + delId);
                                window.location.href = "index.php?view=albums"
                            })
                        })
                    }
                }


                if (Model === 'tracks'){
                    $.each(data, function (i, item) {

                        results +=
                            "<div class='card' id='" + item["TrackId"] + "'>" +
                            "<h2>" + item["Name"] + "</h2>" +
                            "<p>Price: "+ item["UnitPrice"] +"</p>" +
                            "<a href='#' data-name='"+ item['Name'] +"' data-price='"+ item['UnitPrice'] +"' data-id='"+ item['TrackId'] +"' class='cartable'>Add to card</a>"

                            // Here we implement the delete links for Albums
                            if(adminAccess === 1){
                                results +=
                                    "<a href='#' class='trackDelete' data-id='"+ item["TrackId"] +"'>Delete</a>"
                            }
                            results +=
                                "</div>"


                    });
                    // Only append once, build the html above
                    contentList.html(results);

                    // here we implement the Delete functionality utilizing the delete links for tracks.
                    // might need more due to no cascade in db.
                    if(adminAccess === 1) {
                        $(".trackDelete").click(function () {

                            delId = $(this).attr("data-id");
                            console.log(delId)

                            $.ajax({
                                type: 'DELETE',
                                url: 'api/tracks/' + delId,
                            }).done(function () {
                                // make a messaging feedback system if theres time.
                                console.log('Track Deleted on id:' + delId);
                                window.location.href = "index.php?view=tracks"
                            })
                        })
                    }
                }

            })

        }

    })

    // Here we automate a click for the SearchBar Form in order to list all results on page load.
    $('#SearchButton').click();




    // Modal SETUP
    $('#CartButton').click(function(){
        $('#modal-screen').toggle();
    })
    // MODAL Exit
    $('#modal-exit').click(function(){
        $('#modal-screen').toggle();
    })

    // <-- SHOPPING CART  -->

    // Might be an idea to implement SESSION here in some way to save the cart between sessions if need be.

    // ADD TO CART

    // "Event delegation", have to do it this way because my content is dynamically generated
    $(document).ready(function(){
        let resultsArr = '';

        resultsArr +=
            "<tr>"+
            "<th>Track Name</th>"+
            "<th>Artist Name</th>"+
            "<th>Unit Price</th>"+
            "</tr>"

        $(document).on("click",".cartable",function(){
        // Storing key value pairs of track information in cart

            data = {
                "Name": $(this).attr("data-name"),
                "TrackId": $(this).attr("data-id"),
                "UnitPrice": $(this).attr("data-price")
            };

            // Update cart with count of items in it
            $("#CartButton").text("Cart (" + (sessionArray.length+1) + ")");

            // push data to the session array
            sessionArray.push(data);

            // set sessionStorage 'cartArr' to the sessionArray, we do this so we can save cart info between page refreshes.
            sessionStorage.setItem('cartArr', JSON.stringify(sessionArray));

            // We load our sessionArray on click
            $.each(sessionArray, function (i, items) {
                resultsArr +=
                    "<tr>"+
                    "<td>" + items["Name"] + "</td>"+
                    "<td>" + items["TrackId"] + "</td>"+
                    "<td>" + items["UnitPrice"] + "</td>"+
                    "</tr>"
            })

            // Here we use the html tag to send the content to the div when we click a track.
            $('#cart-table').html(resultsArr);
            resultsArr = [];

        });

        // We load our sessionArray on pageload,
        $.each(sessionArray, function (i, items) {
            resultsArr +=
                "<tr>"+
                "<td>" + items["Name"] + "</td>"+
                "<td>" + items["TrackId"] + "</td>"+
                "<td>" + items["UnitPrice"] + "</td>"+
                "</tr>"
        })

        // Here we use the html tag to send the content to the div when we load the page, we get the data from sessionStorage
        $('#cart-table').html(resultsArr);
        resultsArr = [];
    });

    // CLEAR CART
    $('#clear').click(function (event) {
        sessionArray = []
        sessionStorage.setItem('cartArr', JSON.stringify(sessionArray));
        let resultsArr = '';

        resultsArr +=
            "<tr>"+
            "<th>Track Name</th>"+
            "<th>Artist Name</th>"+
            "<th>Unit Price</th>"+
            "</tr>"

        $('#cart-table').html(resultsArr);
        $('#Invoice').html(resultsArr);

        $("#CartButton").text("Cart (" + (sessionArray.length) + ")");

    })


    /* Checkout */

    /* First we need to populate the checkout form */

    $(document).ready(function () {
        let invoiceList = ''
        invoiceList +=
            "<tr>"+
            "<th>Track Name</th>"+
            "<th>Artist Name</th>"+
            "<th>Unit Price</th>"+
            "</tr>"
        let sum = '';

        $.each(sessionArray, function (i, items) {
            sum = Number(sum)+(Number(items["UnitPrice"]));
            invoiceList +=
                "<tr>"+
                "<td>" + items["Name"] + "</td>"+
                "<td>" + items["TrackId"] + "</td>"+
                "<td>" + items["UnitPrice"] + "</td>"+
                "</tr>"
            console.log(sum);
        })
        $('#sum').html(sum);
        $('#Invoice').html(invoiceList);
    })

    /* Customer Create */

    $('#RegForm').submit(function (event) {
        event.preventDefault();

        let FirstName = $("#RegFirstName").val();
        let LastName = $("#RegLastName").val();
        let Password = $("#RegPassword").val();
        let Company = $("#RegCompany").val();
        let Address = $("#RegAddress").val();
        let City = $("#RegCity").val();
        let State = $("#RegState").val();
        let Country = $("#RegCountry").val();
        let PostalCode = $("#RegPostalCode").val();
        let Phone = $("#RegPhone").val();
        let Fax = $("#RegFax").val();
        let Email = $("#RegEmail").val();


        $.post("api/customers", {
            "firstName": FirstName,
            "lastName":LastName,
            "password":Password,
            "company":Company,
            "address":Address,
            "city":City,
            'state':State,
            'country':Country,
            'postalCode':PostalCode,
            'phone':Phone,
            'fax':Fax,
            'email':Email,

        }, function (return_data) {
            alert(return_data);
        });
    })

    /* Customer Update */

    $(document).ready(function () {
        let searchParams = new URLSearchParams(window.location.search);

        const customerId = searchParams.get('id');

        $.get("api/customers/" + customerId, function (profiledata) {

            console.log(profiledata);

            $("#ProfileFirstName").val(profiledata['FirstName']);
            $("#ProfileLastName").val(profiledata['LastName']);
            $("#ProfileCompany").val(profiledata['Company']);
            $("#ProfileAddress").val(profiledata['Address']);
            $("#ProfileCity").val(profiledata['City']);
            $("#ProfileState").val(profiledata['State']);
            $("#ProfileCountry").val(profiledata['Country']);
            $("#ProfilePostalCode").val(profiledata['PostalCode']);
            $("#ProfilePhone").val(profiledata['Phone']);
            $("#ProfileFax").val(profiledata['Fax']);
            $("#ProfileEmail").val(profiledata['Email']);

        })

        $('#ProfileForm').submit(function (event) {
            event.preventDefault();

            let FirstName = $("#ProfileFirstName").val();
            let LastName = $("#ProfileLastName").val();
            let Password = $("#ProfilePassword").val();
            let Company = $("#ProfileCompany").val();
            let Address = $("#ProfileAddress").val();
            let City = $("#ProfileCity").val();
            let State = $("#ProfileState").val();
            let Country = $("#ProfileCountry").val();
            let PostalCode = $("#ProfilePostalCode").val();
            let Phone = $("#ProfilePhone").val();
            let Fax = $("#ProfileFax").val();
            let Email = $("#ProfileEmail").val();


            let payload = {
                "firstName": FirstName,
                "lastName":LastName,
                "password":Password,
                "company":Company,
                "address":Address,
                "city":City,
                'state':State,
                'country':Country,
                'postalCode':PostalCode,
                'phone':Phone,
                'fax':Fax,
                'email':Email
            }

            $.ajax({
                type: 'POST',
                url: 'api/customers/'+customerId,
                contentType: 'application/json',
                data: JSON.stringify(payload), // access in body
            }).done(function () {
                console.log('SUCCESS');
            })





        })
    })





})


