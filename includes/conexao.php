<?php

class Conecta{
	
	public $con;
	
	function __construct(){
		//$this->con = mysql_connect ("localhost", "root", "");
		$this->con = mysql_connect("mysql.clica.ai", "clica_ai", "clica!@#");
		mysql_select_db("db_clica", $this->con);
	}
	
	function close(){
		mysql_close($this->con);
	}
	
}

?>