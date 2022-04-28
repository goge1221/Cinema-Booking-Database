<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$film_name = '';
if(isset($_POST['film_name_update'])){
    $film_name = $_POST['film_name_update'];
}

$film_dauer = '';
if(isset($_POST['film_dauer_update'])){
    $film_dauer = $_POST['film_dauer_update'];
}

$film_genre = '';
if(isset($_POST['film_genre_update'])){
    $film_genre = $_POST['film_genre_update'];
}

$film_jahr = '';
if(isset($_POST['film_jahr_update'])){
    $film_jahr = $_POST['film_jahr_update'];
}



// Delete method
$error_code = $database->updateFilm($film_name, $film_dauer, $film_genre, $film_jahr);



// Check result
if ($error_code == 1){
    $print = "'{$film_name}' ";
    if(!empty($film_dauer)) $print .= "neue Dauer: '{$film_dauer}' ";
    if(!empty($film_genre)) $print .= "neues Genre: '{$film_genre}'";
    if(!empty($film_jahr)) $print .= " neues Jahr: '{$film_jahr}'";
    $print .= " erfolgreich aktualisiert.";
    printf($print);
}
else{
    $print = "Error: '{$film_name}' ";
    if(!empty($film_dauer)) $print .= "neue Dauer: '{$film_dauer}' ";
    if(!empty($film_genre)) $print .= "neues Genre: '{$film_genre}'";
    if(!empty($film_jahr)) $print .= " neues Jahr: '{$film_jahr}'";
    $print .= " nicht erfolgreich Aktualisiert.";}
    echo  $print;
?>

<!-- link back to index page-->
<br>
<a href="FilmeCrud.php">
    go back
</a>