<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request

$filiale = '';
if(isset($_POST['filiale_update_wa'])){
    $filiale = $_POST['filiale_update_wa'];
}

$saal = '';
if(isset($_POST['saal_update_wa'])){
    $saal = $_POST['saal_update_wa'];
}

$film_name = '';
if(isset($_POST['film_update_wa'])){
    $film_name = $_POST['film_update_wa'];
}

$datum = '';
if(isset($_POST['datum_update_wa'])){
    $datum = $_POST['datum_update_wa'];
}

$begin = '';
if(isset($_POST['begin_update_wa'])){
    $begin = $_POST['begin_update_wa'];
}

$datum_neu = '';
if(isset($_POST['datum_neu_update_wa'])){
    $datum_neu = $_POST['datum_neu_update_wa'];
}
// Delete method
$error_code = $database->updateDatumWA($filiale, $saal, $film_name, $datum, $begin, $datum_neu);

// Check result
if ($error_code == 1 or $error_code == 2){
    $print = "Das Datum für den Film {$film_name} in Filiale {$filiale}, saal {$saal} wurde von ";
    $print .= "{$datum} auf {$datum_neu} erfolgreich geändert.";
    printf($print);
}
else {
    $print = "Error: Das Datum für den Film {$film_name} in Filiale {$filiale}, saal {$saal} wurde von ";
    $print .= "{$datum} auf {$datum_neu} nicht erfolgreich geändert.";
    printf($print);
}
?>

<!-- link back to index page-->
<br>
<a href="wirdAbgespieltCrud.php">
    go back
</a>