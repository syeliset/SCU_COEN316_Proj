<?php
require_once 'mysql.php';

	$config = parse_ini_file('config.ini', TRUE);
	
	$db = new mysql();

	$hostname = $config['database']['server'];
	$userName = $config['database']['username'];
	$password = $config['database']['password'];
	$dbName = 	$config['database']['database'];
	$tableName = $config['database']['tableName'];
	$debug = $config['settings']['debug'];
	//echo "<html><head><title>LocInfo Table Viewer</title></head>";
	//echo "<body>";
	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
	{
		$config['database']['username'] = $_SERVER['PHP_AUTH_USER'];
		$config['database']['password'] = $_SERVER['PHP_AUTH_PW'];

		$user = $config['database']['username'];
		//echo "user: $user";
		
        if (!$db->connect($config['database']))
        {
        	unauthorized();
            exit;
        }
	}
	else
	{
		unauthorized();
		exit;
	}
	echo "Successfully connected to database: $database at host: $hostname as user: $user<br>";
	
	echo "Retrieving info from table: $tableName<br>";
	// sending query
	$result = $db->getTable($tableName);
	if (!$result)
	{
	    die("Query to select data from table $tableName failed. Check tablename.<br>\n");
	}
	
	$fields_num = mysql_num_fields($result);
	
	echo "<h1>Table: {$table}</h1>";
	echo "<table border='1'><tr>";
	// printing table headers
	for($i=0; $i<$fields_num; $i++)
	{
	    $field = mysql_fetch_field($result);
	    echo "<td>{$field->name}</td>";
	}
	echo "</tr>\n";
	
	// printing table rows
	while($row = mysql_fetch_row($result))
	{
	    echo "<tr>";
	
	    // $row is array... foreach( .. ) puts every element
	    // of $row to $cell variable
	
	    foreach($row as $cell)
	    {
	    	echo "<td>$cell</td>";
	    }
	
	    echo "</tr>\n";
	}
	mysql_free_result($result);

    /**
     * Send a HTTP 401 response header.
     */
    function unauthorized($realm = 'coen316 location services') {
        header('WWW-Authenticate: Basic realm="'.$realm.'"');
        header('HTTP/1.0 401 Unauthorized');
    }

echo "</body></html>"

?>
