<?php

class Paginator {

    private $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_total;

    const username = 'a12032793'; // use a + your matriculation number
    const password = 'dbs21'; // use your oracle db password
    const con_string = 'lab';


    public function __construct()
    {
        try {
            // Create connection with the command oci_connect(String(username), String(password), String(connection_string))
            $this->_conn = oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );

            //check if the connection object is != null
            if (!$this->_conn) {
                // die(String(message)): stop PHP script and output message:
                die("DB error: Connection can't be established!");
            }

        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }

    public function getData( $limit = 20, $page = 1 ) {

        $this->_limit   = $limit;
        $this->_page    = $page;

        if ( $this->_limit == 'all' ) {
            $query      = $this->_query;
        } else {
            $query      = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
        }
        $rs             = $this->_conn->query( $query );

        while ( $row = $rs->fetch_assoc() ) {
            $results[]  = $row;
        }

        $result         = new stdClass();
        $result->page   = $this->_page;
        $result->limit  = $this->_limit;
        $result->total  = $this->_total;
        $result->data   = $results;

        return $result;
    }


}