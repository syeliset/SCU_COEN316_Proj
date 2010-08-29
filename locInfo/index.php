<?php
require_once('mysql.php');

	$config = parse_ini_file('config.ini', TRUE);
	
	$db = new mysql();
	$hostname = $config['database']['server'];
	$userName = $config['database']['username'];
	$password = $config['database']['password'];
	$dbName = 	$config['database']['database'];
	$tableName = $config['database']['tableName'];
	$debug = $config['settings']['debug'];
	
	if ($db->_connect($hostname, $userName, $password, $dbName))
	{
		if ($debug) echo "Connected successfully.<br>\n";
	}
	else 
	{
		die ("Failed to connect to database. mySql error: ".mysql_errno()." : ".mysql_error());
	}

	if ($debug) echo "REQUEST_METHOD is: ".$_SERVER['REQUEST_METHOD'].'<br>\n';


	if ($_SERVER['REQUEST_METHOD']=='GET')
	{
		if ($debug) echo "QUERY_STRING is: ".$_SERVER['QUERY_STRING'].'<br>';
		
		parse_str($_SERVER['QUERY_STRING'],$params);

		$fbUserName = $params['fbUserName'];
		$locInfoSession = $params['locInfoSession'];
		$callback = $params['callback'];
		
		if ($debug)
		{
			echo "fbUserName is: $fbUserName <br>";
			echo "locInfoSession is: $locInfoSession <br>";
		}

		if (!$locInfoSession)
		{
			if ($debug) echo "Fetching latest locInfo for user: $fbUserName ... <br>";
			$queryResult=$db->getLatestLocInfo($tableName, $fbUserName);

			if ($queryResult != false)
			{
				if ($debug) echo "SUCCESS!!!";
				$latestInfo = mysql_fetch_assoc($queryResult);
				
				$jsonString = json_encode($latestInfo);
				
				if ($callback)
					echo $callback.'('.$jsonString. ')';
				else	
					echo $jsonString;
			}
			else
			{
				echo "No results found.";//mySql error: ".mysql_errno()." : ".mysql_error();
			}
		}
		else
		{
			if ($locInfoSession == "ALLSESSIONS")
			{
				if ($debug) echo "Fetching ALLSESSIONS for user: $fbUserName ...<br>";
	
				$queryResult=$db->getUserSessions($tableName, $fbUserName);
				if ($queryResult != false)
				{
					if ($debug) echo "SUCCESS!!!\n";
	
					$allRows = array();
					while ($row = mysql_fetch_assoc($queryResult))
					{
						array_push($allRows, $row);
					}
	
					$jsonString = json_encode($allRows);
	
					if ($callback)
						echo $callback.'('.$jsonString. ')';
					else	
						echo $jsonString;
				}
				else
				{
					echo "No results found.";//mySql error: ".mysql_errno()." : ".mysql_error();
				}
				
			}
			else 
			{
				$whereClause = "locInfoSession='$locInfoSession' order by TIMESTAMP";
		
				if ($debug) echo "Fetching latest locInfos for user: $fbUserName and sessionId: $locInfoSession ...<br>";
	
				$queryResult=$db->getWhere($tableName, $whereClause);
				if ($queryResult != false)
				{
					if ($debug) echo "SUCCESS!!!\n";
	
					$allRows = array();
					while ($row = mysql_fetch_assoc($queryResult))
					{
						array_push($allRows, $row);
					}
	
					$jsonString = json_encode($allRows);
	
					if ($callback)
						echo $callback.'('.$jsonString. ')';
					else	
						echo $jsonString;
				}
				else
				{
					echo "No results found.";//mySql error: ".mysql_errno()." : ".mysql_error();
				}
			}
		}
	}
	elseif ($_SERVER['REQUEST_METHOD']=='POST')
	{
		$rawPost = $GLOBALS['HTTP_RAW_POST_DATA'];
		$jsonData = json_decode($rawPost, true);
		$retVal = array ("status" => "FAILED");
		if ($jsonData)
		{
			$names = '';
			$values = '';
			foreach($jsonData as $name => $value)
			{
				$names .= $name.',';
				$values .= '\''.$value.'\''.',';
			}
			
			$names = substr($names, 0, strlen($names)-1); //remove extra ',' at the end;
			$values = substr($values, 0, strlen($values)-1); //remove extra ',' at the end;
			
			$names = 'OID,'.$names;
			$values = '\'0\','.$values;

			$queryExecResult = $db->insertRow($tableName, $names, $values);

			if ($queryExecResult)
			{
				if ($debug) echo "SUCCESS!!!";
				$retVal['status']="SUCCESS";
			}
			else
			{
				if ($debug) echo "Insert failed. mySql error: ".mysql_errno(). " : ".mysql_error()."\n";
				$retVal['reason']="mySql error: ".mysql_errno(). " : ".mysql_error();
			}
		}
		else
		{
			if ($debug) echo "\nInvalid POST data received. Expecting JSON. json_decode returned NULL.  Received: \n".$rawPost."\n<br>";
			$retVal['reason']="Invalid POST data received. Expecting JSON. json_decode returned NULL.  Received: ".$rawPost;
		}
		
		echo json_encode($retVal);
	}
		
	$db->close();

//require_once('phprestsql.php');

//$PHPRestSQL =& new PHPRestSQL();
//$PHPRestSQL->exec();

/*
echo '<pre>';
var_dump($PHPRestSQL->output);
//*/

?>