<?php

// Include DatabaseHelper.php file
require_once('DatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new DatabaseHelper();

?>

<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Cineplexx</title>
</head>

<body>
<br>
<h1>Cineplexx</h1>

<br>
<a href="KundenCrud.php">
    Hier geht es zum Kunden CRUD
</a>


<br>
<a href="FilmeCrud.php">
    Hier geht es zum Film CRUD
</a>

<br>
<a href="wirdAbgespieltCrud.php">
    Hier geht es zum wird abgespielt CRUD
</a>

<br>
<a href="kaufenTicketCrud.php">
    Hier geht es zum kaufen ticket CRUD
</a>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</body>
</html>

