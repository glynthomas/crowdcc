<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* callreset
*
* user signs in with a twitter oauth, or auth with crowdcc account,
* we store and update user record ... the account is either new
* or existing updated.
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

/* db config files */
//require_once('db/db_config.php');

/* start session and load library. */
secure_session_start(); // Our custom secure way of starting a php session.

/* found log * log failure outside the JS console ( please comment out / remove later ) */
/* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */

/* phpmailer lib * auth mail lib files * mailresetlink($to, $token_send) * see ccmail app.error.php */
require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');

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
 
	if (!isset($_POST) ) { unset($_SESSION['token']); rtnwebapp('error' , 'bad-post' , 'post');}

	$post_in = $_POST;
    $post_in = explode(":", implode($post_in));
    $token = base64_decode( $post_in[0] );

    $_SESSION['token'] = (isset($_SESSION['token']) ? $_SESSION['token'] : null);

    if ( $_SESSION['token'] !== $token ) { unset($_SESSION['token']); rtnwebapp('error' , 'bad-token' , 'post');}

    $scode = ''; $scode_clean = '';
    $scode = base64_decode($post_in[1]);											// $scode status of post -> _ccu user data | _cca authorise tokens
    $scode_clean = filter_var( $scode ,  FILTER_SANITIZE_STRING);				    // $scode_clean (any problems chars stripped out)

    if (valid_scode($scode_clean) === false) { unset($_SESSION['token']); rtnwebapp('error' , 'bad-token' , 'post');}

    switch (true) {

	 case ($scode === '_ccu'):

	   $ucode  = ''; $ucode_clean = '';
	   $ecode = ''; $ecode_clean  = '';

	   $lcode = ''; $lcode_clean = '';
	   $dcode = ''; $dcode_clean = '';
	   
	   $pltfrm = ''; $pltfrm_clean = '';
	   $browsr = ''; $browsr_clean = '';
	   $timezo = ''; $timezo_clean = '';
	   $matchr = '';

	   /*
	   print_r( $token );
	   print_r('|');
	   print_r( $scode );
	   print_r('|');
	   print_r( decrypt($post_in[2]) );
	   print_r('|');
	   print_r( decrypt($post_in[3]) );
	   print_r('|');
	   print_r( base64_decode($post_in[4]) );
	   print_r('|');
	   print_r( base64_decode($post_in[5]) );
	   print_r('|');
	   print_r( base64_decode($post_in[6]) );
	   print_r('|');
	   print_r( base64_decode($post_in[7]) );
	   print_r('|');
	   print_r( base64_decode($post_in[8]) );
	   */

	   $ucode  = decrypt($post_in[2]); 										        // $ucode
	   $ecode  = decrypt($post_in[3]);											    // $ecode posted email

	   $lcode = base64_decode($post_in[4]);										    // $lcode

	   $dcode = base64_decode($post_in[5]);                                         // $dcode
	   
	   $pltfrm = base64_decode($post_in[6]);  										// $pltfrm
	   $browsr = base64_decode($post_in[7]);									    // $browsr
	   $timezo = base64_decode($post_in[8]);									    // $timezo


	   /* validate input - test for all failures */

	   /* username intial filter */

   	   $ucode_clean  = filter_var( $ucode , FILTER_SANITIZE_STRING);  				// $ucode_clean (any problem chars stripped out)

	   /* email inital validation */

	   if (filter_var($ecode, FILTER_VALIDATE_EMAIL)){
           $ecode_clean = filter_var( $ecode, FILTER_SANITIZE_EMAIL);	            // $ecode_clean (any problem chars stripped out)
       } else {
       	   rtnwebapp('error' , 'email-fail' , 'post');								// test for failure									
       }

       /* locked (radio) validation */

       $lcode_clean = filter_var( $lcode , FILTER_SANITIZE_STRING);                 // $lcode_clean

       /* date validation */

       if (!DateTime::createFromFormat('d/m/Y', $dcode)) {
	   	   rtnwebapp('error' , 'date-format-fail' , 'post');				 	    // test for failure	
	   } 
	
	   /* other platform data sanitation */

	   $pltfrm_clean = filter_var( $pltfrm , FILTER_SANITIZE_STRING);  				// $pltfrm_clean
	   $browsr_clean = filter_var( $browsr , FILTER_SANITIZE_STRING);  				// $browsr_clean
	   $timezo_clean = filter_var( $timezo , FILTER_SANITIZE_STRING);  				// $timezo_clean

	   /* validation checks */

	   switch (true) {

	   	case (valid_username($ucode_clean) === false):
	   		  // print_r('username fail' . $ucode_clean);
	   		  rtnwebapp('error' , 'username-fail' , 'post');	   					// test for failure	
	   	break;

	   	case (valid_email($ecode_clean) === false):   		
	          // print_r('email fail ' . $ecode_clean);			
	    	  rtnwebapp('error' , 'email-fail' , 'post');						    // test for failure	
	    break;
	    
	    case ($ecode !== $ecode_clean):
	    	  // print_r('email fail' . $ecode_clean);
	    	  rtnwebapp('error' , 'email-fail' , 'post');						    // test for failure	
	    break;

	    case ($lcode !== $lcode_clean):
	    	  // print_r('data tamper' . $lcode_clean);							     
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    // test for failure	
	    break;

	    case (valid_date($dcode) === false):   		
	          // print_r('this date id fucked ->' . $dcode);				            
	    	  rtnwebapp('error' , 'date-fail' , 'post');		  				    // test for failure	
	    break;
	
	    case ($pltfrm !== $pltfrm_clean):
	    	  // print_r('data tamper ->' . $pltfrm_clean);
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    // test for failure	
	    break;

	    case ($browsr !== $browsr_clean):
	    	  // print_r('data tamper ->' . $browsr_clean);
	          rtnwebapp('error' , 'data-tamper' , 'post');						    // test for failure	
	    break;

	    case ($timezo !== $timezo_clean):
	    	  // print_r('data tamper ->' . $timezo_clean);
	    	  rtnwebapp('error' , 'data-tamper' , 'post');						    // test for failure	
	    break;

	   }

       /*
	   print_r( $scode );
	   print_r('|');
	   print_r( $ucode );
	   print_r('|');
	   print_r( $ecode_clean );
	   print_r('|');
	   print_r( $lcode_clean );
	   print_r('|');
	   print_r( $dcode );
	   print_r('|');
	   print_r( $token );
	   print_r('|');
       print_r( $_SESSION['token'] );
	   print_r('|');
	   print_r( $pltfrm );
	   print_r('|');
	   print_r( $browsr );
	   print_r('|');
	   print_r( $timezo );
	   */

	   /* validation checks completed - if we get here, data tested good, should match correct entered data on the clients form.
	      user data needs to checked against the app db to ensure it matches;

	      - twitter username
	      - old email address
	      - date of last signin (approx)  
		  
		  if

		  * no match found (false) ;

		  - return to user a non specific error message and ask them to contact us directly via twitter quoting error message

		  * match found (true) ;

		  - return to user message that email has been sent with account recovery instructions;

		    * link to account recovery page, ask user to paste in token (sent with the email * no link for outside app validation)

		    * and paste in token sent via twitter to user id, via direct message.

		  if both tokens match, users account is reset back to old (original) email address and the user is asked to reset password
		  to a more secure one and not share this password with anyone else ... !      	

	    */

	     $matchr = recover_account($ucode, $ecode, $lcode, $dcode, $timezo, $mysqli);

	     $matchr = str_replace(' ', '', $matchr);

	     $matchr = explode(":", $matchr);

	     /*  $matchr[0] === correct : $matchr[1] === process : $matchr[2] === current_email : $matchr[3] === oath_token : $matchr[4] === oath_token_secret */

	     if ($matchr[0] === 'correct') {

	       /* 
		
		   we are now ready to recover the account (account is not locked) , full account recovery happens in several stages;
			
		   1. send account recovery email with 1/2 the recovery token (validate past email address) */
		
		   /* signin_token($ecode, '_ccu', current_email, oath_token, oath_token_secret, $pltfrm, $browsr, $timezo, $mysqli);      /* old method (ignore current email address) */

		   // signin_token($ucode, $ecode, $matchr[1], $matchr[2], $matchr[3], $pltfrm, $browsr, $timezo, $mysqli);                   /* new, capture current email address too    */

		   signin_token($ucode, $ecode, $matchr[2], $matchr[3], $matchr[4], $pltfrm, $browsr, $timezo, $mysqli);                   /* new, capture current email address too    */

		   /*

		   2. send account recover direct message tweet the other 1/2 of the recovery token (validate current valid twitter / crowdcc account)

		   3. if both 1/2's match up and the token is valid (not tanpered) and not expired then onto stage 4

		   4. the account's password is over-ridden with a new random one and the past email address is now the current email address

		   5. the past email address is now BLANK	 

	   	   */

		   /* signin token :: all errors are reported back, so if we get here, no errors */

		   /* echo json_encode( array( "genuine request if true: " => $matchr[0] ) );  // return strings err codes, either success or failure ! */

	     }

	       rtnwebapp($matchr[0] , $matchr[1] , 'post');

	 break;

	 case ($scode === '_cca'):

	 	   $ekey  = ''; $ekey_clean = '';
	       $tkey = ''; $tkey_clean  = '';

	       $pltfrm = ''; $pltfrm_clean = '';
	   	   $browsr = ''; $browsr_clean = '';
	   	   $timezo = ''; $timezo_clean = '';

	   	   $process_code = '';

	 	   $ekey  = decrypt($post_in[2]); 										    	// $ekey
	       $tkey  = decrypt($post_in[3]);												// $tkey
	   
	   	   $pltfrm = base64_decode($post_in[4]);  										// $pltfrm
	   	   $browsr = base64_decode($post_in[5]);									    // $browsr
	   	   $timezo = base64_decode($post_in[6]);									    // $timezo

	   	   /* validate input - test for all failures */

	   	   $ekey = trim_all( $ekey );
	   	   $tkey = trim_all( $tkey );

	   	   $ekey_clean  = filter_var( $ekey , FILTER_SANITIZE_STRING);  				// $ekey_clean (any problem chars stripped out)
	   	   $tkey_clean  = filter_var( $tkey , FILTER_SANITIZE_STRING);  				// $tkey_clean (any problem chars stripped out)

	   	   /* other platform data sanitation */

	   	   $pltfrm_clean = filter_var( $pltfrm , FILTER_SANITIZE_STRING);  				// $pltfrm_clean
	   	   $browsr_clean = filter_var( $browsr , FILTER_SANITIZE_STRING);  				// $browsr_clean
	   	   $timezo_clean = filter_var( $timezo , FILTER_SANITIZE_STRING);  				// $timezo_clean

	   	   /* validation checks */

	   	   switch (true) {

	   		case (valid_key($ekey_clean) === false):
	   		      /* log_found('callreset :: valid_key($ekey_clean) :: $ekey_clean',  $ekey_clean. ' | '. strlen($ekey_clean) , 'start:callreset.php', __LINE__ ); */
	   		      rtnwebapp('error' , 'ekey-fail' , 'post');
	   		break;

	   		case (valid_key($tkey_clean) === false):   		
	   		      /* log_found('callreset :: valid_key($ekey_clean) :: $tkey_clean',  $tkey_clean. ' | '. strlen($tkey_clean) , 'start:callreset.php', __LINE__ ); */
	   		      rtnwebapp('error' , 'tkey-fail' , 'post');
	    	break;
	    
	    	case ($ekey !== $ekey_clean):
	    	      rtnwebapp('error' , 'data-tamper' , 'post');
	    	break;

	    	case ($tkey !== $tkey_clean):
	    		  rtnwebapp('error' , 'data-tamper' , 'post');
	    	break;
	
	    	case ($pltfrm !== $pltfrm_clean):
	    	  	  rtnwebapp('error' , 'data-tamper' , 'post');
	        break;

	    	case ($browsr !== $browsr_clean):
	    	      rtnwebapp('error' , 'data-tamper' , 'post');
	        break;

	    	case ($timezo !== $timezo_clean):
	    	      rtnwebapp('error' , 'data-tamper' , 'post');
	        break;

	       }

           /*
           print_r( $token );
       	   print_r('|');
	       print_r( $_SESSION['token'] );
	   	   print_r('|');
	   	   print_r( $scode );
	   	   print_r('|');
	   	   print_r( $ekey );
	   	   print_r('|');
	   	   print_r( $ekey_clean );
	   	   print_r('|');
	   	   print_r( $tkey );
	   	   print_r('|');
	   	   print_r( $tkey_clean );
	   	   print_r('|');
	   	   print_r( $pltfrm );
	   	   print_r('|');
	   	   print_r( $browsr );
	   	   print_r('|');
	   	   print_r( $timezo );
	   	   */

	   	   /*

		   final part of the account recovery, at this point, if all user data received was good and all keys posted to crowdcc (via email and twitter direct message)
		   were valid then the following will happen;

		   1. process tokens, both keys will be checked against the signin token.

		   2. keys checked to see they are both valid.
 
  		   3. token key email data is used to swap over past email address to current, and current email address to past.

  		   4. the password is reset to a new random unguessable one. (we are done)

	   	   */

	   	   reverse_account($ekey, $tkey, $pltfrm, $browsr, $timezo, $mysqli);

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

	   $_SESSION['token'] = $token_safe;

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

function reverse_account($ekey, $tkey, $pltfrm, $browsr, $timezo, $mysqli) {

	global $timelocal;

	/* test key tokens, ensure valid tokens */

	$tokensend = $ekey;

	$start = 1;

	$dextime = getstrmsgreset($start, $tokensend, 't'); 		// time in string revealed (should be same as time in emailed string) (use as a check)
	$dextime = hexdec($dextime);

    $token_time = date("Y-m-d H:i:s", $dextime);
    $token_time = (string)$token_time;

	$now = time();   		               	     		// compare timestamp to now

	$diff = $now - $dextime;                     		// unix time diff simple calc ,substract from each other
   	$d = $diff / 86400 % 7;
   	$h = $diff / 3600 % 24;
   	$m = $diff / 60 % 60; 
   	$s = $diff % 60;
	
	switch (true) {

		case (!valid_timestamp($token_time)):
			  echo ' ...time stamp is not valid ;-) :: ';
			  /* error message timestamp invalid */
			  rtnwebapp('error' , 'time-invalid' , 'post');
			  exit(); 
		break;

		case ($tokensend !== ''):

			  switch (true) {
				 case ($h >= 2):
					   /* greater than 2 hours old --> timeout invalid */
					   echo ' ... timestamp is ' . $h .':'. $m .':'. $s . ' old and invalid :: ';
					   echo $tokensend;
					   /* error message timestamp is too old */
					   rtnwebapp('error' , 'token-old' , 'post');
					   exit(); 	
				 break;
			  }

		break;

	}

	if  ($s_stmt03 = $mysqli->prepare("SELECT tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser FROM signin_token WHERE tokensend = ? LIMIT 1")) {
      	 $s_stmt03->bind_param('s', $ekey);
		 log_error($timelocal,'bind_param', $s_stmt03, $mysqli); 
		 /* execute the prepared query. */
         $s_stmt03->execute();
         log_error($timelocal,'execute', $s_stmt03, $mysqli);
         $s_stmt03->store_result();  
	     $s_stmt03->bind_result($db_tokensend, $db_tokenstore, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone_user, $db_timeissued, $db_used, $db_eraser);      // get variables from result.
		 $s_stmt03->fetch();
		 $s_stmt03->close();

    } else {

         /* store token failed in db return false; unset session, remove cookies, log any prepare statement errors */
         /* secure_session_destroy(); */
         log_error($timelocal,'prepare', $s_stmt03, $mysqli);
         /* echo json_encode('token_store_failed'); */
         $s_stmt03->close();
    }


    /* initial tests for failure tests should now be completed ... now we validate against data from token */


    switch (true) {

    	case ( strpos($db_email_past, getstrmsgreset(1, $tkey, 'e') ) === false ):
    		  # echo ' ... tkey sample email string d@g in string full email unbiose d@g mail.com does not match ;-) :: ';
    	      rtnwebapp('error' , 'token-no-match' , 'post');
			  exit(); 	
    	break;

    	case ($tkey !== $db_tokenstore):
    		  # echo ' ... tkey does not match ;-) :: ';
    	      rtnwebapp('error' , 'token-no-match' , 'post');
			  exit(); 	
    	break;
    	
    	case ($token_time !== $db_timeissued):
    		  # echo ' ... token time does not match ;-) :: ';
    		  rtnwebapp('error' , 'time-no-match' , 'post');
			  exit(); 	
    	break;
    	
    	case (base64_decode($pltfrm) !== $db_platform_user):
    		  # echo ' ... platform user does not match ;-) :: ';
    	      rtnwebapp('error' , 'platfrm-no-match' , 'post');
			  exit(); 	
    	break;

    	case (base64_decode($browsr) !== $db_browser_user):
    		  # echo ' ... browser does not match ;-) :: ';
    		  rtnwebapp('error' , 'browsr-no-match' , 'post');
			  exit(); 	
    	break;

    	case (base64_decode($timezo) !== $db_timezone_user):
    		  # echo ' ... timezone does not match ;-) :: ';
    		  rtnwebapp('error' , 'timezo-no-match' , 'post');
			  exit(); 	
    	break;

    }

    /* validation token tests now complete ... now we check email address with registered user (addtional failure test) */

	/* if ($s_stmt04 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_past = ? LIMIT 1")) { */
	if ($s_stmt04 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_past = ? LIMIT 1")) {	
		$s_stmt04->bind_param('s', $db_email_past);                /*  bind $db_email_current to parameter, if confirm email, or $db_email_past if update email */
		log_error($timelocal,'bind_param', $s_stmt04, $mysqli); 
		$s_stmt04->execute();                                      /*  execute the prepared query. */
		log_error($timelocal,'execute', $s_stmt04, $mysqli);
		$s_stmt04->store_result();

		/* $s_stmt04->bind_result($db_user_id, $db_uname, $db_reg_email_past, $db_reg_email_current, $db_reg_email_confirm, $db_api_key, $db_reg_passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
		$s_stmt04->bind_result($db_user_id, $db_uname, $db_reg_email_past, $db_reg_email_current, $db_reg_email_confirm, $db_api_key, $db_reg_passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);     // get variables from result.
		$s_stmt04->fetch();
		$s_stmt04->close(); 
									
	} else {
							  
	    log_error($timelocal,'prepare', $s_stmt04, $mysqli);
		$s_stmt04->close();    	 
	}


	switch (true) {

		case ($db_email_current !== $db_reg_email_current):
    		  # echo ' ... current email address does not match WTF? :: ';
    	      rtnwebapp('error' , 'email-no-match' , 'post');
			  exit(); 	
    	break;

	}

	/* password reset */

    # fill current passcode field with random 128 character set, (reset to unknown password)
	$passcode = randomstring(128);
    
	/* email switch-aroo */

	/* if ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
	if ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
		/* $i_stmt01->bind_param('isssssssssssssssss', $db_user_id, $db_uname, $db_reg_email_current, $db_reg_email_past, $db_reg_email_confirm, $db_api_key, $passcode, $db_random_salt, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal); */
		$i_stmt01->bind_param('issssssssssssssss', $db_user_id, $db_uname, $db_reg_email_current, $db_reg_email_past, $db_reg_email_confirm, $db_api_key, $passcode, $db_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone, $db_timelocal);
		log_error($timelocal,'bind_param', $i_stmt01, $mysqli); 
	    /* execute the prepared query. */
		$i_stmt01->execute();
		log_error($timelocal,'execute', $i_stmt01, $mysqli);
		/* $i_stmt04->store_result(); */
		$i_stmt01->close();

		$usr_update = 1;

	} else {

		log_error($timelocal,'prepare', $i_stmt01, $mysqli);
		$i_stmt01->close();
	}


	switch (true) {

		case ($usr_update === 1):

			  $db_locked = 0; $db_visits = 0; $db_token_user = 0;

			  if ($i_stmt02 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
				  $i_stmt02->bind_param('issssssssssss', $db_user_id, $db_uname, $db_reg_email_current, $db_reg_email_past, $db_ip_address, $db_platform_user, $db_browser_user, $db_token_user, $db_time, $db_timezone, $db_timelocal, $db_locked, $db_visits);
				  log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
	    		  /* execute the prepared query. */
				  $i_stmt02->execute();
				  log_error($timelocal,'execute', $i_stmt02, $mysqli);
				  /* $i_stmt04->store_result(); */
				  $i_stmt02->close();

			  } else {

				  log_error($timelocal,'prepare', $i_stmt02, $mysqli);
			      $i_stmt02->close();
			  }

			  $db_eraser = 2; /* 2 === to indicate recover value */
			  $db_used   = 1;

			  if ($i_stmt03 = $mysqli->prepare("REPLACE INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      	 		  $i_stmt03->bind_param('ssssssssssss', $db_tokensend, $db_tokenstore, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_time, $db_timezone_user, $db_timeissued, $db_used, $db_eraser);      // get variables from result.
		 		  log_error($timelocal,'bind_param', $i_stmt03, $mysqli); 
		 		  /* execute the prepared query. */
         		  $i_stmt03->execute();
         		  log_error($timelocal,'execute', $i_stmt03, $mysqli);		 
		 		  $i_stmt03->close();

    		   } else {

         		  /* store token failed in db return false; unset session, remove cookies, log any prepare statement errors */
         		  /* secure_session_destroy(); */
         		  log_error($timelocal,'prepare', $i_stmt03, $mysqli);
         		  /* echo json_encode('token_store_failed'); */
         		  $i_stmt03->close();
    		   }

    		   rtnwebapp('correct' , 'success' , 'post');
			   exit(); 	
		break;

	}


}


function recover_account($ucode, $ecode, $lcode, $dcode, $timezo, $mysqli) {

	global $timelocal;

    $db_date = ''; $in_date = ''; $diff = ''; $db_locked = ''; $locked = '';


	$status = 'correct : process' ;         // assume success ... any failure will set this var flag to false 

	/*


	interesting note: http://stackoverflow.com/questions/6232084/is-mysql-real-escape-string-necessary-when-using-prepared-statements

	when you use '?' placeholder, it is better to pass params through the execute method.
    for example: $sql = $db->prepare('select location from location_job where location like ?'));
	$sql->execute(array($consulta));

	when you use named placeholders, you need to use ->bind_param(),
	for example: $sql = $db->prepare('select location from location_job where location like :item'));
	$sql->bind_param(':item', $consulta);


	*/

   								
	/* if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_past = ? LIMIT 1")) { */
	if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_past = ? LIMIT 1")) {	
		$s_stmt01->bind_param('s', $ecode);     //  bind "$ecode" to parameter.
		log_error($timelocal,'bind_param', $s_stmt01, $mysqli); 
		$s_stmt01->execute();                   //  execute the prepared query.
		log_error($timelocal,'execute', $s_stmt01, $mysqli);
		$s_stmt01->store_result();

		/* $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone_user, $db_timelocal_regist);  */
		$s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_follow_user, $db_time, $db_timezone_user, $db_timelocal_regist);      // get variables from result.
		$s_stmt01->fetch();
		$s_stmt01->close();

	} else {

		log_error($timelocal,'prepare', $s_stmt01, $mysqli);
		$s_stmt01->close();
		
		$status = 'error : email-not-found';										      	 
	}

	/* 

	match order:

	1. ecode  -> $db_email_past
	2. ucode  -> $db_uname
	3. dcode  -> $db_timelocal_user ( last signin, currently 10 days either side, near enough from signin_members )
	4. timezo -> $db_timezone_user  ( same as ... )

	then if good ...

	select signin_members and check the account lock status (why is the accont locked, investigate)

	*/
	
    switch (true) {

        case ($ucode !== $db_uname):
    		  $status = 'error : user-name-no-match'; 
    	break;
    	
    	case ($status === 'correct : process'):

    		  if  ($s_stmt02 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE uname = ? LIMIT 1")) {				 
	               $s_stmt02->bind_param('s', $ucode);     //  bind "$ecode" to parameter.
	               log_error($timelocal,'bind_param', $s_stmt02, $mysqli); 
	 			   /* execute the prepared query. */
	               $s_stmt02->execute();
	               log_error($timelocal,'execute', $s_stmt02, $mysqli);
	               $s_stmt02->store_result();  
				   $s_stmt02->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_token_user, $db_time, $db_timezone_user, $db_timelocal_signin, $db_locked, $db_visits);  // get variables from result.
				   $s_stmt02->fetch();
				   $s_stmt02->close();
				  
	          } else {
	 			   
	               log_error($timelocal,'prepare', $s_stmt02, $mysqli);
	               $s_stmt02->close();
	               
	           	   $status = 'error : user-name-no-match'; 
              }

    	break;

    }


    $in_date = str_replace( '/', '-', $dcode );

	$in_date = date("Y-m-d",strtotime($in_date));
    /* $db_date = date("Y-m-d",strtotime($db_timelocal_user)); */

    $in_date = date_create($in_date, timezone_open($timezo));
    /* $db_date = date_create($db_date, timezone_open($timezo)); */
    $db_date = date_create($db_timelocal_signin, timezone_open($timezo));

    $diff = date_diff($db_date, $in_date);
    $diff = $diff->format("%a");

    /*
       print_r(' value db_locked === ' . $db_locked);
       print_r(' value lcode === ' . $lcode);
       print_r(' is_zero lcode === ' . is_zero($lcode) );
       print_r(' is_zero $db_locked === ' . is_zero($db_locked) );
    */

    switch (true) {

    	case ($diff > '10'): // 10 days either side of the actual last signin 
    	 	  $status = 'error : last-signin-no-match'; 
    	break;

    	case ($db_locked === 1):
    		  $status = 'error : acc-locked';
    	break;

    	case (is_zero($lcode) !== is_zero($db_locked)):
    		  $status = 'error : acc-no-match-lock'; 
    	break;

    }

    $status = $status . ':' . $db_email_current . ':' . $db_oauth_token . ':' . $db_oauth_token_secret; 

    /* return :

               error msg : current email address : oauth_token : oauth_token_secret
	
	      email no match : value of current email : value oauth_token : value oauth_token_secret
	  user name no match : value of current email : value oauth_token : value oauth_token_secret
	last signin no match : value of current email : value oauth_token : value oauth_token_secret
	     email not found : value of current email : value oauth_token : value oauth_token_secret

	    true (no errors) : value of current email : value oauth_token : value oauth_token_secret
    */

	return $status;
}


