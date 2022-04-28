<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variable id from POST request
$kunden_id = '';
if(isset($_POST['id_kunde_update'])){
    $kunden_id = $_POST['id_kunde_update'];
}

$alterNeu = '';
if(isset($_POST['kunde_alter_neu'])){
    $alterNeu = $_POST['kunde_alter_neu'];
}

$kunden_name = '';
if(isset($_POST['kunde_name_neu'])){
    $kunden_name = $_POST['kunde_name_neu'];
}


// Delete method
$error_code = $database->updatePerson($kunden_id, $alterNeu, $kunden_name);

// Check result
if ($error_code == 1){
    echo "'{$kunden_name} {$alterNeu}' successfully updated!'";
}
else{
    echo "Error can't update '{$kunden_name}{$alterNeu}'. Errorcode: {$error_code}";
}
?>

<!-- link back to index page-->
<br>
<a href="KundenCrud.php">
    go back
</a>