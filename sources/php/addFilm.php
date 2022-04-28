<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$name = '';
if(isset($_POST['film_name_af'])){
    $name = $_POST['film_name_af'];
}

$dauer = '';
if(isset($_POST['dauer_min'])){
    $dauer = $_POST['dauer_min'];
}

$genre = '';
if(isset($_POST['film_genre'])){
    $genre = $_POST['film_genre'];
}

$jahr = '';
if(isset($_POST['new_jahr'])){
    $jahr = $_POST['new_jahr'];
}

// Insert method
$success = $database->insertIntoFilm($name, $dauer, $genre, $jahr);

// Check result
if ($success){
    echo "'{$name}' successfully added!'";
}
else{
    echo "Error can't insert into film '{$name}{$dauer}{$genre}{$jahr}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="FilmeCrud.php">
    go back
</a>