<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$kunden_id = '';
if(isset($_POST['kunden_id_kt'])){
    $kunden_id = $_POST['kunden_id_kt'];
}

$film = '';
if(isset($_POST['kaufen_ticket_film'])){
    $film = $_POST['kaufen_ticket_film'];
}

$filiale = '';
if(isset($_POST['filiale_id_kt'])){
    $filiale = $_POST['filiale_id_kt'];
}

$saal = '';
if(isset($_POST['saal_id_kt'])){
    $saal = $_POST['saal_id_kt'];
}

$datum = '';
if(isset($_POST['datum_kt'])){
    $datum = $_POST['datum_kt'];
}

$anfang = '';
if(isset($_POST['spielzeit_kt'])){
    $anfang = $_POST['spielzeit_kt'];
}

// Insert method
$success = $database->insertIntoKaufenTicket($film, $filiale, $saal, $kunden_id, $datum, $anfang);

// Check result
if ($success){
    echo "Ticket zur {$film} am {$datum} in filiale {$filiale} saal {$saal} für kunde {$kunden_id} erfolgreich gekauft!'";
}
else{
    echo "Error Ticket zur {$film} am {$datum} in filiale {$filiale} saal {$saal} für kunde {$kunden_id} nicht gekauft!'";
}
?>

<!-- link back to index page-->
<br>
<a href="kaufenTicketCrud.php">
    go back
</a>