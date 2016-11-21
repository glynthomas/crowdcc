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

/* load required lib files. */
//include 'db/functions.php';  

/* load required lib files : a compatibility library with PHP 5.5's simplified password hashing API. */
// include 'lib/password.php';
require_once($_SERVER["DOCUMENT_ROOT"].'/../lib/password.php');

/* access to crowdcc signin db */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

/* access to crowdcc signin db */	
//require_once('db/db_config.php');

/* access to crowdcc api db */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.api.conn.php');

/* access to api crowdcc api db */ 
//require_once('db/db_config_api.php');

/* phpmailer lib * auth mail lib files * mailresetlink($to, $smtpmail, $stmppass, $token_send) * chgemsg($to, $smtpmail, $stmppass) * see ccmail app.error.php */
require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');

/* found log * log failure outside the JS console ( please comment out / remove later ) */
/* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */

global $timelocal;

$method = $_SERVER['REQUEST_METHOD'];

	switch (true) {

		// case(isset($_POST['ecode'], $_POST['tcode'], $_POST['pltfrm'], $_POST['browsr'], $_POST['timezo'])):

	  case (isset($_POST['_cco'])):

			$token_in = $_POST['_cco'];
			$token_clean = filter_var( $token_in ,  FILTER_SANITIZE_STRING);	// $scode_clean (any problems chars stripped out)

			/* validation checks */

	   		switch (true) {

	   		  case ($token_in !== $token_clean):
	    	        // print_r('data tamper ->' . $pltfrm_clean);
	    	        rtnwebapp('error_tamper' , 'error_tamper' ,'', '', 'post');	// test for failure	
	    	  break;

	   		}

	   		/* process - build token */
	  		
	  		$token_in = explode( ":", $token_in );
	  	    
	  	    $token_in[0] = decrypt($token_in[0]);  		         // _ccu   -> ecode updated (new) or blank
			// print_r( $token_in[0] );
			// print_r('|');
			$token_in[1] = decrypt($token_in[1]);                // ecode  -> ecode registered in the db
			$ccc_token = $token_in[1];

			// print_r( $token_in[1] );
			// print_r('|');
			
			// $token_in[2] = base64_decode($token_in[2]);  	 // -> pcode (not sent)
	  		// print_r( $token_in[2] );
			// print_r('|');
			
			$token_in[3] = base64_decode($token_in[3]);  	     // -> timecode
			$token_in[3] = hexdec($token_in[3]);
			$token_in[3] = (string)$token_in[3];         	    
	  		
	  		// print_r( $token_in[3] );
	  		// print_r('|');

	  		$token_in[4] = base64_decode($token_in[4]);  		 // -> pltfrm

	  		// print_r( $token_in[4] );
	  		// print_r('|');

			$token_in[5] = base64_decode($token_in[5]);  		 // -> browsr

			// print_r( $token_in[5] );
	  		// print_r('|');
			
			$token_in[6] = base64_decode($token_in[6]);  		 // -> timezo

			// print_r( $token_in[6] );
	  		// print_r('|');
	  		
	  		// print_r('thats all she wrote');
	  		// exit();


	  		/* process - end */
	  		
			switch (true) {

				case ( !isvalidtimestamp( $token_in[3]) ):
					 echo ' ...time stamp is not valid ;-) :: ';
					 /* error message timestamp invalid */
					 rtnwebapp('error_pc0de', $token_in[1], '', '', 'post');
					 exit(); 
				break;

				case ($token_in[0] === '_ccu'):

				    switch (true) {
				     
				    	case ( !valid_email( $token_in[1] ) ):
					 		  echo ' ...email domain is not valid ;-) :: ';
					 	  	  /* error message domain of email given is invalid */
					 	      rtnwebapp('error_pc0de', $token_in[1], '', '', 'post');
					 	  	  exit();
						break;
					}
				break;

				case ($token_in[0] !== '_ccu'):

				    switch(true) {
				     
				    	case ( !valid_email( $token_in[0]) ):
					 		  echo ' ...email domain is not valid ;-) :: ';
					 	  	  /* error message domain of email given is invalid */
					 	      rtnwebapp('error_pc0de', $token_in[0], '', '', 'post');
					 	  	  exit();
						break;

						case ( !valid_email( $token_in[1]) ):
					 		  echo ' ...email domain is not valid ;-) :: ';
					 	  	  /* error message domain of email given is invalid */
					 	      rtnwebapp('error_pc0de', $token_in[1], '', '', 'post');
					 	  	  exit();
						break;

					}

				break;

			}

			/* all failure tests should now be completed ... now test for success */
			
			switch (true) {

				case ($token_in[0] === '_ccu'):
				 	
					 
					 $flag = checkecodedb($token_in[1], $token_in[0], $mysqli);


					 /* now ready, confirmation email, return a code indicating the orginal email was used no need to update the client internal array. */

					 switch ($flag) {

					 	case ('true'):              /* email not confirmed in db yet */
					 	case ('email_current'):     /* email_current , send email confirmation */

					 	   /* signin_token($ecode_current, $token_in[0], $emoil_past, $platform_user, $browser_user, $timezone, $mysqli) */

		 			 		  signin_token($token_in[0], $token_in[1], $token_in[4], $token_in[5], $token_in[6], $mysqli);   /* assume success, failure will be reported ... */

		 			 		  rtnwebapp('error_snd_ecode', 'error_snd_ecode', '', '', 'post');  /* no reset array postions required, just inform user that the email has been sent to address. */

					 	break;

					 }

				break;

				case ($token_in[0] !== '_ccu'):

		
					$flag = checkecodedb($token_in[1], $token_in[0], $mysqli);


					switch ($flag) {

						case ('fatal'):  		// rtn fatal the original email address not found !
						
						     rtnwebapp('error_end_ecode','error_snd_ecode', '', '', 'post');  /* fatal error the original email address not found ! */
							 exit();
						break;

						case ('email_in_use'):  // rtn email_new_in_use for email address already in use !
						
							 rtnwebapp('error_idb_ecode', 'error_idb_ecode', '', '', 'post');  /* error new email address already in use ! */
							 exit();
						break;

						case ('true'):  // rtn true for new email added to user record for validation, send email confirmation

							 // echo json_encode('rst_ecode');		  // reset array positions;
							 										  // tweeter.usr[2] == new email
							 										  // tweeter.usr[1] == '_ccu' (original email is deleted)
					 
						  /* signin_token($ecode_current, $token_in[0], $emoil_past, $platform_user, $browser_user, $timezone, $mysqli) */

		 			 		 signin_token($token_in[0], $token_in[1], $token_in[4], $token_in[5], $token_in[6], $mysqli);   /* assume success, failure will be reported ...     */

		 			 		 rtnwebapp('error_rst_ecode', 'error_rst_ecode', '', '', 'post');   /* record for validation, send email confirmation */

		 			 	  /* now ready, confirmation email, return a code indicating the client internal arrays need to be updated in order to reset the new email as the original email. */


						break;

					}

				break;

			}


	  break;


	  case(isset($_POST['_cci'])):

	  		$token_in = $_POST['_cci'];
			
			$token_clean = filter_var( $token_in ,  FILTER_SANITIZE_STRING);	// $scode_clean (any problems chars stripped out)

			/* validation checks */

	   		switch (true) {

	   		 case ($token_in !== $token_clean):
	    	       // print_r('data tamper ->' . $pltfrm_clean);
	               rtnwebapp('error_tamper' , 'error_tamper' ,'', '', 'post');	// test for failure	
	    	 break;

	   		}

			$token_in = explode( ":", $token_in );
			$tokensend = base64_decode($token_in[0]);  			    // $_POST['token'];  -> tokensend registered in the token db

			/* log_found('_cci confirm', $tokensend, '$tokensend', __LINE__ ); */
		
			$dextime = getstrmsg($tokensend, 't');                  /* time in string revealed (should be same as time in emailed string) (use as a check) */
			$dextime = hexdec($dextime);

			/* log_found('confirm _cci', $dextime , '$post_in', __LINE__ ); */

			$ccc_token = base64_decode($token_in[1]); 		 	    // $_POST['token'];  -> tokenstore registered in the token db

			$ecode_in  = decrypt($token_in[2]); 		            // $_POST['ecode'];  -> ecode registered in the token db

			$pcode_in  = decrypt($token_in[3]);                     // $_POST['pcode'];  -> pcode registered in the user db ) needs added encrypt !

			$token_in[4] = base64_decode($token_in[4]);  
			$token_in[4] = hexdec($token_in[4]);
			$token_in[4] = (string)$token_in[4];         	        // $_POST['tcode'];  -> tcode the timestring can be checked against now

			$token_in_time  = date("Y-m-d H:i:s", $token_in[4]);
			$token_db_time  = date("Y-m-d H:i:s", $dextime);

			$token_in[5] = base64_decode($token_in[5]);  // $_POST['pltfrm'];
			$token_in[6] = base64_decode($token_in[6]);  // $_POST['browsr'];
			$token_in[7] = base64_decode($token_in[7]);  // $_POST['timezo'];

			$now = time();   		               	     // compare timestamp to now

			$diff = $now - $dextime;                     // unix time diff simple calc ,substract from each other
   			$d = $diff / 86400 % 7;
   			$h = $diff / 3600 % 24;
   			$m = $diff / 60 % 60; 
   			$s = $diff % 60;

			// echo $tokensend  . ' -> '; echo $ccc_token . ' <-> '; echo $ecode_in . ' <-> '; echo $pcode_in . ' <-> '; echo $token_in_time . ' <-> '; echo $token_in[5] . ' <-> '; echo $token_in[6] . ' <-> '; echo $token_in[7] . ' <-> '; echo $token_db_time . ' <- ';


   				switch (true) {

   					case (!isvalidtimestamp($token_in[4])):
					      /* error message token timestamp invalid */
   					      flagtokendb($token_db_time, $mysqli);
					      // echo 'error_em0n';
					      rtnwebapp('error_em0n' , 'error_em0n' , '', '', 'post' );
					      exit(); 
					break;

					case ($h >= 2):
					      /*  greater than 2 hours old --> timeout invalid */
					      echo ' ... token time ->' . $token_db_time . ' ... now->'. $token_in_time .' timestamp is ' . $h . 'hrs old and invalid :: ';
					      /*  error message token timestamp is too old     */
					      flagtokendb($token_db_time, $mysqli);
					  	  // echo 'error_em1n';
					  	  rtnwebapp('error_em1n' , 'error_em1n' , '', '', 'post' );
					      exit(); 	
				    break;

				}

				$usr_update = 0;
				$db_email_token = '';
				$db_email_past_token = '';
			
				if  ($s_stmt05 = $mysqli->prepare("SELECT tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser FROM signin_token WHERE tokenstore = ? and used=0 LIMIT 1")) {
						 $s_stmt05->bind_param('s', $ccc_token);     				/*  bind "$tokensend" to parameter. */
						 log_error($timelocal,'bind_param', $s_stmt05, $mysqli); 
						 $s_stmt05->execute();                       				/*  execute the prepared query. */
						 log_error($timelocal,'execute', $s_stmt05, $mysqli);
						 $s_stmt05->store_result();
						 $s_stmt05->bind_result($db_tokensend, $ccc_token, $db_email_past_token, $db_email_current_token, $db_ip_address_token, $db_platform_user_token, $db_browser_user_token, $db_time_token, $db_timezone_token, $db_timeissued_token, $db_used, $db_eraser);      // get variables from result.
						 $s_stmt05->fetch();

		      	} else {

		      			 log_error($timelocal,'prepare', $s_stmt05, $mysqli);
			             $s_stmt05->close();
		      	}

		      	/*  user case
		      	/* 
		      	/*  1. new user wishes to confirm registered email address -> $db_email_current, $db_email_past == ''.
		      	/*
		      	/*  2. existing user wishes to change email address, is responding to a token, thus registering and confirming new email address in one go. */	
				
		      	switch (true) {
		      		
		      	   case ($db_email_past_token === ''):
		      	    	 /* 1. new user wishes to confirm registered email address -> $db_email_current, $db_email_past == ''. */
		      	         $db_email_token = $db_email_current_token;
		           break;

		           case ($db_email_past_token !== ''):
		             	 /* 2. existing user wishes to change email address, is responding to a token, thus registering and confirming new email address in one go. */
		             	 /*    $db_email_current_token == new email address and $db_email_past_token is the old email address  */
		      	         $db_email_token = $db_email_past_token;
		      	   break;
		      	
		      	}

      			switch (true) {

		      		case ($s_stmt05->num_rows == 0):
		      		 	  $s_stmt05->close();
		      		 	  flagtokendb($token_db_time, $mysqli);
						  /* echo 'token invalid or expired, not found in db!'; */
		      			  // echo 'error_em2n';
		      			  rtnwebapp('error_em2n' , 'error_em2n' , '', '', 'post' );
		      			  exit();
		      		break;

		      		case ($db_email_current_token != $ecode_in):
						  /* echo 'email no match or not found in db!, password error displayed!'; */
						  // echo '$db_email_token ->' . $db_email_token . ' $ecode_in -->' . $ecode_in;
		      			  flagtokendb($token_db_time, $mysqli);
						  // echo 'error_em3n';
		      			  rtnwebapp('error_em3n' , 'error_em3n' , '', '', 'post' );
					 	  exit();
					break;

					case ($s_stmt05->num_rows > 0):
						  $s_stmt05->close();
  
						  /* db checked against original email address or (1. new user wishes to confirm registered email address -> $db_email_current, $db_email_past == ''.) */
						  
						  /* if ($s_stmt06 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) { */
						  if ($s_stmt06 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) {	
							  $s_stmt06->bind_param('s', $db_email_token);               /*  bind $db_email_current to parameter, if confirm email, or $db_email_past if update email */
							  log_error($timelocal,'bind_param', $s_stmt06, $mysqli); 
							  $s_stmt06->execute();                                      /*  execute the prepared query. */
							  log_error($timelocal,'execute', $s_stmt06, $mysqli);
							  $s_stmt06->store_result();  
							  /* $s_stmt06->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
							  $s_stmt06->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);     // get variables from result.
							  
							  $s_stmt06->fetch();
									
							  /* old method * create a random salt * hash password with random salt * store salt for comparison check */	
							  
							  /* $pcode_in = hash('sha512', $pcode_in.$db_random_salt); */

							  if ( password_verify($pcode_in, $db_passcode) ) { $pcode_in = $db_passcode; } /* stored $db_pcode bcrypt of password matches entered password */

						  } else {
							  
							  log_error($timelocal,'prepare', $s_stmt06, $mysqli);
							  $s_stmt06->close();    	 
						  }

					break;

				}

				switch (true) {		

					case ($s_stmt06->num_rows == 0):								/* failure email check */
						  /* echo 'email match or not found in db!'; */
					      flagtokendb($token_db_time, $mysqli);
						  // echo 'error_em4n';
					      rtnwebapp('error_em4n' , 'error_em4n' , '', '', 'post' );
		      			  exit();
		      		break;

		      		case ($db_timezone != $token_in[7]): 							/* failure timezone check */
						  /* echo 'the timezone of user do not match in db!'; */
		      		      flagtokendb($token_db_time, $mysqli);
		      			  // echo 'error_em5n';
		      		      rtnwebapp('error_em5n' , 'error_em5n' , '', '', 'post' );
		      			  exit();
					break;		

				    case ($s_stmt06->num_rows > 0 && $db_passcode == $pcode_in):	/* final password check */
						  

						  /* all 1. and 2. checks have now been completed, the db is ready for update with the $db_email_token (target) email address 
						     confirm is set to 1 and the CSFR one time token is set to 0 (but field can be reused for future CSFR prevention requests) */

						  $db_email_confirm    = 1;
						  $db_token_user_token = 0;

						  /* test api key * user has updated email, if api key is set * limit 10 upgrade to 20 * if greater than 10 * do not change */
				 
						  $db_api_key_check = explode(',', check_api_token( $db_api_key ));

						  /* $s_stmt06->close(); * moved * 536 */

						  if ($db_api_key_check[1] === '10') {  /* 10 tweets, default social only */

						  	/* update crowdcc api * members table with new api key limit */

						    if ($s_stmt10 = $mysqli_api->prepare("SELECT user_id, uname, ccc_store, ccc_limit, api_key, api_hit, api_hit_date FROM members WHERE uname = ? LIMIT 1")) {
							    $s_stmt10->bind_param('s', $db_uname);                     /*  bind $db_email_current to parameter, if confirm email, or $db_email_past if update email */
							    log_error_api($timelocal,'bind_param', $s_stmt10, $mysqli_api); 
							    $s_stmt10->execute();                                      /*  execute the prepared query. */
							    log_error_api($timelocal,'execute', $s_stmt10, $mysqli_api);
							    $s_stmt10->store_result();  
							    $s_stmt10->bind_result($db_user_id, $db_uname, $db_ccc_store, $db_ccc_limit, $db_api_key, $db_api_hit, $db_hit_date);      // get variables from result.
							    $s_stmt10->fetch();
					
						    } else {
							  
							    log_error_api($timelocal,'prepare', $s_stmt10, $mysqli_api);
							    $s_stmt10->close();    	 
						    }
						    /* init new api key token * 20 tweets * email address registered! */
						    $db_api_key = create_api_token($db_uname, '20');
						    $db_ccc_limit = 20;  

						  	if ($i_stmt10 = $mysqli_api->prepare("REPLACE INTO members (user_id, uname, ccc_store, ccc_limit, api_key, api_hit, api_hit_date) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
							    $i_stmt10->bind_param('issssss', $db_user_id, $db_uname, $db_ccc_store, $db_ccc_limit, $db_api_key, $db_api_hit, $db_hit_date);
							    log_error_api($timelocal,'bind_param', $i_stmt10, $mysqli_api); 
							    $i_stmt10->execute();                                    /*  execute the prepared query. */
							    log_error_api($timelocal,'execute', $i_stmt10, $mysqli_api);
							    /* $i_stmt10->store_result(); */
							    
					
						    } else {
							  
							    log_error_api($timelocal,'prepare', $i_stmt10, $mysqli_api);
							    $i_stmt10->close();    	 
						    }
						        $s_stmt10->close();
						        $i_stmt10->close(); 
						  }
						
						  /* if  ($i_stmt04 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
						  if  ($i_stmt04 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
							   /* $i_stmt04->bind_param('isssssssssssssssss', $db_user_id, $db_uname, $db_email_past_token, $db_email_current_token, $db_email_confirm, $db_api_key, $db_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
							   $i_stmt04->bind_param('issssssssssssssss', $db_user_id, $db_uname, $db_email_past_token, $db_email_current_token, $db_email_confirm, $db_api_key, $db_passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);
							   log_error($timelocal,'bind_param', $i_stmt04, $mysqli); 
							   /* execute the prepared query. */
							   $i_stmt04->execute();
							   log_error($timelocal,'execute', $i_stmt04, $mysqli);
							   /* $i_stmt04->store_result(); */
							   $i_stmt04->close();

							   $usr_update = 1;

						  } else {

							   log_error($timelocal,'prepare', $i_stmt04, $mysqli);
							   $i_stmt04->close();
						  }

						       $s_stmt06->close();
						
					break;
				}

				switch (true) {

					case ($usr_update == 0):
						  /* 'echo password match or not found failure or email already confirmed!'; */
					      flagtokendb($token_db_time, $mysqli);
		      			  // echo 'error_em6n';
					      rtnwebapp('error_em6n' , 'error_em6n' , '', '', 'post' );
		      			  exit();
					break;

					case ($usr_update == 1):

						  // date_default_timezone_set("UTC");
	    				  // $now = time();
	    				  // $date = new DateTime(null, new DateTimeZone($db_timezone));
	   					  // $db_timelocal = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));

						  if  ($s_stmt05 = $mysqli->prepare("SELECT visits FROM signin_members WHERE uname = ? LIMIT 1")) {
      						   $s_stmt05->bind_param('s', $db_uname);    //  bind "$sncode" to parameter.
							   $s_stmt05->execute();                   	 //  execute the prepared query.
							   $s_stmt05->store_result();  
							   $s_stmt05->bind_result($db_visits);       // get variables from result.
							   $s_stmt05->fetch();
							   $s_stmt05->close();

	              		  } else {
	 						   /* registration failed in db return false; unset session, remove cookies, log any prepare statement errors */
	              			   /* secure_session_destroy(); */
	              			   log_error($timelocal,'prepare', $s_stmt05, $mysqli);
	              			   $s_stmt05->close();
	              			   // echo json_encode('error_tcode');
	              			   rtnwebapp('error_tcode' , 'error_tcode' , '', '', 'post' );
	              			   exit();
            			  }

            			  $db_locked = 0;

            			  $db_visits = $db_visits + 1;

						  
						  if  ($i_stmt06 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
							   
							   $i_stmt06->bind_param('issssssssssss', $db_user_id, $db_uname, $db_email_past_token, $db_email_current_token, $db_ip_address_token, $db_platform_user_token, $db_browser_user_token, $db_token_user_token, $db_time_token, $db_timezone_token, $db_timeissued_token, $db_locked, $db_visits);

							   /* change 4th may 2014 -> $i_stmt06->bind_param('isssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_token, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timelocal, $db_locked, $db_visits); */
							   
							   /* new -> below are the new fields from the signin_token table -> update of either confirmed email (ip, browser, time & place) or new email   
						       /* $db_ip_address_token, $db_platform_user_token, $db_browser_user_token, $db_time_token, $db_timezone_token, $db_timeissued_token,     ... */

							   log_error($timelocal,'bind_param', $i_stmt06, $mysqli); 
							   /* execute the prepared query. */
							   $i_stmt06->execute();
							   log_error($timelocal,'execute', $i_stmt06, $mysqli);
							   /* $i_stmt04->store_result(); */
							   $i_stmt06->close();

						  } else {

							   log_error($timelocal,'prepare', $i_stmt06, $mysqli);
							   $i_stmt06->close();
						  }

						  if  ($i_stmt05 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
							      					 			 
							   $db_used = 1; $db_eraser = 1;

							   $i_stmt05->bind_param('ssssssssssss', $db_tokensend, $ccc_token, $db_email_past_token, $db_email_current_token, $db_ip_address_token, $db_platform_user_token, $db_browser_user_token, $db_time_token, $db_timezone_token, $db_timeissued_token, $db_used, $db_eraser);
							   log_error($timelocal,'bind_param', $i_stmt05, $mysqli); 
							   /* execute the prepared query. */
							   $i_stmt05->execute();
							   log_error($timelocal,'execute', $i_stmt05, $mysqli);
							   /* $insert_stmt->store_result(); */
							   $i_stmt05->close();

							   /* send update email message to old email address ... if there is an old email address ! */
							   if ($db_email_past_token !== $db_email_current_token) {
							   /*  chgemsg($db_email_past_token); */
							       chgemsg($db_email_past_token, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr');
							   }
								                      
							   /* echo 'update completed, email has been confirmed ;-) '; */
							   // echo 'pass_emin';

							   rtnwebapp('pass_emin' , 'pass_emin' , '', '', 'post' );

						  } else {
							   
							   log_error($timelocal,'prepare', $i_stmt05, $mysqli);
							   $i_stmt05->close();
						  }	

					break;

				}

	  break;

	  case (isset($_GET['token'])):

       	    if (empty($_GET['token'])) { rtnwebapp('error_tamper' , 'error_tamper' , '', '', 'post'); exit(); }

       	    $token_uname = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
	        $token_uname = htmlspecialchars($token_uname, ENT_COMPAT | ENT_QUOTES | ENT_HTML5, 'UTF-8');
	        $token_uname = trim(decrypt($token_uname));

	   		$ip_address = $_SERVER['REMOTE_ADDR'];
	   		/* $ip_address = mysql_real_escape_string($ip_address); */
	   		$ip_address = mysqli_real_escape_string($mysqli, $ip_address);

	   		date_default_timezone_set("UTC");

	   		/* $key = 'back2crowdcc'; */
	   		/* ultD5FGzuit3sK4IfugwtGEfPjIdx4S6mWZYyBGplnw=   based on rails twitter token of 44 chars */
	   		/* date_default_timezone_set("UTC");
	   		   $rand_string = substr(md5( time() . mt_rand(1,100)), 0, 11);
	   		   $auth_string = $rand_string .'|'. $ip_address .'|'. time();
	   		   $token = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $auth_string, MCRYPT_MODE_CBC, md5(md5($key)))); */
	   		/* $token_safe = strtr( $token, "+/", "-_" ); original */
	   		/* $token_unsafe = strtr( $token_safe, "-_", "+/" ); original */
	   		/* $token_safe = strtr( $token, "+/", "$:" ); */
	   		/* $token_unsafe = strtr( $token_safe, "$:", "+/" ); /* warning :: must be base64 encodeded from client :: */
	   		/* $token_decrypt = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($token_unsafe), MCRYPT_MODE_CBC, md5(md5($key))), "\0"); */
	   		/* $token_safe = ccrypt( $token_uname .'|'. $ip_address .'|'. date("Y-m-d",time()) , 'AES-256-CFB', 'en' );  // encrypt token for storage */
	   		
	   		/* ultD5FGzuit3sK4IfugwtGEfPjIdx4S6mWZYyBGplnw=   based on rails twitter token of 44 chars */
	   		
	   		$rand_string = substr(md5( time() . mt_rand(1,100)), 0, 10);
	   		$token_safe = ccrypt(  $rand_string .'|'. $token_uname , 'AES-256-CFB', 'en' );     /* encrypt token for storage */

	   		if (set_token_user($token_uname, $token_safe, $mysqli)) { rtnwebapp('correct' , $token_safe , '', '', 'post'); }

	  break;

      case (isset($_GET['up'])):

      		if (empty($_GET['up'])) { rtnwebapp('error_tamper' , 'error_tamper' , '', '', 'post'); exit(); }

			$tokensend = filter_input(INPUT_GET, 'up', FILTER_SANITIZE_STRING);
			$tokensend = htmlspecialchars($tokensend, ENT_COMPAT | ENT_QUOTES | ENT_HTML5, 'UTF-8');

			/* log_found('confirm up', $tokensend , '$tokensend', __LINE__ ); */
		
   			$dextime = getstrmsg($tokensend, 't'); /* time in string revealed (should be same as time in emailed string) (use as a check) */
			$dextime = hexdec($dextime);

			/* log_found('confirm up', $dextime , '$dextime', __LINE__ ); */

			$now = time();   		               // compare timestamp to now

			$diff = $now - $dextime;               // unix time diff simple calc ,substract from each other
   			$d = $diff / 86400 % 7;
   			$h = $diff / 3600 % 24;
   			$m = $diff / 60 % 60; 
   			$s = $diff % 60;


			if  ($s_stmt04 = $mysqli->prepare("SELECT tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser FROM signin_token WHERE tokensend = ? and used=0 LIMIT 1")) {
				 $s_stmt04->bind_param('s', $tokensend);     				//  bind "$tokensend" to parameter.
				 log_error($timelocal,'bind_param', $s_stmt04, $mysqli); 
				 $s_stmt04->execute();                       				//  execute the prepared query.
				 log_error($timelocal,'execute1', $s_stmt04, $mysqli);
				 $s_stmt04->store_result();
				 $s_stmt04->bind_result($db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);      // get variables from result.
				 $s_stmt04->fetch();
				

				 // echo $db_tokensend  . ' -> '; echo $ccc_token . ' <-> '; echo $db_email . ' <-> '; echo $db_email_new . ' <-> '; echo $db_timeissued . ' <-> '; echo $db_used . ' <-> '; echo $db_eraser . ' <- ';


				 /*

				 test for response :: tokensend ->
				 
				 -> confirm?up=arbRbkbhavbXc9cycq:AbTapc5:q::bJa4c8aI:Nckbkcn:3a:aAa7bjcma1cGcAcYa$bxcMaRcXaAljvp;sgebqakag:yaJbwaBbybY:J:4bBaycQbf:Gcyb9:kbOcr
				 
				 echo $db_tokensend, $ccc_token, $db_email, $db_email_new, $db_timeissued, $db_used, $db_eraser

				 -> $db_tokensend
				    arbRbkbhavbXc9cycq:AbTapc5:q::bJa4c8aI:Nckbkcn:3a:aAa7bjcma1cGcAcYa$bxcMaRcXaAljvp;sgebqakag:yaJbwaBbybY:J:4bBaycQbf:Gcyb9:kbOcr

					$ccc_token
				 -> bk:mcXc9:Mbibm:6:H:X:lbxb:az:Pb6arbBbUbJ:La8avc5byc6:daO:daPaP:2cObIaH:nawaD:Vrhefrnezyqnugyc!argyc$lhr:zblc4c1:8bmcJbpa2:Pc0cva 

 				 ->	$db_email
 				 	monumentivefail@gmail.com

 				 -> $db_email_new
					(nothing)
		
				 -> $db_timeissued
				    2014-05-01 14:03:23

				 -> $db_used
				  	0

				 -> $db_eraser
				    0

				 */


      		} else {

      			 log_error($timelocal,'prepare', $s_stmt04, $mysqli);
	    		  $s_stmt04->close();
      		}	
		
				switch (true) {
					case ($s_stmt04->num_rows > 0):
					      $s_stmt04->close();

						switch (true) {
					/*  test for failure -> check timestamp is valid and is not expired (more than 2 hours old!) */
							case (isvalidtimestamp($dextime)):
							 	/* echo json_encode('timestamp is invalid or expired or no token string match!'); */
							 	/* marked for deletion ... */
			  		 			/* $result = $oDB->replace('signin_token', $field_values); */

								if  ($i_stmt02 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      					 			 
									 $db_used = 1; $db_eraser = 1;

      					 			 $i_stmt02->bind_param('ssssssssssss', $db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
						             log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
	 					 			 // Execute the prepared query.
	             		 			 $i_stmt02->execute();
	              		  			 log_error($timelocal,'execute', $i_stmt02, $mysqli);
	              		 			 // $insert_stmt->store_result();
	                     			 $i_stmt02->close();
	                     			 rtnwebapp('error_pc0de', 'error_pc0de', '', '', 'get');
      							} else {
      					 			 log_error($timelocal,'prepare', $i_stmt02, $mysqli);
	              		 			 $i_stmt02->close();
      							}	

							break;

							case ($h >= 2):
								 /* 1 - 12 hours link token sent to email timeout */
						    	 /* echo json_encode('timestamp is invalid or expired or no token string match!'); */
								 /* marked for deletion ... */

								if  ($i_stmt02 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      					 			 
 									 $db_used = 1; $db_eraser = 1;

      					 			 $i_stmt02->bind_param('ssssssssssss', $db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
						             log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
	 					 			 // Execute the prepared query.
	             		 			 $i_stmt02->execute();
	              		  			 log_error($timelocal,'execute3', $i_stmt02, $mysqli);
	              		 			 // $insert_stmt->store_result();
	                     			 $i_stmt02->close();
	                     			 // echo json_encode('error_pc0de');
	                     			 rtnwebapp('error_ec0de', 'error_ec0de', '', '', 'get');
	                     			
      							} else {
      					 			 log_error($timelocal,'prepare', $i_stmt02, $mysqli);
	              		 			 $i_stmt02->close();
      							}					

	 						break;
						}

					/*  test for failure -> $db_timeissued == $dextime */

		  		 		switch (false) {

		  		 			case (strtotime($db_timeissued) == $dextime):
		  		 				/* echo json_encode('error no tokenstore / timestamp match!'); */
								
								/* marked for deletion ... */
								/* $result = $oDB->replace('signin_token', $field_values); */
							

		  		 				if  ($i_stmt03 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      					 			 
 									 $db_used = 1; $db_eraser = 1;
      					 			 
      					 			 $i_stmt03->bind_param('ssssssssssss', $db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
						             log_error($timelocal,'bind_param', $i_stmt03, $mysqli); 
	 					 			 // Execute the prepared query.
	             		 			 $i_stmt03->execute();
	              		  			 log_error($timelocal,'execute4', $i_stmt03, $mysqli);
	              		 			 // $insert_stmt->store_result();
	                     			 $i_stmt03->close();
	                     			 rtnwebapp('error_ec0de', $ccc_token, '', '', 'get' );
	                     			 
      							} else {
      					 			 log_error($timelocal,'prepare', $i_stmt03, $mysqli);
	              		 			 $i_stmt03->close();
      							}

		  		 			break;

 					/*  test for failure -> $db_email not found */

		  		 			case ($db_email_current != ''):
		  		 				 /* echo json_encode('the email record not found / is bad'); */
					 			 rtnwebapp('error_ec2de', $ccc_token, '', '', 'get' );
		  		 			break;
		  		 		}

		  			break;


		  			case ($s_stmt04->num_rows == 0):
		  				  $s_stmt04->close();
						  /* echo json_encode('invalid link or password already changed') */
		  				  
		  				  rtnwebapp('error_ec1de', $ccc_token, '', '', 'get' );
		  			break;

				}

			  /* all tests for failure are complete ..., create a secure sessionised html page for inserting the new password to the account */
			  /* ensure the session times out, so as not to be displayed for other user to change password to account. */

		      // echo ' rtnwebapp -> '; echo $ccc_token . ' <-> '; echo $db_email . ' <-> '; echo $db_email_new . ' get ';

		      rtnwebapp('ccc_ecode', $ccc_token, $db_email_past, $db_email_current, 'get' );

	  break;

	}

	/*  functions  */



	function get_token_user($token_uname, $mysqli) {

	    global $timelocal;

		$s_stmt09 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE uname = ? LIMIT 1");
	    $s_stmt09->bind_param('s', $token_uname);      //  bind "session_id" to parameter.
		log_error($timelocal,'bind_param', $s_stmt09, $mysqli); 
		$s_stmt09->execute();						   //  execute the prepared query.
		log_error($timelocal,'execute', $s_stmt09, $mysqli);                        
		$s_stmt09->store_result();  
	    $s_stmt09->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_pltfrm, $db_browsr, $db_token_safe, $db_now, $db_timezo, $db_timelocal_user, $db_locked, $db_visits);      // get variables from result.
		$s_stmt09->fetch();
		log_error($timelocal,'prepare', $s_stmt09, $mysqli);
		$s_stmt09->close();

		return $db_token_safe;
	}
 	

 	function set_token_user($token_uname, $token_safe, $mysqli) {

 		global $timelocal;

		/* function updates the session_auth field in the crowdcc_sessions database, sessions table to include the auth token sent from the server to the client
		   during user account db changes, email address and password updates, this session_auth token is then checked when the client sends back the change request */

		$s_stmt08 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE uname = ? LIMIT 1");
	    $s_stmt08->bind_param('s', $token_uname);      //  bind "session_id" to parameter.
		log_error($timelocal,'bind_param', $s_stmt08, $mysqli); 
		$s_stmt08->execute();						   //  execute the prepared query.
		log_error($timelocal,'execute', $s_stmt08, $mysqli);                             
		$s_stmt08->store_result();  
	    $s_stmt08->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_pltfrm, $db_browsr, $db_token_safe, $db_now, $db_timezo, $db_timelocal_user, $db_locked, $db_visits);      // get variables from result.
		$s_stmt08->fetch();
		log_error($timelocal,'prepare', $s_stmt08, $mysqli);
			
		if  ($i_stmt08 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      		 $i_stmt08->bind_param('issssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_pltfrm, $db_browsr, $token_safe, $db_now, $db_timezo, $db_timelocal_user, $db_locked, $db_visits);
	         log_error($timelocal,'bind_param', $i_stmt08, $mysqli); 
	 		 /* execute the prepared query. */
	         $i_stmt08->execute();
	         log_error($timelocal,'execute', $i_stmt08, $mysqli);
	         /* $insert_stmt->store_result(); */
	         $i_stmt08->close();
	         return true;

	    } else {
	 		 
	 		 /* registration failed in db return false; unset session, remove cookies, log any prepare statement errors */
	         /* secure_session_destroy(); */
	         log_error($timelocal,'prepare', $i_stmt10, $mysqli);
	         $i_stmt08->close();
	         $s_stmt08->close();
	         // echo json_encode('error_tcode');
	         rtnwebapp('error_tcode' , 'error_tcode' , 'post');  // social, no twitter account details found
	         exit();
        }

        $s_stmt08->close(); 
	}


	function rtnwebapp($msgcode = 'ccc_ecode', $msgtoken, $ecode_past, $ecode_current, $whofor ) {

	/*  function is passed the following ;
	 *
	 *  $msgcode        -> 'ccc_ecode' (default) no failure
	 *  $msgtoken       -> tokenstore to check against the tokensend (other half)
	 *  $ecode_current  -> current email address
	 *  $ecode_new      -> new email address is set
	 *  $whofor         -> for twitter or for crowdcc
	 *
	 */

		$ecode_token = $ecode_current;
	
		$ccc_token = $msgtoken;
		$ccc_msg = $msgcode;

		$output = array();
		$ceode  = array();

		// start mod :: add email encoded for confimation dialog

		$ceode = base64_encode($ecode_token);

		// echo ' $ecode_token -->' . $ecode_token . 'base64_encode $ecode_token ($ceode) ->' . $ceode;

		// end mod ::

		switch($whofor) {

			case('get'):
				include('html.inc');
				# unset vars
				exit();
			break;

			case('post'):

				switch ($msgcode) {

				    case ('error_tamper'):     /* string injection detected */

					case ('error_pc0de'):      /* email domain invalid ! */
	  			    case ('error_snd_ecode');  /* no reset array postions required, just inform user that the email has been sent to address. */
			        case ('error_end_ecode');  /* fatal error the original email address not found ! */
					case ('error_idb_ecode');  /* error new email address already in use ! * */
					case ('error_em6n'):       /* 'echo password match or not found failure or email already confirmed!'; */
					case ('error_em5n'):       /* failure timezone check */
					case ('error_em4n'):       /* failure email check */
					case ('error_em3n'):	   /* email no match ! */
					case ('error_em1n'):       /* error message token timestamp is too old */
					case ('error_em2n'):	   /* token invalid ! */
					case ('error_em0n'):       /* error message token timestamp invalid    */

					case ('error_rst_ecode'):  /* record for validation, send email confirmation */
					case ('pass_emin'):        /* update completed, email has been confirmed ;-) */
 					case ('correct'): 		   /* get token returned ;-) */

					case ('error_pc0de'):      /* token timestamp invalid (expired) */
					case ('error_ec0de'):      /* token timestamps do not match!  */
					case ('error_ec1de'):      /* password already updated or link expired */
					case ('error_ec2de'):      /* email record not found or bad */
					case ('error_tcode'):      /* social, no twitter account details found */

						  echo json_encode( $msgcode . ':*:' . $msgtoken );

					break;

					case ('ccc_ecode'):

						  echo json_encode($msgcode);

					break;

				}


			    // if ($msgcode === 'ccc_ecode') {
			    // echo json_encode($msgcode);
			    // } else {
			    // echo json_encode( $msgcode . ':*:' . $msgtoken );
				// }

				# unset vars
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

	function flagtokendb($token_db_time, $mysqli) {

		/* function is passed the signin token time issued, which is matched against the issued token;
		/*
		/* the token flags are written to;
		/*   used = 1
		/* eraser = 1
		/*
		/* the sigin token is then invalid and waits in the db for later deletion
		*/

		global $timelocal;

		  if  ($s_stmt07 = $mysqli->prepare("SELECT tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser FROM signin_token WHERE timeissued = ? and used=0 LIMIT 1")) {
			   $s_stmt07->bind_param('s', $token_db_time);     			//  Bind "$tokensend" to parameter.
			   log_error($timelocal,'bind_param', $s_stmt07, $mysqli); 
			   $s_stmt07->execute();                       				//  Execute the prepared query.
			   log_error($timelocal,'execute', $s_stmt07, $mysqli);
			   $s_stmt07->store_result();
			   $s_stmt07->bind_result($db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);      // get variables from result.
			   $s_stmt07->fetch();

		  } else {

		       log_error($timelocal,'prepare', $s_stmt07, $mysqli);
			   $s_stmt07->close();
		  }	


		  if  ($i_stmt07 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
							      					 			 
			   $db_used = 1; $db_eraser = 1;

			   $i_stmt07->bind_param('ssssssssssss', $db_tokensend, $ccc_token, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone, $db_timeissued, $db_used, $db_eraser);
			   log_error($timelocal,'bind_param', $i_stmt07, $mysqli); 
			   /* execute the prepared query. */
			   $i_stmt07->execute();
			   log_error($timelocal,'execute', $i_stmt07, $mysqli);
			   /* $insert_stmt->store_result(); */
			   $i_stmt07->close();                      
			   /* echo 'update completed, email has been confirmed ;-) '; */
			   /* echo 'error_em2n'; */

		   } else {
							   
			   log_error($timelocal,'prepare', $i_stmt07, $mysqli);
			   $i_stmt07->close();
		   }	



	}


	function checkecodedb($ecode_current, $ecode_token, $mysqli) {

		/* $chkemail = checkecodedb($ecode_current, $token_in[0], $mysqli); */

		/* db check ... the user may update / change their email address, 2 possible results ;
		/*
		/* 1. the email is already in the db ($ecode_current)
		/* 2. the email is not already in the db, it is a new email user changed / updated ($new_email)
		/* 
		/* inputs :: -> 
		/* $ecode_current   == $ecode_in
		/* $ecode_token     == $token_in[0]
		/* $ecode_token     == '_ccu' OR $ecode_token == 'new_email@gmail.com'
		*/

		global $timelocal;

		$stotus = '';
		// $emoil_current = '';
		// $emoil_past = '';

		/* $return[0] == status, $return[1] == $db_email_current, $return[2] == $db_email_past */

		if (valid_email($ecode_token)) { 
							 
			/* if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) { */
			if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) {	
				$s_stmt01->bind_param('s', $ecode_token );                  //  bind $ecode_array["email_new"] to parameter.
				log_error($timelocal,'bind_param', $s_stmt01, $mysqli); 
				$s_stmt01->execute();                                       //  execute the prepared query.
				log_error($timelocal,'execute', $s_stmt01, $mysqli);
				$s_stmt01->store_result();
				/* $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
				$s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);     // get variables from result.
				$s_stmt01->fetch();

			} else {

			 	log_error($timelocal,'prepare', $s_stmt01, $mysqli);
			    $s_stmt01->close();
		    } 			

		    switch(true) {

		        case($s_stmt01->num_rows > 0):    /* $ecode_array["email_new"], already existing in db ! */
		      		 $s_stmt01->close();
		      		 
		      		 /* $return[0] == status, $return[1] == $db_email_current, $return[2] == $db_email_past */

		      		 $stotus = 'email_in_use';
		             // $emoil_current = $db_email_current;
					 // $emoil_past = $db_email_past;
	
		        break;

		        case($s_stmt01->num_rows == 0):   /* echo '  ::  new email selected use ' . $ecode_new . ' email not registered yet in db'; */
		      		 $s_stmt01->close();

		      		 /* $return[0] == status, $return[1] == $db_email_current, $return[2] == $db_email_past */

		      		 $stotus = 'true';
		             // $emoil_current = $ecode_token;
					 // $emoil_past = $ecode_token;

		      	break;		      	  

		    }

	    } else {


	    	/* if ($s_stmt02 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) { */
			if ($s_stmt02 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) {
				$s_stmt02->bind_param('s', $ecode_current );                //  bind $ecode_array["email_current"] to parameter.
				log_error($timelocal,'bind_param', $s_stmt02, $mysqli); 
				$s_stmt02->execute();                                       //  execute the prepared query.
				log_error($timelocal,'execute', $s_stmt02, $mysqli);
				$s_stmt02->store_result();
				/* $s_stmt02->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
				$s_stmt02->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);     // get variables from result.
				$s_stmt02->fetch();

			} else {

		 		log_error($timelocal,'prepare', $s_stmt01, $mysqli);
		        $s_stmt01->close();
	      	} 			

	      	switch(true) {

	      		case($s_stmt02->num_rows == 0): /* :: case 1. --> new email_current , to be confirmed ... */
	      			 $s_stmt02->close();
	      			 
	      			 /* $return[0] == status, $return[1] == $db_email_current, $return[2] == $db_email_past */

	      			 $stotus = 'true';
		             // $emoil_current = $ecode_current;
					 // $emoil_past = $ecode_current;

	      		break;

	      		case($s_stmt02->num_rows > 0):
	      			 $s_stmt02->close();

	      			 /* $return[0] == status, $return[1] == $db_email_current, $return[2] == $db_email_past */

	      			 $stotus = 'email_current';
		             // $emoil_current = $db_email_current;
					 // $emoil_past = $db_email_past;


	      	    // :: old email address exisits in the db, want to retain old email address, until new email address has been confirmed !
	      			
		        //   if  ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email, email_confirm, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, user_platform, user_browser, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
		      	//	      $i_stmt01->bind_param('issssssssssssss', $db_user_id, $db_uname, $new_email, $db_email_confirm, $db_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_user_platform, $db_user_browser, $db_time, $db_timezone, $db_timelocal);
				//		  log_error($timelocal,'bind_param', $i_stmt01, $mysqli); 
			 	//		  // Execute the prepared query.
			    // 	      $i_stmt01->execute();
			    //		  log_error($timelocal,'execute', $i_stmt01, $mysqli);
			    //		  // $insert_stmt->store_result();
			    //		  $i_stmt01->close();
			    //		  // rtnwebapp('error_pc3de', $ccc_token, 'post');
			    //		  $result = 'true';  
		        //	  } else {
		        //		  log_error($timelocal,'prepare', $i_stmt01, $mysqli);
			    //		   $i_stmt01->close();
			    //		   $result = 'false';
			    //	  }

			    break;		              		 		
			}

		}

      	/* return array($stotus, $emoil_current, $emoil_past); */

      	return $stotus;
	}


	function arrayfilter($var) {
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

	function signin_token($ecode_token, $ecode_in, $platform_user, $browser_user, $timezone, $mysqli) {
			
		  /* signin_token($token_in[0], $token_in[1], $token_in[4], $token_in[5], $token_in[6], $mysqli); */

		  /*

		     $token_in[0] ( $ecode_token ) could equal == '_ccu' when the current email requires confirmation

			 AND
	
		     $ecode_in == current email address

		     OR

		     $token_in][0] ($ecode_token) could equal == the new email address 

		     AND

		     $ecode_in == the past email address

		  */

		global $timelocal;

		$ecode_target = '';


		switch(true) {

			case($ecode_token === '_ccu'):
				 $email_past = $ecode_in;
				 $email_current = $ecode_in;
				 $ecode_target = $ecode_in;
			break;
			case($ecode_token !== '_ccu'):
			     /* active data $ecode_in */
				 $email_past = $ecode_in;
				 /* from db $emoil_past */
				 // $email_past = $emoil_past;
				 $email_current = $ecode_token;
				 $ecode_target = $ecode_token;
			break;
			
		}
        					    
		$used = 0;
		$eraser = 0;

		/* $token_send   = getrandomstr(120, dechex(time()),'t'); */
		/* $token_store  = getrandomstr(120, $ecode_target,'e'); */

		$token_send   = getrandomstr(dechex(time()),'t');
		/* log_found('confirm signin_token', $token_send, '$token_send', __LINE__ ); */

		$token_store  = getrandomstr($ecode_target,'e');
		/* log_found('confirm signin_token', $token_store, '$token_store', __LINE__ ); */

		$ip_address = $_SERVER['REMOTE_ADDR'];
	    /* $ip_address = mysql_real_escape_string($ip_address); */
        $ip_address = $mysqli->real_escape_string($ip_address);

		$now = time();

		if  ($i_stmt07 = $mysqli->prepare("INSERT INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      		 $i_stmt07->bind_param('ssssssssssss', $token_send[1], $token_store[1], $email_past, $email_current, $ip_address, $platform_user, $browser_user, $now, $timezone, $token_send[2], $used, $eraser);
			 log_error($timelocal,'bind_param', $i_stmt07, $mysqli); 
			 /* execute the prepared query. */
             $i_stmt07->execute();
             log_error($timelocal,'execute', $i_stmt07, $mysqli); 
        } else {
             /* store token failed in db return false; unset session, remove cookies, log any prepare statement errors */
             /* secure_session_destroy(); */
             log_error($timelocal,'prepare', $i_stmt07, $mysqli);
             /* echo json_encode('token_store_failed'); */
        }

		// if $return != '', return unknown error code (not implemented)

		if ($i_stmt07) {
		/*  mailresetlink($ecode_target, $token_send);                                                   // all good, signin token created */
			mailresetlink($ecode_target, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr', $token_send);  // all good, signin token created
			/* all good, signin token created */
		} else {
			/* db error, unknown at this point, check log (send notification back to user?) */
		}

		//   $i_stm01->store_result();
             $i_stmt07->close();
				
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

	/*
	function mailresetlink($to, $token_send) {
		$subject = "Please confirm you crowdcc account";
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		$message = '
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Confirm your crowdcc contact email</title>
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
		<img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" title="crowdcc" style="position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;">
		</td>
		<td valign="top" style="padding-top:10px;text-align:right">
		</td>
		</tr>		
        <!-- /header -->
		<tr>
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<h4>Hi</h4>
		<p>By confirming your email address you gain <b>full access to your crowdcc account</b>.
		<p>
		You can confirm your
		<b><a href="'. $uri .'/confirm?up='. $token_send[1] .'" style="color:#1c1c2f">account here</a></b>
		.
		</p>
		<p>Any future notifications and important information regarding your account will be sent to this address.</p>
		<p>If you didn\'t make this request then you can ignore this email.</p>		
		<p>&nbsp;</p>
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">	
		<span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message sent from crowdcc, please don\'t reply directly to this email, this email link is valid for 2 hours.</span>
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
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: confirm@crowdcc.com <confirm@crowdcc.com>' . "\r\n";
		$headers .= "Return-path: <bounce@crowdcc.com>\r\n";
		$headers .= "Errors-To: <bounce@crowdcc.com>\r\n";
		if (mail($to,$subject,$message,$headers,"-fbounce@crowdcc.com")) {
			// echo json_encode('pass_ecode'); 
			// email pass (found in db, pass to token function)
			// echo "We have sent the password reset link to your email id <b>".$to."</b>";
		} else {
			echo json_encode('fail_ecode');
			// email fail (found in db, pass to token function), but have failed to be able to send it to		  
			// to the email address provided, please try again !
		}
	}
	*/

	function mailresetlink($to, $smtpmail, $stmppass, $token_send) {

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
		$mail->setFrom('confirm@crowdcc.com', 'confirm@crowdcc.com');

		/* Set an alternative reply-to address */
		$mail->addReplyTo('noreply@crowdcc.com', 'no reply');

		/* Set who the message is to be sent to */
		// $mail->addAddress($to, $fullname);
		$mail->addAddress($to);

		/* Set the subject line */
		$mail->Subject = 'Please confirm you crowdcc account';

		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;

		$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Confirm your crowdcc contact email</title>
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
		<img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" title="crowdcc" style="position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;">
		</td>
		<td valign="top" style="padding-top:10px;text-align:right">
		</td>
		</tr>		
        <!-- /header -->
		<tr>
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<h4>Hi</h4>
		<p>By confirming your email address you gain <b>full access to your crowdcc account</b>.
		<p>
		You can confirm your
		<b><a href="'. $uri .'/confirm?up='. $token_send[1] .'" style="color:#1c1c2f">account here</a></b>
		.
		</p>
		<p>Any future notifications and important information regarding your account will be sent to this address.</p>
		<p>If you didn\'t make this request then you can ignore this email.</p>		
		<p>&nbsp;</p>
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">	
		<span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message sent from crowdcc, please don\'t reply directly to this email, this email link is valid for 2 hours.</span>
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
			/* log_found('mail check', ' mail fail ' , 'errorhandle', __LINE__ ); */                     
			
			echo json_encode('fail_ecode');

			/* email fail (found in db, pass to token function), but have failed to be able to send it to */
            /* to the email address provided, please try again ! */
            /* social, no twitter account details found */
			 									                                
		} else {

			/* echo json_encode('pass_ecode'); */
			// email pass (found in db, pass to token function)
			// echo "We have sent the password reset link to your email id <b>".$to."</b>";

		}
	}

	/*
	function chgemsg($to) {
	  if ($to) {
		$subject = "crowdcc has received a request to update your account email";
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		$message = '
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>crowdcc has received a request to update your account email</title>
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
		You recently updated the email associated with your crowdcc account.
		</p>
		To confirm this update, please follow the details in the confirmation message sent to you.
		<p>
		If you didn\'t request this update and believe your crowdcc account has been compromised,
		<p>
		contact crowdcc support by clicking this link:
		</p>
		<p style="padding-bottom:10px;"><a href="'. $uri .'/@hacked" style="color:#1c1c2f">crowdcc/hacked</a>.</p>		
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">
		<span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
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
	}
	*/

	function chgemsg($to, $smtpmail, $stmppass) {
	  if ($to) {

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
		$mail->setFrom('hello@crowdcc.com', 'hello@crowdcc.com');

		/* Set an alternative reply-to address */
		$mail->addReplyTo('noreply@crowdcc.com', 'no reply');

		/* Set who the message is to be sent to */
		// $mail->addAddress($to, $fullname);
		$mail->addAddress($to);

	    /* Set the subject line */
		$mail->Subject = 'crowdcc has received a request to update your account email';

		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;

		$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>crowdcc has received a request to update your account email</title>
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
		You recently updated the email associated with your crowdcc account.
		</p>
		To confirm this update, please follow the details in the confirmation message sent to you.
		<p>
		If you didn\'t request this update and believe your crowdcc account has been compromised,
		<p>
		contact crowdcc support by clicking this link:
		</p>
		<p style="padding-bottom:10px;"><a href="'. $uri .'/@hacked" style="color:#1c1c2f">crowdcc/hacked</a>.</p>		
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">
		<span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
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

			/* log_found('mail check', ' mail fail ' , 'errorhandle', __LINE__ ); */    

			// echo json_encode('fail_ecode');		 
		    // email pass (found in db, pass to token function), but have failed to be able to send it to
		    // to the email address provided, please try again !
	        //  } else {
		    // echo json_encode('pass_ecode');       
		    // email pass (found in db, pass to token function)
		    // echo "We have sent the password reset link to your email id <b>".$to."</b>";
		}
	  }
	}

?>