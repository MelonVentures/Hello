<?php
class dbFAQ{
	//var $dbhost = 'localhost';
	//var $dbuser = 'monetize_faq';
	//var $dbpass = 'V9HGuTd(vWpF';
	//var $dbname = 'monetize_faq';
	var $dbhost = 'localhost';
	var $dbuser = 'root';
	var $dbpass = '';
	var $dbname = 'monetize_faq';
	public $conn;
	function connect(){
		$this->conn = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass,$this->dbname);
		if(! $this->conn ) {
			die('Could not connect: ' . mysqli_error());
		}
		return $this->conn;
	}
	function close(){
		return mysqli_close($this->conn);
	}
}

?>