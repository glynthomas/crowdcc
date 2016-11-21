<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* session admin: smin v1.00.00
*
* 1. clears out old redundant sessions, where the user did not signout or
*    and the gc task has not captured the old redundant sessions.
*
* 2. delete old signin attempts.
*
* * script can be ran manually as an admin task or automated via php shed
*
*/

/* access to crowdcc db * crowdcc_sessions * crowdcc_signin */	

require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.sess.conn.php'); // crowdcc_sess
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');      // crowdcc_signin

date_default_timezone_set("UTC");

global $timelocal; 

$done = 0;

// 1.

	$max = 2 * 60 * 60;											 // - 2 hours ago ...

    $max_time = time() - $max;

	if ($d_stmt01 = $mysqli_sess->prepare("DELETE FROM sessions WHERE set_time < ?")) {
		$d_stmt01->bind_param('i', $max_time);
		log_error_sess($timelocal,'bind_param', $d_stmt01, $mysqli_sess); 
		$d_stmt01->execute();                                    //  execute the prepared query.
		log_error_sess($timelocal,'execute', $d_stmt01, $mysqli_sess);
		$d_stmt01->close();
		$done = 1;
												
	} else {

		log_error_sess($timelocal,'prepare', $d_stmt01, $mysqli_sess);
		$d_stmt01->close();
													      	 
	};


// 2.

	if ($d_stmt04 = $mysqli->prepare("DELETE FROM signin_attempts WHERE eraser = ?")) {
		$d_stmt04->bind_param('i', $eraser);
		log_error($timelocal,'bind_param', $d_stmt04, $mysqli); 
		$d_stmt04->execute();                                    //  execute the prepared query.
		log_error($timelocal,'execute', $d_stmt04, $mysqli);
		$d_stmt04->close();
		$done = 2;
												
	} else {

		log_error($timelocal,'prepare', $d_stmt04, $mysqli);
		$d_stmt04->close();
													      	 
	};

	if ($done == 2) {print('session admin completed ...');};


?>
	