<?php
//include DatabaseHelper.php file
require_once('DatabaseHelper.php');

//instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Grab variables from POST request
$name = '';
if(isset($_POST['name'])){
    $name = $_POST['name'];
}

$alter = '';
if(isset($_POST['alter'])){
    $alter = $_POST['alter'];
}

// Insert method
$success = $database->insertIntoPerson($name, $alter);

// Check result
if ($success){
    echo "Person '{$name} {$alter}' successfully added!'";
}
else{
    echo "Error can't insert Person '{$name} {$alter}'!";
}
?>

<!-- link back to index page-->
<br>
<a href="KundenCrud.php">
    go back
</a>