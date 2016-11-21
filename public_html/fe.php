<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* fe.php
*
* feedback :: collect and store feedback data from users.
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

/* php sheduler */
include_once($_SERVER["DOCUMENT_ROOT"].'/../slib/firepjs.php');

/* access to crowdcc signin db */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

/* access to crowdcc feedback db */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.feedback.conn.php');

/* phpmailer lib * auth mail lib files * mailresetlink($to, $token_send) * see ccmail app.error.php */
require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');


/* start session and load library. */
secure_session_start(); // Our custom secure way of starting a php session.


/* twitter oauth lib * source: https://twitteroauth.com * version: v0.4.1 * modified v0.1 */
require_once('tweetpath.php');
use crowdcc\TwitterOAuth\TwitterOAuth;

/* $session = new session(); Set to true if using https, $session->start_session('_s', false); */

$method = $_SERVER['REQUEST_METHOD'];

/* $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1)); */

switch ($method) {
  case ('PUT'):
        /* rest_put($request);    */  
  break;
  case ('POST'):
 
	if (!isset($_POST) ) { unset($_SESSION['tokenf']); rtnwebapp('error' , 'bad-post' , 'post');}

	$post_in = $_POST;
    $post_in = explode(":", implode($post_in));
    $token = base64_decode( $post_in[0] );

    $_SESSION['tokenf'] = (isset($_SESSION['tokenf']) ? $_SESSION['tokenf'] : null);

    if ( $_SESSION['tokenf'] !== $token ) { unset($_SESSION['tokenf']); rtnwebapp('error' , 'bad-token' , 'post');}

    $scode = ''; $scode_clean = '';
    $scode = base64_decode($post_in[1]);						  /* $scode status of post -> _ccf user data | _cca authorise tokens * $scode_clean (any problems chars stripped out) */
    $scode_clean = filter_var( $scode ,  FILTER_SANITIZE_STRING);	

    if (valid_scode($scode_clean) === false) { unset($_SESSION['tokenf']); rtnwebapp('error' , 'bad-token' , 'post');}

    switch (true) {

	 case ($scode === '_ccf'):
	   /* 15 data items * prepare php data scrubbed vars */

	   /* $token :: tokenf */
	   /* $scode :: _ccf */

	   $vuser  = ''; 
	   $vmail  = '';
	   $commments = '';

	   $a = ''; 
	   $b = ''; 
	   $c = '';
	   $d = ''; 
	   $e = ''; 

	   $pltfrm = ''; 
	   $browsr = ''; 
	   $time = ''; 
	   $timezo = ''; 

	   /* decrypt vars */
	
	   $post_in[2]  = decrypt($post_in[2]);		    /* vuser twitter screen name */
	   $post_in[3]  = decrypt($post_in[3]);			/* vmail */
	   $post_in[4] = base64_decode($post_in[4]);    /* comments */

	   $post_in[5] = base64_decode($post_in[5]);	/* a * sstore */
	   $post_in[6] = base64_decode($post_in[6]);	/* b * every */
	   $post_in[7] = base64_decode($post_in[7]);	/* c * future */
	   $post_in[8] = base64_decode($post_in[8]);	/* d * select */
	   $post_in[9] = base64_decode($post_in[9]);	/* e * email */
	   
	   $post_in[10] = base64_decode($post_in[10]);	/* pltfrm */
	   $post_in[11] = base64_decode($post_in[11]);	/* browsr */
	   $post_in[12] = base64_decode($post_in[12]);	/* date * time */
	   $post_in[13] = base64_decode($post_in[13]);	/* timezo */

	   /* scrub data */

	   $vuser  = filter_var( $post_in[2] , FILTER_SANITIZE_STRING);  	  /* vuser twitter screen name clean */
	   $vmail = filter_var( $post_in[3], FILTER_SANITIZE_EMAIL );	      /* vmail clean * $vcode (any problem chars stripped out) */
       $comments = filter_var( $post_in[4], FILTER_SANITIZE_STRING);  	  /* comments clean */

	   $a = filter_var( $post_in[5] , FILTER_SANITIZE_STRING);  	      /* a * sstore clean */
	   $b = filter_var( $post_in[6] , FILTER_SANITIZE_STRING);  	      /* b * every clean */
	   $c = filter_var( $post_in[7] , FILTER_SANITIZE_STRING);  	      /* c * future clean */
	   $d = filter_var( $post_in[8] , FILTER_SANITIZE_STRING);      	  /* d * select clean */
	   $e = filter_var( $post_in[9] , FILTER_SANITIZE_STRING);  	      /* e * email clean */

	   $pltfrm = filter_var( $post_in[10] , FILTER_SANITIZE_STRING);  	  /* pltfrm clean */
	   $browsr = filter_var( $post_in[11] , FILTER_SANITIZE_STRING);      /* browsr clean */
	   $time = filter_var( $post_in[12] , FILTER_SANITIZE_STRING);        /* date * time clean */
	   $timezo = filter_var( $post_in[13] , FILTER_SANITIZE_STRING);      /* timezo clean */

	   /* validation checks */

	   switch (true) {

	   	case ($vuser !== $post_in[2]):
	          rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($vmail !== $post_in[3]):
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($comments !== $post_in[4]):
	          rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($a !== $post_in[5]):
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($b !== $post_in[6]):
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($c !==  $post_in[7]):
	          rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($d !== $post_in[8]):
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($e !==  $post_in[9]):
	          rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($pltfrm !== $post_in[10]):
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
		case ($browsr !==  $post_in[11]):
	          rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($time !== $post_in[12]):
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;
	    case ($timezo !== $post_in[13]):
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    /* test for valid data */
	    break;

	   }

	   /* data fail checks */

	   switch (true) {

	   	case (valid_username($vuser) === false):
	   		  rtnwebapp('error' , 'username-fail' , 'post');	   					/* test for failure	*/
	   	break;
	   	case (valid_email($vmail) === false):   			
	    	  rtnwebapp('error' , 'email-fail' , 'post');						    /* test for failure	*/
	    break;
	    /* case (valid_timestamp($time) === false): */
	    case (valid_date($time) === false):  					            
	    	  rtnwebapp('error' , 'date-fail' , 'post');		  				    /* test for failure	* timestamp fail * form already submitted ! */
	    break;
	
	   }

	   /* validation and data fail checks completed * if we get here, data tested good, should match correct entered data on the clients form. */

	   /* convert * y, x, n to 3, 2, 1 */

	   switch (true) {case ($a === 'y'):$a = '2';break;case ($a === 'x'):$a = '0';break;case ($a === 'n'):$a = '1';break;}
	   switch (true) {case ($b === 'y'):$b = '2';break;case ($b === 'x'):$b = '0';break;case ($b === 'n'):$b = '1';break;}
	   switch (true) {case ($c === 'y'):$c = '2';break;case ($c === 'x'):$c = '0';break;case ($c === 'n'):$c = '1';break;}
	   switch (true) {case ($d === 'y'):$d = '2';break;case ($d === 'x'):$d = '0';break;case ($d === 'n'):$d = '1';break;}
	   switch (true) {case ($e === 'y'):$e = '2';break;case ($e === 'x'):$e = '0';break;case ($e === 'n'):$e = '1';break;}
	
	   /*
	   print_r( $token );
	   print_r('|');
	   print_r( $vuser );
	   print_r('|');
	   print_r( $vmail );
	   print_r('|');
	   print_r( $post_in[3] );
	   print_r('|');
	   print_r( $post_in[4] );
	   print_r('|');
	   print_r( $sstore );
	   print_r('|');
	   print_r( $every );
	   print_r('|');
	   print_r( $future );
	   print_r('|');
	   print_r( $uselect );
	   print_r('|');
	   print_r( $email );
	   print_r('|');
	   print_r( $post_in[11] );
	   print_r('|');
	   print_r( $post_in[12] );
	   print_r('|');
	   print_r( $post_in[13] );
	   print_r('|');
	   print_r( $post_in[14] );
	   print_r('|');
       */

	   /* check data * with registered users db for user id with twitter screen name and maybe email * record data in crowdcc customer feedback * development db */

	   /* record_feedback($vuser, $vmail, $a, $b, $c, $d, $e, $comments, $pltfrm, $browsr, $time, $timezo, $send_email = true, $send_tweet = true, $mysqli, $mysqli_feedback); */

	   record_feedback($vuser, $vmail, $a, $b, $c, $d, $e, $comments, $pltfrm, $browsr, $time, $timezo, $send_email = true, $send_tweet = true, $mysqli, $mysqli_feedback);
	   exit();

	 break;

	}

  break;
  case ('GET'):
       /* rest_get($request);    */
       if ( empty($_SESSION['time']) ) { $_SESSION['time'] = time(); }

	   $ip_address = $_SERVER['REMOTE_ADDR'];
	   /* $ip_address = mysql_real_escape_string($ip_address); */
	   $ip_address = mysqli_real_escape_string($mysqli, $ip_address);

	   $key = 'back2crowdcc';

	   /* ultD5FGzuit3sK4IfugwtGEfPjIdx4S6mWZYyBGplnw=   based on rails twitter token of 44 chars */

	   date_default_timezone_set("UTC");
	   $rand_string = substr(md5( time() . mt_rand(1,100)), 0, 11);
	   $auth_string = $rand_string .'|'. $ip_address .'|'. time();

	   /* $auth_token = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $auth_string, MCRYPT_MODE_CBC, md5(md5($key)))); */
	   /* $auth_decrypt = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($auth_token), MCRYPT_MODE_CBC, md5(md5($key))), "\0"); */

	   $token = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $auth_string, MCRYPT_MODE_CBC, md5(md5($key))));
	   
	   // $token_safe = strtr( $token, "+/", "-_" );
	   // $token_unsafe = strtr( $token_safe, "-_", "+/" );

	   $token_safe = strtr( $token, "+/", "$:" );
	   $token_unsafe = strtr( $token_safe, "$:", "+/" );

	   $token_decrypt = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($token_unsafe), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

	   $_SESSION['tokenf'] = $token_safe;

	   /* var_dump($auth_token);
	   /  var_dump($auth_token_safe);
	   /  var_dump($auth_decrypt);   */

	   # echo json_encode( array( "token"=>$token_safe ) );

	   rtnwebapp('correct' , $token_safe , 'get');
	   # exit();
  break;
  case ('HEAD'):
       /* rest_head($request);    */
       break;
  case ('DELETE'):
       /* rest_delete($request);  */  
  break;
  case ('OPTIONS'):
       /* rest_options($request); */   
  break;
  default:
       /* rest_error($request);   */
  break;
}


