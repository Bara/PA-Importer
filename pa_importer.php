<?php

	// servers to port
	$servers = array(
		"0.0.0.0:25015", // interface/steamworks fail?
		"1.2.3.4:25015" // old server ip:po
	);

	$host      = "";
	$port      = 3306;
	$database  = "";
	$user      = "";
	$pass      = "";
	
	$newHost      = "";
	$newPort      = 3306;
	$newDatabase  = "";
	$newUser      = "";
	$newPass      = "";

	$mysqli = new mysqli($host, $user, $pass, $database, $port);

	if (mysqli_connect_errno())
	{
		printf("<strong><font color='red'>Connect failed: %s\n</font></strong>", mysqli_connect_error());
		exit();
	}

	foreach ($servers as $server)
	{
		echo "Server: " . $server . "\n"; // just for debugging

		$query = "SELECT * FROM player_analytics WHERE server_ip = \"$server\"";

		if ($result = $mysqli->query($query))
		{
			while ($row = $result->fetch_object())
			{
				$id = $row->id;
				$server_ip = $row->server_ip;
				$name = $row->name;
				$auth = $row->auth;
				$connect_time = $row->connect_time;
				$connect_date = $row->connect_date;
				$connect_method = $row->connect_method;
				$numplayers = $row->numplayers;
				$map = $row->map;
				$duration = $row->duration;
				$flags = $row->flags;
				$ip = $row->ip;
				$city = $row->city;
				$region = $row->region;
				$country = $row->country;
				$country_code = $row->country_code;
				$country_code3 = $row->country_code3;
				$html_motd_disabled = $row->html_motd_disabled;
				$os = $row->os;

				$newmysqli = new mysqli($newHost, $newUser, $newPass, $newDatabase, $newPort);

				if (mysqli_connect_errno())
				{
					printf("<strong><font color='red'>Connect failed: %s\n</font></strong>", mysqli_connect_error());
					exit();
				}

				$preinsertQuery = "INSERT INTO player_analytics (server_ip, name, auth, connect_time, connect_date, connect_method, numplayers, map, duration, flags, ip, city, region, country, country_code, country_code3, html_motd_disabled, os) VALUES (\"$server_ip\", \"$name\", \"$auth\", \"$connect_time\", \"$connect_date\", \"$connect_method\", \"$numplayers\", \"$map\", \"$duration\", \"$flags\", \"$ip\", \"$city\", \"$region\", \"$country\", \"$country_code\", \"$country_code3\", \"$html_motd_disabled\", \"$os\");";
				$insertQuery = str_replace("0.0.0.0", $host, $preinsertQuery);
				echo $insertQuery . "\n";
				$newmysqli->query($insertQuery);
				$newmysqli->close();

				$deleteQuery = "DELETE FROM player_analytics WHERE id = $id";
				echo $deleteQuery . "\n";
				$mysqli->query($deleteQuery);
			}
		}
		$result->close();
	}

	$mysqli->close();
?>