/* send direct message to filtered / checked twitter account */  

function sendtweet($ucode, $tweet_token, $oauth_token, $oauth_token_secret) {

	/* $tweet_token = substr($tweet_token, 0, -11);      # process incoming token to 128 char (check removal of token char info) */
	/* $tweet_token * digital key message update * set to 42 fixed length  */

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

   	if ( $connection->lastHttpCode() !== 200 ) {
    /* if ($connection->{'http_code'} !== 200) { */
        rtnwebapp('error' , 'twitter-conn-fail' , 'post'); # twitter pass (tokens found in db, pass to tokens function), but have failed to be able to send or receive it!
        exit();
    }

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
			  echo json_encode( array( "ccc" => $token ) );
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
	  
	  case (strlen($key) == 42):
			$kvalid = true;
	  break;
		
	}

  return $kvalid;

}


function valid_scode($scode) {
	$iscode = false;
	switch (true) {

		case ($scode === '_ccu'):
			  $iscode = true;
		break;

		case ($scode === '_cca'):
			  $iscode = true;
		break;

	}
	return $iscode;
}


function valid_username($username) {
    return preg_match('/^[A-Za-z0-9_]{1,15}$/', $username);
}


function trim_all( $str , $what = NULL , $with = ' ' ) {
    
    if( $what === NULL ) {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
    }
       
    return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
}


