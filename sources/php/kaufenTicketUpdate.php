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

$kunden_nr = '';
if(isset($_POST['kunde_update'])){
    $kunden_nr = $_POST['kunde_update'];
}

$datum = '';
if(isset($_POST['datum_update'])){
    $datum = $_POST['datum_update'];
}

$begin = '';
if(isset($_POST['begin_update'])){
    $begin = $_POST['begin_update'];
}

$kunde_neu = '';
if(isset($_POST['neue_kunden_nr'])){
    $kunde_neu = $_POST['neue_kunden_nr'];
}
// Delete method
$error_code = $database->updateTicket($film_name, $kunden_nr, $datum, $begin, $kunde_neu);

// Check result
if ($error_code == 1 or $error_code == 2){
    $print = "Erfolgreich: Der Kunde mit dem ID {$kunde_neu} besitzt jetzt einen Ticket zu {$film_name} am {$datum} statt {$kunden_nr}";
    printf($print);
}
else {
    $print = "Error: Der Kunde mit dem ID {$kunden_nr} wollte den ticket an {$kunde_neu} für den film {$film_name} am {$datum} nicht abgeben.";
    printf($print);
}
?>

<!-- link back to index page-->
<br>
<a href="kaufenTicketCrud.php">
    go back
</a>