/* functions */

 function record_feedback($vuser, $vmail, $a, $b, $c, $d, $e, $comments, $pltfrm, $browsr, $time, $timezo, $send_email, $send_tweet, $mysqli, $mysqli_feedback) {

 	global $timelocal;

 	/* check feedback_members * if more than 2 feedback records * registered or not * uname  */

 	$feedback_received = 0;

 	if ($s_stmt02 = $mysqli_feedback->prepare("SELECT user_id, uname, email_current, email_confirm, api_key, uid, a, b, c, d, e, comments, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM feedback_members WHERE uname = ?")) {
		$s_stmt02->bind_param('s', $vuser);                        /*  bind $db_email_current to parameter, if confirm email, or $db_email_past if update email */
		log_error_feedback($timelocal,'bind_param', $s_stmt02, $mysqli_feedback); 
		$s_stmt02->execute();                                      /*  execute the prepared query. */
		log_error_feedback($timelocal,'execute', $s_stmt02, $mysqli_feedback);
		$s_stmt02->store_result();  
		$s_stmt02->bind_result($db_user_id, $db_uname, $db_email_current, $db_email_confirm, $db_api_key, $db_uid, $db_a, $db_b, $db_c, $db_d, $db_e, $db_comments, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);   /* get variables from result. */
		$s_stmt02->fetch();
									
	} else {
							  
	    log_error_feedback($timelocal,'prepare', $s_stmt02, $mysqli_feedback);
		$s_stmt02->close();    	 
				
	}

	if ($s_stmt02->num_rows > 1) {
		$s_stmt02->close(); 
		/* check if more than 1 feedback records * exceeded 1 feedbacks from same user */
		rtnwebapp('error' , 'feedback-fail' , 'post');	   					           /* test for feedback exceeded */
		exit();

	}

	$s_stmt02->close(); 

 	/* 15 data items * prepare php data scrubbed vars */

	/* if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1")) { */
	if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1")) {	
		$s_stmt01->bind_param('s', $vuser);                        /*  bind $db_email_current to parameter, if confirm email, or $db_email_past if update email */
		log_error($timelocal,'bind_param', $s_stmt01, $mysqli); 
		$s_stmt01->execute();                                      /*  execute the prepared query. */
		log_error($timelocal,'execute', $s_stmt01, $mysqli);
		$s_stmt01->store_result();
		/* $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
		$s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);     /* get variables from result. */
		$s_stmt01->fetch();
									
	} else {
							  
	    log_error($timelocal,'prepare', $s_stmt01, $mysqli);
		$s_stmt01->close();    	 
	}

	/* registered user may not have completed registration ( blank email address ) * if blank use email address given in feedback form */

	if ( $db_email_current === '') { $db_email_current = $vmail; }


    /* check to see if we have the twitter screen name or the current email * capture the user_id * returned rows */
		
	switch (true) {
	  case ($s_stmt01->num_rows > 0):
			$s_stmt01->close();
		    /* uname * email check * already a registered user */

		    if  ($i_stmt01 = $mysqli_feedback->prepare("REPLACE INTO feedback_members (user_id, uname, email_current, email_confirm, api_key, uid, a, b, c, d, e, comments, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      							
	 			 $i_stmt01->bind_param('issssssssssssssssss', $db_user_id, $db_uname, $db_email_current, $db_email_confirm, $db_api_key, $db_uid, $a, $b, $c, $d, $e, $comments, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);
				 log_error_feedback($timelocal,'bind_param', $i_stmt01, $mysqli_feedback); 
	 			 /* execute the prepared query. */
	             $i_stmt01->execute();
	             log_error_feedback($timelocal,'execute', $i_stmt01, $mysqli_feedback);
	             /* $insert_stmt->store_result(); */
	             $i_stmt01->close();
	             /* return true; */

	             $feedback_received = 3;        /* registered user completed * send tweet *  $send_tweet = true */
	             // or  $feedback_received = 1; /* registered user completed * send email *  $send_email = true */
	        
	        } else {
	 				     
	 			 /* namecheck failed in db, return false; */
	             log_error_feedback($timelocal,'prepare', $i_stmt01, $mysqli_feedback);
	             $i_stmt01->close();
           	
           	}

	  break;

	  case ($s_stmt01->num_rows === 0):
			$s_stmt01->close();
		    /* uname * email check * not already a registered user * generate a user id */

		    /* new user id simpleflake */
            $user_id = simpleflake();

		    $email_confirm = 0;
	        $api_key = 0;
	        $from_user_uid = 0;

		    $ip_address = $_SERVER['REMOTE_ADDR'];
	        /* $ip_address = mysql_real_escape_string($ip_address); */
	   		$ip_address = mysqli_real_escape_string($mysqli_feedback, $ip_address);
	        $fcode = 0;

	        date_default_timezone_set("UTC");
    		$now = time();

    		$timezone_user = $timezo;
            
            if ($timezo == '') {
                $timezone_user = 'Europe/Malta'; // error timezone --> UTC+01:00
    		}

    		$date = new DateTime(null, new DateTimeZone($timezone_user));
   			$timelocal_user = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));

		    if  ($i_stmt02 = $mysqli_feedback->prepare("REPLACE INTO feedback_members (user_id, uname, email_current, email_confirm, api_key, uid, a, b, c, d, e, comments, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      							
	 			$i_stmt02->bind_param('issssssssssssssssss', $user_id, $vuser, $vmail, $email_confirm, $api_key, $from_user_uid, $a, $b, $c, $d, $e, $comments, $ip_address, $pltfrm, $browsr, $fcode, $time, $timezone_user, $timelocal_user);
				log_error_feedback($timelocal,'bind_param', $i_stmt02, $mysqli_feedback); 
	 			/* execute the prepared query. */
	            $i_stmt02->execute();
	            log_error_feedback($timelocal,'execute', $i_stmt02, $mysqli_feedback);
	            /* $insert_stmt->store_result(); */
	            $i_stmt02->close();
	            /* return true; */

	            $feedback_received = 2;  /* unregistered user completed * send email *  $send_email = true */
	        
	        } else {
	 				     
	 			/* namecheck failed in db, return false; */
	            log_error_feedback($timelocal,'prepare', $i_stmt02, $mysqli_feedback);
	            $i_stmt02->close();
           	
           	}
	  break;

	}

	if ($send_email) {

	  switch (true) {

	   case ($feedback_received === 1):  /*  registered user completed * send email *  $send_email = true */
	   		 sendmailfeedback($db_email_current, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr'); 
	   break;
	   case ($feedback_received === 2): /* unregistered user completed * send email *  $send_email = true */
	   		 sendmailfeedback($vmail, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr');
	   break;

	  }
	}

	if ($send_tweet) {

	  switch (true) {

	   case ($feedback_received === 3): /* registered user completed * send tweet * $send_tweet = true */

	   		 $tweet_token = 'from crowdcc : all feedback and comments are read and considered, feedback is vital to the future direction and growth of crowdcc thank you.';

	   		 sendmailfeedback($db_email_current, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr');
	   		 
	   		 sendtweetfeedback($db_uname, $tweet_token, $db_oauth_token, $db_oauth_token_secret); /* registered user completed * send tweet * may only get the message if user follows crowdcc */
	   		 
	   break;

	  }
	}

 }


/* send direct message to filtered / checked twitter account */  

function sendtweetfeedback($ucode, $tweet_token, $oauth_token, $oauth_token_secret) {

	/* $tweet_token = substr($tweet_token, 0, -11);      # process incoming token to 128 char (check removal of token char info) */

	$uauth_token = ''; $uauth_token_secret = '';
	    		
	$uauth_token = ccrypt( $oauth_token , 'AES-256-OFB', 'de' );                  /* un-encrypt out of storage */
	$uauth_token_secret = ccrypt( $oauth_token_secret , 'AES-256-OFB', 'de');	  /* un-encrypt out of storage */

	/* $ucode = 'glynthom'; * for display purposes only */
	/* currently using tokens from -- > glynthom (for test only) */
	/* $oauth_token = '295131454-wYMOwfuKhz1dYeAPGddzAWDa6h2UZtX5sIghJhAQ'; */
	/* $oauth_token_secret = 'KDYvGgWDiCO4UXmywPjef04ampfRnwwTWspC8DZ6zBc'; */

    /* will be sent from the SAME user's tokens (same name domain policy) */

	/* initialize the connection */
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $uauth_token, $uauth_token_secret);

	/* send a direct message */
	$options = array("screen_name" => $ucode, "text" => $tweet_token);
    $connection->post('direct_messages/new', $options);

    /* message may be rejected by twiter in this context as the user may not follow crowdcc, result in $connection->lastHttpCode() !== 200 this is normal */

   	/* if ( $connection->lastHttpCode() !== 200 ) { rtnwebapp('error' , 'twitter-conn-fail' , 'post'); exit(); } */

}


function rtnwebapp( $flag, $token, $whofor ) {

 /*  function is passed the following ;
  *
  *  $msgcode    -> status or error
  *  $token_safe -> token data || status data || error data
  *  $whofor     -> post || get
  *
 */

	switch ($whofor) {
     
		case ('get'):
			  echo json_encode( array( "ccf" => $token ) );
			  # unset vars
			  # unset($method, );
			  exit();
		break;

		case ('post'):
			  // echo json_encode( $flag . ciphermod($token) );
			  echo json_encode( $flag . ' ' . $token );
		      # unset vars
		      # unset($method, );
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

function is_zero($value) {
	$iszero = 'false';
	if ( empty($value) ) {
		 $iszero = 'true';
	}
    return $iszero;
}

function valid_key($key) {
	$kvalid = false;

	switch (true) {
	  
	  case (strlen($key) == 128):
			$kvalid = true;
	  break;
		
	}

  return $kvalid;

}


function valid_scode($scode) {
	$iscode = false;
	switch (true) {

		case ($scode === '_ccf'):
			  $iscode = true;
		break;

	}
	return $iscode;
}


function valid_username($username) {
    return preg_match('/^[A-Za-z0-9_]{1,15}$/', $username);
}


function arrayfilter($var) {
  	return ($var !== NULL && $var !== FALSE && $var !== '');
}


function validate_date($date, $format = 'Y-m-d H:i:s') {

    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}


function valid_timestamp($timestamp) {

	$ret = false;
	$re_sep='[\/\-\.]';
	# $re_time='( (([0-1]?\d)|(2[0-3])):[0-5]\d)?';
	$re_time='( (([0-1]?\d)|(2[0-3])):[0-5]\d:[0-5]\d)?'; # now accept the format 'Y-m-d H:i:s':

	$re_d='(0?[1-9]|[12][0-9]|3[01])'; $re_m='(0?[1-9]|1[012])'; $re_y='(19\d\d|20\d\d)';

	if (!preg_match('!' .$re_sep .'!',$timestamp)) { 

	    $timestamp = strftime("%d-%m-%Y %H:%M",$timestamp); 

	};  # convert Unix timestamp to entryFormat

	if (preg_match('!^' .$re_d .$re_sep .$re_m .$re_sep .$re_y. $re_time. '$!',$timestamp, $m))      # dd-mm-yyyy
		$ret = checkdate($m[2], $m[1], $m[3]);
	elseif (preg_match('!^' .$re_y .$re_sep .$re_m .$re_sep .$re_d. $re_time. '$!',$timestamp, $m))  # yyyy-mm-dd
		$ret = checkdate($m[2], $m[3], $m[1]);
	elseif (preg_match('!^' .$re_m .$re_sep .$re_d .$re_sep .$re_y. $re_time. '$!',$timestamp, $m))  # mm-dd-yyyy
		$ret = checkdate($m[1], $m[2], $m[3]);

	return $ret && strtotime($timestamp);
}

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

function valid_date($date) {
    $isdate = false;
 	//$date = "01/02/0000";
	$date = date_parse($date); // or date_parse_from_format("d/m/Y", $date);

	if (checkdate($date['month'], $date['day'], $date['year'])) {
    //  valid Date
		$isdate = true;
    }

   return $isdate;
}


function signin_token($ucode, $ecode_in, $ecode_token, $oauth_token, $oauth_token_secret, $platform_user, $browser_user, $timezone, $mysqli) {


	      /* signin_token($ucode, $ecode_in, $matchr[1], $matchr[2], $matchr[3], $pltfrm, $browsr, $timezo, $mysqli); */
			
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
				 $email_current = $ecode_token;
				 $ecode_target = $ecode_in;

				 /* $ecode_target = $ecode_token; */
			break;
			
		}
        					    
		$used = 0;
		$eraser = 0;

		/* $token_send   = getrandomstr(120, dechex(time()),'t'); */
		/* $token_store  = getrandomstr(120, $ecode_target,'e'); */

		$token_send   = getrandomstr(dechex(time()),'t');
		/* log_found('fe signin_token', $token_send, '$token_send', __LINE__ ); */

		$token_store  = getrandomstr($ecode_target,'e');
		/* log_found('fe signin_token', $token_store, '$token_store', __LINE__ ); */

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

	
		if ($i_stmt07) {

		/*  mailresetlink($ecode_target, $token_send);	                                                 // all good, signin token created */
			mailresetlink($ecode_target, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr', $token_send);  // all good, signin token created

			sendtweet($ucode, $token_store[1], $oauth_token, $oauth_token_secret);

		} else {
													        /* db error, unknown at this point, check log (send notification back to user?) */
		}
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


	function ciphermod($phrasein) {

    /* 

    experimental cipher mod, based on a homophonic substitution cipher.
    $items is the zero array set (distribution below), this can be enhanced by
    counting how many time for example the letter a has been pressed, or changing the
    set based on day of the week (for example) ...

	switch (true) {
	   case ($strset === 0):
	         $items = array(09,12,33,47,53,67,78,92);
	   break;
	   case ($strset === 1):
	         $items = array(76,25,99,02,33,56,64,22); // example only
	   break;
	}

    mod: leading zero's and repeat characters do not work well with array_rand
    so for all leading zero's a 1 is added in front, so 09 === 109 

    */

    $alpha = ''; $rtn = ''; $phraseout = '';
    $pcount = mb_strlen( $phrasein );
    $str = str_split( $phrasein );

	for ($i = 0; $i < $pcount; $i++) {

		$alpha = $str[$i];

		switch ($alpha) {

			case ('a'):
	      	  	  $items = array(109,12,33,47,53,67,78,92);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('b'):
	      	  	  $items = array(48,81);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	    	case ('c'):
	      	  	  $items = array(13,41,62);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	 		case ('d'):
	      	 	  $items = array(101,03,45,79);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('e'):
	      	  	  $items = array(14,16,24,44,46,55,57,64,74,82,87,98);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('f'):
	      	  	  $items = array(10,31);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	    	case ('g'):
	      	  	  $items = array(106,25);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	 		case ('h'):
	      	 	  $items = array(23,39,50,56,65,68);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('i'):
	      	  	  $items = array(32,70,73,83,88,93);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	    	case ('j'):
	      	  	  $items = array(15);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	 		case ('k'):
	      	 	  $items = array(104);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('l'):
	      	  	  $items = array(26,37,51,84);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	    	case ('m'):
	      	  	  $items = array(22,27);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	 		case ('n'):
	      	 	  $items = array(18,58,59,66,71,91);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('o'):
	      	 	  $items = array(100,105,107,54,72,90,99);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('p'):
	      	  	  $items = array(38,95);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	    	case ('q'):
	      	  	  $items = array(94);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	 		case ('r'):
	      	 	  $items = array(29,35,40,42,77,80);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('s'):
	      	 	  $items = array(11,19,36,76,86,96);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('t'):
	      	  	  $items = array(17,20,30,43,49,69,75,85,97);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	    	case ('u'):
	      	  	  $items = array(108,61,63);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	 		case ('v'):
	      	 	  $items = array(34);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('w'):
	      	  	  $items = array(60,89);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	    	case ('x'):
	      	  	  $items = array(28);
	      	  	  $rtn = $items[array_rand($items)];
			break;
	 		case ('y'):
	      	 	  $items = array(21,52);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('z'):
	      	 	  $items = array(102);
	      	  	  $rtn = $items[array_rand($items)];
			break;
			case ('-'):
				  $items = array(88,65,39,23,13);
	      	  	  $rtn = $items[array_rand($items)];
			break;

		}

		$phraseout = $phraseout . $rtn;
	}

	/* distribution
	00,01,02,03,04,05,06,07,08,09,10,11,12,14,15,16,17,18,19,20,21,22,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,40,
	42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,
	81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99                                                            
	*/

 	return $phraseout;

    } 


	function randomstring($length = 10) {
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        	$randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
    	}

    	#  $random = substr( md5(rand()), 0, 7);
    	#  echo $random;

    return $randomString;
	
	}

	/*
	function sendmailfeedback($to) {
		$subject = "Hello from crowdcc";
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		$message = '
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Thank you for your crowdcc feedback</title>
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
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<h4>Hi</h4>
		<p>All feedback and comments you leave are read and considered, if this is your current email address and you have already registered this with us, then great.</p>
		We will be able to stay in touch with you and may occasionally send you crowdcc updates which your feedback may have inspired.</p>
	    <p>Future notifications and important information regarding your account will be sent to this email address</p>
		<p>Your feedback along with with other feedback we receive is vital to the future direction and growth of crowdcc.</p>
		<p>Thank you for your feedback.<p>
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
		<span>Have a question or just want to say hello? <a href="https://twitter.com/crowdccHQ" style="color:#2F5BB7;">tweet us</a></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message, please don\'t reply directly to this email, this email key is valid for 2 hours.</span>
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
		$headers .= 'From: hello@crowdcc.com <hello@crowdcc.com>' . "\r\n";
		$headers .= "Return-path: <bounce@crowdcc.com>\r\n";
		$headers .= "Errors-To: <bounce@crowdcc.com>\r\n";
		if (mail($to,$subject,$message,$headers,"-fbounce@crowdcc.com")) {		 									  
		} else {
			rtnwebapp('error' , 'email-conn-fail' , 'post');   // email pass (found in db, pass to token function), but have failed to be able to send it to the email address provided !
            exit();											  
		}
	}
    */

    function sendmailfeedback($to, $smtpmail, $stmppass) {

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
		$mail->Subject = 'Hello from crowdcc';

		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;

		$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Thank you for your crowdcc feedback</title>
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
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<h4>Hi</h4>
		<p>All feedback and comments you leave are read and considered, if this is your current email address and you have already registered this with us, then great.</p>
		We will be able to stay in touch with you and may occasionally send you crowdcc updates which your feedback may have inspired.</p>
	    <p>Future notifications and important information regarding your account will be sent to this email address</p>
		<p>Your feedback along with with other feedback we receive is vital to the future direction and growth of crowdcc.</p>
		<p>Thank you for your feedback.<p>
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
		<span>Have a question or just want to say hello? <a href="https://twitter.com/crowdccHQ" style="color:#2F5BB7;">tweet us</a></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message, please don\'t reply directly to this email, this email key is valid for 2 hours.</span>
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
		    rtnwebapp('error' , 'email-conn-fail' , 'post');   // email pass (found in db, pass to token function), but have failed to be able to send it to the email address provided !
            /* email fail (found in db, pass to token function), but have failed to be able to send it to */
            /* to the email address provided, please try again ! */
            /* social, no twitter account details found */
            exit();											  	 									  
		} else {
			// rtnwebapp('error' , 'error_pass_ecode' , 'post', '', ''); // no success message!
			/* email pass (found in db, pass to token function) */                        
			/* social, no twitter account details found */
			/* echo "We have sent the password reset link to your email id <b>".$to."</b>"; */											  
		}
	}


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
		$mail->setFrom('recover@crowdcc.com', 'recover@crowdcc.com');

		/* Set an alternative reply-to address */
		$mail->addReplyTo('noreply@crowdcc.com', 'no reply');

		/* Set who the message is to be sent to */
		// $mail->addAddress($to, $fullname);
		$mail->addAddress($to);

		/* Set the subject line */
		$mail->Subject = 'Recover your crowdcc account';

		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;

		$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Recover your crowdcc contact email</title>
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
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<h4>Hi</h4>
		<p>Please read this email carefully, it contains instructions on how to recover your <b>crowdcc account</b>.
		<p>
		This email contains a digital key;<p>
		(copy all the green text)<p>
		<b><p>crowdcc email key:</p></b>
		<span style="display:block;width:280px;word-wrap:break-word;color:#3d623b">'. $token_send[1] .'</span></p>
		You will shortly receive a twitter digital key directly sent (messaged) privately to your crowdcc twitter account.</p>
		Both <b>digital keys</b> will be required to reset and recover your account.  When you have received <b>both digital keys</b></p>
		please return to the <span style="color:#4488F6">crowdcc:help signing in to your account</span> browser page for further instructions.</p>
		<p>Any future notifications and important information regarding your account will be sent to this address.</p>
		<p>If you didn\'t make this request then you can safely ignore this email.</p>
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
		<span>Have a question or just want to say hello? <a href="https://twitter.com/crowdccHQ" style="color:#2F5BB7;">tweet us</a></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message, please don\'t reply directly to this email, this email key is valid for 2 hours.</span>
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
 		    rtnwebapp('error' , 'email-conn-fail' , 'post');
 		    /* email fail (found in db, pass to token function), but have failed to be able to send it to */
            /* to the email address provided, please try again ! */
            /* social, no twitter account details found */
            exit();							 									  
		} else {
			// rtnwebapp('error' , 'error_pass_ecode' , 'post', '', ''); // no success message!
			/* email pass (found in db, pass to token function) */                        
			/* social, no twitter account details found */
			/* echo "We have sent the password reset link to your email id <b>".$to."</b>"; */									  
		}
	}
	

?>