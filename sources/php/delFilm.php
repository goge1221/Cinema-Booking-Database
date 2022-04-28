<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$filmname = '';
if(isset($_POST['delete_film_name'])){
    $filmname = $_POST['delete_film_name'];
}

// Delete method
$error_code = $database->deleteFilm($filmname);

// Check result
if ($error_code == 1){
    echo "'{$filmname}' successfully deleted!'";
}
else{
    echo "Error can't delete '{$filmname}'. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="FilmeCrud.php">
    go back
</a>