/* Sara Petersson - Webbutveckling för mobila enheter, DT148G */

// Kollar att dokumentet är redo
$(document).ready(function() {
    /*
    MENY
    */
    /* Visar meny-knappen om personen har javascript aktiverat och tar bort classen js-menu samt alla element med class no-js */
    $('#toggle-nav').show();
    $('#mainmenu').removeClass('js-menu');
    $('.no-js').remove();
    /* Togglar menyn */
    $('#toggle-nav').click(function(e) {
        $(this).toggleClass('active');
        $('#mainmenu ul').toggleClass('active');
        e.preventDefault();
    });
    /* Tar bort no-js-formulär */
    $('#no-js-form').remove();
	/*
    LOGIN.PHP
    */
    $('#loginButton').click(function(event) {
        event.preventDefault();
        loginUser();
    });
    // Inskrivna uppgifter valideras, ev. felmeddelanden skrivs ut, AJAX-anrop för inloggning
    function loginUser() {
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var email = $('#email').val();
        var password = $('#password').val();
        var hasError = false;
        // Kollar om e-post och lösenord fyllts i
        if (email == '' && password == '') {
            $('<p class="errorMessageBox">Du måste fylla i din e-post</p>').insertAfter('#email');
            $('<p class="errorMessageBox">Du måste fylla i ditt lösenord</p>').insertAfter('#password');
            hasError = true;
        }
        // Kollar om e-post fyllts i
        else if (email == '') {
            $('<p class="errorMessageBox">Du måste fylla i din e-post</p>').insertAfter('#email');
            hasError = true;
        }
        // Kollar om lösenord fyllts i
        else if (password == '') {
            $('<p class="errorMessageBox">Du måste fylla i ditt lösenord</p>').insertAfter('#password');
            hasError = true;
        }
        // Om inga fel hittas görs ett AJAX-anrop för att kontrollera att inloggningsuppgifterna stämmer
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'ajax/loginUser.php',
                data: {
                    email: email,
                    password: password
                }
            }).done(function(result) {
                // Om inloggningen var korrekt skickas användaren till index-php
                if (result == true) {
                    window.location.href = 'index.php';
                }
                // Om inloggningen misslyckades skrivs ett felmeddelande ut
                else {
                    $('<p class="errorMessageBox">' + result + '</p>').insertAfter('#password');
                }
            });
        }
    }
    /*
    REGISTER.PHP
    */
    /* Efter användaren tryckt på "Registrera"-knapp anropas funktionen registerUser() */
    $('#registerButton').click(function(event) {
        event.preventDefault();
        registerUser();
    });
    /* Validerar inskrivna uppgifter, ev. felmeddelanden skrivs ut,
    AJAX-anrop för att lägga till användaren i databasen */
    function registerUser() {
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var company_name = $('#company').val();
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var passwordCheck = $('#passwordCheck').val();
        var hasCompanyError = false;
        // Kollar om företagsnamn fyllts i
        if (company_name == '') {
            $('<p class="errorMessageBox">Du måste fylla i företagsnamn</p>').insertAfter('#company');
            var hasCompanyError = true;
        }
        // Validerar förnamn, efternamn och e-post genom funktionen validateUserInfo()
        var hasError = validateUserInfo(firstname, lastname, email);
        // Validerar lösenord
        var hasPasswordError = validatePassword(password, passwordCheck);
        // Om inga fel hittas görs ett AJAX-anrop för att lägga till användaren till databasen
        if (hasError == false && hasPasswordError == false && hasCompanyError == false) {
            $.ajax({
                method: 'POST',
                url: 'ajax/registerUser.php',
                data: {
                	company_name: company_name,
                    firstname: firstname,
                    lastname: lastname,
                    email: email,
                    password: password
                }
            }).done(function(result) {
                // Om användaren lades till i databasen skickas denna till index.php
                if (result == true) {
                    window.location.href = 'index.php';
                }
                // Om företaget redan är registrerat i databasen skrivs ett felmeddelande ut
                else if (result == false) {
                    $('<p class="errorMessageBox">Det angivna företagsnamnet har redan ett konto kopplat till sig</p>').insertAfter('#company');
                }
                // Om användarens e-post redan fanns i databasen skrivs ett felmeddelande ut
                else {
                    $('<p class="errorMessageBox">' + result + '</p>').insertAfter('#email');
                }
            });
        }
    }
    /*
    INDEX.PHP
    */
    /* Visar js-formuläret (ska synas direkt vid stora skärmar) */
    $('#addRoomForm').show();
    /* Visar/döljer formuläret för att lägga till rum */
    $('#addRoomFormButton').click(function(event) {
        event.preventDefault();
        $('#addRoom').slideToggle('slow', function() {
            if ($('#addRoom').is(':visible')) {
                $('#addRoomFormButton').text('Dölj formulär');
            }
            else {
                $('#addRoomFormButton').text('Lägg till rum');
            }
        });
    });
    /* Efter användaren tryckt på "Lägg till"-knapp anropas funktionen addRoom() */
    $('#addRoomButton').click(function(event) {
        event.preventDefault();
        addRoom();
    });
    /* Validerar inskrivna uppgifter, ev. felmeddelanden skrivs ut,
    AJAX-anrop för att lägga till rummet i databasen */
    function addRoom() {
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var roomName = $('#roomName').val();
        var desc = CKEDITOR.instances.editor.getData();
        var hasError = false;
        // Kollar om rummets namn fyllts i
        if (roomName == '') {
            $('<p class="errorMessageBox">Du måste fylla i rummets namn</p>').insertAfter('#roomName');
            hasError = true;
        }
        // Om inga fel hittas görs ett AJAX-anrop för att lägga till rummet till databasen
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'ajax/addRoom.php',
                data: {
                	roomName: roomName,
                    desc: desc
                }
            }).done(function(result) {
                // Om rummet lades till i databasen töms och göms formuläret, knappen ändras, alla rum visas samt ett meddelande skrivs ut
                if (result != true) {
                    $('#roomName').val('');
                    CKEDITOR.instances.editor.setData('');
                    if ($('#addRoom').css('float') != 'right') {
                        $('#addRoom').toggle('slow');
                        $('#addRoomFormButton').text('Lägg till rum');
                    }
                    $('#allRooms').html(result);
                    var msg = 'Rummet har sparats';
                    showMessage(msg);
                }
                // Om rummet inte kunde läggas till i databasen skrivs ett felmeddelande ut
                else {
                    $('<p class="errorMessageBox">Tyvärr, rummet kunde inte sparas i databasen</p>').insertAfter('#addRoomForm');
                }
            });
        }
    }
    /* Funktion som säkerställer att ett rum ska raderas, och raderar det sedan från databasen */
    $('#allRooms').on('click', '.delete', function () {
        event.preventDefault();
        var roomId = this.id;
        /* Kollar om användaren tryckt på JA */
        if (confirm("Är du säker på att du vill radera rummet?") == true) {
            // AJAX-anrop som raderar rummet från databasen
           $.ajax({
                method: 'POST',
                url: 'ajax/deleteRoom.php',
                data: {
                    roomId: roomId
                }
            }).done(function(result) {
                // Om raderingen lyckades tas rummet bort och ett meddelande skrivs ut genom funktionen showMessage()
                if (result != false) {
                    $('#allRooms').html(result);
                    var msg = 'Rummet har raderats';
                    showMessage(msg);
                }
                // Om rummet inte kunde raderas skrivs ett felmeddelande ut
                else {
                    var container = $("#" + roomId).parent().parent();
                    $('<p class="errorMessageBox">Tyvärr, rummet kunde inte raderas</p>').insertBefore(container);
                }
            });
        }
    });
    /*
    ROOM.PHP
    */
    /* Validerar inskrivna uppgifter, ev. felmeddelanden skrivs ut,
    AJAX-anrop för att uppdatera rummet i databasen */
    $('#editRoomButton').click(function() {
        event.preventDefault();
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var roomName = $('#roomName').val();
        var desc = CKEDITOR.instances.desc.getData();
        if ($('#status').prop('checked') == true) {
            var status = 1;
        }
        else {
            var status = 0;
        }
        var hasError = false;
        var roomId = getParam("id");
            // Kollar om rummets namn fyllts i
        if (roomName == '') {
            $('<p class="errorMessageBox">Du måste fylla i rummets namn</p>').insertAfter('#roomName');
            hasError = true;
        }
        // Om inga fel hittas görs ett AJAX-anrop för att uppdatera rummet i databasen
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'ajax/updateRoom.php',
                data: {
                    roomId: roomId,
                    roomName: roomName,
                    desc: desc,
                    status: status
                }
            }).done(function(result) {
                // Om rummet lades till i databasen skrivs meddelande ut
                if (result == true) {
                    var msg = 'Rummets uppgifter har sparats';
                    showMessage(msg);
                }
                // Om rummet inte kunde läggas till i databasen skrivs ett felmeddelande ut
                else {
                    $('<p class="errorMessageBox">Tyvärr, ändringarna kunde inte sparas i databasen.</p>').insertAfter('#updateRoomForm');
                }
            });
        }
    });
    /* Skriver ut meddelande om att drag-drop fungerar, ska ej synas för användare utan JS */
    if (!$('.dropzone').has('img')) {
        $('.dropzone').html('<p>Släpp din bild här eller välj en bild nedan</p>');
    }
    /* Gör så att "Ladda upp bild"-knappen är disabled */
    $("#submitImage").addClass('inactive');
    // Då en fil dras över dropzone förändras färgen
    $('.dropzone').on('dragover', function(e){
        e.stopPropagation();
        e.preventDefault();
        $(e.target).addClass('dragover');
        // Tar bort eventuella felmeddelanden
        $('#uploads').html('');
        $('#uploads').removeClass('errorMessageBox');
    });
    // Då en fil dras ifrån dropzone återställs färgen
    $('.dropzone').on('dragleave', function(e){
        e.stopPropagation();
        e.preventDefault();
        $(e.target).removeClass('dragover');
    });
    /* Då en fil släpps över dropzone återställs färgen, en koll görs att filen är av rätt filformat och är mindre än 500kB.
    Skickar till funktionen upload() om inga felmeddelanden getts*/
    $('.dropzone').on('drop', function(e){
        e.stopPropagation();
        e.preventDefault();
        $(e.target).removeClass('dragover');
        var files = e.originalEvent.dataTransfer.files;
        file = files[0];
        // Kolla att filen är en JPEG
        if (file.type != "image/jpeg") {
            $('#uploads').html('<p>Endast jpeg är tillåtet</p>');
            $('#uploads').addClass('errorMessageBox');
        }
        // Kolla att filstorleken är mindre än 500kB
        else if (file.size > 500000) {
            $('#uploads').html('<p>Filen är för stor (max 500kB)</p>');
            $('#uploads').addClass('errorMessageBox');
        }
        // Ladda upp bilden
        else {
            upload(files);
        }
    });
    /* Validerar bilden som väljs för uppladdning (innan klick på "Ladda upp bild").
    Om bilden validerar korrekt görs "Ladda upp bild"-knappen tillgänglig */
    $('#fileToUpload').change(function() {
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var files = this.files;
        var file = this.files[0];
        var name = file.name;
        var size = file.size;
        var type = file.type;
        // Kolla att filen är en JPEG
        if (type != "image/jpeg") {
            $('#fileToUpload').after('<p class="errorMessageBox">Endast jpeg är tillåtet</p>');
        }
        // Kolla att filstorleken är mindre än 500kB
        else if (file.size > 500000) {
            $('#fileToUpload').after('<p class="errorMessageBox">Filen är för stor (max 500kB)</p>');
        }
        else {
            // Gör "Ladda upp bild"-knappen tillgänglig
            $("#submitImage").removeClass('inactive');
            // Laddar upp bilden vid klick på knappen
            $('#submitImage').on('click', function(e) {
                e.preventDefault();
                upload(files);
            });
        }
    });
    /* Om "Ladda upp bild"-knappen är inaktiv så ska inget hända vid klick */
    if ($('#submitImage').hasClass('inactive')) {
        $('#submitImage').on('click', function(e) {
            e.preventDefault();
        });
    }
    // Visar den uppladdade bilden i uppladdningsrutan genom att skapa ett nytt bildelement
    function displayUploads(data, roomId) {
        $("#fileToUpload").val("");
        /* Gör så att "Ladda upp bild"-knappen är disabled */
        $("#submitImage").addClass('inactive');
        var anchor;
        anchor = document.createElement('img');
        anchor.src = data[0].file;
        anchor.alt = data[0].name;
        $('#dropzone').html(anchor);
        // Lägger till "Radera"-knapp
        $('#dropzone').append('<a id="deleteImageButton" class="delete" href="room.php?id=' + roomId + '&del"><i class="fa fa-trash-o" aria-hidden="true"></i></a>');
        var msg = 'Bilden har nu sparats';
        showMessage(msg);
    }
    // Laddar upp bildfilen med AJAX
    function upload(files) {
        var roomId = getParam("id");
        var formData = new FormData(),
        xhr = new XMLHttpRequest(),
        x;
        // Begränsar till att endast kunna ladda upp en bild
        for (x = 0; x < 1; x++) {
            formData.append('file[]', files[x]);
        }
        // Tar emot svar och skickar vidare till funktionen displayUploads() som visar den uppladdade bilden
        xhr.onload = function() {
            var data = JSON.parse(this.responseText);
            displayUploads(data, roomId);
        }
        xhr.open('post', 'ajax/uploadImage.php?id=' + roomId);
        xhr.send(formData);
    }
    /* Raderar bild */
    $('.dropzone').on('click', '#deleteImageButton', function () {
        event.preventDefault();
        var roomId = getParam("id");
        // AJAX-anrop för att radera bilden
            $.ajax({
                method: 'POST',
                url: 'ajax/deleteImage.php',
                data: {
                    roomId: roomId
                }
            }).done(function(result) {
                // Om rummet raderades skrivs meddelande ut
                if (result == true) {
                    $('#dropzone').html("Släpp din bild här eller välj en bild nedan");
                    var msg = 'Bilden har raderats';
                    showMessage(msg);
                }
                // Om rummet inte kunde raderas skrivs ett felmeddelande ut
                else {
                    $('#uploads').html("<p>Tyvärr, bilden kunde inte raderas.</p>");
                    $('#uploads').addClass('errorMessageBox');
                }
            });
    });
    /*
    USERS.PHP
    */
    /* Visar js-formuläret (ska synas direkt vid stora skärmar) */
    $('#addUserForm').show();
    /* Visar/döljer formuläret för att lägga till rum */
    $('#addUserFormButton').click(function(event) {
        event.preventDefault();
        $('#addUser').slideToggle('slow', function() {
            if ($('#addUser').is(':visible')) {
                $('#addUserFormButton').text('Dölj formulär');
            }
            else {
                $('#addUserFormButton').text('Lägg till användare');
            }
        });
    });
    /* Validerar inskrivna uppgifter, ev. felmeddelanden skrivs ut,
    AJAX-anrop för att lägga till användaren i databasen */
    $('#addUserButton').click(function(event) {
        event.preventDefault();
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var passwordCheck = $('#passwordCheck').val();
        if ($('#admin').prop('checked') == true) {
            var admin = 1;
        }
        else {
            var admin = 0;
        }
        // Validerar förnamn, efternamn och e-post genom funktionen validateUserInfo()
        var hasError = validateUserInfo(firstname, lastname, email);
        // Validerar lösenord
        var hasPasswordError = validatePassword(password, passwordCheck);
        // Om inga fel hittas görs ett AJAX-anrop för att lägga till användaren till databasen
        if (hasError == false && hasPasswordError == false) {
            $.ajax({
                method: 'POST',
                url: 'ajax/addUser.php',
                data: {
                    firstname: firstname,
                    lastname: lastname,
                    email: email,
                    password: password,
                    admin: admin
                }
            }).done(function(result) {
                // Om användaren lades till i databasen töms formuläret, meddelande skrivs ut
                if (result != false) {
                    $('#firstname').val('');
                    $('#lastname').val('');
                    $('#email').val('');
                    $('#password').val('');
                    $('#passwordCheck').val('');
                    $('#admin').prop('checked', false);
                    if ($('#addUser').css('float') != 'right') {
                        $('#addUser').slideToggle('slow');
                    }
                    $('#allUsers').html(result);
                    var msg = 'Användaren har lagts till';
                    showMessage(msg);
                }
                // Om användaren inte kunde läggas till i databasen skrivs felmeddelande ut
                else {
                    $('<p class="errorMessageBox">Den angivna e-posten har redan ett konto kopplat till sig</p>').insertAfter('#email');
                }
            });
        }
    });
    /* Funktion som säkerställer att en användare ska raderas, och raderar den sedan från databasen */
    $('#allUsers').on('click', '.delete', function () {
        event.preventDefault();
        var deleteId = this.id;
        /* Kollar om användaren tryckt på JA */
        if (confirm("Är du säker på att du vill radera användaren?") == true) {
            // AJAX_anrop som raderar rummet från databasen
           $.ajax({
                method: 'POST',
                url: 'ajax/deleteUser.php',
                data: {
                    deleteId: deleteId
                }
            }).done(function(result) {
                if (result == true) {
                    var container = $("#" + deleteId).parent();
                    $('<p class="errorMessageBox">Du kan inte radera ditt eget konto</p>').insertBefore(container);
                }
                // Om raderingen lyckades tas användaren bort och ett meddelande skrivs ut genom funktionen showMessage()
                else if (result == false) {
                    var container = $("#" + deleteId).parent();
                    $('<p class="errorMessageBox">Tyvärr, användaren kunde inte raderas</p>').insertBefore(container);
                }
                // Om användaren inte kunde raderas skrivs ett felmeddelande ut
                else {
                    $('#allUsers').html(result);
                    var msg = 'Användaren har raderats';
                    showMessage(msg);
                }
            });
        }
    });
    /*
    UPDATE.PHP
    */
    /* Validerar inskrivna uppgifter, ev. felmeddelanden skrivs ut,
    AJAX-anrop för att uppdatera användaren i databasen */
    $('#editUserButton').click(function(event) {
        event.preventDefault();
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var userId = getParam("id");
        if ($('#admin').prop('checked') == true) {
            var admin = 1;
        }
        else {
            var admin = 0;
        }
        // Validerar förnamn, efternamn och e-post genom funktionen validateUserInfo()
        var hasError = validateUserInfo(firstname, lastname, email);
        // Om inga fel hittas görs ett AJAX-anrop för att uppdatera användaren i databasen
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'ajax/updateUser.php',
                data: {
                    userId: userId,
                    firstname: firstname,
                    lastname: lastname,
                    email: email,
                    admin: admin
                }
            }).done(function(result) {
                // Om användaren lades till i databasen skrivs meddelande ut
                if (result == true) {
                    var msg = 'Uppgifterna har nu uppdaterats';
                    showMessage(msg);
                }
                // Om användaren inte kunde läggas till i databasen skrivs felmeddelande ut
                else {
                    $('<p class="errorMessageBox">' + result + '</p>').insertAfter('#editUserForm');
                }
            });
        }
    });
    /* Validerar inskrivna uppgifter, ev. felmeddelanden skrivs ut,
    AJAX-anrop för att uppdatera lösenordet i databasen */
    $('#passwordButton').click(function(event) {
        event.preventDefault();
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var password = $('#password').val();
        var passwordCheck = $('#passwordCheck').val();
        var userId = getParam("id");
        // Validerar lösenord
        var hasPasswordError = validatePassword(password, passwordCheck);
        // Om inga fel hittas görs ett AJAX-anrop för att uppdatera lösenordet databasen
        if (hasPasswordError == false) {
            $.ajax({
                method: 'POST',
                url: 'ajax/updatePassword.php',
                data: {
                    userId: userId,
                    password: password
                }
            }).done(function(result) {
                // Om användaren lades till i databasen skrivs meddelande ut
                if (result == true) {
                    var msg = 'Lösenordet har nu uppdaterats';
                    showMessage(msg);
                }
                // Om användaren inte kunde läggas till i databasen skrivs felmeddelande ut
                else {
                    $('<p class="errorMessageBox">Tyvärr, lösenordet kunde inte uppdateras</p>').insertAfter('#updatePasswordForm');
                }
            });
        }
    });
    /*
    FUNKTIONER
    */
    // Kontrollerar användarinformation
    function validateUserInfo(firstname, lastname, email) {
        var hasError = false;
        // Kollar att fältet inte är tomt
        if (firstname == '') {
            $('<p class="errorMessageBox">Du måste fylla i ditt förnamn</p>').insertAfter('#firstname');
            hasError = true;
        }
        // Kollar att fältet inte är tomt
        if (lastname == '') {
            $('<p class="errorMessageBox">Du måste fylla i ditt efternamn</p>').insertAfter('#lastname');
            hasError = true;
        }
        // Kollar att fältet inte är tomt
        if (email == '') {
            $('<p class="errorMessageBox">Du måste fylla i din e-post</p>').insertAfter('#email');
            hasError = true;
        }
        else {
            // Validerar e-posten genom funktionen validateEmail()
            var valid = validateEmail(email);
            if (valid != true) {
                $('<p class="errorMessageBox">Ogiltigt e-postformat</p>').insertAfter('#email');
                hasError = true;
            }
        }
        // Returnerar resultatet av hasError
        return hasError;
    }
    // Validerar e-post och returnerar 'true' eller 'false'
    function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test(email);
    }
    // Validerar lösenord
    function validatePassword(password, passwordCheck) {
        var hasError = false;
        // Kollar att lösenordsfältet inte är tomt
        if (password == '') {
            $('<p class="errorMessageBox">Du måste fylla i ett lösenord</p>').insertAfter('#password');
            hasError = true;
        }
        // Kollar om lösenordet är minst 6 tecken långt
        else if (password.length < 6) {
            $('<p class="errorMessageBox">Ditt lösenord måste bestå av minst 6 tecken</p>').insertAfter('#password');
            hasError = true;
        }
        // Kollar om lösenordet fyllts i det andra fältet
        if (passwordCheck == '') {
            $('<p class="errorMessageBox">Du måste fylla i ett lösenord</p>').insertAfter('#passwordCheck');
            hasError = true;
        }
        // Kollar om de båda lösenorden överensstämmer med varandra
        else if (passwordCheck != password) {
            $('<p class="errorMessageBox">Lösenordsfälten överensstämmer inte</p>').insertAfter('#passwordCheck');
            hasError = true;
        }
        return hasError;
    }
    // Hämta parameter från url
    function getParam(name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null){
            return null;
        }
        else {
            return results[1] || 0;
        }
    }
    // Visar ett meddelande som försvinner efter 2s i fönstrets överkant
    function showMessage(msg) {
        $('<div class="successMessage">' + msg + '</div>').insertBefore('#mainContent').delay(2000).fadeOut(function(){
            $(this).remove(); 
        });
        // Hämtar in information om var på sidan användaren har sin överkant och bredden på #mainContainer
        var screenTop = $(document).scrollTop();
        var elwidth = $('#mainContainer').width();
        var styles = {
            position: 'absolute',
            top: screenTop,
            'width': elwidth
        };
        // Lägger till i css-reglerna ovan till classen successMessage
        $('.successMessage').css(styles);
        // Tar bort meddelandet vid klick
        $('.successMessage').click(function() {
            $('.successMessage').remove();
        });
    }
});