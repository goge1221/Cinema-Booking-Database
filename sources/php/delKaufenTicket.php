<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$filiale_id = '';
if(isset($_POST['kt_del_filiale'])){
    $filiale_id = $_POST['kt_del_filiale'];
}

$saalNr = '';
if(isset($_POST['kt_del_saal'])){
    $saalNr = $_POST['kt_del_saal'];
}

$filmname = '';
if(isset($_POST['kt_del_filmname'])){
    $filmname = $_POST['kt_del_filmname'];
}

$datum = '';
if(isset($_POST['kt_del_datum'])){
    $datum = $_POST['kt_del_datum'];
}

$uhrzeit = '';
if(isset($_POST['kt_del_begin'])){
    $uhrzeit = $_POST['kt_del_begin'];
}

$kundenNr = '';
if(isset($_POST['kt_del_kunden'])){
    $kundenNr = $_POST['kt_del_kunden'];
}

// Delete method
$error_code = $database->deleteKaufenTicket($filmname, $filiale_id, $saalNr, $kundenNr, $datum, $uhrzeit);

// Check result
if ($error_code == 1){
    echo "Kunden Nr. '{$kundenNr}' von '{$filmname}' in filiale '{$filiale_id}' saal '{$saalNr}' am '{$datum}' erfolgreich gelöscht. ";
}
else{
    echo "Error: Kunden Nr. '{$kundenNr}' von '{$filmname}' in filiale '{$filiale_id}' saal '{$saalNr}' am '{$datum}' konnte nicht gelöscht werden. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="kaufenTicketCrud.php">
    go back
</a>