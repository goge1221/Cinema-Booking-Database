<?php

require_once('DatabaseHelper.php');

$database = new DatabaseHelper();


$filiale = '';
if(isset($_GET['filiale_search_id'])){
    $filiale = $_GET['filiale_search_id'];
}

$saal = '';
if(isset($_GET['saal_search_wa'])){
    $saal = $_GET['saal_search_wa'];
}

$filmName = '';
if(isset($_GET['film_search_wa'])){
    $filmName = $_GET['film_search_wa'];
}

$datum = '';
if(isset($_GET['date_search_wa'])){
    $datum = $_GET['date_search_wa'];
}

$anfang = '';
if(isset($_GET['anfang_search_wa'])){
    $anfang = $_GET['anfang_search_wa'];
}

$kino_array = $database->getAllCinemas();
$wird_abgespielt_array = $database->selectAllWirdAbgespielt($filiale, $saal, $filmName, $datum, $anfang);
$filme_array = $database->getAllMovies();
?>


<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>

<h1>Wird Abgespielt Crud</h1>
<br>

<h2>Wird abgespielt adder:</h2>
<form method="post" action="wirdAbgespielt.php">
    <!-- ID is not needed, because its autogenerated by the database -->

    <!-- Filiale dropdown list -->
    <div>
        Filiale:
        <select name = "wird_abgespielt_filiale">
            <option value = "">---Select---</option>
            <?php foreach ($kino_array as $kino) : ?>
                <option value = "<?php echo $kino['FILIALEID'] ?>"><?php echo $kino['NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>

    <!-- Saal dropdown list -->
    <div>
        Saal:
        <select name = "wird_abgespielt_saal">
            <option value = "">---Select---</option>
            <option value = "1">1</option>
            <option value = "2">2</option>
            <option value = "3">3</option>
            <option value = "4">4</option>
        </select>
    </div>
    <br>

    <!-- Filme dynamic dropdown list-->
    <div>
        Film:
        <select name = "wird_abgespielt_filme">
            <option value = "">---Select---</option>
            <?php foreach ($filme_array as $film) : ?>
                <option value = "<?php echo $film['NAME'] ?>"><?php echo $film['NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>

    <!-- datum -->
    <div>
        <label for="new_datum">Datum (YYYY-MM-TT): </label>
        <input id="new_datum" name="wird_abgespielt_datum" type="text" maxlength="20">
    </div>
    <br>


    <div>
        <lavel for = "new_uhrzeit">Uhrzeit (HH:MM): </lavel>
        <input id = "new_uhrzeit" name="wird_abgespielt_uhrzeit" type = "text" maxlength="5">
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button type="submit" class="btn btn-outline-success">
            Add to wird abgespielt
        </button>
    </div>
</form>
<br>
<hr>


<h2>Wird abgespielt Search:</h2>
<form method="get">
    <!-- ID textbox:-->
    <div>
        <label for="filiale_search_wa">FilialeID:</label>
        <input id="filiale_search_wa" name="filiale_search_id" type="number" value='<?php echo $filiale; ?>' min="1" max="6">
    </div>
    <br>

    <div>
        <label for="saal_search_wa">Saal Nr.:</label>
        <input id="saal_search_wa" name="saal_search_wa" type="number" value='<?php echo $saal; ?>' min="1" max="4">
    </div>
    <br>

    <div>
        <label for="film_search_wa">Film Name:</label>
        <input id="film_search_wa" name="film_search_wa" type="text" maxlength="25"
               value='<?php echo $filmName; ?>'>
    </div>
    <br>

    <div>
        <label for="date_search_wa">Datum:</label>
        <input id="date_search_wa" name="date_search_wa" type="text" maxlength="10"
               value='<?php echo $datum; ?>'>
    </div>
    <br>

    <div>
        <label for="anfang_search_wa">Beginn:</label>
        <input id="anfang_search_wa" name="anfang_search_wa" type="text" maxlength="5"
               value='<?php echo $anfang; ?>'>
    </div>
    <br>

    <!-- Submit button -->
    <div>
        <button id='submit' type='submit' class="btn btn-outline-success">
            Search
        </button>
    </div>
</form>
<br>
<hr>


<!-- Delete wird abgespielt -->
<h2>Delete from wird abgespielt: </h2>
<form method="post" action="delWirdAbgespielt.php">
    <!-- ID textbox -->
    <div>
        <label for="del_filiale">Filiale:</label>
        <input id="del_filiale" name="delete_filiale_id" type="number" min="0">
    </div>
    <br>

    <div>
        <label for="del_saal">Saal:</label>
        <input id="del_saal" name="delete_saal" type="number" min="0">
    </div>
    <br>

    <div>
        <label for="del_filmname">Filmname:</label>
        <input id="del_filmname" name="delete_filmname" type="text" maxlength="25">
    </div>
    <br>

    <div>
        <label for="del_datum">Datum:</label>
        <input id="del_datum" name="delete_datum" type="text" maxlength="10">
    </div>
    <br>

    <div>
        <label for="del_uhrzeit">Uhrzeit:</label>
        <input id="del_uhrzeit" name="delete_uhrzeit" type="text" maxlength="10">
    </div>
    <br>


    <!-- Submit button -->
    <div>
        <button type="submit" class="btn btn-outline-danger">
            Delete entry
        </button>
    </div>
</form>
<br>
<hr>

<h2>Update Datum: </h2>
<form method="post" action="updateDatumWA.php">
    <!-- ID textbox -->
    <div>
        <label for="wa_update_fil">Filiale:</label>
        <input id="wa_update_fil" name="filiale_update_wa" type="number">
    </div>
    <br>


    <div>
        <label for="wa_saal">Saal:</label>
        <input id="wa_saal" name="saal_update_wa" type="number" min="1" max="4">
    </div>
    <br>

    <div>
        <label for="wa_film">Film:</label>
        <input id="wa_film" name="film_update_wa" type="text" maxlength="30">
    </div>
    <br>

    <div>
        <label for="wa_datum">Datum(YYYY-MM-DD):</label>
        <input id="wa_datum" name="datum_update_wa" type="text">
    </div>
    <br>

    <div>
        <label for="wa_begin">Anfang:</label>
        <input id="wa_begin" name="begin_update_wa" type="text">
    </div>
    <br>

    <div>
        <label for="wa_datum_neu">Neues Datum:</label>
        <input id="wa_datum_neu" name="datum_neu_update_wa" type="text">
    </div>
    <br>
    <!-- Submit button -->
    <div>
        <button type="submit"  class="btn btn-outline-success">
            Update Eintrag
        </button>
    </div>
    <br>
</form>
<br>



<!-- FILME ABGESPIELT WERDEN SEARCH RESULT -->
<h2>Wo filme abgespielt werden:</h2>
<div class="mx-3 mt-3">
    <table class="table mt-3">
        <thead>
        <tr>
            <th class="table-column-25">
                <div class="header" data-column-index="0">
                    <span class="pull-left">FilialeId</span>
                </div>
            </th>
            <th class="table-column-25">
                <div class="header" data-column-index="0">
                    <span class="pull-left">Filiale</span>
                </div>
            </th>
            <th class="table-column-25">
                <div class="header" data-column-index="1">
                    <span class="pull-left">Saal Nr</span>
                </div>
            </th>
            <th class="table-column-25">
                <div class="header" data-column-index="1">
                    <span class="pull-left">Filmname</span>
                </div>
            </th>
            <th class="table-column-25">
                <div class="header" data-column-index="1">
                    <span class="pull-left">Datum</span>
                </div>
            </th>
            <th class="table-column-25">
                <div class="header" data-column-index="1">
                    <span class="pull-left">Beginn</span>
                </div>
            </th>
        </tr>
        </thead>
        <?php foreach ($wird_abgespielt_array as $element) : ?>
            <tbody>
            <tr>
                <td><?php echo $element['FILIALE']?> </td>
                <td><?php echo $element['NAME']; ?>  </td>
                <td><?php echo $element['SAAL']; ?>  </td>
                <td><?php echo $element['FILMNAME']; ?>  </td>
                <td><?php echo $element['DATUM']; ?>  </td>
                <td><?php echo $element['BEGINNZEIT']; ?>  </td>
            </tr>
            </tbody>
        <?php endforeach; ?>
    </table>
</div>
<br>
<hr>


<br>
<a href="index.php">
    go back to main page
</a>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</body>
</html>