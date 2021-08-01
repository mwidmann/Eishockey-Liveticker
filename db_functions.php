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
		$this->dbHandle = mysqli_init();
		mysqli_real_connect($this->dbHandle, $this->server, $this->user, $this->password);
		if (!$this->dbHandle)
			die("<span class=\"error\">Sorry pal, can't connect to the " .
				"database.\n" . mysqli_connect_error());

		$this->selectDatabase($this->database);
		$this->initialized = true;
	}

	function finalize() {
		if ($this->dbHandle) {
			@mysqli_close($this->dbHandle);
		}
	}


	/*
	selects the given Database as the working database.
	*/
	function selectDatabase($database) {
		if (!@mysqli_select_db($this->dbHandle, $database))
			die("<span class=\"error\">Can't select database '$database'.\n" . mysqli_error($this->dbHandle));
	}

	/*
	escapes a string to be formatted correctly
	*/
	function escape($string) {

		return mysqli_real_escape_string($this->dbHandle, $string);
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

		$this->resultSet = @mysqli_query($this->dbHandle, $sql);

		if ( preg_match("/^\\s*(insert|delete|update|replace) /i", $sql) ) {

			// the query is a query that doesn't return a resultset, but the
			// number of records affected.
			$this->resultCount = mysqli_affected_rows($this->dbHandle);

			// if we made an insert or a replace we also take note of the last
			// inserted id
			if ( preg_match("/^\\s*(insert|replace) /i",$sql) ) {
				$this->lastInsertedId = mysqli_insert_id($this->dbHandle);
			}

			$return_val = $this->resultCount;
		} else {
			$num_rows = 0;
			while ( $row = @mysqli_fetch_object($this->resultSet) ) {
				$this->resultRows[$num_rows] = $row;
				$num_rows++;
			}

			@mysqli_free_result($this->resultSet);
			$this->resultCount = $num_rows;

			$returnval = $num_rows;
		}

		return $returnval;

	}

}

$conn = new DBLayer(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);


?>