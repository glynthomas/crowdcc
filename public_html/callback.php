<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* take the user when they return from twitter. get access tokens.
* verify credentials and redirect, based on response from twitter.
*
* crowdcc account may or may not exist;
*
* user signs in with a twitter oauth, no crowdcc account,
* we store the uid, tokens and create a user record, so we can store ccc data
* associated to their uid ... the account is either new or existing updated
*
* user signs in using twitter oauth, but already with a crowdcc account,
* we update the twitter uid, tokens but do not delete the email / password
*
* notes: crowdcc_signin * regist_members * field 8 salt char(128) * removed for upgrade compatibility library with PHP 5.5's simplified password hashing API.
*
*/

/* load crowdcc app.error ( error handle ) && app.functions ( general app functions ) */
require_once('ccpath.php');

/* load crowdcc err handle */
// require_once('db/errorhandle.php');

/* load required lib files : a compatibility library with PHP 5.5's simplified password hashing API. */
// include 'lib/password.php';
require_once($_SERVER["DOCUMENT_ROOT"].'/../lib/password.php');

/* load required lib files. */
// include 'db/functions.php';

/* access to crowdcc signin db */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

/* access to crowdcc signin db */ 
//require_once('db/db_config.php');

/* access to crowdcc api db */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.api.conn.php');

/* access to api crowdcc api db */ 
//require_once('db/db_config_api.php');

/* start session and load library. */
secure_session_start(); // custom secure way of starting a php session.


/* twitter oauth lib * source: https://twitteroauth.com * version: v0.4.1 * modified v0.1 */
require_once('tweetpath.php');
use crowdcc\TwitterOAuth\TwitterOAuth;

/* start session and load library. */
//secure_session_start(); // custom secure way of starting a php session.

/*
switch (true) {
  case (isset($_GET['denied'])):
  case (isset($_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] === ""): // PHP notice error: undefined index: oauth_token in /var/www/crowdcc.dev/public_html/callback.php on line 69
  case (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']):
        $_SESSION['oauth_status'] = 'invalid';
        // load and clear sessions 
        session_start();
        session_destroy();
        //  if the oauth_token is old or invalid redirect to the connect page mmm.
        header('Location: ./');
        exit();
}
*/

/* oauth_token is old redirect to the home (re-connect) page. */
/*
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  	$_SESSION['oauth_status'] = 'oldtoken';
  	// load and clear sessions *  header('Location: ./clearsessions.php');
   	if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
  	session_destroy();
  	header('Location: ./');
    exit();
}
*/

/* oauth_token is old redirect to the home (re-connect) page. */

switch (true) {
  case (isset($_GET['denied'])):
  case (empty($_REQUEST['oauth_token'])):
  case (!isset($_REQUEST['oauth_token'])):  
  case (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']):
        $_SESSION['oauth_status'] = 'oldtoken';
        // load and clear sessions *  header('Location: ./clearsessions.php');
        if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
        session_destroy();
        header('Location: ./');
        exit();
}


/* create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* request access tokens from twitter * source: http://abrah.am org * version: v0.1.2 * modified v0.2 */
/* $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']); */

$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

/* get Social -> Twitter details already in db, but no email or password. */
$ecode = $_SESSION['user_ecode'];
$pcode = $_SESSION['user_pcode'];

$fcode = $_SESSION['user_fcode'];

/* get platform details from session */
$pltfrm = $_SESSION['user_platform'];
$browsr = $_SESSION['user_browser'];
$timezo = $_SESSION['user_timezone'];

/* save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

 $uname = $access_token['screen_name'];


/* current flow * check for existing screen_name ($uname) records in the db */

 /* $s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1"); */
 $s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1");
 $s_stmt01->bind_param('s', $uname);  				   //  bind "$db_uname" to parameter.
 $s_stmt01->execute();                   				 //  execute the prepared query.
 $s_stmt01->store_result();  
 /* $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_uid_user, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_now, $db_timezone_user, $db_timelocal_user); */
 $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_uid_user, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_now, $db_timezone_user, $db_timelocal_user);     // get variables from result.
 $s_stmt01->fetch();
 

 /* $ck_pcode  = hash('sha512', $pcode.$db_random_salt); // old method */

 $ck_pcode = ''; /* init var */

 if ( password_verify($pcode, $db_pcode) ) { $ck_pcode = $db_pcode; } /* stored $db_pcode bcrypt of password matches entered password */

