<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* token admin: tmin v1.0
*
* 1. delete old signin tokens
*   
* * script can be ran manually as an admin task or automated via php shed
*
*/

/* access to crowdcc db * crowdcc_signin */	

require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php'); // crowdcc_signin

date_default_timezone_set("UTC");

global $timelocal;

$done = 0;  

// 1.
	
	$eraser = 1;

	if ($d_stmt02 = $mysqli->prepare("DELETE FROM signin_token WHERE eraser = ?")) {
		$d_stmt02->bind_param('i', $eraser);
		log_error($timelocal,'bind_param', $d_stmt02, $mysqli); 
		$d_stmt02->execute();                                    //  execute the prepared query.
		log_error($timelocal,'execute', $d_stmt02, $mysqli);
		$d_stmt02->close();
		$done = 1;
												
	} else {

		log_error($timelocal,'prepare', $d_stmt02, $mysqli);
		$d_stmt02->close();
													      	 
	};


	if ($d_stmt03 = $mysqli->prepare("DELETE FROM signin_token WHERE timeissued < (DATE(NOW() - INTERVAL 1 DAY))")) {
		log_error($timelocal,'bind_param', $d_stmt03, $mysqli); 
		$d_stmt03->execute();                                    //  execute the prepared query.
		log_error($timelocal,'execute', $d_stmt03, $mysqli);
		$d_stmt03->close();
		$done = 2;										
	} else {

		log_error($timelocal,'prepare', $d_stmt03, $mysqli);
		$d_stmt03->close();
													      	 
	};

	if ($done == 2) {print('token admin completed ...');};
	