function arrayfilter($var) {
  	return ($var !== NULL && $var !== FALSE && $var !== '');
}


function valid_timestamp($timestamp){
	$ret = false;
	$re_sep='[\/\-\.]';
	# $re_time='( (([0-1]?\d)|(2[0-3])):[0-5]\d)?';
	$re_time='( (([0-1]?\d)|(2[0-3])):[0-5]\d:[0-5]\d)?'; # now accept the format 'Y-m-d H:i:s':

	$re_d='(0?[1-9]|[12][0-9]|3[01])'; $re_m='(0?[1-9]|1[012])'; $re_y='(19\d\d|20\d\d)';

	if (!preg_match('!' .$re_sep .'!',$timestamp)) $timestamp=strftime("%d-%m-%Y %H:%M",$timestamp);         # convert Unix timestamp to entryFormat

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


/* update signin_token for callreset, as twitter direct unbroken message text length is now 74 characters, twitter token now 56 +/- characters length ! */


function signin_token($ucode, $ecode_in, $ecode_token, $oauth_token, $oauth_token_secret, $platform_user, $browser_user, $timezone, $mysqli) {

	    // signin_token($ucode, $ecode_in, $matchr[1], $matchr[2], $matchr[3], $pltfrm, $browsr, $timezo, $mysqli);	
		// $token_in[0] ( $ecode_token ) could equal == '_ccu' when the current email requires confirmation
		// AND
		// $ecode_in == current email address
		// OR
		// $token_in][0] ($ecode_token) could equal == the new email address 
		// AND
		// $ecode_in == the past email address

		global $timelocal;
		$ecode_target = '';

		switch(true) {

			case($ecode_token === '_ccu'):
				 $email_past = $ecode_in;
				 $email_current = $ecode_in;
				 $ecode_target = $ecode_in;
			break;
			case($ecode_token !== '_ccu'):
			     // active data $ecode_in
				 $email_past = $ecode_in;
				 $email_current = $ecode_token;
				 $ecode_target = $ecode_in;
				 // $ecode_target = $ecode_token;
			break;
			
		}	    
		$used = 0;
		$eraser = 0;

		$token_send   = getrandomstreset(34, dechex(time()),'t');
		$token_store  = getrandomstreset(39, $ecode_target,'e');

		$ip_address = $_SERVER['REMOTE_ADDR'];
	    // $ip_address = mysql_real_escape_string($ip_address);
        $ip_address = $mysqli->real_escape_string($ip_address);
		$now = time();

		if  ($i_stmt07 = $mysqli->prepare("INSERT INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      		 $i_stmt07->bind_param('ssssssssssss', $token_send[1], $token_store[1], $email_past, $email_current, $ip_address, $platform_user, $browser_user, $now, $timezone, $token_send[2], $used, $eraser);
			 log_error($timelocal,'bind_param', $i_stmt07, $mysqli); 
			 // execute the prepared query.
             $i_stmt07->execute();
             log_error($timelocal,'execute', $i_stmt07, $mysqli); 
        } else {
             // store token failed in db return false; unset session, remove cookies, log any prepare statement errors
             // secure_session_destroy();
             log_error($timelocal,'prepare', $i_stmt07, $mysqli);
             // echo json_encode('token_store_failed');
        }
		if ($i_stmt07) {

		/*  mailresetlink($ecode_target, $token_send);	                                           // all good, signin token created */
			mailresetlink($ecode, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr', $token_send);   // all good, signin token created

			sendtweet($ucode, $token_store[1], $oauth_token, $oauth_token_secret);

		} else {
		    // db error, unknown at this point, check log (send notification back to user?) 
		}
            $i_stmt07->close();
}


/* update signin_token * getrandomstreset * for callreset, as twitter direct unbroken message text length is now 74 characters, twitter token now 56 +/- characters length ! */


function getrandomstreset($length, $msg, $tore) {
        // 65 chars !
        $chars = ':abcdfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890:$:';
        $tmp_result = '';
        $output = array();
        $arrayout = array();
        
        for ($p = 0; $p < $length; $p++)
        {
            // $tmp_result .= ($p%2) ? $chars[mt_rand(4, 70)] : $chars[mt_rand(0, 3)];
            $tmp_result .= ($p%2) ? $chars[mt_rand(4, 64)] : $chars[mt_rand(0, 3)];
        }
        /*
        fixed first digit so as to prevent addtional mysql lookup to -> 78
        $rand_digits = rand(0, 9).rand(0, 9);
        $output[0] = substr($tmp_result, 0, $rand_digits);
        $output[1] = substr($tmp_result, $rand_digits);
        */
        $rand_digits = 1;

        $output[0] = substr($tmp_result, 0, $rand_digits);
        $output[1] = substr($tmp_result, $rand_digits, strlen($tmp_result));    
        
        switch($tore) {
            case('t'):
                $arrayout = timemute('f', $msg);

                /* log_found('test :: $getrandstr() :: $msg',  $msg . ' | '. strlen($msg) , 'start:test.php', __LINE__ ); */
                /* log_found('test :: $getrandstr() :: $msg',  $output[0] . '|' . $arrayout[0] . '|' . $arrayout[1] . '|' . $arrayout[2] . '|' . $arrayout[3] . '|' . $arrayout[4] . '|' . $arrayout[5] . '|' . $arrayout[6] . '|' . $arrayout[7] . '|' . $output[1] , 'start:test.php', __LINE__ ); */  
                /* log_found('test :: $getrandstr() :: $output[1]',  $output[1] . ' | '. strlen($output[1]) , 'start:test.php', __LINE__ ); */

                $getrand = array(strlen($output[0]), $output[0] . $arrayout[0] . $arrayout[1] . $arrayout[2] . $arrayout[3] . $arrayout[4] . $arrayout[5] . $arrayout[6] . $arrayout[7] . $output[1], date('Y-m-d H:i:s',time()));

                /* log_found('test :: $getrandstr() :: $getrand',  $getrand[0] .' | '. $getrand[1] .' | '. $getrand[2] . ' | '. strlen($getrand[1]) , 'start:test.php', __LINE__ ); */
            break;
            case('e');
                $arrayout = textmute(0,'f',$msg);
                $arrayout = implode('', $arrayout);

                /* log_found('$arrayout ! position', strrpos($arrayout,'!') , 'start:test.php', __LINE__ ); */
                /* log_found('$getrandstr',  $arrayout  , 'start:test.php', __LINE__ ); */
                /* log_found('getrandomstr() :: $arrayout', substr($arrayout, (strrpos($arrayout,'!') -1) , 1 ) . '!' . substr($arrayout, (strrpos($arrayout,'!') +1) , 1 ) , 'start:test.php', __LINE__ ); */

                /* $getrand = array(strlen($output[0]), $output[0] . $arrayout  . $output[1], time()); */
                /* return the smallest possible email id to check * a@b (.com) * check a @ b either side of the @ symbol leave the .com domain, check for known data length * assume ! === @ */

                $getrand = array(strlen($output[0]), $output[0] . substr($arrayout, (strrpos($arrayout,'!') -1) , 1 ) . '!' . substr($arrayout, (strrpos($arrayout,'!') +1) , 1 )  . $output[1], time());

                /* log_found('test :: $getrandstr() :: $getrand',  $getrand[0] .' | '. $getrand[1] .' | '. $getrand[2] . ' | '. strlen($getrand[1]) , 'start:test.php', __LINE__ ); */
            break;
        }

    return $getrand;
    
}


function getstrmsgreset($start, $randstr, $tore) {

  /* getstrmsgreset(18, $token_store[1], 'e') === unbiosed@gmail.com */

  $return = '';

    if (strlen($randstr) > 0 ) {

        $str2 = substr($randstr, $start, strlen($randstr));

        switch($tore) {
            case('t'):
                $str3 = substr($str2, 0, 8);
                $arrayout = timemute('u', $str3);   
                // $return = $arrayout[0] . $arrayout[1] . $arrayout[2] . $arrayout[3] . $arrayout[4] . $arrayout[5] . $arrayout[6] . $arrayout[7];
                $return = implode('', $arrayout); 
            break;
            case('e');
                $leftstr = strlen($str2);
                $pos = (strpos($str2, '$') + 4);
                $str3 = substr($str2, 0, $pos);
                $arrayout = textmute(0,'u',$str3);
                $return = implode('', $arrayout); // returns unbios@gmail.com * from * fepyhx!argyc$lhr * update * x!a === s@g */

                $return = substr($return, (strrpos($return,'@') -1) , 1 ) . '@' . substr($return, (strrpos($return,'@') +1) , 1 );    
            
            break;
        }

    }
    
  return $return;
    
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
	function mailresetlink($to, $token_send) {
		$subject = "Recover your crowdcc account";
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		$message = '
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
		</html>
		';
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: recover@crowdcc.com <recover@crowdcc.com>' . "\r\n";
		$headers .= "Return-path: <bounce@crowdcc.com>\r\n";
		$headers .= "Errors-To: <bounce@crowdcc.com>\r\n";
		if (mail($to,$subject,$message,$headers,"-fbounce@crowdcc.com")) {		 									  
		} else {
			rtnwebapp('error' , 'email-conn-fail' , 'post');   // email pass (found in db, pass to token function), but have failed to be able to send it to the email address provided !
            exit();											  
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