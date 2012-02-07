<?php

/*
opens a connection to the database and returns a handle to it.
*/
class DBLayer {

	var $dbHandle;			// the handle to the opened connection
	var $resultSet;			// the result of the last query
	var $resultCount;		// the count of rows returned or affected
	var $lastInsertedId;	// the last inserted ID
	var $columnInfo;		// information about the selected columns 
	var $resultRows;		// the result itself
	
	var $server;
	var $database;
	var $user;
	var $password;
	var $initialized = false;
	
	/*
	constructs a new DBLayer object and selects the given database. The handle 
	to the opened connection is stored in dbHandle.
	*/
	function DBLayer($server, $database, $user, $password) {
		$this->server = $server;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
	}
	
	function initialize() {
		$this->dbHandle = @mysql_connect($this->server, $this->user, $this->password);
		if (!$this->dbHandle) 
			die("<span class=\"error\">Sorry pal, can't connect to the " .
				"database.\n" . mysql_error());
				
		$this->selectDatabase($this->database);
		$this->initialized = true;
	}
	
	function finalize() {
		if ($this->dbHandle) {
			@mysql_close($this->dbHandle);
		}
	}
	
	
	/*
	selects the given Database as the working database.
	*/
	function selectDatabase($database) {
		if (!@mysql_select_db($database, $this->dbHandle)) 
			die("<span class=\"error\">Can't select database '$database'.\n" .
				mysql_error());
	}
	
	/*
	escapes a string to be formatted correctly
	*/
	function escape($string) {
		return addslashes( $string ); // Disable rest for now, causing problems
		if( !$this->dbHandle || version_compare( phpversion(), '4.3.0' ) == '-1' )
			return mysql_escape_string( $string );
		else
			return mysql_real_escape_string( $string, $this->dbHandle );
	}
	
	/*
	unescapes a string - don't know why they (I mean the PHP guys) didn't think
	about this
	*/
	function unescape($s) {
		
		$sl=strlen($s);	 
		$s2 = "";
		for ($a=0;$a<$sl;$a++) {
			$c=substr($s,$a,1);	 
			if ($c == "\\") {
				
				$tmp = substr($s,$a+1,1);
				switch ($tmp) {
					case "0":  
						$c="";	
						break;	

					case "n":  
						$c="n";	 
						break;	

					case "t":  
						$c="t";	 
						break;	
						  
					case "r":  
						$c="r";	 
						break;	

					case "b":  
						$c="b";	 
						break;	
	  
					case "'":  
						$c="'";	 
						break;	

					case '"':  
						$c='"';	 
						break;	

					case "":  
						$c="";	
						break;	

					case "%":  
						$c="%";	 
						break;	

					case "_":  
						$c="_";	 
						break;
						 
					case "\\":
						$c = "";
						break; 

					default:  
						echo("unhandled exception! $tmp<br>");
				}
				$a++;
			}
			$s2 .= $c; 
			
		}

		return $s2;
	}
	
	/*
	queries the database.
	*/
	function query($sql) {
		$returnval = 0;
		
		if (!$this->initialized) {
			$this->initialize();
		}
		
		$this->resultSet = @mysql_query($sql, $this->dbHandle);
		
		// If there is an error then take note of it..
		if ( mysql_error() ) {
			die("<span class=\"error\">Running the query returned an error:\n" .
				mysql_error());
		}
		
		if ( preg_match("/^\\s*(insert|delete|update|replace) /i", $sql) ) {
			
			// the query is a query that doesn't return a resultset, but the
			// number of records affected.
			$this->resultCount = mysql_affected_rows();
			
			// if we made an insert or a replace we also take note of the last
			// inserted id
			if ( preg_match("/^\\s*(insert|replace) /i",$sql) ) {
				$this->lastInsertedId = mysql_insert_id($this->dbHandle);	
			}
			
			$return_val = $this->resultCount;
		} else {
			$i = 0;
			while ($i < @mysql_num_fields($this->resultSet)) {
				$this->columnInfo[$i] = @mysql_fetch_field($this->resultSet);
				$i++;
			}
			$num_rows = 0;
			while ( $row = @mysql_fetch_object($this->resultSet) ) {
				   $this->resultRows[$num_rows] = $row;
				$num_rows++;
			}
	
			@mysql_free_result($this->resultSet);
			$this->resultCount = $num_rows;
			
			$returnval = $num_rows;
		}
		
		return $returnval;
		
	}
	
}

$conn = new DBLayer(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);


?>