/* 
 print_r('back from twitter * screen name * $uname === ' . $uname . '<br>');
 print_r('back from twitter * screen name * $db_uname === ' . $db_uname . '<br>');

 print_r('back from twitter * ecode  === ' . $ecode . '<br>');
 print_r('back from twitter * pcode  === ' . $pcode . '<br>');

 print_r('back from twitter * fcode  === ' . $fcode . '<br>');

 print_r('back from twitter * ck_pcode  === ' . $ck_pcode . '<br>');
 print_r('back from twitter * db_pcode  === ' . $db_pcode . '<br>');
 print_r('back from twitter * valid ecode  === ' . valid_email($ecode) . '<br>');
 print_r('... all she wrote!');
 exit();
*/ 


/* new user flow * check for valid twitter uname, uname record found which ALREADY has an associated email address (error capture) in db */

 switch (true) {

		case ($s_stmt01->num_rows > 0):

        if ($ecode && $pcode !== '_$twitter') {

          /* new email check -> check for valid twitter uname, uname record found which ALREADY has an associated email address (error capture) in db */
          switch (true) {

            case (valid_email($ecode) === 0):
                  if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
                  session_destroy();
                  header( 'Location: ./?errors=error_ureg' );  /* new user check * twitter screen name already in use! */
                  rtnwebapp( 'error', 'error_ureg', 'post' ); 
            break;

            case ($db_email_past !== $ecode):
                  /* check incoming * if $ecode !=== $db_email_past OR $db_email_current */
            case ($db_email_current !== $ecode):
                  /* load and clear sessions */
                  if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
                  session_destroy();
                  header( 'Location: ./?errors=error_ureg' );  /* new user check * twitter screen name already in use! */
                  rtnwebapp( 'error', 'error_ureg', 'post' ); 
            break;

            case ($ck_pcode !== $db_pcode):
                  /* load and clear sessions */
                  if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
                  session_destroy();
                  header( 'Location: ./?errors=error_ereg' );  /* new user check * twitter email already in use! */
                  rtnwebapp( 'error', 'error_ereg', 'post' ); 
            break;

          }

        }

					switch (true) {

								case (strlen($db_email_current) > 0):
							    /*  current email check -> already have valid email and password, add social twitter details and replace (email + passcode) but protect email and password */

									/* if ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
                  if ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      										 		 
											$uname = $access_token['screen_name'];
											$user_uid = $access_token['user_id'];

											$oauth_token =  ccrypt( $access_token['oauth_token'], 'AES-256-OFB', 'en' );                  /* re-encrypt before storage */
											$oauth_token_secret =  ccrypt( $access_token['oauth_token_secret'], 'AES-256-OFB', 'en' );    /* re-encrypt before storage */

      						 /* $i_stmt01->bind_param('isssssssssssssssss', $db_user_id, $uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_uid_user, $oauth_token, $oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_now, $db_timezone_user, $db_timelocal_user); */
											$i_stmt01->bind_param('issssssssssssssss', $db_user_id, $uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_uid_user, $oauth_token, $oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_now, $db_timezone_user, $db_timelocal_user);
                      log_error($timelocal,'bind_param', $i_stmt01, $mysqli); 
 											// execute the prepared query.
             					$i_stmt01->execute();
              				log_error($timelocal,'execute db_ecode > 0', $i_stmt01, $mysqli);
                      
                      /* $api_key = juggle_api_token( $db_api_key ); postpone implementation */
                      $api_key = $db_api_key;
                 
                      $i_stmt01->close();
           				
                  } else {
 									
                      // registration failed in db return false; unset session, remove cookies, log any prepare statement errors
              				// secure_session_destroy();
              				log_error($timelocal,'prepare', $i_stmt01, $mysqli);
              				$i_stmt01->close();
              				// echo json_encode('error_tcode');
              				// exit();
            			}

            					switch (true) {

            							case ($ecode == ''):
                                $_SESSION['ccode'] = 'soc';
                                $_SESSION['ucode'] = $uname;
                                $_SESSION['ceode'] = '_$twitter';
                                $_SESSION['fcode'] = $db_fcode;

                                $_SESSION['ccid']  = $api_key;
                                
                                $_SESSION['cauth_token'] = $oauth_token;
                                $_SESSION['cauth_token_secret'] = $oauth_token_secret;  
                          break; 
                          case ($ecode != '' && $db_email_confirm == 0):
                                $_SESSION['ccode'] = 'ccn';
                                $_SESSION['ucode'] = $uname;
                                $_SESSION['ceode'] = $db_email_current;
                                $_SESSION['fcode'] = $db_fcode;
                                
                                $_SESSION['ccid']  = $api_key;
  
                                $_SESSION['cauth_token'] = $oauth_token;
                                $_SESSION['cauth_token_secret'] = $oauth_token_secret;  
                                social_visit($uname, $mysqli);
                          break;
                        	case ($ecode != '' && $db_email_confirm == 1):
                                $_SESSION['ccode'] = 'ccc';
                                $_SESSION['ucode'] = $uname;
                                $_SESSION['ceode'] = $db_email_current;
                                $_SESSION['fcode'] = $db_fcode;

                                $_SESSION['ccid']  = $api_key;
                                
                                $_SESSION['cauth_token'] = $oauth_token;
                                $_SESSION['cauth_token_secret'] = $oauth_token_secret;  
                                social_visit($uname, $mysqli);
                          break;

                      }

								break;

								case ($db_uname == $uname):
								  /*  twitter social username check -> already have valid twitter shortname (screen name), and replace email and password details but protect twitter social details */

				       /* if ($i_stmt02 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
      						if ($i_stmt02 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {				 		 
											/* old method * create a random salt * hash password with random salt * store salt for comparison check */

            					/* $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true)); */
            					/* $pcode  = hash('sha512', $pcode.$random_salt);  /* create random salt * hash password with random salt, _username, _email (careful not to over season) */

                      $pcode = password_hash($pcode, PASSWORD_BCRYPT, array("cost" => 11)); /* default is cost 10 */

            					if ($ecode == '_$twitter') { $email_past = ''; $ecode =  ''; } else { $email_past = $ecode; }

      								/* $i_stmt02->bind_param('issssssssssssssss', $db_user_id, $db_uname, $email_past, $ecode, $db_email_confirm, $db_api_key, $pcode, $random_salt, $db_uid_user, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_now, $db_timezone_user, $db_timelocal_user); */
											$i_stmt02->bind_param('issssssssssssssss', $db_user_id, $db_uname, $email_past, $ecode, $db_email_confirm, $db_api_key, $pcode, $db_uid_user, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_now, $db_timezone_user, $db_timelocal_user);
                      log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
 											// execute the prepared query.
             					$i_stmt02->execute();
              				log_error($timelocal,'execute db_uname == uname', $i_stmt02, $mysqli);

                      /* $api_key = juggle_api_token( $db_api_key ); postpone implementation */
                      $api_key = $db_api_key;
                
                      $i_stmt02->close();
              				// return true;
                      // echo json_encode('pass_tcode');
        							// exit();
 									} else {
 									    // registration failed in db return false; unset session, remove cookies, log any prepare statement errors
              				// secure_session_destroy();
              				log_error($timelocal,'prepare', $i_stmt02, $mysqli);
              				$i_stmt02->close();
              				// echo json_encode('error_tcode');
              				// exit();
            			}

            					switch (true) {

            							case ($ecode == ''):
                                $_SESSION['ccode'] = 'soc';
                                $_SESSION['ucode'] = $uname;
                                $_SESSION['ceode'] = '_$twitter';
                                $_SESSION['fcode'] = $db_fcode;

                                $_SESSION['ccid']  = $api_key;
                                
                                $_SESSION['cauth_token'] = $db_oauth_token;
                                $_SESSION['cauth_token_secret'] = $db_oauth_token_secret;  
                                social_visit($db_uname, $mysqli); 
                          break; 

            							case ($ecode != '' && $db_email_confirm == 0):
                                $_SESSION['ccode'] = 'ccn';
                                $_SESSION['ucode'] = $uname;
                                $_SESSION['ceode'] = $ecode;
                                $_SESSION['fcode'] = $db_fcode;

                                $_SESSION['ccid']  = $api_key;
                                
                                $_SESSION['cauth_token'] = $db_oauth_token;
                                $_SESSION['cauth_token_secret'] = $db_oauth_token_secret;  
                                social_visit($db_uname, $mysqli);
                          break;

                          case ($ecode != '' && $db_email_confirm == 1):
                                $_SESSION['ccode'] = 'ccc';
                                $_SESSION['ucode'] = $db_uname;
                                $_SESSION['ceode'] = $ecode;
                                $_SESSION['fcode'] = $db_fcode;

                                $_SESSION['ccid']  = $api_key;
                                
                                $_SESSION['cauth_token'] = $db_oauth_token;
                                $_SESSION['cauth_token_secret'] = $db_oauth_token_secret;  
                                social_visit($db_uname, $mysqli);
                          break;

                      }

								break;

					}

    break;

		case ($s_stmt01->num_rows == 0):

					/*  add a brand new twitter usr, not signed in before, init default content api token key  */

									/* if ($i_stmt02 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
                  if ($i_stmt02 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
	      										 
											/* $remoteipheader = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
             						 $ip_address = substr($remoteipheader, 0, 6); */

                      /* new user id simpleflake */
                      $user_id = simpleflake();

                      /* init new api key token * 10 tweets * 20 once email address registered! */
                      $api_key = create_api_token($uname, '10');

                      /* old method * create a random salt * hash password with random salt * store salt for comparison check */
     
                      /* $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true)); */
                      /* $pcode  = hash('sha512', $pcode.$random_salt);  /* create random salt * hash password with random salt, _username, _email (careful not to over season) */

                      $pcode = password_hash($pcode, PASSWORD_BCRYPT, array("cost" => 11)); /* default is cost 10 */

             					$ip_address = $_SERVER['REMOTE_ADDR'];
            					/* $ip_address = mysql_real_escape_string($ip_address); */
                      $ip_address = $mysqli->real_escape_string($ip_address);

                      $platform_user = $pltfrm;
                      $browser_user = $browsr;

											date_default_timezone_set("UTC");
    									$now = time();

    										  $timezone_user = $timezo;
                      if ($timezo == '') {
                          $timezone_user = 'Europe/Malta'; // error timezone --> UTC+01:00
    									}

    									$date = new DateTime(null, new DateTimeZone($timezone_user));
   										$timelocal_user = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));

											$uname = $access_token['screen_name'];
											$user_uid = $access_token['user_id'];

                      $oauth_token =  ccrypt( $access_token['oauth_token'], 'AES-256-OFB', 'en' );                 /* encrypt before storage */
                      $oauth_token_secret =  ccrypt( $access_token['oauth_token_secret'], 'AES-256-OFB', 'en' );   /* encrypt before storage */
               
											if ($ecode == '_$twitter') { $email_past = ''; $ecode =  ''; } else { $email_past = $ecode; }
											
											$email_confirm = 0;
                      $db_fcode = 0;

                      /* new user * case 1 * with email to confirm * who (default) selected follow * upon signin/up $fcode === 2 (flag follow after signin/up ) */
                      /* new user * case 2 * no email social signin/up (email to reg later) * follow (not default) is in-app follow * $fcode === 0 (flag no follow until selected in-app) */

                      if ($fcode !== 2) { $fcode = 0; } /* if not case 1 * case 2 ($fcode = 0) */ 
                     
	      					 /* $i_stmt02->bind_param('isssssssssssssssss', $user_id, $uname, $email_past, $ecode, $email_confirm, $api_key, $pcode, $random_salt, $user_uid, $oauth_token, $oauth_token_secret, $ip_address, $platform_user, $browser_user, $db_fcode, $now, $timezone_user, $timelocal_user); */
											$i_stmt02->bind_param('issssssssssssssss', $user_id, $uname, $email_past, $ecode, $email_confirm, $api_key, $pcode, $user_uid, $oauth_token, $oauth_token_secret, $ip_address, $platform_user, $browser_user, $db_fcode, $now, $timezone_user, $timelocal_user);
                      log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
	 										// execute the prepared query.
	             				$i_stmt02->execute();
	              			log_error($timelocal,'execute fail->' . $access_token['screen_name'] . '<-OK', $i_stmt02, $mysqli);
	              			// $insert_stmt->store_result();
	              
                      /* $api_key = juggle_api_token( $api_key ); postpone implementation */
                      /* $api_key = $api_key; */
       
                      // return true;
	                    // echo json_encode('pass_tcode');
	        						// exit();
	 								} else {
	 									  // registration failed in db return false; unset session, remove cookies, log any prepare statement errors
	              			// secure_session_destroy();
	              			log_error($timelocal,'prepare', $i_stmt02, $mysqli);
	              			$i_stmt02->close();
	              			// echo json_encode('error_tcode');
	              			// exit();
	            		}


                  if ($s_stmt02 = $mysqli->prepare("SELECT user_id FROM regist_members WHERE uname = ? LIMIT 1")) {
                      $s_stmt02->bind_param('s', $uname);                      //  bind "$db_uname" to parameter.
                      log_error($timelocal,'bind_param', $s_stmt02, $mysqli); 
                      $s_stmt02->execute();                                    //  execute the prepared query.
                      log_error($timelocal,'execute', $s_stmt02, $mysqli);
                      $s_stmt02->store_result();  
                      $s_stmt02->bind_result($db_user_id);
                      $s_stmt02->fetch();                                      // get variables from result.
                      $s_stmt02->close();
                
                  } else {
                
                       log_error($timelocal,'prepare', $s_stmt02, $mysqli);
                       $s_stmt02->close();      
                  }

                       $visits =  1;
                       $locked = 0;
                       $token_user = 0;

									if  ($i_stmt04 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                       $i_stmt04->bind_param('issssssssssss', $db_user_id, $uname, $email_past, $ecode, $ip_address, $platform_user, $browser_user, $token_user, $now, $timezone_user, $timelocal_user, $locked, $visits);
                       log_error($timelocal,'bind_param', $i_stmt04, $mysqli); 
                       // execute the prepared query.
                       $i_stmt04->execute();
                       log_error($timelocal,'execute', $i_stmt04, $mysqli);
                       // $i_stmt04->store_result();
                       $i_stmt04->close();

                  } else {

                       log_error($timelocal,'prepare', $i_stmt04, $mysqli);
                       $i_stmt04->close();
                  }

                  /* update * $mysqli_api -> crowdcc_api -> members -> api_key */

                  /* defaults */
                  $api_hit   = 0;   /* api hit, 0, no hits yet ...      */
                  $ccc_limit = 10;  /* 10 tweets, default social only   */
                  $ccc_store = 0;   /* store set to 0, no tweets stored */

                  if  ($i_stmt05 = $mysqli_api->prepare("REPLACE INTO members (user_id, uname, ccc_store, ccc_limit, api_key, api_hit, api_hit_date) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
                       $i_stmt05->bind_param('issssss', $db_user_id, $uname, $ccc_store, $ccc_limit, $api_key, $api_hit, $timelocal_user);
                       log_error_api($timelocal,'bind_param', $i_stmt05, $mysqli_api); 
                       // execute the prepared query.
                       $i_stmt05->execute();
                       log_error_api($timelocal,'execute', $i_stmt05, $mysqli_api);
                       // $i_stmt04->store_result();
                       $i_stmt05->close();

                  } else {

                       log_error_api($timelocal,'prepare', $i_stmt05, $mysqli_api);
                       $i_stmt05->close();
                  }

                  switch (true) {

                     case ($ecode == ''):
                           $_SESSION['ccode'] = 'soc';
                           $_SESSION['ucode'] = $uname;
                           $_SESSION['ceode'] = '_$twitter';
                           $_SESSION['fcode'] = $fcode;
                           
                           $_SESSION['ccid']  = $api_key;
               
                           $_SESSION['cauth_token'] = $oauth_token; 
                           $_SESSION['cauth_token_secret'] = $oauth_token_secret; 
                     break; 
                     case ($ecode != ''):
                           $_SESSION['ccode'] = 'ccn';
                           $_SESSION['ucode'] = $uname;
                           $_SESSION['ceode'] = $ecode;
                           $_SESSION['fcode'] = $fcode;

                           $_SESSION['ccid']  = $api_key;
                           
                           $_SESSION['cauth_token'] = $oauth_token; 
                           $_SESSION['cauth_token_secret'] = $oauth_token_secret;  
                     break;
                  }

                  $i_stmt02->close();

                $s_stmt01->close();

		break;

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


  /* if HTTP response is 200 continue otherwise send to connect page to retry */

  if (200 === $connection->lastHttpCode()) {
	/* if (200 == $connection->http_code) { */
	    /* the user has been verified and the access tokens can be saved for future use */
	    $_SESSION['status'] = 'verified';
	    /* header('Location: ./?auto='. $access_token['screen_name'] ); */
    
      /* clear down connection objects */
      $access_token = NULL;
	    $connection = NULL;

      /* clear down local vars */

      $ecode = NULL; $pcode = NULL; $pltfrm = NULL; $browsr = NULL; $timezo = NULL; $uname = NULL;
      $db_user_id = NULL; $db_uname = NULL; $db_email_past = NULL; $db_ecode = NULL; $db_email_confirm = NULL; $db_pcode = NULL; $db_random_salt = NULL;
      $db_user_uid = NULL; $db_oauth_token = NULL; $db_oauth_token_secret = NULL; $db_ip_address = NULL; $db_platform_user = NULL; $db_browser_user = NULL;
      $db_now = NULL; $db_timezone_user = NULL; $timezone_user = NULL; $db_timelocal_user = NULL; $visits = NULL; $email_confirm = NULL;
      $user_uid = NULL; $oauth_token = NULL; $oauth_token_secret = NULL; $date = NULL; $timelocal_user = NULL; $email_past = NULL;
      $ip_address = NULL; $platform_user = NULL; $browser_user = NULL; $now = NULL; $timezone_user = NULL; $locked = NULL; $visits = NULL;
                                       
      $random_salt = NUll;

      /* clear down mysqli DB object vars */

      $mysqli = NULL; $timelocal = NULL;
      $s_stmt01 = NULL; $s_stmt02 = NULL; $s_stmt03 = NULL;
      $i_stmt01 = NULL; $i_stmt02 = NULL; $i_stmt03 = NULL; $i_stmt04 = NULL;
      

      /* .htaccess ** hide .php extention
      
      <IfModule mod_rewrite.c>
       #turn on url rewriting 
       RewriteEngine on
       #remove the need for .php extention 
       RewriteCond %{REQUEST_FILENAME} !-d 
       RewriteCond %{REQUEST_FILENAME}\.php -f 
       RewriteRule ^(.*)$ $1.php
      </IfModule>

      */

      /* header('Location: ./verified.php'); */
      /* header('Location: ./verified');     */
	    header('Location: http://' . $_SERVER["HTTP_HOST"] .'/verified');


	} else {
	    /* save HTTP status for error dialog on connnect page. */

	    if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
	    session_destroy();

      /* clear down connection objects */
      $access_token = NULL;
	    $connection = NULL; 
      header('Location: ./');
	    /* header('Location: ./clearsessions.php'); */
	}


	function social_visit($uname, $mysqli) {

    global $timelocal;

		if ($s_stmt03 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE uname = ? LIMIT 1")) {
			  $s_stmt03->bind_param('s', $uname);                      //  bind "$db_uname" to parameter.
			  log_error($timelocal,'bind_param', $s_stmt03, $mysqli); 
			  $s_stmt03->execute();                                    //  execute the prepared query.
			  log_error($timelocal,'execute', $s_stmt03, $mysqli);
			  $s_stmt03->store_result();  
			  $s_stmt03->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_token_user, $db_time, $db_timezone, $db_timelocal, $db_locked, $db_visits);
			  $s_stmt03->fetch();										                   //  get variables from result.
								
		} else {
							  
			  log_error($timelocal,'prepare', $s_stmt03, $mysqli);
			  $s_stmt03->close();    	 
		}

		$db_visits = $db_visits + 1;


		if ($i_stmt03 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
			  $i_stmt03->bind_param('issssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_token_user, $db_time, $db_timezone, $db_timelocal, $db_locked, $db_visits);
			  log_error($timelocal,'bind_param', $i_stmt03, $mysqli); 
			  // execute the prepared query.
			  $i_stmt03->execute();
			  log_error($timelocal,'execute', $i_stmt03, $mysqli);
			  // $i_stmt04->store_result();
			  $i_stmt03->close();

		} else {

			  log_error($timelocal,'prepare', $i_stmt03, $mysqli);
			  $i_stmt03->close();

		}


	}


