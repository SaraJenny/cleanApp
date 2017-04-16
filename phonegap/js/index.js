/* Sara Petersson - Webbutveckling för mobila enheter, DT148G */
// Initialize your app
var myApp = new Framework7();

// Export selectors engine
var $$ = Dom7;

// Add views
var view1 = myApp.addView('#view-1');
var view2 = myApp.addView('#view-2');
var view3 = myApp.addView('#view-3');

$(document).ready(function() {
    document.addEventListener("deviceready", onDeviceReady, false);
    onDeviceReady();
});

function onDeviceReady() {
    /*
    ALLMÄNT
    */
    // Om användaren är inloggad hämtas information som sedan skrivs ut
    if (localStorage.getItem('userId') != null) {
        var userId = localStorage.userId;
        getCompanyName();
        getToDoList();
        getAllRooms();
        getProfile();
        getToDoNumber();
    }
    else {
        myApp.loginScreen();
    }
    // Vid klick på "Logga ut"
    $('#logout').click(function(event) {
        event.preventDefault();
        localStorage.removeItem("userId");
        myApp.loginScreen();
    });
    // Hämtar företagets namn
    function getCompanyName() {
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/getCompanyName.php',
            data: {
                userId: userId
            }
        }).done(function(result) {
            // Om företagsnamnet kunde hämtas skrivs det ut
            if (result != false) {
                $('.companyName').html(result);
            }
        });
    }
    // Hämtar antalet rum som ska städas
    function getToDoNumber() {
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/getToDoNumber.php',
            data: {
                userId: userId
            }
        }).done(function(result) {
            if (result != false) {
                $("#toDoNumber").html(result);
            }
            else {
                $("#toDoNumber").html("0");
            }
        });
    }
    /*
    VIEW 1: ATT GÖRA
    */
    // Hämtar och skriver ut information om rum som ska städas
    function getToDoList() {
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/getToDoList.php',
            data: {
                userId: userId
            }
        }).done(function(result) {
            // Om hämtningen lyckades skrivs listan ut
            if (result != false) {
                $('#toDo').html(result);
            }
        });
    }
    // Vid klick på knappen "Markera som städat" ändras rummets status och tas bort från listan
    $('#toDo').on('touchstart', '.changeStatus', function () {
        event.preventDefault();
        var roomId = this.id;
        var status = 1;
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/changeStatus.php',
            data: {
                roomId: roomId,
                userId: userId,
                status: status
            }
        }).done(function(result) {
            // Om statusändringen lyckades tas rummet bort från listan och markeras som ostädat i listan för alla rummen
            if (result == true) {
                myApp.closeModal('.picker-' + roomId)
                $('#room_' + roomId).addClass('hide');
                $('#swipeAll_' + roomId).text('Ostädat?');
                $('#button_' + roomId).text('Markera som ostädat');
                getToDoNumber();
            }
            else {
                myApp.addNotification({
                    message: 'Tyvärr, rummets status kunde inte ändras'
                });
                $('.notifications').addClass('close-notification');
            }
        });
    });
    // Vid klick på swipe "KLAR" ändras rummets status och tas bort från listan
    $('#toDo').on('touchstart', '.action1', function () {
        event.preventDefault();
        var id = this.id;
        var roomId = id.split("_").pop();
        var status = 1;
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/changeStatus.php',
            data: {
                roomId: roomId,
                userId: userId,
                status: status
            }
        }).done(function(result) {
            // Om statusändringen lyckades tas rummet bort från listan och markeras som ostädat i listan för alla rummen
            if (result == true) {
                $('#room_' + roomId).addClass('hide');
                $('#swipeAll_' + roomId).text('Ostädat?');
                $('#button_' + roomId).text('Markera som ostädat');
                getToDoNumber();
            }
            else {
                myApp.addNotification({
                    message: 'Tyvärr, rummets status kunde inte ändras'
                });
                $('.notifications').addClass('close-notification');
            }
        });
    });
    /*
    VIEW 2: ALLA RUM
    */
    // Hämtar och skriver ut information om alla rum
    function getAllRooms() {
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/getAllRooms.php',
            data: {
                userId: userId
            }
        }).done(function(result) {
            // Om hämtningen lyckades skrivs listan ut
            if (result != false) {
                $('#allRooms').html(result);
            }
        });
    }
    // Vid klick på knappen "Markera som städat/ostädat" ändras rummets status
    $('#allRooms').on('touchstart', '.changeStatus', function () {
        event.preventDefault();
        var id = this.id;
        var roomId = id.split("_").pop();
        var status = $("#" + id).text();
        // Byter status från vad det var
        if (status == "Markera som ostädat") {
            status = 0;
        }
        else {
            status = 1;
        }
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/changeStatus.php',
            data: {
                roomId: roomId,
                userId: userId,
                status: status
            }
        }).done(function(result) {
            // Om statusändringen lyckades uppdateras knappen
            if (result == true) {
                if (status == 1) {
                    myApp.closeModal('.picker-' + roomId)
                    $('#swipeAll_' + roomId).text('Ostädat?');
                    $('#button_' + roomId).text('Markera som ostädat');
                    $('#room_' + roomId).addClass('hide');
                }
                else {
                    myApp.closeModal('.picker-' + roomId)
                    $('#swipeAll_' + roomId).text('Städat?');
                    $('#button_' + roomId).text('Markera som städat');
                    $('#room_' + roomId).removeClass('hide');
                }
                getToDoNumber();
            }
            else {
                myApp.addNotification({
                    message: 'Tyvärr, rummets status kunde inte ändras'
                });
                $('.notifications').addClass('close-notification');
            }
        });
    });
    // Vid klick på swipe "Städat? / Ostädat?" ändras rummets status
    $('#allRooms').on('touchstart', '.action1', function () {
        event.preventDefault();
        var id = this.id;
        var roomId = id.split("_").pop();
        var status = $("#" + id).text();
        if (status == "Ostädat?") {
            status = 0;
            statusName = "Städat?";
        }
        else {
            status = 1;
            statusName = "Ostädat?";
        }
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/changeStatus.php',
            data: {
                roomId: roomId,
                userId: userId,
                status: status
            }
        }).done(function(result) {
            // Om statusändringen lyckades ändras frågan och stänger swipe, samt ändrar att-göra-listan
            if (result == true) {
                $("#" + id).html(statusName);
                myApp.swipeoutClose("#roomAll_" + roomId);
                // Ändrar att-göra-listan
                if (status == 1) {
                    $('#room_' + roomId).addClass('hide');
                }
                else {
                    $('#room_' + roomId).removeClass('hide');
                }
                getToDoNumber();
            }
            else {
                myApp.addNotification({
                    message: 'Tyvärr, rummets status kunde inte ändras'
                });
                $('.notifications').addClass('close-notification');
            }
        });
    });
    /*
    VIEW 3: MIN PROFIL
    */
    // Hämtar information användaren
    function getProfile() {
        $.ajax({
            method: 'POST',
            url: 'http://sarapdesign.se/WU/cleanapp/ajax/getProfile.php',
            data: {
                userId: userId
            }
        }).done(function(result) {
            // Om 
            if (result != false) {
                $('#profile').html(result);
            }
            else {
            }
        });
    }
    // Validerar fält och sparar i databasen
    $('#profile').on('change', '#firstname', function () {
        // Radera ev. felmeddelanden
        $(".notifications").remove();
        $(".errorMessageBox").remove();
        var firstname = $('#firstname').val();
        var hasError = false;
        // Validerar fältet
        if (firstname == '') {
            $('<p class="errorMessageBox">Du måste fylla i ditt förnamn</p>').insertAfter('#firstnameBox');
            hasError = true;
        }
        // Om inga fel hittas görs ett AJAX-anrop för att ändra användarinformationen i databasen
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'http://sarapdesign.se/WU/cleanapp/ajax/changeFirstname.php',
                data: {
                    userId: userId,
                    firstname: firstname
                }
            }).done(function(result) {
                if (result == true) {
                    myApp.addNotification({
                        message: 'Ditt namn har sparats'
                    });
                    $('.notifications').addClass('close-notification');
                }
                else {
                    $('<p class="errorMessageBox">' + result + '</p>').insertAfter('#firstnameBox');
                }                
            });
        };
    });
    // Validerar fält och sparar i databasen
    $('#profile').on('change', '#lastname', function () {
        // Radera ev. felmeddelanden
        $(".notifications").remove();
        $(".errorMessageBox").remove();
        var lastname = $('#lastname').val();
        var hasError = false;
        // Validerar fältet
        if (lastname == '') {
            $('<p class="errorMessageBox">Du måste fylla i ditt efternamn</p>').insertAfter('#lastnameBox');
            hasError = true;
        }
        // Om inga fel hittas görs ett AJAX-anrop för att ändra användarinformationen i databasen
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'http://sarapdesign.se/WU/cleanapp/ajax/changeLastname.php',
                data: {
                    userId: userId,
                    lastname: lastname
                }
            }).done(function(result) {
                if (result == true) {
                    myApp.addNotification({
                        message: 'Ditt namn har sparats'
                    });
                    $('.notifications').addClass('close-notification');
                }
                else {
                    $('<p class="errorMessageBox">' + result + '</p>').insertAfter('#lastnameBox');
                }                
            });
        };
    });
    // Validerar fält och sparar i databasen
    $('#profile').on('change', '#newEmail', function () {
        // Radera ev. felmeddelanden
        $(".notifications").remove();
        $(".errorMessageBox").remove();
        var email = $('#newEmail').val();
        var hasError = false;
        // Validerar fältet
        if (email == '') {
            $('<p class="errorMessageBox">Du måste fylla i din e-post</p>').insertAfter('#emailBox');
            hasError = true;
        }
        else {
            // Validerar e-posten genom funktionen validateEmail()
            var valid = validateEmail(email);
            if (valid != true) {
                $('<p class="errorMessageBox">Ogiltigt e-postformat</p>').insertAfter('#emailBox');
                hasError = true;
            }
        }
        // Om inga fel hittas görs ett AJAX-anrop för att ändra användarinformationen i databasen
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'http://sarapdesign.se/WU/cleanapp/ajax/changeEmail.php',
                data: {
                    userId: userId,
                    email: email
                }
            }).done(function(result) {
                if (result == true) {
                    myApp.addNotification({
                        message: 'Din e-post har sparats'
                    });
                    $('.notifications').addClass('close-notification');
                }
                else {
                    $('<p class="errorMessageBox">' + result + '</p>').insertAfter('#emailBox');
                }                
            });
        };
    });
    // Validerar fält och sparar i databasen
    $('#profile').on('touchstart', '#passwordButton', function () {
        // Radera ev. felmeddelanden
        $(".notifications").remove();
        $(".errorMessageBox").remove();
        var password = $('#newPassword').val();
        var hasError = false;
        // Kollar om lösenordsfältet är tomt
        if (password == '') {
            $('<p class="errorMessageBox">Du måste fylla i ett lösenord</p>').insertAfter('#passwordBox');
            hasError = true;
        }
        // Kollar om lösenordet är minst 6 tecken långt
        else if (password.length < 6) {
            $('<p class="errorMessageBox">Ditt lösenord måste bestå av minst 6 tecken</p>').insertAfter('#passwordBox');
            hasError = true;
        }
        // Om inga fel hittas görs ett AJAX-anrop för att ändra användarinformationen i databasen
        if (hasError == false) {
            myApp.modalPassword('Fyll i ditt nya lösenord:', 'CleanApp', function (passwordCheck) {
                // Om lösenorden överensstämmer sparas det i databasen
                if (passwordCheck == password) {
                    $.ajax({
                        method: 'POST',
                        url: 'http://sarapdesign.se/WU/cleanapp/ajax/changePassword.php',
                        data: {
                            userId: userId,
                            password: password
                        }
                    }).done(function(result) {
                        // 
                        if (result == true) {
                            myApp.addNotification({
                                message: 'Ditt lösenord har ändrats'
                            });
                            $('.notifications').addClass('close-notification');
                        }
                        else {
                            $('<p class="errorMessageBox">' + result + '</p>').insertAfter('#passwordBox');
                        }
                    });
                }
                else {
                    myApp.alert('Tyvärr, lösenorden överensstämmer inte', 'CleanApp');
                }
            });
        };
    });
    /*
    INLOGGNING
    */
    $('#loginButton').click(function(event) {
        event.preventDefault();
        // Radera ev. felmeddelanden
        $(".errorMessageBox").remove();
        // Skapa variabler
        var email = $('#email').val();
        var password = $('#password').val();
        var hasError = false;
        // Kollar om e-post och lösenord fyllts i
        if (email == '' && password == '') {
            $('.list-block-label').html('<p class="errorMessageBox">Du måste fylla i din e-post</p><p class="errorMessageBox">Du måste fylla i ditt lösenord</p>');
            hasError = true;
        }
        // Kollar om e-post fyllts i
        else if (email == '') {
            $('.list-block-label').html('<p class="errorMessageBox">Du måste fylla i din e-post</p>');
            hasError = true;
        }
        // Kollar om lösenord fyllts i
        else if (password == '') {
            $('.list-block-label').html('<p class="errorMessageBox">Du måste fylla i ditt lösenord</p>');
            hasError = true;
        }
        // Om inga fel hittas görs ett AJAX-anrop för att kontrollera att inloggningsuppgifterna stämmer
        if (hasError == false) {
            $.ajax({
                method: 'POST',
                url: 'http://sarapdesign.se/WU/cleanapp/ajax/loginUserM.php',
                data: {
                    email: email,
                    password: password
                }
            }).done(function(result) {
                // Om inloggningen var korrekt skickas användaren till index-php
                if (result != false) {
                    localStorage.setItem("userId", result);
                    window.location.href = 'index.html';
                }
                // Om inloggningen misslyckades skrivs ett felmeddelande ut
                else {
                    $('.list-block-label').html('<p class="errorMessageBox">Felaktig inloggning</p>');
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
}