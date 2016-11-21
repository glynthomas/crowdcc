<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* access token management
*
* notes: crowdcc_signin * regist_members * field 8 salt char(128) * removed for upgrade compatibility library with PHP 5.5's simplified password hashing API.
*
*/

/* load crowdcc app.error ( error handle ) && app.functions ( general app functions ) */
require_once('ccpath.php');

/* load crowdcc err handle */
//require_once('db/errorhandle.php');

//require_once('crypt/RSA.php');

define("KEY_PRIVATE", "-----BEGIN RSA PRIVATE KEY----- 
MIIBOgIBAAJBAIZPnO71UhxLWgDlVAJTkKX4SK7rtPw+fRz7dB8iq4ULEbx6uJrJ
AY0yOKpB36uEjI8kun5DfUFDLu8b8nidfg8CAwEAAQJAAbt/0rU8seYRlcwKIV2N
PWwjw93WdR/OjVPQ/ksm2zhxrvdq/NFhkHOT5owniwZn1i469CgIcp+yg2hWyelW
wQIhAItA9dgKEdDLM5MGbDbIpH6/LChT/whhP3BfRvWxpFi1AiEA9unSnq1F0BoT
8Oh2IxPzYGkv8yiOKpTd2RQ3p0z9yjMCIBvrhl8uhavrUgfkfcXuLK0M/3mGfdfc
R6/sKnoQh/cRAiEAyFyTkjmvJiCTP/GTNAHTg8+3nkyxmLI2mBoE01jxtOsCIBUO
lpientwfH+sLxnELlZHa3KIA4qxHMm2jAAX5oJB8 
-----END RSA PRIVATE KEY-----");


/* load required lib files : a compatibility library with PHP 5.5's simplified password hashing API. */
require_once($_SERVER["DOCUMENT_ROOT"].'/../lib/password.php');

/* access to crowdcc signin db */ 
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

/* found log * log failure outside the JS console ( please comment out / remove later ) */
/* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */

/* phpmailer lib * auth mail lib files * mailresetlink($to, $token_send) * see ccmail app.error.php */
require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');

global $timelocal;

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

	  case ('POST'):
  			$post_in = $_POST['_ccc'];
			$post_len = strlen( $post_in );

			/*
	  		print_r( $post_in );
	  		print_r('|');
	  		print_r( $post_len );
			print_r('|');
	  		print_r( $token_in );  	
	  		print_r('|');
	  		print_r( $pcode_in );  	
	  		print_r('|');
	  		print_r('thats all she wrote');
	  		exit();
	  		*/

	  		$post_clean = filter_var( $post_in ,  FILTER_SANITIZE_STRING);				   // $scode_clean (any problems chars stripped out)

	  		/* validation checks */

	   		switch (true) {

	   		  case ($post_in !== $post_clean):
	    	        // print_r('data tamper ->' . $pltfrm_clean);
	    	        rtnwebapp('error' , 'error_tamper' , 'post');						   // test for failure	
	    	  break;

	   		}

			/*
			
			The stored token is posted back for verification, we can check;
			
			* it unencypts correctly using the private key (not yet deployed)
			* the email address unencrypts correctly and matches the stored email address in the db
			* the age of the token (using the issuedate)
			
			once all these tests are passed, the db can be updated with the new password and the success message code
			returned (json_encoded) back to the user. 

			*/

			/* $token_in  = $_POST['ecode']; */
			/* $token_in = substr( $post_in , 0, 128); */
			$token_in = substr( $post_in , 0, 84);
			/* log_found('verify', $token_in, '$token_in', __LINE__ ); */

			/* $pcode_in  = $_POST['pcode']; */
			/* $pcode_in = substr( $post_in, 128, $post_len); */
			$pcode_in = substr( $post_in, 84, $post_len);
			/* log_found('verify', $pcode_in, '$pcode_in', __LINE__ ); */
			
			$pcode_in  = decrypt($pcode_in);
			/* log_found('verify', $pcode_in, '$pcode_in_decrypt', __LINE__ ); */

			/* $email_out = getstrmsg(78, $token_in, 'e'); // email in string revealed */

			$email_out = getstrmsg($token_in, 'e');
		
			/* $email_out = 'glynfoo@gmailcom'; */

			/* email unencrypt check, is it valid, return true or false and quit db look up (no additional server cost) */
			$email_chk = valid_email($email_out);

			switch(true) {

				case($email_chk):
					// db check stored email in token matches db record
					/* Load required lib files. */
					// require_once('db/db_config.php');
					// require_once('db/db_lib.php');
					// $oDB = new db;
					// current user (exists) query / check

					$s_stmt01 = $mysqli->prepare("SELECT tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser FROM signin_token WHERE tokenstore = ? and used=0 LIMIT 1");
					$s_stmt01->bind_param('s', $token_in);    //  Bind "$sncode" to parameter.
					$s_stmt01->execute();                     //  Execute the prepared query.
					$s_stmt01->store_result();  
					$s_stmt01->bind_result($db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);      // get variables from result.
					$s_stmt01->fetch();
					
					// $query = "SELECT * FROM signin_token WHERE tokenstore = '$token_in' and used=0 LIMIT 1";
					// $result = $oDB->select($query);

					switch(true) {
						// case ($result->num_rows > 0):
						case ($s_stmt01->num_rows > 0):
						      $s_stmt01->close();
				 			  /*
				 			  while ($row = mysqli_fetch_assoc($result)) {
				 					 $db_token_send	        = $row["tokensend"];
				 				    // $db_token_store	    = $row["tokenstore"]; 
				 					 $ccc_token 		    = $row["tokenstore"];
		  	   	 					 $db_email	        	= $row["email"];
		  		 					 $db_timeissued      	= $row["timeissued"];	
		  		 					 $db_used 				= $row["used"];
		  		 			  }
		  		 			  */

		  		 			  $current_time = time();
		  		 			  $time_chk = abs(strtotime($db_timeissued) - $current_time);

					 // test for failure -> $db_timeissued > $dextime

		  		 			  switch(true) {
		  		 				 // case(abs(strtotime($db_timeissued) - $current_time) > 10800):     // token is 3 hours (from UTC) old, expire token
		  		 				 case(abs(strtotime($db_timeissued) - $current_time) > 21600):        // token is 6 hours (from UTC) old, expire token

		  		 				 																   	  // 10800 3 hours, 21600 6 hours  - verify received token link from email timeout
		  		 				 	  // echo json_encode('error_pc0de');                             // token is older than 6 hours old! -> erro_pc0de ;
		  		 				 	  rtnwebapp('error_pc0de', $ccc_token, 'post');
		  		 				 	  // delete record ...
		  		 				 break;

 					 // test for failure -> $db_email not found

		  		 				 case($db_email_current == ''):
		  		 					  // echo json_encode('email record not found / is bad');
		  		 					  // echo json_encode('error_pc2de');
		  		 					  rtnwebapp('error_pc2de', $ccc_token, 'post'); 
		  		 				 break;
		  		 			  }

		  				break;

		  				// case ($result->num_rows == 0):
		  				case ($s_stmt01->num_rows == 0):
		  					  $s_stmt01->close();
		  					  // echo json_encode('invalid link or password already changed')
		  				      // echo json_encode('error_pc1de');
		  				      rtnwebapp('error_pc1de', $ccc_token, 'post');
		  				      // delete record ...
		  				break;

					}

				break;

				case(!$email_chk):
					 // echo json_encode('the email record not found / is bad');
					 // echo json_encode('error_pc2de');
					 rtnwebapp('error_pc2de', $ccc_token, 'post'); 
				break;

			}

			// all tests for failure are complete ... if we get to here then we are good to go ...

		/* if ($s_stmt02 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) { */
		if ($s_stmt02 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) {	
			$s_stmt02->bind_param('s', $email_out);                 //  Bind "$email_out" to parameter.
			log_error($timelocal,'bind_param', $s_stmt02, $mysqli); 
			$s_stmt02->execute();                                   //  Execute the prepared query.
			log_error($timelocal,'execute', $s_stmt02, $mysqli);
			$s_stmt02->store_result();  
			/* $s_stmt02->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
			$s_stmt02->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);     // get variables from result.
			$s_stmt02->fetch();

		} else {

	 		log_error($timelocal,'prepare', $s_stmt02, $mysqli);
	        $s_stmt02->close();
      	}			


			// $query = "SELECT * FROM users WHERE email = '$email_out' LIMIT 1";
			// $result = $oDB->select($query);


			switch(true) {
				  // case ($result->num_rows > 0):
					case ($s_stmt02->num_rows > 0):
					      $s_stmt02->close();
				    /*

						while ($row = mysqli_fetch_assoc($result)) {
					    $db_from_screen_name	 = $row["uname"];
					    $db_from_user_email	     = $row["email"];
					    $db_from_passcode		 = $row["passcode"];
		  	   	  		$db_from_user_uid        = $row["uid"];
		  		  		$db_access_token         = $row["oauth_token"];
		  		  		$db_access_token_secret  = $row["oauth_token_secret"];
		  		  		}
		  		  		$field_values =   'uname = "' . $db_from_screen_name . '", ' .
						                  'email = "' . $db_from_user_email. '", ' .	  			
 										  'passcode = "' . $pcode_in . '", ' .
 										  'uid = "'  . $db_from_user_uid . '", ' .
      									  'oauth_token = "' . $db_access_token . '", ' .
    	                                  'oauth_token_secret = "' . $db_access_token_secret . '"' ;
    	            */

    	            /* hash pcode before inserting into DB */

    	            /* old method * create a random salt * hash password with random salt * store salt for comparison check */

    	            /* $pcode_in = hash('sha512', $pcode_in.$db_random_salt); */

    	            $pcode_in = password_hash($pcode_in, PASSWORD_BCRYPT, array("cost" => 11)); /* default is cost 10 */ 

					/* if  ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
				    if  ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {	
      					 /* $i_stmt01->bind_param('isssssssssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $pcode_in, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
						 $i_stmt01->bind_param('issssssssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $pcode_in, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);
						 log_error($timelocal,'bind_param', $i_stmt01, $mysqli); 
	 					 // Execute the prepared query.
	             		 $i_stmt01->execute();
	              		 log_error($timelocal,'execute', $i_stmt01, $mysqli);
	              		 // $insert_stmt->store_result();
	                     $i_stmt01->close();
	              
      				} else {

      					 log_error($timelocal,'prepare', $i_stmt01, $mysqli);
	              		 $i_stmt01->close();
      				}					


						// $result = $oDB->replace('users', $field_values, 'uname = "'. $db_from_screen_name . '"' );

						//switch(true) {
						//	  case($result != ''):
							  	  // failure db record error update
							  	  // echo json_encode('error updating the users db record');
							  	  // echo json_encode('error_pc3de');
							  	  // rtnwebapp('error_pc3de', $ccc_token, 'post'); 
							  // break;

							  // case($result == ''):
							  	   // success db record updated, continue
							  // break;
						// }
					
						// need to update the token as being used ...

						// $query = "SELECT * FROM signin_token WHERE tokenstore = '$token_in' and used=0 LIMIT 1";
						// $result = $oDB->select($query);

					if  ($s_stmt03 = $mysqli->prepare("SELECT tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser FROM signin_token WHERE tokenstore = ? and used=0 LIMIT 1")) {
						 $s_stmt03->bind_param('s', $token_in);    //  Bind "$sncode" to parameter.
						 log_error($timelocal,'bind_param', $s_stmt03, $mysqli); 
		 				 // Execute the prepared query.
						 $s_stmt03->execute();                     //  Execute the prepared query.
						 log_error($timelocal,'execute', $s_stmt03, $mysqli);
						 $s_stmt03->store_result();  
						 $s_stmt03->bind_result($db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);      // get variables from result.
						 $s_stmt03->fetch();
						
      				} else {

      					 log_error($timelocal,'prepare', $s_stmt03, $mysqli);
	              		 $s_stmt03->close();
      				}					
					
					switch(true) {
							case($s_stmt03->num_rows > 0):
						//		 while ($row = mysqli_fetch_assoc($result)) {
						//    	 $db_token_send	          = $row["tokensend"];
						     //  $db_token_store	   	  = $row["tokenstore"]; 
				 		//		 $ccc_token 		      = $row["tokenstore"];
						//   	 $db_token_email		  = $row["email"];
			  	   	  	//		 $db_timeissued           = $row["timeissued"];
			  		  	//		 $db_used      			  = $row["used"];
			  		  	//		 $db_eraser      	      = $row["eraser"];
			  		  	//		 }
			  		  	//		 $field_values =   'tokensend = "' . $db_token_send . '", ' .
						//                  		   'tokenstore = "' . $ccc_token . '", ' .	  			
 						//				  	 	   'email = "' . $db_token_email . '", ' .
 						//				  		   'timeissued = "'  . $db_timeissued . '", ' .
      					//				  		   'used = "1", ' .
    	                //                  		   'eraser = "1"' ;

						

						// $result = $oDB->replace('signin_token', $field_values);

								$db_used = 1; $db_eraser = 1;

					
							if  ($i_stmt02 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
		      					 $i_stmt02->bind_param('ssssssssssss', $db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
								 log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
			 					 // Execute the prepared query.
			             		 $i_stmt02->execute();
			              		 log_error($timelocal,'execute', $i_stmt02, $mysqli);
			              		 $i_stmt02->store_result();
			                     $i_stmt02->fetch();
			                     $i_stmt02->close();
			                     // success db record updated, continue
			                     // echo json_encode('pass_pcode');
			                     rtnwebapp('pass_pcode', $ccc_token, 'post');

		      				} else {

		      					 log_error($timelocal,'prepare', $i_stmt02, $mysqli);
			              		 $i_stmt02->close();
		      				}					

								 switch(true) {
							  		case($i_stmt02->num_rows == 0):
							  		     $i_stmt02->close();
							  			// failure db record error update
							  			// echo json_encode('error updating the tokenstore db record');
							  			// echo json_encode('error_pc3de');
							  			rtnwebapp('error_pc3de', $ccc_token, 'post' ); 
							  		break;

								 }

							break;

							case($s_stmt03->num_rows == 0):
								 $s_stmt03->close();
								 // echo json_encode('error no tokenstore / timestamp match!');
								 // echo json_encode('error_pc0de');
								 rtnwebapp('error_pc0de',$ccc_token, 'post');
							break;

						}

				  break;

				  case ($s_stmt02->num_rows == 0):
				  	    $s_stmt02->close();
					    // echo json_encode('the email record not found / is bad');
					    // echo json_encode('error_pc2de');
					    rtnwebapp('error_pc2de', $ccc_token, 'post');
				  break;

			}

		break;

		case(empty($_GET['up'])):

			// $token = $_GET['up'];
			// header('Location: ./destroysessions.php');
    		/* Load and clear sessions */
			if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
			session_destroy();
			header('Location: ./');
			exit();
		break;

		case(!empty($_GET['up'])):

			$tokensend = filter_input(INPUT_GET, 'up', FILTER_SANITIZE_STRING);
			$tokensend = htmlspecialchars($tokensend, ENT_COMPAT | ENT_QUOTES | ENT_HTML5, 'UTF-8');

			/* log_found('verify up', $tokensend , '$tokensend', __LINE__ ); */

			$dextime = getstrmsg($tokensend, 't');
			$dextime = hexdec($dextime);

			/* log_found('verify up', $dextime , '$dextime', __LINE__ ); */

			$now = time();   		               // compare timestamp to now

			$diff = $now - $dextime;               // unix time diff simple calc ,substract from each other
   			$d = $diff / 86400 % 7;
   			$h = $diff / 3600 % 24;
   			$m = $diff / 60 % 60; 
   			$s = $diff % 60;

   			// echo "send token age --> {$d} days, {$h} hours, {$m} minutes and {$s} secs old! <p>";

			/* Load required lib files. */
			// require_once('db/db_config.php');
			// require_once('db/db_lib.php');
			// $oDB = new db;
			
			// current user (exists) query / check
			// $query = "SELECT * FROM signin_token WHERE tokensend = '$token_send' and used=0 LIMIT 1";
			// $result = $oDB->select($query);

			if  ($s_stmt04 = $mysqli->prepare("SELECT tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser FROM signin_token WHERE tokensend = ? and used=0 LIMIT 1")) {
				 $s_stmt04->bind_param('s', $tokensend);     				//  Bind "$tokensend" to parameter.
				 log_error($timelocal,'bind_param', $s_stmt04, $mysqli); 
				 $s_stmt04->execute();                       				//  Execute the prepared query.
				 log_error($timelocal,'execute', $s_stmt04, $mysqli);
				 $s_stmt04->store_result();
				 $s_stmt04->bind_result($db_tokensend, $db_tokenstore, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);      // get variables from result.
				 $s_stmt04->fetch();


				 // print_r($db_tokensend);
				 // print_r($ccc_tokenstore);
				 // print_r($db_email);
				 // print_r($db_email_new);
			     // print_r($db_timeissued);
			     // print_r($db_used);
			     // print_r($db_eraser);

				 // print_r($s_stmt04);
				 
				 /* fetch values */
    			 // while ($s_stmt04->fetch()) {
        		 // 		printf("%s %s\n", $db_tokensend, $ccc_token, $db_email, $db_timeissued, $db_used, $db_eraser);
    			 // } 
		         // $insert_stmt->store_result();
		         // $i_stmt04->close();
		         // rtnwebapp('error_pc3de', $ccc_token, 'post');

      		} else {

      			 log_error($timelocal,'prepare', $i_stmt04, $mysqli);
	             $s_stmt04->close();
      		}	

      		

				switch(true) {
					case ($s_stmt04->num_rows > 0):
					      $s_stmt04->close();

				 	//	while ($row = mysqli_fetch_assoc($result)) {
				 	//	$db_token_send	        = $row["tokensend"];
				 	    //  $db_token_store	  		= $row["tokenstore"]; 
				 	//	$ccc_token 		        = $row["tokenstore"];
		  	   	 	//	$db_email	        	= $row["email"];
		  		 	//	$db_timeissued      	= $row["timeissued"];	
		  		 	//	$db_used 				= $row["used"];
		  		 	//	$db_eraser      	    = $row["eraser"];
		  		 	//	}

		  		 	//	$field_values =   'tokensend = "' . $db_token_send . '", ' .
					//	                  'tokenstore = "' . $ccc_token . '", ' .	  			
 					//					  'email = "' . $db_token_email . '", ' .
 					//					  'timeissued = "'  . $db_timeissued . '", ' .
      				//					  'used = "1", ' .
    	            //                    'eraser = "1"' ;

			
	    	            switch(true) {
					//  test for failure -> check timestamp is valid and is not expired (more than 30min old)
							case(isvalidtimestamp($dextime)):
							 	// echo json_encode('timestamp is invalid or expired or no token string match!');
							 	// marked for deletion ...
			  		 			// $result = $oDB->replace('signin_token', $field_values);

								if  ($i_stmt02 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      					 			 
									 $db_used = 1; $db_eraser = 1;

      					 			 $i_stmt02->bind_param('ssssssssssss', $db_tokensend, $db_tokenstore, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
						             log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
	 					 			 // Execute the prepared query.
	             		 			 $i_stmt02->execute();
	              		  			 log_error($timelocal,'execute', $i_stmt02, $mysqli);
	              		 			 // $insert_stmt->store_result();
	                     			 $i_stmt02->close();
	                     			 rtnwebapp('error_pc0de', 'error_pc0de', 'get');
      							} else {
      					 			 log_error($timelocal,'prepare', $i_stmt02, $mysqli);
	              		 			 $i_stmt02->close();
      							}	

							break;
							case($h >= 2):
								// 1 - 12 hours link token sent to email timeout
						    	// echo json_encode('timestamp is invalid or expired or no token string match!');
								// marked for deletion ... 

								// $result = $oDB->replace('signin_token', $field_values);


								if  ($i_stmt02 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      					 			 
 									 $db_used = 1; $db_eraser = 1;

      					 			 $i_stmt02->bind_param('ssssssssssss', $db_tokensend, $db_tokenstore, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
						             log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
	 					 			 // Execute the prepared query.
	             		 			 $i_stmt02->execute();
	              		  			 log_error($timelocal,'execute', $i_stmt02, $mysqli);
	              		 			 // $insert_stmt->store_result();
	                     			 $i_stmt02->close();
	                     			 rtnwebapp('error_pc0de', 'error_pc0de', 'get');
      							} else {
      					 			 log_error($timelocal,'prepare', $i_stmt02, $mysqli);
	              		 			 $i_stmt02->close();
      							}

	 						break;
						}

					// test for failure -> $db_timeissued == $dextime

		  		 		switch(false) {

		  		 			case(strtotime($db_timeissued) == $dextime):
		  		 				// echo json_encode('error no tokenstore / timestamp match!');
								
								// marked for deletion ... 
								// $result = $oDB->replace('signin_token', $field_values);
								

		  		 				if  ($i_stmt03 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      					 			 
 									 $db_used = 1; $db_eraser = 1;
      					 			 
      					 			 $i_stmt03->bind_param('ssssssssssss', $db_tokensend, $db_tokenstore, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
						             log_error($timelocal,'bind_param', $i_stmt03, $mysqli); 
	 					 			 // Execute the prepared query.
	             		 			 $i_stmt03->execute();
	              		  			 log_error($timelocal,'execute', $i_stmt03, $mysqli);
	              		 			 // $insert_stmt->store_result();
	                     			 $i_stmt03->close();
	                     			 rtnwebapp('error_pc0de', $db_tokenstore, 'get');
      							} else {
      					 			 log_error($timelocal,'prepare', $i_stmt03, $mysqli);
	              		 			 $i_stmt03->close();
      							}					

		  		 			break;

 					        /* test for failure -> $db_email not found */

		  		 			case($db_email_current != ''):
		  		 				 /* echo json_encode('the email record not found / is bad'); */
					 			 rtnwebapp('error_pc2de', $db_tokenstore, 'get');
		  		 			break;
		  		 		}

		  			break;
		  			case ($s_stmt04->num_rows == 0):
		  				  $s_stmt04->close();
						  /* echo json_encode('invalid link or password already changed') */
		  				  rtnwebapp('error_pc1de', $db_tokenstore, 'get');
		  			break;

				}

				/*  
				    all tests for failure are complete ..., create a secure sessionised html page for inserting the new password to the account
				    ensure the session times out, so as not to be displayed for other user to change password to account. 
				*/
				  	 
		/* send (password) update email message to old email address ... if old email address exist to inform user password has been changed ... */
		if ($db_email_past !== $db_email_current) {		  	  
			/* chgpmsg($db_email_past); */
			chgpmsg($db_email_past, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr');
		}

		rtnwebapp('pass_pcode', $db_tokenstore, 'get');

		break;

	}

 

/*  functions  */

	function rtnwebapp($flag = 'pass_pcode', $token, $whofor ) {

	/*  function is passed the following ;
  	 *
  	 *  $to      -> email_address
  	 *  $flag    -> status or error
  	 *  $token   -> token data || status data || error data
  	 *  $whofor  -> post || get
  	 *
 	 */

		// session set / clear
    	// $_SESSION['ccc_token'] = $ccc_token;
		// unset($_SESSION['ccc_token']);
	
		$ccc_token = $token;
		$ccc_msg = $flag;

		$output = array();
		$ceode  = array();

		switch ($whofor) {

			case ('get'):
				  include('html.inc');
				  exit();
			break;

			case ('post'):
				  echo json_encode( $flag . ':*:' . $token );
				  exit();
			break;


		}

		// clean vars (needs to be added to)
		// $query = NULL; $result = NULL;
    	// $db_from_screen_name = NULL;
    	// $db_from_user_uid = NULL;
    	// $db_access_token = NULL;
        // $db_access_token_secret = NULL;
        // $field_values = NULL;
    	// $row = NULL;

	}

    /*
	function chgpmsg($to) {
		$subject = "crowdcc has received a request to update your account password";
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		$message = '
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>crowdcc has received a request to update your account password</title>
		</head>
		<body bgcolor="#FFFFFF" style="-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;">
		<!-- header -->
		<table class="head-wrap" style="max-width: 95%;" width="95%" cellspacing="0" cellpadding="0" border="0" align="center" >
		<tbody>
		<tr>
		<td valign="top">
		<tr>
		<td valign="top" height="30" style="min-height:30px"> </td>
		</tr>
		<tr>
		<td>
		<table cellspacing="0" cellpadding="0" border="0" align="center" style="font-family:\'Helvetica Neue\',sans-serif;border-collapse:collapse">
		<tbody>
		<tr>
		<td valign="top">
		<img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" style="width:135px;height:34px;position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;cursor:pointer;">
		</td>
		<td valign="top" style="padding-top:10px;text-align:right">
		</td>
		</tr>	
        <!-- /header -->
		<tr>
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #E9E9E9" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<h4>Hi</h4>
		<p>
		You recently updated the password associated with your crowdcc account.
		</p>
		To confirm this update, please follow the details in the confirmation message sent to you.
		<p>
		If you didn\'t request this update and believe your crowdcc account has been compromised,
		<p>
		contact crowdcc support by clicking this link:
		</p>
		<p style="padding-bottom:10px;"><a href="'. $uri .'"/~macbook/crowdcc/@hacked" style="color:#3d623b">crowdcc/hacked</a>.</p>		
		<h4>
        <a href="https://twitter.com/crowdcccrowd" style="text-decoration: none; color:#000000;">The crowdcc crowd</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">
		<span>Have a question or just want to say hello? <a href="https://twitter.com/crowdcccrowd" style="color:#2F5BB7;">tweet us</a></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message sent from crowdcc, please don\'t reply directly to this email.</span>
		</td>
		</tr>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<!-- /footer -->
		</table>
		</body>
		</html>
		';
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: hello@crowdcc.com <hello@crowdcc.com>' . "\r\n";
		if(mail($to,$subject,$message,$headers)){
		   // echo json_encode('pass_ecode');
		   // email pass (found in db, pass to token function)
		   // echo "We have sent the password reset link to your email id <b>".$to."</b>";       
		   //  } else {
		   // echo json_encode('fail_ecode');
		   // email pass (found in db, pass to token function), but have failed to be able to send it to
		   // to the email address provided, please try again !
		}
	}
    */


	function chgpmsg($to, $smtpmail, $stmppass) {

	   /* SMTP needs accurate times, and the PHP time zone MUST be set */

        if (date_default_timezone_get() === '') {
            date_default_timezone_set('Europe/London');
        }

		/* Create a new PHPMailer instance */
		$mail = new PHPMailer;

		/* Tell PHPMailer to use SMTP */
		$mail->isSMTP();

		/* Enable SMTP debugging */
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;

		/* Ask for HTML-friendly debug output */
		$mail->Debugoutput = 'html';

		/* Set the hostname of the mail server */
		$mail->Host = 'smtp.gmail.com';
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6

		/* Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission */
		$mail->Port = 587;

		/* Set the encryption system to use - ssl (deprecated) or tls */
		$mail->SMTPSecure = 'tls';

		/* Whether to use SMTP authentication */
		$mail->SMTPAuth = true;

		/* Username to use for SMTP authentication - use full email address for gmail */
		$mail->Username = $smtpmail;

		/* Password to use for SMTP authentication */
		$mail->Password = $stmppass;

	    /* Set who the message is to be sent from */
		$mail->setFrom(' hello@crowdcc.com', 'hello@crowdcc.com');

		/* Set an alternative reply-to address */
		$mail->addReplyTo('noreply@crowdcc.com', 'no reply');

		/* Set who the message is to be sent to */
		// $mail->addAddress($to, $fullname);
		$mail->addAddress($to);

		/* Set the subject line */
		$mail->Subject = 'crowdcc has received a request to update your account password';

		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		
		$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>crowdcc has received a request to update your account password</title>
		</head>
		<body bgcolor="#FFFFFF" style="-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;">
		<!-- header -->
		<table class="head-wrap" style="max-width: 95%;" width="95%" cellspacing="0" cellpadding="0" border="0" align="center" >
		<tbody>
		<tr>
		<td valign="top">
		<tr>
		<td valign="top" height="30" style="min-height:30px"> </td>
		</tr>
		<tr>
		<td>
		<table cellspacing="0" cellpadding="0" border="0" align="center" style="font-family:\'Helvetica Neue\',sans-serif;border-collapse:collapse">
		<tbody>
		<tr>
		<td valign="top">
		<img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" style="width:135px;height:34px;position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;cursor:pointer;">
		</td>
		<td valign="top" style="padding-top:10px;text-align:right">
		</td>
		</tr>	
        <!-- /header -->
		<tr>
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #E9E9E9" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<h4>Hi</h4>
		<p>
		You recently updated the password associated with your crowdcc account.
		</p>
		To confirm this update, please follow the details in the confirmation message sent to you.
		<p>
		If you didn\'t request this update and believe your crowdcc account has been compromised,
		<p>
		contact crowdcc support by clicking this link:
		</p>
		<p style="padding-bottom:10px;"><a href="'. $uri .'"/~macbook/crowdcc/@hacked" style="color:#3d623b">crowdcc/hacked</a>.</p>		
		<h4>
        <a href="https://twitter.com/crowdcccrowd" style="text-decoration: none; color:#000000;">The crowdcc crowd</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">
		<span>Have a question or just want to say hello? <a href="https://twitter.com/crowdcccrowd" style="color:#2F5BB7;">tweet us</a></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message sent from crowdcc, please don\'t reply directly to this email.</span>
		</td>
		</tr>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<!-- /footer -->
		</table>
		</body>
		</html>');

	    /* Replace the plain text body with one created manually */
		$mail->AltBody = 'This is a plain-text message body';

		/* Attach an image file */
		// $mail->addAttachment('images/phpmailer_mini.png');
		
			/* send the message, check for errors */
		if (!$mail->send()) {
 		    // echo "Mailer Error: " . $mail->ErrorInfo;
 		    // echo json_encode('fail_ecode');
		    // email fail (found in db, pass to token function), but have failed to be able to send it to
		    // to the email address provided, please try again !		 									  
		    // } else {
			// echo json_encode('pass_ecode');
		    // email pass (found in db, pass to token function)
		    // echo "We have sent the password reset link to your email id <b>".$to."</b>"; 								  
		}

	}


	function textmute($number, $ocrypt, $arrayin) {

	/*

	1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37 38
	a  b  c  d  e  f  g  h  i  j  k  l  m  n  o  p  q  r  s  t  u  v  w  x  y  z  0  1  2  3  4  5  6  7  8  9  @  .
	g  p  l  w  n  u  a  o  y  :  k  c  r  e  h  b  v  m  x  z  f  q  d  s  i  t  6  3  7  1  9  8  0  2  5  4  !  $
	p  h  l  m  r  s  w  b  z  u  t  c  d  v  x  a  y  e  f  k  j  n  g  o  q  i  4  7  6  8  0  9  2  1  3  5  !  $
		
	*/

	$transin = array("@" => "!", "." => "$");
	$arrayin = strtr($arrayin, $transin);


	/*  $number = (rand(0,9));  */

	/*	$number == 0            */
		$batch = array('a' => 'g',
					   'b' => 'p',
					   'c' => 'l',
					   'd' => 'w',
					   'e' => 'n',
					   'f' => 'u',
					   'g' => 'a',
					   'h' => 'o',
					   'i' => 'y',
					   'j' => ':',
					   'k' => 'k',
					   'l' => 'c',
					   'm' => 'r',
					   'n' => 'e',
		  			   'o' => 'h',
					   'p' => 'b',
					   'q' => 'v',
					   'r' => 'm',
					   's' => 'x',
					   't' => 'z',
					   'u' => 'f',
					   'v' => 'q',
					   'w' => 'd',
					   'x' => 's',
					   'y' => 'i',
					   'z' => 't',
					   '0' => '6',
					   '1' => '3',
					   '2' => '7',
					   '3' => '1',
					   '4' => '9',
					   '5' => '8',
					   '6' => '0',
					   '7' => '2',
					   '8' => '5',
					   '9' => '4',
					   '*' => '*',
					   '$' => '$',
					   '~' => '~',
					   '!' => '!'

					   );
	/*  $number == 1 			*/
		$natch = array('a' => 'p',
					   'b' => 'h',
					   'c' => 'l',
					   'd' => 'm',
					   'e' => 'r',
					   'f' => 's',
					   'g' => 'w',
					   'h' => 'b',
					   'i' => 'z',
					   'j' => 'u',
					   'k' => 't',
					   'l' => 'c',
					   'm' => 'd',
					   'n' => 'v',
		  			   'o' => 'x',
					   'p' => 'a',
					   'q' => 'y',
					   'r' => 'e',
					   's' => 'f',
					   't' => 'k',
					   'u' => 'j',
					   'v' => 'n',
					   'w' => 'g',
					   'x' => 'o',
					   'y' => 'q',
					   'z' => 'i',
					   '0' => '4',
					   '1' => '7',
					   '2' => '6',
					   '3' => '8',
					   '4' => '0',
					   '5' => '9',
					   '6' => '2',
					   '7' => '1',
					   '8' => '3',
					   '9' => '5',
					   '*' => '*',
					   '$' => '$',
					   '~' => '~',
					   '!' => '!'
					   );

		switch(true) {
				case($number % 2 == 0):
					 $afocus = $batch;
				break;
				case($number % 2 != 0):
					 $afocus = $natch;
				break;
		}
		$data = str_split($arrayin);
		foreach ($data as $value) {
			$arrayout[] = array_search($value, $afocus);
		}

		/* start array half flip experiment 

		$nsize = sizeof($arrayout);
		$flop = round(($nsize / 2), 0, PHP_ROUND_HALF_UP); 	
		$arrayx1 = array_slice($arrayout, 0 ,$flop);

		switch(true) {
			case($nsize % 2 == 0):
				 $arrayx2 = array_slice($arrayout,($nsize - $flop), $flop);
				 $arrayout = array_merge($arrayx2, $arrayx1);
			break;
			case($nsize % 2 != 0):
				 $arrayx2 = array_slice($arrayout,($nsize - $flop), $flop);
				 $arrayi2 = array_pop($arrayx1);
				 $arrayout =  array(implode('', $arrayx1), implode('', $arrayx2));
			break;
		}
		
		 * end array half flip experiment */

		$array = implode('', $arrayout);

		switch(true) {
			case($ocrypt == 'u'):
				 $transout = array("!" => "@", "$" => ".");
	    		 $array = strtr($array, $transout); 
			break;
		}

		$array = str_split($array);

	return $array;
	
	}


	function timemute($ocrypt, $arrayin) {

	/*

	info: timesmute() for mixing up the UNIX time string 

	hex (A - F)   1 2 3 4 5 6 7 8 9 0
	A B C D E F | H G J I L K : M ! $ | 
	W E Y F R N | O P Q S ; V U T Z X |

	*/

	$item = '';

	$data = preg_split('//', $arrayin, -1, PREG_SPLIT_NO_EMPTY);

		foreach($data as $onein) {

			$number = (rand(0,9));
			switch(true) {
				case($ocrypt == 'f'):
					switch($onein) {
						case('1'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = 'h';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 'o';
								break;
							}
							break;
						case('2'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = 'g';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 'p';
								break;
							}
							break;
						case('3'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = 'j';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 'q';
								break;
							}
							break;
						case('4'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = 'i';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 's';
								break;
							}
							break;
						case('5'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = 'l';
								break;
								case($number % 2 != 0):
									 $arrayout[] = ';';
								break;
							}
							break;
						case('6'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = 'k';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 'v';
								break;
							}
							break;
						case('7'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = ':';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 'u';
								break;
							}
							break;
						case('8'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = 'm';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 't';
								break;
							}
							break;
						case('9'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = '!';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 'z';
								break;
							}
							break;
						case('0'):
							switch(true) {
								case($number % 2 == 0):
									 $arrayout[] = '$';
								break;
								case($number % 2 != 0):
									 $arrayout[] = 'x';
								break;
							}
						break;
			
						case('A'):
							$arrayout[] = 'W';
							break;
						case('a'):
							$arrayout[] = 'w';
							break;
						case('B'):
							$arrayout[] = 'E';
							break;
						case('b'):
							$arrayout[] = 'e';
							break;
						case('C'):
							$arrayout[] = 'Y';
							break;
						case('c'):
							$arrayout[] = 'y';
							break;
						case('D'):
							$arrayout[] = 'F';
							break;
						case('d'):
							$arrayout[] = 'f';
							break;
						case('E'):
							$arrayout[] = 'R';
							break;
						case('e'):
							$arrayout[] = 'r';
							break;
						case('F'):
							$arrayout[] = 'N';
							break;
						case('f'):
							$arrayout[] = 'n';
						break;
					}
				break;
				case($ocrypt == 'u'):
					switch($onein) {
						case('h'):
						case('o'):
							$arrayout[] = '1';
							break;
						case('g'):
						case('p'):
							$arrayout[] = '2';
							break;
						case('j'):
						case('q'):
							$arrayout[] = '3';
							break;
						case('i'):	
						case('s'):
							$arrayout[] = '4';
							break;
						case('l'):	
						case(';'):
							$arrayout[] = '5';
							break;
						case('k'):		
						case('v'):
							$arrayout[] = '6';
							break;
						case(':'):			
						case('u'):
							$arrayout[] = '7';
							break;
						case('m'):			
						case('t'):
							$arrayout[] = '8';
							break;
						case('!'):			
						case('z'):
							$arrayout[] = '9';
							break;
						case('$'):			
						case('x'):
							$arrayout[] = '0';
						break;
						case('W'):
							$arrayout[] = 'A';
							break;
						case('w'):
							$arrayout[] = 'a';
							break;
						case('E'):
							$arrayout[] = 'B';
							break;
						case('e'):
							$arrayout[] = 'b';
							break;
						case('Y'):
							$arrayout[] = 'C';
							break;
						case('y'):
							$arrayout[] = 'c';
							break;
						case('F'):
							$arrayout[] = 'D';
							break;
						case('f'):
							$arrayout[] = 'd';
							break;
						case('R'):
							$arrayout[] = 'E';
							break;
						case('r'):
							$arrayout[] = 'e';
							break;
						case('N'):
							$arrayout[] = 'F';
							break;
						case('n'):
							$arrayout[] = 'f';
						break;
					}
				break;
			}

		$arrayout[] = $item;
		$arrayout   = array_filter($arrayout, "arrayfilter");

		}

	return $arrayout;

	} 

	function arrayfilter($var){
  		return ($var !== NULL && $var !== FALSE && $var !== '');
	}

	function isvalidtimestamp($timestamp) {
    	return ((string) (int) $timestamp === $timestamp) 
    	&& ($timestamp <= PHP_INT_MAX)
    	&& ($timestamp >= ~PHP_INT_MAX);
	}

	/* already declared in functions */
    /*
	function valid_email($email) {
 	  	$isValid = true;
   	  	$atIndex = strrpos($email, "@");
   		if (is_bool($atIndex) && !$atIndex) {
      		$isValid = false;
   		} else {
	      	$domain = substr($email, $atIndex+1);
	      	$local = substr($email, 0, $atIndex);
	      	$localLen = strlen($local);
	      	$domainLen = strlen($domain);
	      	if ($localLen < 1 || $localLen > 64) {
	         // local part length exceeded
	         $isValid = false;
	      	} else if ($domainLen < 1 || $domainLen > 255) {
	         // domain part length exceeded
	         $isValid = false;
	      	} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
	         // local part starts or ends with '.'
	         $isValid = false;
	        } else if (preg_match('/\\.\\./', $local)) {
	         // local part has two consecutive dots
	         $isValid = false;
	        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
	         // character not valid in domain part
	         $isValid = false;
	        } else if (preg_match('/\\.\\./', $domain)) {
	         // domain part has two consecutive dots
	         $isValid = false;
	        } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
	          // character not valid in local part unless 
	          // local part is quoted
	          if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
	            $isValid = false;
	          }
	        }
	        if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
	         // domain not found in DNS
	         $isValid = false;
	        }
        }
        return $isValid;
    }
    */

