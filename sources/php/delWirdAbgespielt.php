<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$filiale_id = '';
if(isset($_POST['delete_filiale_id'])){
    $filiale_id = $_POST['delete_filiale_id'];
}

$saalNr = '';
if(isset($_POST['delete_saal'])){
    $saalNr = $_POST['delete_saal'];
}

$filmname = '';
if(isset($_POST['delete_filmname'])){
    $filmname = $_POST['delete_filmname'];
}

$datum = '';
if(isset($_POST['delete_datum'])){
    $datum = $_POST['delete_datum'];
}

$uhrzeit = '';
if(isset($_POST['delete_uhrzeit'])){
    $uhrzeit = $_POST['delete_uhrzeit'];
}


// Delete method
$error_code = $database->deleteWirdAbgespielt($filiale_id,$saalNr,$filmname,$datum,$uhrzeit);

// Check result
if ($error_code == 1){
    echo "'{$filmname}' von '{$datum}' um '{$uhrzeit}' in Filiale '{$filiale_id}' Saal '{$saalNr}' erfolgreich gelöscht von wird abgespielt!'";
}
else{
    echo "Error: '{$filmname}' von '{$datum}' um '{$uhrzeit}' in Filiale '{$filiale_id}' Saal '{$saalNr}' konnte nicht gelöscht werden. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="wirdAbgespieltCrud.php">
    go back
</a>