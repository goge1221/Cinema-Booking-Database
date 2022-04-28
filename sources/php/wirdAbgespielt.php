<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$filiale = '';
if(isset($_POST['wird_abgespielt_filiale'])){
    $filiale = $_POST['wird_abgespielt_filiale'];
}

$saal = '';
if(isset($_POST['wird_abgespielt_saal'])){
    $saal = $_POST['wird_abgespielt_saal'];
}

$filmName = '';
if(isset($_POST['wird_abgespielt_filme'])){
    $filmName = $_POST['wird_abgespielt_filme'];
}

$datum = '';
if(isset($_POST['wird_abgespielt_datum'])){
    $datum = $_POST['wird_abgespielt_datum'];
}

$anfang = '';
if(isset($_POST['wird_abgespielt_uhrzeit'])){
    $anfang = $_POST['wird_abgespielt_uhrzeit'];
}


// Insert method
 $success = $database->insertIntoWirdAbgespielt($filiale, $saal, $filmName, $datum, $anfang);

// Check result
if ($success){
    echo "Insert into WIRD_ABGESPIELT von '{$filiale} {$saal} {$filmName} {$datum} {$anfang}' successfully added!'";
}
else{
    echo "Error can't insert into wird_abgespielt '$filiale $saal $filmName $datum $anfang'!";
}
?>

<!-- link back to index page-->
<br>
<a href="wirdAbgespieltCrud.php">
    go back
</a>