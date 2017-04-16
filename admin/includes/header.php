<?php
/* Webbutveckling för mobila enheter, DT148G */
// Läser in config-filen
include("includes/config.php");
?>
<!DOCTYPE html>
<html lang="sv">
    <head>
        <!-- För äldre IE-versioner -->
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <title><?php echo $site_title . $divider . $page_title; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700" rel="stylesheet">
        <!-- Stilmall -->
        <link rel="stylesheet" href="css/index.css" type="text/css">
        <!-- FontAwsome -->
        <script src="https://use.fontawesome.com/85be48415d.js"></script>
        <!-- CKEditor -->
        <script src="//cdn.ckeditor.com/4.5.11/basic/ckeditor.js"></script>
    </head>
    <body>
        <!-- Sidhuvud -->
        <header>
            <div id="mainHeader">
                <a id="logo" href="index.php">
                    <h1>CleanApp</h1>
                    <p>admin</p>
                </a>
<?php
                if (isset($_SESSION['user_id'])) {
?>
                    <a href="#" id="toggle-nav">&#9776;</a>
<?php
                }
?>
            </div>
<?php
                if (isset($_SESSION['user_id'])) {
?>
                    <!-- Meny -->
                    <?php include("includes/mainmenu.php");?>
<?php
                }
?>
        </header><!-- Sidhuvud slut -->
        <section id="mainContent">