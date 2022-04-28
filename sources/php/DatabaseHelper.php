<?php

class DatabaseHelper
{
    // Since the connection details are constant, define them as const
    // We can refer to constants like e.g. DatabaseHelper::username
    const username = 'a12032793'; // use a + your matriculation number
    const password = 'dbs21'; // use your oracle db password
    const con_string = 'lab';

    // Since we need only one connection object, it can be stored in a member variable.
    // $conn is set in the constructor.
    protected $conn;

    // Create connection in the constructor
    public function __construct()
    {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            $this->conn = oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->conn) {
                // die(String(message)): stop PHP script and output message:
                die("DB error: Connection can't be established!");
            }

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }

    public function __destruct()
    {
        // clean up
        oci_close($this->conn);
    }

    // This function creates and executes a SQL select statement and returns an array as the result
    // 2-dimensional array: the result array contains nested arrays (each contains the data of a single row)
    public function selectAllPersons($kunden_id, $name, $alter)
    {
        // Define the sql statement string
        // Notice that the parameters $person_id, $surname, $name in the 'WHERE' clause
        $sql = "SELECT * FROM KundeIn
            WHERE KUNDENNR LIKE '%{$kunden_id}%'
              AND upper(NAME) LIKE upper('%{$name}%')
              AND KUNDENALTER LIKE '%{$alter}%'
            ORDER BY KUNDENNR ASC";

        // oci_parse(...) prepares the Oracle statement for execution
        // notice the reference to the class variable $this->conn (set in the constructor)
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    // This function creates and executes a SQL insert statement and returns true or false
    public function insertIntoPerson($name, $alter)
    {
        $sql = "INSERT INTO KundeIn (NAME, KUNDENALTER) VALUES ('{$name}', '{$alter}')";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    // Using a Procedure
    public function deletePerson($person_id)
    {
        // It is not necessary to assign the output variable,
        // but to be sure that the $errorcode differs after the execution of our procedure we do it anyway
        $errorcode = 0;

        // In our case the procedure P_DELETE_PERSON takes two parameters:
        //  1. person_id (IN parameter)
        //  2. error_code (OUT parameter)

        // The SQL string
        $sql = 'BEGIN P_DELETE_kundein(:person_id, :errorcode); END;';
        $statement = @oci_parse($this->conn, $sql);

        //  Bind the parameters
        @oci_bind_by_name($statement, ':person_id', $person_id);
        @oci_bind_by_name($statement, ':errorcode', $errorcode);

        // Execute Statement
        @oci_execute($statement);

        //Note: Since we execute COMMIT in our procedure, we don't need to commit it here.
        //@oci_commit($statement); //not necessary

        //Clean Up
        @oci_free_statement($statement);

        //$errorcode == 1 => success
        //$errorcode != 1 => Oracle SQL related errorcode;
        return $errorcode;
    }

    public function getAllMovies(){

        $sql = "SELECT NAME FROM FILM";

        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function getAllCinemas(){
        $sql = "SELECT FILIALEID, NAME FROM FILIALE ORDER BY FILIALEID";

        $statement = oci_parse($this->conn, $sql);

        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);

        return $res;
    }

    public function insertIntoWirdAbgespielt($filiale, $saal, $filmname, $datum, $beginn)
    {
        $sql = "INSERT INTO WIRD_ABGESPIELT (filiale, saal, filmname, datum, beginnzeit) 
                        VALUES ({$filiale}, {$saal}, '{$filmname}', to_date('{$datum}','YYYY-MM-DD'), '{$beginn}')";
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function selectAllWirdAbgespielt($filiale, $saal, $filmName, $datum, $anfang)
    {
        $myString = "date '";
        $myString .= $datum;
        $myString .= "'";

        if(!empty($datum)) {
            $datum = "date '{$datum}'";
        }

        $sql = "SELECT * FROM WIRD_ABGESPIELT left join filiale f on f.FILIALEID = WIRD_ABGESPIELT.FILIALE
            WHERE filiale LIKE '%{$filiale}%'
              AND saal LIKE '%{$saal}%'
              AND upper(FILMNAME) LIKE upper('%{$filmName}%')
            /*  AND datum LIKE '%{$datum}%'   */
              AND BEGINNZEIT LIKE '%{$anfang}%'
            ORDER BY DATUM ASC ";
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function deleteWirdAbgespielt($filiale_id, $saalNr, $filmname, $datum, $uhrzeit)
    {
        $errorcode = 0;

        // The SQL string
        $sql = "DELETE FROM WIRD_ABGESPIELT WHERE FILIALE = {$filiale_id} AND SAAL = {$saalNr} and FILMNAME = '{$filmname}'
                AND DATUM = date '{$datum}' AND beginnzeit = '{$uhrzeit}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;

    }

    public function insertIntoKaufenTicket($film, $filiale, $saal, $kunden_id, $datum, $anfang)
    {
        $sql = "INSERT INTO KAUFEN_TICKET VALUES ('{$film}', {$filiale}, {$saal}, {$kunden_id}, to_date('{$datum}','YYYY-MM-DD'),
                                  '{$anfang}')";
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function selectAllKaufenTicket($film_kt, $filiale_id_kt, $saal_kt, $kunden_id_kt, $datum_kt, $anfang_kt)
    {
        $sql = "SELECT * FROM KAUFEN_TICKET
            WHERE upper(FILM) LIKE upper('%{$film_kt}%')
            AND FILIALEID LIKE '%{$filiale_id_kt}%'
            AND SAAL LIKE '%{$saal_kt}%'
            AND KUNDENNR LIKE '%{$kunden_id_kt}%'
          /*  AND DATUM LIKE to_date('%{$datum_kt}%','YYYY-MM-DD') */
            AND ANFANG LIKE '%{$anfang_kt}%' 
            ORDER BY KUNDENNR ASC";
        // oci_parse(...) prepares the Oracle statement for execution
        // notice the reference to the class variable $this->conn (set in the constructor)
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function deleteKaufenTicket($film_del_kt, $filiale_del_kt, $saal_del_kt, $kunden_del_kt, $datum_del_kt, $anfang_del_kt){

        // The SQL string
        $sql = "DELETE FROM KAUFEN_TICKET WHERE FILM = '{$film_del_kt}' AND 
                FILIALEID = {$filiale_del_kt} AND SAAL = {$saal_del_kt} AND KUNDENNR = {$kunden_del_kt}
                AND DATUM = date '{$datum_del_kt}' AND ANFANG = '{$anfang_del_kt}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function selectAllFilms($filmname, $filmdauer, $filmgenre, $filmjahr)
    {
        $sql = "SELECT * FROM FILM
            WHERE upper(NAME) LIKE upper('%{$filmname}%')
              AND dauer LIKE '%{$filmdauer}%'
              AND upper(GENRE) LIKE upper('%{$filmgenre}%')
              AND ERCHEINUNGSJAHR LIKE '%{$filmjahr}%'
            ORDER BY NAME ASC";

        // oci_parse(...) prepares the Oracle statement for execution
        // notice the reference to the class variable $this->conn (set in the constructor)
        $statement = oci_parse($this->conn, $sql);

        // Executes the statement
        oci_execute($statement);

        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        //clean up;
        oci_free_statement($statement);

        return $res;
    }

    public function insertIntoFilm($name, $dauer, $genre, $jahr)
    {
        $sql = "INSERT INTO FILM(name, dauer, genre, ercheinungsjahr) VALUES ('{$name}', {$dauer}, '{$genre}', {$jahr})";
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function deleteFilm($filmname)
    {
        $sql = "DELETE FROM FILM WHERE NAME = '{$filmname}'";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function updatePerson($kunden_nr, $alter, $name){
     // but to be sure that the $errorcode differs after the execution of our procedure we do it anyway
        $sql = "UPDATE KundeIn SET";
        if(!empty($alter)) $sql .= " kundenalter = {$alter}";
        if(!empty($name)) {
            if(!empty($alter)) $sql .= ", ";
            $sql .= " name = '{$name}'";
        }
        $sql.= "WHERE Kundennr = {$kunden_nr}";

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function updateFilm($film_name, $film_dauer, $film_genre, $film_jahr)
    {
        $sql = "UPDATE FILM SET";
        if(!empty($film_dauer)) $sql .= " dauer = {$film_dauer}";
        if(!empty($film_genre)) {
            if(!empty($film_dauer)) $sql .= ", ";
            $sql .= " genre = '{$film_genre}'";
        }
        if(!empty($film_jahr)) {
            if(!empty($film_dauer) or !empty($film_genre)) $sql .= ", ";
            $sql .= " ercheinungsjahr = {$film_jahr}";
        }
        $sql.= " WHERE name = '{$film_name}'";

       // printf($sql);

        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }

    public function updateDatumWA($filiale, $saal, $film_name, $datum, $begin, $datum_neu)
    {

        $errorcode = 0;
        $sqlprep = "alter session set constraints = deferred";
        $statementprep = oci_parse($this->conn, $sqlprep);
        oci_execute($statementprep) && oci_commit($this->conn);
        oci_free_statement($statementprep);
        // The SQL string
        $sql = "BEGIN P_UPDATE_WIRD_ABGESPIELT(:filiale, :saal, :film_name, date '{$datum}', :begin, date '{$datum_neu}', :errorcode); END;";
        $statement = @oci_parse($this->conn, $sql);

        //  Bind the parameters
        @oci_bind_by_name($statement, ':filiale', $filiale);
        @oci_bind_by_name($statement, ':saal', $saal);
        @oci_bind_by_name($statement, ':film_name', $film_name);
      //  @oci_bind_by_name($statement, ':datum', $datum);
        @oci_bind_by_name($statement, ':begin', $begin);
      //  @oci_bind_by_name($statement, ':datum_neu', $datum_neu);
        @oci_bind_by_name($statement, ':errorcode', $errorcode);
        // Execute Statement
        @oci_execute($statement);
        @oci_free_statement($statement);

        $sqlprep = "alter session set constraints = immediate";
        $statementprep = oci_parse($this->conn, $sqlprep);
        oci_execute($statementprep) && oci_commit($this->conn);
        oci_free_statement($statementprep);

        return $errorcode;
    }

    public function updateTicket($film_name, $kunden_nr, $datum, $begin, $kunde_neu)
    {
        $sql = "UPDATE KAUFEN_TICKET SET KUNDENNR = {$kunde_neu} WHERE FILM = '{$film_name}' and KUNDENNR = {$kunden_nr} and 
                DATUM = date '{$datum}' and ANFANG = '{$begin}'";
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }


}