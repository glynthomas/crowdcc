<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* follow us management
*
* option to follow @crowdccHQ twitter account
* 
* tw.usr.ccfollow === 0 : no follow * tw.usr.ccfollow === 2 : promise follow * tw.usr.ccfollow === 1 : following
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

/* access to crowdcc signin db */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');


/* twitter oauth lib * source: https://twitteroauth.com * version: v0.4.1 * modified v0.1 */
require_once('tweetpath.php');
use crowdcc\TwitterOAuth\TwitterOAuth;

global $timelocal;

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

 case ('POST'):

      if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post', ''); exit(); }

      $post_in = $_POST;
      $post_in = explode(":", implode($post_in));

      /* sanitize data */

      switch (true) {

         case ( $post_in[0] !== filter_var( $post_in[0] , FILTER_SANITIZE_STRING) ):
               /* $post_in[0] === _fo :: flag */
               rtnwebapp('error_tamper' , 'error_tamper' , 'post');	/* test for failure	*/
               exit();
         break;
         case( $post_in[1] !== filter_var( $post_in[1] , FILTER_SANITIZE_STRING) ):
               /* $post_in[1] === crowdccteam  :: username */
               rtnwebapp('error_obj' , 'data-error-1' , 'post');
               exit();
         break;

      }

      /* check data */
     
      $post_in[0] = base64_decode($post_in[0]);   /* _fo :: flag */
      $post_in[1] = decrypt($post_in[1]);         /* uname */

      if ( ! validate_username( $post_in[1] ) ) { rtnwebapp('error_tamper' , 'error_tamper' , 'post'); }

	  /* all tests for failure are complete */ 

      follow($post_in[1], $mysqli);
   
 break;

}

/* functions  */

function follow($whofor, $mysqli) {

   global $timelocal; $connection = ''; $nucode = '';

    /* look up valid username to match oauth tokens */

    /* if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1")) { */
    if ($s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1")) {	
		$s_stmt01->bind_param('s', $whofor);                                                           /*  bind "$whofor" to parameter. */
		log_error($timelocal,'bind_param', $s_stmt01, $mysqli); 
		$s_stmt01->execute();                                               						   /*  execute the prepared query.  */
		log_error($timelocal,'execute', $s_stmt01, $mysqli);
		$s_stmt01->store_result();  
		/* $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user); */
		$s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user);
		$s_stmt01->fetch();
		  							
	} else {
							  
		log_error($timelocal,'prepare', $s_stmt01, $mysqli);
		$s_stmt01->close();
		rtnwebapp('error_db_follow' , 'error_db_follow' , 'post');     	 
	}

	if ( $db_fcode === 1 ) { rtnwebapp('error_usr_follow' , 'error_usr_follow' , 'post' ); }           /* failure user already following crowdccHQ */

	/* decrypt out of storage * oauth tokens */
	    		
	$oauth_token = ccrypt( $db_oauth_token, 'AES-256-OFB', 'de' );                                    /* un-encrypt out of storage */
	$oauth_token_secret = ccrypt( $db_oauth_token_secret, 'AES-256-OFB', 'de' );	                  /* un-encrypt out of storage */

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
	  
    /* if method is set change API call made. test is called by default. */
	   
    /* $content = $connection->post('friendships/create', array('id' => 2179631712));                 /* 2179631712 uid for crowdccteam */
    $connection->post('friendships/create', array('screen_name' => 'crowdcchq', 'follow' => true) );  /* 2179631712 uid for crowdHQ */

    /*
    $nucode = json_encode($content);
	$nucode = json_decode($nucode);
	$nucode = $nucode->screen_name;
	if ($nucode === '') { return false; exit();}
	*/

	/* in app * test for failure on follow * is important! */
    if ( ($connection->lastHttpCode()) !== 200 ) { rtnwebapp('error' , 'error_' . $connection->lastHttpCode() , 'post'); }

    $db_fcode = 1;

	/* if ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
    if ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {

	    /* $i_stmt01->bind_param('isssssssssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user); */
	    $i_stmt01->bind_param('issssssssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user);									
	    log_error($timelocal,'bind_param', $i_stmt01, $mysqli); 
	    /* execute the prepared query. */
	    $i_stmt01->execute();
	    log_error($timelocal,'execute', $i_stmt01, $mysqli);
	    $i_stmt01->store_result();
	    $i_stmt01->close();

	} else {
	 									    	             								 	
	    log_error($timelocal,'prepare', $i_stmt05, $mysqli);
	    $i_stmt01->close();
	    rtnwebapp('error_db_follow' , 'error_db_follow' , 'post');    								 	  
	
    }

    switch (true) {		

	  case ($s_stmt01->num_rows === 0):						    /* failure uname check */
	        $s_stmt01->close();
			// echo 'uname match or not found in db!';
			rtnwebapp('error_nil_follow' , 'error_nil_follow' , 'post' );
	  break;

	  case ($s_stmt01->num_rows === 1):						    /* success user now following crowdccHQ */
	        $s_stmt01->close();
		  	// echo 'user now following crowdcchq!?';
		  	rtnwebapp('correct_pass_follow' , 'correct_pass_follow' , 'post' );
	  break;    
    
    }

}


function rtnwebapp( $flag, $token, $whofor ) {

 	/*  function is passed the following ;
  	 *
  	 *  $flag    -> status or error
  	 *  $token   -> token data || status data || error data
  	 *  $whofor  -> post || get
  	 *
 	 */

  switch ($whofor) {
     
	case ('post'):
			 
		  echo json_encode( $flag . ':*:' . $token );
		      
		  # unset vars
		  # unset($method, );
		  exit();

	break;

  }

}


function validate_username($username) {
    return preg_match('/^[A-Za-z0-9_]{1,15}$/', $username);
}

