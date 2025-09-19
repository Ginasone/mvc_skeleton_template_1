<?php
require_once('db_cred.php');

class db_connection
{
    public $db = null;
    public $results = null;

    function db_connect(){
        $this->db = mysqli_connect(SERVER, USERNAME, PASSWD, DATABASE);
        if (mysqli_connect_errno()) {
            return false;
        } else {
            return true;
        }
    }

    function db_conn(){
        $this->db = mysqli_connect(SERVER, USERNAME, PASSWD, DATABASE);
        if (mysqli_connect_errno()) {
            return false;
        } else {
            return $this->db;
        }
    }

    function db_query($sqlQuery){
        if ($this->db == null) {
            if (!$this->db_connect()) {
                return false;
            }
        }
        $this->results = mysqli_query($this->db, $sqlQuery);
        if ($this->results == false) {
            return false;
        } else {
            return true;
        }
    }

    function db_fetch_one($sql){
        if(!$this->db_query($sql)){
            return false;
        } 
        return mysqli_fetch_assoc($this->results);
    }

    function db_fetch_all($sql){
        if(!$this->db_query($sql)){
            return false;
        } 
        return mysqli_fetch_all($this->results, MYSQLI_ASSOC);
    }

    function db_count(){
        if ($this->results == null || $this->results == false) {
            return false;
        }
        return mysqli_num_rows($this->results);
    }
}
?>