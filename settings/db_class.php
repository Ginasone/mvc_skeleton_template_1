<?php
//database

//database credentials
require_once('db_cred.php');

/**
 *@author David Sampah
 *@version 1.2 - Fixed type annotations and connection handling
 */
class db_connection
{
	
	//properties
	public $db = null;
	public $results = null;

	//connect
	/**
	*Database connection
	*@return boolean
	**/
	function db_connect(){
		
		//connection
		$this->db = mysqli_connect(SERVER,USERNAME,PASSWD,DATABASE);
		
		//test the connection
		if (mysqli_connect_errno()) {
			return false;
		}else{
			return true;
		}
	}

	/**
	*Get database connection
	*@return mysqli|false
	**/
	function db_conn(){
		
		//connection
		$this->db = mysqli_connect(SERVER,USERNAME,PASSWD,DATABASE);
		
		//test the connection
		if (mysqli_connect_errno()) {
			return false;
		}else{
			return $this->db;
		}
	}


	//execute a query
	/**
	*Query the Database
	*@param string $sqlQuery - SQL query string
	*@return boolean
	**/
	function db_query($sqlQuery){
		
		// Ensure we have a connection
		if ($this->db == null) {
			if (!$this->db_connect()) {
				return false;
			}
		}

		//run query 
		$this->results = mysqli_query($this->db, $sqlQuery);
		
		if ($this->results == false) {
			// Log the error for debugging
			error_log("Database query failed: " . mysqli_error($this->db) . " | Query: " . $sqlQuery);
			return false;
		}else{
			return true;
		}
	}

	//execute a query with mysqli real escape string
	//to safeguard from sql injection
	/**
	*Query the Database with escaped string
	*@param string $sqlQuery - SQL query string
	*@return boolean
	**/
	function db_query_escape_string($sqlQuery){
		
		// Ensure we have a connection
		if ($this->db == null) {
			if (!$this->db_connect()) {
				return false;
			}
		}
		
		//run query 
		$this->results = mysqli_query($this->db, $sqlQuery);
		
		if ($this->results == false) {
			// Log the error for debugging
			error_log("Database query failed: " . mysqli_error($this->db) . " | Query: " . $sqlQuery);
			return false;
		}else{
			return true;
		}
	}

	//fetch a data
	/**
	*get select data
	*@param string $sql - SQL query string
	*@return array|false - a record or false on failure
	**/
	function db_fetch_one($sql){
		
		// if executing query returns false
		if(!$this->db_query($sql)){
			return false;
		} 
		//return a record
		return mysqli_fetch_assoc($this->results);
	}

	//fetch all data
	/**
	*get select data
	*@param string $sql - SQL query string
	*@return array|false - all records or false on failure
	**/
	function db_fetch_all($sql){
		
		// if executing query returns false
		if(!$this->db_query($sql)){
			return false;
		} 
		//return all record
		return mysqli_fetch_all($this->results, MYSQLI_ASSOC);
	}


	//count data
	/**
	*get select data count
	*@return int|false - count of records or false on failure
	**/
	function db_count(){
		
		//check if result was set
		if ($this->results == null) {
			return false;
		}
		elseif ($this->results == false) {
			return false;
		}
		
		//return a record count
		return mysqli_num_rows($this->results);

	}
	
}
?>