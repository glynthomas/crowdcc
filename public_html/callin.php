<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* callin for lazy signin
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
// require_once('db/errorhandle.php');

// require_once('crypt/RSA.php');

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

/* php sheduler */
include_once($_SERVER["DOCUMENT_ROOT"].'/../slib/firepjs.php');

/* access to crowdcc signin db */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

/* access to crowdcc signin db */	
//require_once('db/db_config.php');

/* access to crowdcc api db */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.api.conn.php');

/* access to api crowdcc api db */ 
//require_once('db/db_config_api.php');

/* access to crowdcc network connect get * check server connection to * twitter * google */
/* 1. network check commented out due to outbound bandwidth restrictions */  
/* require_once('cg.php'); */

/* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */
/* log_found('found log test', ' checking' . 'log writes' , 'callin.php', __LINE__ ); */

/* access to allowed hosts : crowdcc.apc, crowdcc.dev, crowdcc.com * okcomputer('crowdcc.dev', 'crowdcc.apc', 'crowdcc.com'); */
okcomputer( 'crowdcc.apc', '127.0.0.1', 'crowdcc.dev', '192.168.1.100', 'crowdcc.com', '52.19.98.153' );

/* twitter oauth lib * source: http://abrah.am org * version: v0.1.2 * modified v0.2 */
/* require_once('oauth/config.php'); */
/* require_once('oauth/twitteroauth.php'); */

/* phpmailer lib * auth mail lib files * mailresetlink($to, $token_send) * see ccmail app.error.php */
require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');

/* start session and load library. */
/* $session = new session(); Set to true if using https, $session->start_session('_s', false); */
secure_session_start(); // Our custom secure way of starting a php session.

/* twitter oauth lib * source: https://twitteroauth.com * version: v0.4.1 * modified v0.1 */
require_once('tweetpath.php');
use crowdcc\TwitterOAuth\TwitterOAuth;

/* start session and load library. */
// secure_session_start(); // Our custom secure way of starting a php session.

/* before processing post data * check connection to twitter * google */

/* if ( cc_connect() ) { rtnwebapp('error' , 'error_network' , 'post', '', ''); exit(); } */
/* 2. network check commented out due to outbound bandwidth restrictions */ 

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case ('PUT'):
        /* rest_put($request);    */  
  break;
  case ('POST'):

      /* if ( empty($_SESSION['time']) ) { $_SESSION['time'] = time(); } */

  	  if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post', '', ''); exit(); }

      $post_in = $_POST;
      $post_in = explode(":", implode($post_in));

	  $ecode  = ''; $ecode_clean = '';
	  $pcode  = ''; $pcode_clean = '';

	  $sncode = ''; $sncode_clean = '';
	  $uscode = ''; $uscode_clean = '';

	  $pltfrm = ''; $pltfrm_clean = '';
	  $browsr = ''; $browsr_clean = '';
	  $timezo = ''; $timezo_clean = '';
	  $kcode = '';  $kcode_clean = '';

	  $fcode = ''; /* new user follow flag * 1 | 0 */

	  /* 
	  print_r( decrypt($post_in[0]) );
	  print_r('|');
	  print_r( decrypt($post_in[1]) );
	  print_r('|');
	  print_r( base64_decode($post_in[2]) );
	  print_r('|');
	  print_r( base64_decode($post_in[3]) );
	  print_r('|');
	  print_r( base64_decode($post_in[4]) );
	  print_r('|');
	  print_r( base64_decode($post_in[5]) );
	  print_r('|');
	  print_r( base64_decode($post_in[6]) );
	  print_r('|');
	  print_r( base64_decode($post_in[7]) );
	  print_r('thats all she wrote');
	  exit();
	  */

	  $ecode  = decrypt($post_in[0]); 	     /* $ecode */
	  $pcode  = decrypt($post_in[1]); 	     /* $pcode */

	  $sncode = base64_decode($post_in[2]);  /* $sncode */
	  $uscode = base64_decode($post_in[3]);  /* $uscode */

	  $pltfrm = base64_decode($post_in[4]);  /* $pltfrm */
	  $browsr = base64_decode($post_in[5]);  /* $browsr */
	  $timezo = base64_decode($post_in[6]);  /* $timezo */

	  $kcode =  base64_decode($post_in[7]);  /* $kcode */


	  /* validate input - test for all failures */

	  /* username / email intial filter */

	  $ecode_clean  = filter_var( $ecode , FILTER_SANITIZE_STRING);      /* $ecode_clean (any problem chars stripped out) */

  	  /* passcode intial filter */

	  $pcode_clean  = filter_var( $pcode , FILTER_SANITIZE_STRING);      /* $pcode_clean (any problem chars stripped out) */

	  /* screen name intial filter */

	  $sncode_clean  = filter_var( $sncode , FILTER_SANITIZE_STRING);    /* $sncode_clean (any problem chars stripped out) */

  	  /* user status intial filter */

	  $uscode_clean  = filter_var( $uscode , FILTER_SANITIZE_STRING);    /* $uscode_clean (any problem chars stripped out) */


	  /* other platform data sanitation */

	  $pltfrm_clean = filter_var( $pltfrm , FILTER_SANITIZE_STRING);  	 /* $pltfrm_clean */
	  $browsr_clean = filter_var( $browsr , FILTER_SANITIZE_STRING);  	 /* $browsr_clean */
	  $timezo_clean = filter_var( $timezo , FILTER_SANITIZE_STRING);  	 /* $timezo_clean */

	  $kcode_clean = filter_var( $kcode , FILTER_SANITIZE_STRING);  	 /* $tkode_clean */


	  /* validation checks */


	  switch (true) {
	    
	    case ($ecode !== $ecode_clean):
	    	  // print_r('email fail' . $ecode_clean);
	    	  rtnwebapp('error' , 'email-fail' , 'post', '', '');		    /* test for failure */	
	    break;

	    case ($pcode !== $pcode_clean):
	    	  // print_r('password fail' . $ecode_clean);
	    	  rtnwebapp('error' , 'passcode-fail' , 'post', '', '');		/* test for failure */	
	    break;

	   
	    // case (valid_password($pcode_clean) === false):					/* new validation password rules! */
	       // print_r('password fail' . $pcode_clean);
	       // rtnwebapp('error' , 'passcode-fail' , 'post', '', '');	    /* test for failure */	
	    // break;
		
    
	    case ($sncode !== $sncode_clean):
	    	  print_r('data tamper' . $lcode_clean);							     
	    	  rtnwebapp('error' , 'error_tamper' , 'post', '', '');			 /* test for failure */	
	    break;

		case ($uscode !== $uscode_clean):
	    	  print_r('data tamper ->' . $pltfrm_clean);
	    	  rtnwebapp('error' , 'error_tamper' , 'post', '', '');			 /* test for failure */	
	    break;

	    case ($pltfrm !== $pltfrm_clean):
	    	  print_r('data tamper ->' . $pltfrm_clean);
	    	  rtnwebapp('error' , 'error_tamper' , 'post', '', '');			 /* test for failure */	
	    break;

	    case ($browsr !== $browsr_clean):
	    	  print_r('data tamper ->' . $browsr_clean);
	          rtnwebapp('error' , 'error_tamper' , 'post', '', '');			 /* test for failure */	
	    break;

	    case ($timezo !== $timezo_clean):
	    	  print_r('data tamper ->' . $timezo_clean);
	    	  rtnwebapp('error' , 'error_tamper' , 'post', '', '');			 /* test for failure */	
	    break;

	    case ($kcode !== $kcode_clean):
	    	  print_r('data tamper ->' . $timezo_clean);
	    	  rtnwebapp('error' , 'error_tamper' , 'post', '', '');			 /* test for failure */	
	    break;

	   }

	
	  switch (true) {

	  	 /* using default XSFR auth_token 333dc638eb62fe4a57964afedfb2bac0a0e333 for passing follow flag information * _ccc[kcode] == 333dc638eb62fe4a57964afedfb2bac0a0e333[1 or 0] */

	  	 case ($uscode === 'new_usr'):
	
	  	 		switch (true) {

	  	 	   	 case ($kcode === '333dc638eb62fe4a57964afedfb2bac0a0e333'):
	  	 	   	 	   /* correct * default XSFR auth token * no cc follow * set to 0 * no follow promise * tw.usr.ccfollow === 0 */
	  	 	   	 	   $fcode = 0;
	  	 	   	 break;

	  	 	   	 case ($kcode === '333dc638eb62fe4a57964afedfb2bac0a0e3331'):
	  	 	   	 	   /* correct * default XSFR auth token * cc follow * set to 2 * new follow promise usr * tw.usr.ccfollow === 2 */
	  	 	   	 	   $fcode = 2;
	  	 	   	 break;
	  	 	   	 
	  	 	   	 default:
	  	 	   	       rtnwebapp('error' , 'error_tamper' , 'post', '', '');	   /*  XSFR default auth token test for failure */
	  	 	   	 break;	  
	  	 	   }

	  	 break;

	  	 /* using a XSFR auth_token for providing addtional validation to protect current user updates into db either email or password */

	  	 case ($uscode === 'new_usr_upd'):  /* changed from case ($uscode == 'new_usr') * new_usr to ($uscode == 'new_usr') _upd(ate) */
	  	 	   
	  	 	   $uscode = 'new_usr';

	  	 	   /*	
	  	 	   print_r('token in db  === ' . get_token_user($sncode, $mysqli) );
	  	 	   print_r('screen name posted ===' . $sncode);
	  	 	   print_r('token posted === ' . $kcode);
               */
	  	 	   
     	 	   switch (true) {

	  	 	   	 case (get_token_user($sncode, $mysqli) === $kcode): /*  XSFR auth token test for success */
	  	 	   	 	   /* correct * default XSFR auth token * no cc follow * set to 0 * no follow promise * tw.usr.ccfollow === 0 */
	  	 	   	 	   $fcode = 0;
	  	 	   	 break;

	  	 	   	 case ((get_token_user($sncode, $mysqli). 1) === $kcode): /*  XSFR auth token test for success . 1 */
	  	 	   	 	   /* correct * default XSFR auth token * no cc follow * set to 0 * no follow promise * tw.usr.ccfollow === 0 */
	  	 	   	 	   $fcode = 2;
	  	 	   	 break;

	  	 	   	 default:
	  	 	   	  	   rtnwebapp('error' , 'error_tamper' , 'post', '', '');	  /*  XSFR auth token test for failure * none of the above matched ! */
	  	 	   	 break;
	  	 	   	
	  	 	   }

	  	 	   /*
	  	 	   print_r('token in db  === ' . get_token_user($sncode, $mysqli) );
	  	 	   print_r(' : screen name posted ===' . $sncode);
	  	 	   print_r(' : token posted === ' . $kcode);
	  	 	   print_r(' : token match? === ' . (get_token_user($sncode, $mysqli). 1) );
	  	 	   print_r(' : fcode  === ' . $fcode );
			   exit();
			   */
	  	 	   
	  	 break;

	  	 case ($uscode === '_reset'):  /* added to change $pcode === _reset * add tamper detect */
	
	  	 	   /* possible tamper check * is $pcode a valid email address */

	  	 	   $pcode = '_reset';

	  	 break;

	  }


	  switch (true) {


	  case ($ecode == '_$twitter' && $pcode == '_$twitter'):
		    // twitteroauth('_$twitter','_$twitter', $pltfrm, $browsr, $timezo); --> found var values upated? commented out updated 25th March 2014 **
		    twitteroauth('_$twitter','_$twitter', $pltfrm, $browsr, $timezo, $fcode);
		    exit();
	  break;

	  case ($sncode == '_$twitter'):
		    // twitteroauth($ecode, $pcode, $pltfrm, $browsr, $timezo); **
		    twitteroauth($ecode, $pcode, $pltfrm, $browsr, $timezo, $fcode);
		    exit();
	  break;

	  case ($sncode == '_reset' && $pcode == '_reset'):
		    // $signin_result = signin_process($ecode, $pcode, $sncode , $uscode, $pltfrm, $browsr, $timezo, $mysqli, $mysqli_api); **
    	    $signin_result = signin_process($ecode, $pcode, $sncode, $uscode, $pltfrm, $browsr, $timezo, $fcode, $mysqli, $mysqli_api);

      break;

	  case ($ecode != '_$twitter' && $pcode != '_$twitter'):

		 // $signin_result = signin_process($ecode, $pcode, $sncode, $uscode, $pltfrm, $browsr, $timezo, $mysqli, $mysql_api); **
		 $signin_result = signin_process($ecode, $pcode, $sncode, $uscode, $pltfrm, $browsr, $timezo, $fcode, $mysqli, $mysqli_api);

		 // echo $signin_result[0] . '|' . $signin_result[1] . '|' .  $signin_result[2] . '|' . $signin_result[3] . '|' . $signin_result[4];
		 // exit();

	  break;

	  }

	  /*
	
	  testing for failure rtn from signin_process() if failure detected,
	  messages are passed back to the client and the continued processing of this script
	  is terminated.

	  $signin_result = signin_process($ecode, $pcode, $sncode , $uscode, $pltfrm, $browsr, $timezo, $mysqli, $mysqli_api);
	
	  returns;
		
	  $signin_result[0];  -> 	username                      good/bad   
	  $signin_result[1];  -> 	email                         good/bad   
	  $signin_result[2];  -> 	password                      good/bad 
	  $signin_result[3];  -> 	uid twitter account           good/bad
      $signin_result[4];  -> 	token twitter account        (if uid found )
      $signin_result[5];  -> 	token secret twitter account (if uid found )
    
      $signin_result[6];  -> 	1 == confirmed email account

	  */

	  switch ($uscode) {

		case ('_reset'):

			switch (false) {
				case ($signin_result[0]):
					  // echo json_encode('error_ucode');     		        //  username fail (not found in db rtn to app)
					  rtnwebapp('error' , 'error_ucode' , 'post', '', '');	/*  username fail (not found in db rtn to app) */
					  exit();
				break;
				case ($signin_result[1]):
					  // echo json_encode('error_ecode');     		        // ... email fail (not found in db rtn to app)
					  rtnwebapp('error' , 'error_ecode' , 'post', '', '');  /* ... email fail (not found in db rtn to app) */
					  exit();
				break;
				 				                         
				case (!$signin_result[0]):				  		/* username or */
				case (!$signin_result[1]):				  		/* email ...   */
					  // echo json_encode('pass_ecode');     	// email or username pass (found in db, pass to token function) ... defer until token sent ...
					  // signin_token($ecode, $mysqli);	   		// assume success, failure will be reported ... 

    /*
	  print_r( $ecode );
	  print_r('|');
	  print_r( $pltfrm );
	  print_r('|');
	  print_r( $browsr );
	  print_r('|');
	  print_r( $timezo );
	  print_r('thats all she wrote');
	  exit();
    */
					  signin_token($ecode, $pltfrm, $browsr, $timezo, $mysqli);
					  exit();
				break; 				                       		/* or die() unless you req an exit code */
			}

		break;

		case ('new_usr'):

			switch (false) {
				case ($signin_result[1]):
					  // echo json_encode('error_ecode');     		        /* email fail */
					  rtnwebapp('error' , 'error_ecode' , 'post', '', '');  /* ... email fail (not found in db rtn to app) */
				      exit();
				break; 				                       		            /* or die() unless you req an exit code */
				case ($signin_result[2]):
					  // echo json_encode('error_pcode');     		        /* password fail */
					  rtnwebapp('error' , 'error_pcode' , 'post', '', '');  /* ... password fail */
				      exit(); 				               	                /* or die() unless you req an exit code */
			    break;
			}

		break;

		case ('cur_usr'):

			switch (false) {

				case ($signin_result[0]):
					  // print_r($signin_result);
					  // echo $signin_result[0] . '|' . $signin_result[1] . '|' .  $signin_result[2] . '|' . $signin_result[3] . '|' . $signin_result[4];
					  // echo json_encode('error_ucode');     		        // username fail
					  rtnwebapp('error' , 'error_ucode' , 'post', '', '');  /* username fail */
					  exit();							   		            /* or die() unless you req an exit code */
				break; 	
				case ($signin_result[1]):
					  // echo json_encode('error_ecode');     		        // email fail
					  rtnwebapp('error' , 'error_ecode' , 'post', '', '');  /* ... email fail (not found in db rtn to app) */
					  exit();							   		  	        /* or die() unless you req an exit code */
				break; 				                    
				case ($signin_result[2]):				  		  	        /* password fail */
					  switch (true) {						  	            /* count how many failed signin (suspect hacker block) */

						 case ($signin_result[6] == 1):	                    /* on fully registered accounts (with confirmed email) */
							   
							   $visitor = trespass($signin_result[1], $pcode, $mysqli);
				   	  
				   	  	/*  
				   	    	   returns, true (attempts + 1), false
					    	   (update db error) or locked (attempts == 5)
					    	   send email reset to user on 5th attempt,
					    	   after that continue with password fail messages
					  	*/

					    	   switch ($visitor) {

						    	  case ('locked'):
						    		     // signin_token($ecode, $mysqli);		   /* assume success, failure will be reported ... */
						    	         signin_token($ecode, $pltfrm, $browsr, $timezo, $mysqli);
						    	 	  	 unset($content);
				    				  	 exit(); 				                   /* or die() unless you req an exit code */
						          break;
						       }

						 break;

					  }

					  // echo json_encode('error_pcode');                   // password fail
					  rtnwebapp('error' , 'error_pcode' , 'post', '', '');  /* ... password fail */
				      exit(); 				                                /* or die() unless you req an exit code */
				break;
			}

			switch (false) {
				case ($signin_result[3]):
				      // print_r($signin_result); 		                    // use $signin_result[7] blank array position to check array
					  // echo json_encode('error_tcode');                   // social, no twitter account details found
					  rtnwebapp('error' , 'error_tcode' , 'post', '', '');  /* social, no twitter account details found */
					  exit();
				break;
			}
			
			/*
			 *	testing for success rtn from signin_process(); email, password have been verified,
			 *	account options need to be checked and social network connections made. 
			*/

		    switch(true) {
				case($signin_result[3]):

					/* session_start();	*/

					// $_SESSION['cauth_token'] = $signin_result[4]; 
	    			// $_SESSION['cauth_token_secret'] =  $signin_result[5];
	    			// $_SESSION['cauth_token'] = ccrypt( $signin_result[4], 'AES-256-OFB', 'de' );          /* un-encrypt out of storage */
	    			// $_SESSION['cauth_token_secret'] =  ccrypt( $signin_result[5], 'AES-256-OFB', 'de');	  /* un-encrypt out of storage */

	    			$uauth_token = '';
	    			$uauth_token_secret = '';
	    		
	    			$uauth_token = ccrypt( $signin_result[4], 'AES-256-OFB', 'de' );          /* un-encrypt out of storage */
	    			$uauth_token_secret = ccrypt( $signin_result[5], 'AES-256-OFB', 'de');	  /* un-encrypt out of storage */

	    			/* build twitterOAuth object with client credentials unencrypted from db */
	    			/* $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['cauth_token'], $_SESSION['cauth_token_secret']); */

					$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $uauth_token, $uauth_token_secret); 
					
					$content = $connection->get('account/verify_credentials');

					if ( $connection->lastHttpCode() !== 200 ) { rtnwebapp('error' , 'error_network' , 'post', '', ''); exit(); }


					/*
					check to see if twitter user name / screen name is the same as the name stored in the db, users change their user id sometimes
					if the twitter username has been changed --> updated the username in the db so they match, prevent cross contamination ... 
					*/


				    if( empty( $content->errors ) ) {

						// if ( is_array($content) ) {

				    	$nucode = '';
				    	
				    	/*
						$nucode = json_encode($content);
						$nucode = json_decode($nucode);
						$nucode = $nucode->screen_name;
						*/

						$nucode = $content->screen_name;

						name_check($nucode, $signin_result[1], $mysqli);

					    // }

				    } else {

				    	rtnwebapp('error' , 'error_authentication' , 'post', '', '');  /* ... Bad Authentication data */
				    	/* {"errors":[{"message":"Bad Authentication data","code":215}]}} */
						unset($content);
				    	exit(); 				                                       /* or die() unless you req an exit code */

				    }					

					switch (true) {

						case ($signin_result[6] == 0):

							  $usr = array('ccuser' => 'ccn','ccname' => base64_encode($nucode),'ccmail0' => base64_encode($signin_result[1]),'ccmail1' => base64_encode($signin_result[1]), 'ccmail2' => base64_encode($signin_result[1]), 'ccfollow' => $signin_result[7], 'cctoken' => $signin_result[4], 'ccspace' => 0, 'cclimit' => 0 );

							  $output = array('usr' => $usr,'content' => $content);

							  /*
							  $output = array(
    						 	 'user'  => 'ccn',
    						 	 'ucode'   => base64_encode($nucode),
    						 	 'ecode'   => base64_encode($signin_result[1]),
    						 	 'fcode'   => $signin_result[7],
    						 	 'content' => $content
							  );
                              */

							  rtnwebapp('ccc', $output, 'post', $signin_result[5], $signin_result[8]);

							  unset($output);
							  unset($content);

						break;
						case ($signin_result[6] == 1):

							  $visitor = visitation($signin_result[1], $mysqli);

						      switch ($visitor) {

						    	case ('locked'):
						    		  rtnwebapp('error' , 'error_pcode' , 'post', '', '');  /* ... password fail */
						    		  unset($content);
				    				  exit(); 				                                /* or die() unless you req an exit code */
						    	break;
					
						      }


						      $usr = array('ccuser' => 'ccc','ccname' => base64_encode($nucode),'ccmail0' => base64_encode($signin_result[1]),'ccmail1' => base64_encode($signin_result[1]), 'ccmail2' => base64_encode($signin_result[1]), 'ccfollow' => $signin_result[7], 'cctoken' => $signin_result[4], 'ccspace' => 0, 'cclimit' => 0 );

						      $output = array('usr' => $usr,'content' => $content);


						      /*
							  $output = array(
    						     'user'  => 'ccc',
    						     'ucode'   => base64_encode($nucode),
    						     'ecode'   => base64_encode($signin_result[1]),
    						     'fcode'   => $signin_result[7],
    						 	 'content' => $content
							  );
							  */

							  rtnwebapp('ccc', $output, 'post', $signin_result[5], $signin_result[8]);

							  unset($output);
							  unset($content);

						break;

					}

				break;
			}

		break;

	  } 

  	break;

  	case (isset($_GET['token'])):
      
       /* if ( empty($_SESSION['time']) ) { $_SESSION['time'] = time(); } */

       if (empty($_GET['token'])) { rtnwebapp('error' , 'error_tamper' , 'post', '', ''); exit(); }

       $token_uname = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
	   $token_uname = htmlspecialchars($token_uname, ENT_COMPAT | ENT_QUOTES | ENT_HTML5, 'UTF-8');
	   $token_uname = trim(decrypt($token_uname));

	   $ip_address = $_SERVER['REMOTE_ADDR'];
	   /* $ip_address = mysql_real_escape_string($ip_address); */
       $ip_address = $mysqli->real_escape_string($ip_address);

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

	    if (set_token_user($token_uname, $token_safe, $mysqli)) { rtnwebapp('correct' , $token_safe , 'get', '', ''); }
    
    break;

    }



	function rtnwebapp( $flag, $token, $whofor, $secret, $apikey ) {

 	/*  function is passed the following ;
  	 *
  	 *  $flag    -> status or error
  	 *  $token   -> token data || status data || error data
  	 *  $whofor  -> post || get
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

				if ($flag === 'ccc') {

					$domain = ($_SERVER['HTTP_HOST'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : false;

				    // $ccheck = ccrypt( $_SESSION['cauth_token_secret'], 'AES-256-OFB', 'en' );   /* for testing  */
	    			// $ccheck = ccrypt( $ccheck, 'AES-256-OFB', 'de');	                           /* for testing  */
				    
				    /* setcookie('cauth_token', $_SESSION['cauth_token_secret'], time()+60*60*24*365, '/', $domain, true, true); */

					/* options -> setcookie('cauth_token', $secret, time()+60*60*24*365, '/', $domain, 'secure', 'httponly'); */

					setcookie('cauth_token', $secret, time()+60*60*24*365, '/', $domain, false, true);
					setcookie('ccid', $apikey, time()+60*60*24*365, '/', $domain, false, true);

				    echo json_encode( $token );

				} else {

			  	    echo json_encode( $flag . ':*:' . $token );
		      	
			    }

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


	function get_token_user($token_uname, $mysqli) {

		$s_stmt15 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE uname = ? LIMIT 1");
	    $s_stmt15->bind_param('s', $token_uname);      /*  bind "session_id" to parameter. */
		$s_stmt15->execute();                          /*  execute the prepared query.     */
		$s_stmt15->store_result();  
	    $s_stmt15->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_pltfrm, $db_browsr, $db_token_safe, $db_now, $db_timezo, $db_timelocal_user, $db_locked, $db_visits);      // get variables from result.
		$s_stmt15->fetch();
		$s_stmt15->close();

		return $db_token_safe;

	}


	function set_token_user($token_uname, $token_safe, $mysqli) {

		/* function updates the session_auth field in the crowdcc_sessions database, sessions table to include the auth token sent from the server to the client
		   during user account db changes, email address and password updates, this session_auth token is then checked when the client sends back the change request */

		$s_stmt14 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE uname = ? LIMIT 1");
	    $s_stmt14->bind_param('s', $token_uname);      /*  bind "session_id" to parameter. */
		$s_stmt14->execute();                          /*  execute the prepared query.     */
		$s_stmt14->store_result();  
	    $s_stmt14->bind_result($db_user_id, $db_uname, $email_past, $db_email_current, $db_ip_address, $db_pltfrm, $db_browsr, $db_token_safe, $db_now, $db_timezo, $db_timelocal_user, $db_locked, $db_visits);      // get variables from result.
		$s_stmt14->fetch();
		$s_stmt14->close();
				
		if  ($i_stmt10 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      		 $i_stmt10->bind_param('issssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_pltfrm, $db_browsr, $token_safe, $db_now, $db_timezo, $db_timelocal_user, $db_locked, $db_visits);
	         log_error($timelocal,'bind_param', $i_stmt10, $mysqli); 
	 		 /* execute the prepared query. */
	         $i_stmt10->execute();
	         log_error($timelocal,'execute', $i_stmt10, $mysqli);
	         /* $insert_stmt->store_result(); */
	         $i_stmt10->close();
	         /* return true; */

	    } else {
	 		 
	 		 /* registration failed in db return false; unset session, remove cookies, log any prepare statement errors */
	         /* secure_session_destroy(); */
	         log_error($timelocal,'prepare', $i_stmt10, $mysqli);
	         $i_stmt10->close();
	         rtnwebapp('error' , 'error_tcode' , 'post', '', '');  /* social, no twitter account details found */
	         exit();
        }
        				   
	}



	function name_check($nucode, $ecode, $mysqli) {

		global $timelocal;

		if ($ecode !== '') {

		    /* $s_stmt12 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1"); */
		    $s_stmt12 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1");
			$s_stmt12->bind_param('s', $ecode);      /*  bind "$ecode" to parameter. */
			$s_stmt12->execute();                    /*  execute the prepared query. */
			$s_stmt12->store_result();

			/* $s_stmt12->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user); */
			$s_stmt12->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user);     // get variables from result.
			$s_stmt12->fetch();
			$s_stmt12->close();
	
			switch (true) {
			
				case ($nucode !== $db_uname):
				
				  	/* if  ($i_stmt03 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
      				if  ($i_stmt03 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      						
	 				   	 /* $i_stmt03->bind_param('isssssssssssssssss', $db_user_id, $nucode, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user); */
	 				   	 $i_stmt03->bind_param('issssssssssssssss', $db_user_id, $nucode, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user);
					     log_error($timelocal,'bind_param', $i_stmt03, $mysqli); 
	 				     /* execute the prepared query. */
	             	     $i_stmt03->execute();
	              	     log_error($timelocal,'execute', $i_stmt03, $mysqli);
	              	     /* $insert_stmt->store_result(); */
	                     $i_stmt03->close();
	              	     /* return true; */
	                } else {
	 				     /* namecheck failed in db, return false; */
	              	     log_error($timelocal,'prepare', $i_stmt03, $mysqli);
	              	     $i_stmt03->close();
            	    }
			    
			    break;
		    }

		}

	}

	function toArray( $data ) {
    	
    	if ( is_object( $data ) ) {
    		 $data = get_object_vars( $data );
    	}
    	return is_array($data) ? array_map(__FUNCTION__, $data) : $data;
    }
    
    function signin_process($ecode, $pcode, $sncode , $uscode, $pltfrm, $browsr, $timezo, $fcode, $mysqli, $mysqli_api) {
	
	   /*

		inputs;

		$ecode   // email address or usernmae
		$epcode	 // password

		inputs global;

		$oDB     // db object

		returns;
		
		$signin_result[0];  // username                      good/bad   
		$signin_result[1];  // email                         good/bad   
	    $signin_result[2];  // password                      good/bad 
	    $signin_result[3];  // uid twitter account           good/bad
        $signin_result[4];  // token twitter account        (if uid found )
        $signin_result[5];  // token secret twitter account (if uid found )

        $signin_result[6];  // email confirm flag set un-set $db_email_confirm
	    $signin_result[7];  // follow user flag set un-set $db_fcode
		$signin_result[8];  // ccid token * api key

	   */

        // global $timelocal;
		        
		$signin_array = array();

		$ecode_rows = 0; $ucode_rows = 0;

		switch($uscode) {

			case('new_usr'):

				switch(true) {

				    /* new user with twitter account screen name, uid and tokens already stored in db * update with email and password */
				    case (!empty($sncode)):

				  		/* guard check for valid_email($ecode), ensure invalid email address is not injected into db */
				  		if (valid_email($ecode)) {
						   
				  			/* $s_stmt20 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1"); */
				  			$s_stmt20 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1");     // 18 fields
						    $s_stmt20->bind_param('s', $ecode);    /*  bind "$sncode" to parameter. */
						    $s_stmt20->execute();                  /*  execute the prepared query.  */
						    $s_stmt20->store_result();  
						    
						    /* $s_stmt20->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user); */
						    $s_stmt20->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user);     // get variables from result.
						    $s_stmt20->fetch();
						    
						    switch (true) {
						    	/* signin in as new user without twitter account * email check * email already in use */
						    	case ($s_stmt20->num_rows > 0):
						    		  /* print_r('num_rows = ' . $s_stmt20->num_rows); */
						    		  $s_stmt20->close();
								      /* print_r(' fake new user * num rows > 0 * without twitter account * email check * email already in use * thats all she wrote!'); */
								      rtnwebapp('error' , 'error_ereg' , 'post', '', ''); 
								      exit();
						    	break;

						    	case ($s_stmt20->num_rows === 0):
						    	/* signin in as new user social user with twitter account * email check * email NOT already in use * genuine new user */
						    		  /* print_r('num_rows = ' . $s_stmt20->num_rows); */
						    		  $s_stmt20->close();
								      /* print_r(' real new user * num rows === 0 * with twitter account * email check * email not already in use * thats all she wrote!'); */
						    	break;

							}

					        /* $s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1"); */
					        $s_stmt01 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1");     // 18 fields
						    $s_stmt01->bind_param('s', $sncode);    /*  bind "$sncode" to parameter. */
						    $s_stmt01->execute();                   /*  execute the prepared query.  */
						    $s_stmt01->store_result();

						    /* $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user); */
						    $s_stmt01->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone_user, $db_timelocal_user);     // get variables from result.
						    $s_stmt01->fetch();

						    /* valid email detected * user data fetched from db $s_stmt01->num_rows === 1 */
							
							switch(true) {
							  case ($s_stmt01->num_rows > 0):

							    /* print_r('num_rows = ' . $s_stmt01->num_rows); print_r('start * new user * thats all she wrote!');exit(); */

						        /* if  ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) { */
						        if  ($i_stmt01 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
								     
								     /* new user id simpleflake * if($db_user_id === '') { $db_user_id = simpleflake(); } */

								     /* init new api key token * 20 tweets * 50 tweets if update with user email is confirmed * if ($db_api_key === '') { $db_api_key = create_api_token($db_uname, '20'); } */
								     
								     /* old method * create a random salt * hash password with random salt * store salt for comparison check */
		 
	            				     /* $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true)); */
	            				     /* $pcode  = hash('sha512', $pcode.$random_salt);  /* create random salt * hash password with random salt, _username, _email (careful not to over season) */

	            				     /* password_hash($password, PASSWORD_BCRYPT, array("cost" => 11)); * compatibility library with PHP 5.5's simplified password hashing API. * random salt and hash are combined in 60 character salt*hash */

	            				     $pcode = password_hash($pcode, PASSWORD_BCRYPT, array("cost" => 11)); /* default is cost 10 */

								     $ip_address = $_SERVER['REMOTE_ADDR'];
	            				     /* $ip_address = mysql_real_escape_string($ip_address); */
        							 $ip_address = $mysqli->real_escape_string($ip_address);

	            				     /* $follow_user = 0; */

								     if ($timezo != '') {
	    						    	 date_default_timezone_set("UTC");
	    						    	 $now = time();
	    						    	 $date = new DateTime(null, new DateTimeZone($timezo));
	   							         $timelocal_user = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));
	  							     } else {
	    						         /* echo json_encode('error_tcode'); */
	  							    	 rtnwebapp('error' , 'error_tcode' , 'post', '', '');      /* social, no twitter account details found */
	    						         exit();
	    						     }

	    						     $email_current = $ecode;
	    						     $email_past = $ecode;

	      						     /* $i_stmt01->bind_param('isssssssssssssssss', $db_user_id, $db_uname, $email_past, $email_current, $db_email_confirm, $db_api_key, $pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $ip_address, $pltfrm, $browsr, $db_fcode, $now, $timezo, $timelocal_user); */
	      						     $i_stmt01->bind_param('issssssssssssssss', $db_user_id, $db_uname, $email_past, $email_current, $db_email_confirm, $db_api_key, $pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $ip_address, $pltfrm, $browsr, $db_fcode, $now, $timezo, $timelocal_user);
								     global $timelocal;
								     log_error($timelocal,'bind_param', $i_stmt01, $mysqli); 
	 							     /* execute the prepared query. */
	             				     $i_stmt01->execute();
	              				     log_error($timelocal,'execute', $i_stmt01, $mysqli);
	              				     /* $insert_stmt->store_result(); */
	                      		     $i_stmt01->close();
	              				     /* return true; */

	              				     /* new user update * for native crowdcc signin ccid * api key managment * start */
	              				     /* api key is set * new user limit 50 * with unconfirmed email * $db_api_key_check = explode(',', check_api_token( $db_api_key )); */

	              				     /*
	              				     if ($s_stmt16 = $mysqli_api->prepare("SELECT user_id, uname, ccc_store, ccc_limit, api_key, api_hit, api_hit_date FROM members WHERE uname = ? LIMIT 1")) {
							        	 $s_stmt16->bind_param('s', $db_uname);                     //  bind $db_email_current to parameter, if confirm email, or $db_email_past if update email
							        	 log_error_api($timelocal,'bind_param', $s_stmt16, $mysqli_api); 
							        	 $s_stmt16->execute();                                      //  execute the prepared query.
							        	 log_error_api($timelocal,'execute', $s_stmt16, $mysqli_api);
							        	 $s_stmt16->store_result();  
							        	 $s_stmt16->bind_result($db_mem_user_id, $db_mem_uname, $db_ccc_store, $db_ccc_limit, $db_mem_api_key, $db_api_hit, $db_hit_date);      // get variables from result.
							        	 $s_stmt16->fetch();
						    	      } else {
							        	 log_error_api($timelocal,'prepare', $s_stmt16, $mysqli_api);
							        	 $s_stmt16->close();    	 
						    	      }
						    	     
						    	      // test SELECT for rows returned from members table
						    	  
						    	      switch (true) {
						    	        case ($s_stmt16->num_rows === 0):
						    	              $s_stmt16->close();

						    	              // members tables records not found * build new table record
						    	  
						    	     	      $db_ccc_store = 0;
						    	              $db_ccc_limit = 50;
						                      $db_api_hit = 0;
						    	              date_default_timezone_set("UTC");
	    						      		  $now = time();

						                      if ($i_stmt11 = $mysqli_api->prepare("REPLACE INTO members (user_id, uname, ccc_store, ccc_limit, api_key, api_hit, api_hit_date) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
							                      $i_stmt11->bind_param('issssss', $db_user_id, $db_uname, $db_ccc_store, $db_ccc_limit, $db_api_key, $db_api_hit, $now);
							         	          log_error_api($timelocal,'bind_param', $i_stmt11, $mysqli_api); 
							   	    	          $i_stmt11->execute();                                           // execute the prepared query.
							                      log_error_api($timelocal,'execute', $i_stmt11, $mysqli_api);
							                      $i_stmt11->store_result();
							        	          $i_stmt11->close();  
							    
						    	              } else {
							  
							         	        log_error_api($timelocal,'prepare', $i_stmt11, $mysqli_api);
							         	        $i_stmt11->close();    	 
						    	              }
						    	
						    	      break; 	

				    			      case ($s_stmt16->num_rows > 0):
				    			         	$s_stmt16->close(); 
				    			  
				    			             // $db_api_key should already be same in both regist_members and members tables * nothing to do!

				    			      break;

				    			      }
				    			     
				    			      */

	              				      /* new user update * for native crowdcc signin ccid * api key managment * end */

	              				      if  ($s_stmt05 = $mysqli->prepare("SELECT token_user, visits FROM signin_members WHERE uname = ? LIMIT 1")) {
      							           $s_stmt05->bind_param('s', $db_uname);                   //  bind "$sncode" to parameter.
      							           log_error($timelocal,'bind_param', $s_stmt05, $mysqli); 
								           $s_stmt05->execute();                   				   //  execute the prepared query.
								           log_error($timelocal,'execute', $s_stmt05, $mysqli);
								           $s_stmt05->store_result();  
								           $s_stmt05->bind_result($db_token_usr, $db_visits);       // get variables from result.
								           $s_stmt05->fetch();
								           $s_stmt05->close();

	              				      } else {
	 									  
	 							           /* registration failed in db return false; unset session, remove cookies, log any prepare statement errors */
	              				           /* secure_session_destroy(); */
	              								 
	              				           log_error($timelocal,'prepare', $s_stmt05, $mysqli);
	              				   	       $s_stmt05->close();
	              								 	  
	              				           // echo json_encode('error_tcode');
	              								 
	              				           rtnwebapp('error' , 'error_tcode' , 'post', '', '');      // social, no twitter account details found
	              				           exit();
            					      }

            					      	   $db_token_usr = 0;
            					           $db_locked = 0;
            					           $db_visits = $db_visits + 1;

	                      		      if  ($i_stmt05 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      									 
	                      		   	       $i_stmt05->bind_param('issssssssssss', $db_user_id, $db_uname, $email_past, $email_current, $ip_address, $pltfrm, $browsr, $db_token_usr, $now, $timezo, $timelocal_user, $db_locked, $db_visits);
	                      		           log_error($timelocal,'bind_param', $i_stmt05, $mysqli); 
	 							  
	 							           /* execute the prepared query. */
	             				  
	             				           $i_stmt05->execute();
	              				           log_error($timelocal,'execute', $i_stmt05, $mysqli);
	              								 	  
	              				           /* $insert_stmt->store_result(); */
	                      						 
	                      		           $i_stmt05->close();
	              				           /* return true; */

	                      		       } else {
	 									   /* registration failed in db return false; unset session, remove cookies, log any prepare statement errors */
	              						   /* secure_session_destroy(); */
	              				    	   log_error($timelocal,'prepare', $i_stmt05, $mysqli);
	              				    	   $i_stmt05->close();
	              				           echo json_encode('error_tcode');
	              				    	   rtnwebapp('error' , 'error_tcode' , 'post', '', '');  	/* social, no twitter account details found */
	              				    	   exit();
            					       }

	                      		       switch(true) {

	                      		         case ($db_email_confirm == 1):
	                      				       /* full user confirmed email, password good, social ids good */
	                      				       /* echo json_encode('pass_tcode'); */
	                      				       /* rtnwebapp('error' , 'pass_tcode' , 'post'); */
	                      		 	  	       rtnwebapp('error' , 'error_pass_tcode' , 'post', '', '');
	                      		 	     break;

	                      		  	     case ($db_email_confirm == 0):
	                      				       /* limited user unconfirmed email, password good, social ids good */
	                      				       /* echo json_encode('pass_scode'); */
	                      				       /* rtnwebapp('error' , 'pass_scode' , 'post'); */
	                      				       if ( $fcode === 2 ) {
	                      		 	               rtnwebapp('error' , 'error_pass_scode_fcode' , 'post', '', '');
	                      		 	           } else {
	                      		 	           	   rtnwebapp('error' , 'error_pass_scode' , 'post', '', '');
	                      		 	           }
	                      		  	     break;

	                      		        }

 								  } else {
	 								     /* registration failed in db return false; unset session, remove cookies, log any prepare statement errors */
	              					     /* secure_session_destroy(); */
	              				         log_error($timelocal,'prepare', $i_stmt01, $mysqli);
	              				         $i_stmt01->close();
	              				         /* echo json_encode('error_tcode'); */
	              				         rtnwebapp('error' , 'error_tcode' , 'post', '', '');  /* social, no twitter account details found */
	              				         // exit();
            					  }

    	                          // $oDB->replace('users',$field_values, 'uname = "'. $db_from_screen_name . '"' );
    	                          // echo json_encode('pass_tcode');
    	                          // $query = NULL; $result = NULL;
    	                          // $db_from_screen_name = NULL; $db_from_user_uid = NULL; $db_access_token = NULL; $db_access_token_secret = NULL; $field_values = NULL;
    	                          // $row = NULL;
								  // exit(); 
								
							  break;

							  case ($s_stmt01->num_rows === 0):

	              					/*			 
									  print_r('num_rows = ' . $s_stmt01->num_rows);
								      print_r('end inner * new user * thats all she wrote!');
								      exit();
									*/

									$s_stmt01->close();


									/* user is really a new user social, no twitter account details found, assume, email and password details post+ */

									echo json_encode('error_tcode');
									exit();
							  break;

							}

						} else {

							rtnwebapp('error' , 'error_ecode' , 'post', '', '');  /* ... email not valid for insert into db   */
					        exit();							   		  	          /* ... or die() unless you req an exit code */
						}

					break;

					/* user already exists */
					case ($s_stmt01->num_rows > 0):

						  /*	
						  print_r('num_rows = ' . $s_stmt01->num_rows);
						  print_r('end outer * new user * thats all she wrote!');
					      exit();
						  */		

						  $s_stmt01->close();

					/* continue as if already registered ... */
					break;

				}	

			break;

		}


		$email_chk = valid_email($ecode);

		switch (true) {

			case ($email_chk):
			      global $timelocal;
				  /* $s_stmt02 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1"); */
				  $s_stmt02 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1");
				  $s_stmt02->bind_param('s', $ecode);  	  					 /*  bind "$ecode" to parameter. */
				  log_error($timelocal,'bind_param', $s_stmt02, $mysqli); 
				  $s_stmt02->execute();										 /*  execute the prepared query. */
				  log_error($timelocal,'execute', $s_stmt02, $mysqli);                    
				  $s_stmt02->store_result();  
				  /* $s_stmt02->bind_result($db_user_id, $db_uname, $db_email_past, $db_ecode, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone, $db_timelocal); */
				  $s_stmt02->bind_result($db_user_id, $db_uname, $db_email_past, $db_ecode, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone, $db_timelocal);     // get variables from result.
				  $s_stmt02->fetch();
				  log_error($timelocal,'prepare', $s_stmt02, $mysqli);
				  $ecode_rows = $s_stmt02->num_rows;
				  $s_stmt02->close();
			break;

			case (!$email_chk):
			      global $timelocal;
			      /* $s_stmt03 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1"); */
			      $s_stmt03 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE uname = ? LIMIT 1");
				  $s_stmt03->bind_param('s', $ecode);  	                     /*  bind "$ecode" to parameter. */
				  log_error($timelocal,'bind_param', $s_stmt03, $mysqli); 
				  $s_stmt03->execute();                                      /*  execute the prepared query. */
				  log_error($timelocal,'execute', $s_stmt03, $mysqli);  
				  $s_stmt03->store_result();  
				  /* $s_stmt03->bind_result($db_user_id, $db_uname, $db_email_past, $db_ecode, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone, $db_timelocal); */
				  $s_stmt03->bind_result($db_user_id, $db_uname, $db_email_past, $db_ecode, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone, $db_timelocal);     // get variables from result.
				  $s_stmt03->fetch();
				  log_error($timelocal,'prepare', $s_stmt03, $mysqli);
				  $ucode_rows = $s_stmt03->num_rows;
				  $s_stmt03->close();
			break;

		}

		/* $pcode = hash('sha512', $pcode.$db_random_salt); */

		/* stored $db_pcode bcrypt of password matches entered password * opportunity to update * upgrade password BCRYPT hash cost > 11 */

		if ( password_verify($pcode, $db_pcode) ) { 
			 $pcode = $db_pcode;

			 if ( password_needs_rehash($db_pcode, PASSWORD_BCRYPT, array('cost' => 11)) ) {
			 	  $db_pcode = password_hash($pcode,  PASSWORD_BCRYPT, array('cost' => 11));
			 	  /* update * upgrade hash back into db */

			 	  /* 
			 	  if  ($i_stmt11 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
	 				   $i_stmt11->bind_param('isssssssssssssssss', $db_user_id, $db_uname, $db_email_past, $db_ecode, $db_email_confirm, $db_api_key, $db_pcode, $db_random_salt, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone, $db_timelocal);
	 			  */	   
 				  
 				  if  ($i_stmt11 = $mysqli->prepare("REPLACE INTO regist_members (user_id, uname, email_past, email_current, email_confirm, api_key, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
	 				   $i_stmt11->bind_param('issssssssssssssss', $db_user_id, $db_uname, $db_email_past, $db_ecode, $db_email_confirm, $db_api_key, $db_pcode, $db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_ip_address, $db_platform_user, $db_browser_user, $db_fcode, $db_time, $db_timezone, $db_timelocal);

					   log_error($timelocal,'bind_param', $i_stmt11, $mysqli); 
	 				   /* execute the prepared query. */
	             	   $i_stmt11->execute();
	              	   log_error($timelocal,'execute', $i_stmt11, $mysqli);
	              	   /* $insert_stmt->store_result(); */
	                   $i_stmt11->close();
	              	   /* return true; */
	              } else {
	 				   /* namecheck failed in db, return false; */
	              	   log_error($timelocal,'prepare', $i_stmt11, $mysqli);
	              	   $i_stmt11->close();
            	  }
			 } 
	    }

		/*

		$signin_array
		
		$signin_array[0];   // username                      good/bad   
		$signin_array[1];   // email                         good/bad   
	    $signin_array[2];   // password                      good/bad 
	    $signin_array[3];   // uid twitter account           good/bad
        $signin_array[4];   // token twitter account        (if uid found )
        $signin_array[5];   // token secret twitter account (if uid found )

        $signin_result[6];  // email confirm flag set un-set $db_email_confirm
	    $signin_result[7];  // follow user flag set un-set $db_fcode
		$signin_result[8];  // ccid token * api key   

        */

		switch (true) {
		
			case ($ecode_rows > 0):  					/* email address valid and found or */
			case ($ucode_rows > 0):	 					/* username valid and found */
		
				  switch(true) {
					  case($db_pcode !== $pcode):		/* passwords don't match */
					  
					  $signin_array = array($db_uname,
					  						$db_ecode,
					  						false,
					  						false
					  						);
					  
					  break;

					  case($db_uname == $ecode):
					  /* username found matches input */
					  case($db_ecode == $ecode):
					  /* email found matches input */
					  /* echo $db_ecode . " email exists and matches;-) <br>"; */
					  case($db_pcode == $pcode):

					  /*
					  print_r('$db_pcode  ==  $pcode * $db_pcode | $pcode ' . $db_pcode . '|' . $pcode );
					  print_r('end outer * new user * thats all she wrote!');
					  exit();
					  */

					  /* password found matches input */
 					  $signin_array = array($db_uname,
 					  						$db_ecode,
					  						$db_pcode,
					  						false
					  						);
					  break;

				  }

				  switch(true) {
				  	  case(!empty($db_from_user_uid)):
					  /* twitter details found for user */
					  /* echo $db_from_user_uid . " twitter details (uid) exist and match user ;-) <br>"; */
				  	  /* array_splice($signin_array, -1, count($signin_array), array($db_from_user_uid . " twitter details (uid) exist and match user ;-) <br> ", $db_access_token . " twitter token <br>", $db_access_token_secret . " twitter token secret")); */
					  
				  	  /*  array_splice($signin_array, -1, count($signin_array), array($db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_email_confirm)); */
				  	  /*  add 2nd july 2014 ->  $db_fcode to signin array */


				  	  /* current user update * for native crowdcc signin ccid * api key managment * start */

					  if ($s_stmt17 = $mysqli_api->prepare("SELECT api_key FROM members WHERE uname = ? LIMIT 1")) {
						  $s_stmt17->bind_param('s', $db_uname);                     /*  bind $db_uname */
						  log_error_api($timelocal,'bind_param', $s_stmt17, $mysqli_api); 
						  $s_stmt17->execute();                                      /*  execute the prepared query. */
						  log_error_api($timelocal,'execute', $s_stmt17, $mysqli_api);
						  $s_stmt17->store_result();  
						  $s_stmt17->bind_result($db_api_key);                       // get variables from result.
		    			  $s_stmt17->fetch();
					
					  } else {
							  
						  log_error_api($timelocal,'prepare', $s_stmt17, $mysqli_api);
						  $s_stmt17->close();    	 
					  }

					  /* update * native crowdcc signin ccid * api key managment * $db_api_key */
  					  /* array_splice($signin_array, -1, count($signin_array), array($db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_email_confirm, $db_fcode)); */
 
  					  array_splice($signin_array, -1, count($signin_array), array($db_from_user_uid, $db_oauth_token, $db_oauth_token_secret, $db_email_confirm, $db_fcode, $db_api_key));

  					  $s_stmt17->close();

				  	  /* array output check 
				  	  echo $signin_array[0] . '|' . $signin_array[1] . '|' .  $signin_array[2] . '|' . $signin_array[3] . '|' . $signin_array[4] . '|' . $signin_array[5] . '|' . $signin_array[6] . '|' . $signin_array[7] . '|' . $signin_array[8];
					  exit();
					  */
					  
					  break;
				  }

			break;

			case ($ecode_rows == 0 && $ucode_rows == 0):   					 /* email address / username not valid */
				 
				  switch (true) {
			 		  case($db_ecode != $ecode):							 /* email... match not found! */
			 		  case($db_uname != $ecode):							 /* username match not found! */
					  /* email found not match input */
					  /* echo $ecode . " email not match! <br>"; */
					  $signin_array = array(false,
					   						false,
					  						false,
					  						false
					  						);

					  /* failure ... process error message */
					  break;
				  }

			break;
 	  
		    }

		    $ecode_rows = null; $ucode_rows = null;


		   	/* array output check 
			echo $signin_array[0] . '|' . $signin_array[1] . '|' .  $signin_array[2] . '|' . $signin_array[3] . '|' . $signin_array[4] . '|' . $signin_array[5] . '|' . $signin_array[6] . '|' . $signin_array[7] . '|' . $signin_array[8];
			exit();
			*/
			

		    return $signin_array;

	}

	function twitteroauth($ecode, $pcode, $pltfrm, $browsr, $timezo, $fcode) {
	
		/* build twitterOAuth object with client credentials. */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	 
		/* get temporary credentials. */
		/* $request_token = $connection->getRequestToken(OAUTH_CALLBACK); * twitter oauth lib * source: http://abrah.am org * version: v0.1.2 * modified v0.2 */
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

		/* save Social -> twitter details already in db, but no email or password. */
		$_SESSION['user_ecode'] = $ecode;
		$_SESSION['user_pcode'] = $pcode;

		$_SESSION['user_fcode'] = $fcode;

		/* save platform details to session */
		$_SESSION['user_platform'] = $pltfrm;
		$_SESSION['user_browser']  = $browsr;
		$_SESSION['user_timezone'] = $timezo;

		/* save temporary credentials to session. */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];


		/* switch ($connection->http_code) { * twitter oauth lib * source: http://abrah.am org * version: v0.1.2 * modified v0.2 */
		switch ($connection->lastHttpCode()) {
			case 200:
			    /* build authorize URL and redirect user to twitter. */
			    /* $url = $connection->getAuthorizeURL($token); * twitter oauth lib * source: http://abrah.am org * version: v0.1.2 * modified v0.2 */
			    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
			    /* header('Location: ' . $url); echo '<script>location.href="'.$url.'";</script>'; exit(); */
			break;
			default:
			    /* show notification if something went wrong. */
			    /* echo 'Could not connect to twitter. Refresh the page or try again later.'; echo json_encode('error_pc4de'); */
			    rtnwebapp('error' , 'error_pc4de' , 'post', '', '');  /* social, no twitter account details found */
			    /* exit(); */
			break;
		}
	    /* redirected to twitter ... awaiting callback ... see callback.php */
		/* echo json_encode($url); */
		rtnwebapp('_uri' , $url , 'post', '', '');
	}


	function trespass($ecode, $pcode, $mysqli) {

		global $timelocal;

		$result = 'false';

		$gotya = 0;  $un_ip = 0;  $un_platform = 0; $un_browser = 0;  $un_timezone = 0;  $db_locked = 0;

		if (valid_email($ecode)) {


			if ($s_stmt11 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE email_current = ? LIMIT 1")) {
				$s_stmt11->bind_param('s', $ecode);                      //  bind "$ecode_in" to parameter.
				log_error($timelocal,'bind_param', $s_stmt11, $mysqli); 
				$s_stmt11->execute();                                    //  execute the prepared query.
				log_error($timelocal,'execute', $s_stmt11, $mysqli);
				$s_stmt11->store_result();  
				$s_stmt11->bind_result($db_user_id_sig, $db_uname_sig, $db_email_past_sig, $db_email_sig, $db_ip_address_sig, $db_platform_sig, $db_browser_sig, $db_token_sig, $db_time_sig, $db_timezone_sig, $db_timelocal_sig, $db_locked, $db_visits_sig);      // get variables from result.
				$s_stmt11->fetch();

			    if ($db_locked == 1) { $result = 'locked';}		  
										
			 } else {

				log_error($timelocal,'prepare', $s_stmt11, $mysqli);
				$s_stmt11->close();
										      	 
			 }

			 	if ($db_locked !== 1) {


					/* if ($s_stmt09 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, passcode, salt, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) { */
					if ($s_stmt09 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, email_confirm, passcode, uid, oauth_token, oauth_token_secret, ip_address, platform_user, browser_user, follow_user, time, timezone, timelocal FROM regist_members WHERE email_current = ? LIMIT 1")) {	
						$s_stmt09->bind_param('s', $ecode);                     		  //  bind "$ecode_in" to parameter.
						log_error($timelocal,'bind_param', $s_stmt09, $mysqli); 
						$s_stmt09->execute();                                   		  //  execute the prepared query.
						log_error($timelocal,'execute', $s_stmt09, $mysqli);
						$s_stmt09->store_result();  
						$s_stmt09->bind_result($db_user_id_reg, $db_uname_reg, $db_email_past_reg, $db_ecode_reg, $db_email_confirm_reg, $db_pcode_reg, $db_uid_reg, $db_oauth_token_reg, $db_oauth_token_secret_reg, $db_ip_address_reg, $db_platform_reg, $db_browser_reg, $db_follow_reg, $db_time_reg, $db_timezone_reg, $db_timelocal_reg);   // get variables from result.
						$s_stmt09->fetch();
											
					} else {

						log_error($timelocal,'prepare', $s_stmt09, $mysqli);
						$s_stmt09->close();
											      	 
					}

					switch (true) {

						case ($s_stmt09->num_rows > 0):
							  $s_stmt09->close();

						 	if ($s_stmt07 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE email_current = ? LIMIT 1")) {
								$s_stmt07->bind_param('s', $ecode);                      //  bind "$ecode_in" to parameter.
							    log_error($timelocal,'bind_param', $s_stmt07, $mysqli); 
							    $s_stmt07->execute();                                    //  execute the prepared query.
							    log_error($timelocal,'execute', $s_stmt07, $mysqli);
								$s_stmt07->store_result();  
								$s_stmt07->bind_result($db_user_id_sig, $db_uname_sig, $db_email_past_sig, $db_email_sig, $db_ip_address_sig, $db_platform_sig, $db_browser_sig, $db_token_sig, $db_time_sig, $db_timezone_sig, $db_timelocal_sig, $db_locked, $db_visits_sig);      // get variables from result.
								$s_stmt07->fetch();

								$gotya = 1;
													
							} else {

								log_error($timelocal,'prepare', $s_stmt07, $mysqli);
								$s_stmt07->close();
													      	 
							}

						break;

						case ($s_stmt09->num_rows == 0):
							  $s_stmt09->close();

							  $result = 'false';

					    break;

					}

					switch ($gotya == 1) {												 // comparison testing starts, set flags for $un_platform, $un_browser, un_ip, un_timezone
							
						case ($db_ip_address_reg !== $db_ip_address_sig):
							  $un_ip = $un_ip + 1;				  
						case ($db_platform_reg !== $db_platform_sig):
							  $un_platform = $un_platform + 1;				  
						case ($db_browser_reg !== $db_browser_sig):
							  $un_browser = $un_browser + 1;
						case ($db_timezone_reg !== $db_timezone_sig):
							  $un_timezone = 1;
							
						break;

					}

					switch (true) {

						case ($s_stmt07->num_rows > 0):
							  $s_stmt07->close();

						    if ($s_stmt10 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timelocal, ip_unequal, platform_unequal, browser_unequal, timezone_unequal, password_bad, attempts, eraser FROM signin_attempts WHERE email_current = ? LIMIT 1")) {
								$s_stmt10->bind_param('s', $ecode);                      //  bind "$ecode_in" to parameter.
								log_error($timelocal,'bind_param', $s_stmt10, $mysqli); 
								$s_stmt10->execute();                                    //  execute the prepared query.
								log_error($timelocal,'execute', $s_stmt10, $mysqli);
								$s_stmt10->store_result();  
								$s_stmt10->bind_result($db_user_id_sig, $db_uname_sig, $db_email_past_sig, $ecode, $db_ip_address_sig, $db_platform_sig, $db_browser_sig, $db_time_sig, $db_timezone_sig, $db_timelocal_sig, $un_ip, $un_platform, $un_browser, $un_timezone, $pcode, $db_attempts, $db_eraser);      // get variables from result.
								$s_stmt10->fetch();
														
							} else {

								log_error($timelocal,'prepare', $s_stmt10, $mysqli);
								$s_stmt10->close();
														      	 
							}

						    switch (true) {

								case ($s_stmt10->num_rows > 0):
								  	  $s_stmt10->close();
			 							   
			 						  switch (true){

			 						 	  case ($db_attempts < 3):
								  			      $db_eraser = 1;
										  case ($db_attempts > 3):
								  			      $db_eraser = 0;
								  		  case ($db_attempts > 4):
								  			      $db_locked = 1;
								  			      $result = 'locked';
								  		  break;
								  	  }

								  	  if ($i_stmt09 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
								 	  	  $i_stmt09->bind_param('issssssssssss', $db_user_id_sig, $db_uname_sig, $db_email_past_sig, $db_email_sig, $db_ip_address_sig, $db_platform_sig, $db_browser_sig, $db_token_sig, $db_time_sig, $db_timezone_sig, $db_timelocal_sig, $db_locked, $db_visits_sig);
									  	  log_error($timelocal,'bind_param', $i_stmt09, $mysqli); 
									  	  $i_stmt09->execute();                                    //  execute the prepared query.
									  	  log_error($timelocal,'execute', $i_stmt09, $mysqli);
									  	  // $i_stmt09->store_result();  
									  	  // $i_stmt09->bind_result($db_user_id_sig, $db_uname_sig, $db_email_sig, $db_ip_address_sig, $db_platform_sig, $db_browser_sig, $db_time_sig, $db_timezone_sig, $db_timelocal_sig, $db_lock, $db_visits_sig);      // get variables from result.
									  	  // $i_stmt09->fetch();
									  	  $i_stmt09->close();
												  		  	
								  	  } else {

								 	  	  log_error($timelocal,'prepare', $i_stmt09, $mysqli);
									  	  $i_stmt09->close();
													      	 
								  	  }

									  $db_attempts = $db_attempts + 1;

									  if ($i_stmt08 = $mysqli->prepare("REPLACE INTO signin_attempts (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timelocal, unequal_ip, platform_unequal, browser_unequal, timezone_unequal, password_bad, attempts, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
										  $i_stmt08->bind_param('issssssssssssssss', $db_user_id_sig, $db_uname_sig, $db_email_past_sig, $ecode, $db_ip_address_sig, $db_platform_sig, $db_browser_sig, $db_time_sig, $db_timezone_sig, $db_timelocal_sig, $un_ip, $un_platform, $un_browser, $un_timezone, $pcode, $db_attempts, $db_eraser);
										  log_error($timelocal,'bind_param', $i_stmt08, $mysqli); 
									      // execute the prepared query.
										  $i_stmt08->execute();
										  log_error($timelocal,'execute', $i_stmt08, $mysqli);
										  // $i_stmt04->store_result();
										  $i_stmt08->close();

									  } else {

										  log_error($timelocal,'prepare', $i_stmt08, $mysqli);
										  $i_stmt08->close();
									  }

								          $result = 'true';
								break;

								case ($s_stmt10->num_rows == 0):
								      $s_stmt10->close();

								  	  $result = 'false';

							    break;

							}

							break;

							case ($s_stmt07->num_rows == 0):
								  $s_stmt07->close();

								  $result = 'false';

							break;

						}

						
					}

				return $result;		

			}
	
	}


	function visitation($ecode, $mysqli) {

		$db_locked = 0;
		// add trespass detection, if account locked, correct password has to be entered twice
		
		global $timelocal;
		
		$result = 'false';
		// initialize variable 

		if (valid_email($ecode)) {

	 	    if ($s_stmt06 = $mysqli->prepare("SELECT user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits FROM signin_members WHERE email_current = ? LIMIT 1")) {
				$s_stmt06->bind_param('s', $ecode);                     //  bind "$ecode_in" to parameter.
				log_error($timelocal,'bind_param', $s_stmt06, $mysqli); 
				$s_stmt06->execute();                                   //  execute the prepared query.
				log_error($timelocal,'execute', $s_stmt06, $mysqli);
				$s_stmt06->store_result();  
				$s_stmt06->bind_result($db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_token_user, $db_time, $db_timezone, $db_timelocal, $db_locked, $db_visits);      // get variables from result.
				$s_stmt06->fetch();
								
			} else {

				log_error($timelocal,'prepare', $s_stmt06, $mysqli);
				$s_stmt06->close();
								      	 
			}	

			switch (true) {

				case ($s_stmt06->num_rows > 0 && $db_locked !== 1):

					  date_default_timezone_set("UTC");
					  // $now = time();
					  $date = new DateTime(null, new DateTimeZone($db_timezone));
	   				  $db_timelocal = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));

					  $db_visits = $db_visits + 1;
 
					  if ($i_stmt06 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
						  $i_stmt06->bind_param('issssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_token_user, $db_time, $db_timezone, $db_timelocal, $db_locked, $db_visits);
						  log_error($timelocal,'bind_param', $i_stmt06, $mysqli); 
				          // execute the prepared query.
						  $i_stmt06->execute();
						  log_error($timelocal,'execute', $i_stmt06, $mysqli);
						  // $i_stmt04->store_result();
						  $i_stmt06->close();

					  } else {

						  log_error($timelocal,'prepare', $i_stmt06, $mysqli);
						  $i_stmt06->close();
					  }

					  $result = 'true';

				break;

				case ($db_locked == 1):

					  $db_locked = 0;
 
					  if ($i_stmt07 = $mysqli->prepare("REPLACE INTO signin_members (user_id, uname, email_past, email_current, ip_address, platform_user, browser_user, token_user, time, timezone, timelocal, locked, visits) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
						  $i_stmt07->bind_param('issssssssssss', $db_user_id, $db_uname, $db_email_past, $db_email_current, $db_ip_address, $db_platform_user, $db_browser_user, $db_token_user, $db_time, $db_timezone, $db_timelocal, $db_locked, $db_visits);
						  log_error($timelocal,'bind_param', $i_stmt07, $mysqli); 
				          // execute the prepared query.
						  $i_stmt07->execute();
						  log_error($timelocal,'execute', $i_stmt07, $mysqli);
						  // $i_stmt04->store_result();
						  $i_stmt07->close();

					  } else {

						  log_error($timelocal,'prepare', $i_stmt07, $mysqli);
						  $i_stmt07->close();
					  }

					  $result = 'locked';


				break;

				case ($s_stmt06->num_rows == 0):

					  $result = 'false';

				break;

			}

		}

		return $result;

	}


	function signin_token($ecode, $pltfrm, $browsr, $timezo, $mysqli) {

		/* signin_token($ecode, $post_in[4], $post_in[5], $post_in[6], $mysqli); */

		  /*

			 $ecode === email_current   

		     field values:

		     tokensend =  $token_send[1], tokenstore = $token_store[1], email = $ecode, timeissued = $token_send[2], eraser = $eraser, used  = $used

		  */

		global $timelocal;

		$used = 0;
		$eraser = 0;

		/*
		$token_send   = getrandomstr(120, dechex(time()),'t');
		$token_store  = getrandomstr(120, $ecode,'e');
	    */
		
		$token_send   = getrandomstr(dechex(time()),'t');
		$token_store  = getrandomstr($ecode,'e');

		$ip_address = $_SERVER['REMOTE_ADDR'];
	    /* $ip_address = mysql_real_escape_string($ip_address); */
        $ip_address = $mysqli->real_escape_string($ip_address);

		$now = time();

			 $s_stmt04 = $mysqli->prepare("SELECT email_past, email_current FROM regist_members WHERE email_current = ? LIMIT 1");
			 $s_stmt04->bind_param('s', $ecode);    					        //  bind "$ecode" to parameter.
			 $s_stmt04->execute(); 				                            //  execute the prepared query.
			 $s_stmt04->store_result();  
			 $s_stmt04->bind_result($db_email_past, $db_email_current);      // get variables from result.
			 $s_stmt04->fetch();
			 $s_stmt04->close();

		if  ($i_stmt02 = $mysqli->prepare("INSERT INTO signin_token (tokensend, tokenstore, email_past, email_current, ip_address, platform_user, browser_user, time, timezone, timeissued, used, eraser) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
      		 $i_stmt02->bind_param('ssssssssssss', $token_send[1], $token_store[1], $db_email_past, $ecode, $ip_address, $pltfrm, $browsr, $now, $timezo, $token_send[2], $used, $eraser);
			 log_error($timelocal,'bind_param', $i_stmt02, $mysqli); 
			 // Execute the prepared query.
             $i_stmt02->execute();
             log_error($timelocal,'execute', $i_stmt02, $mysqli); 
        } else {
             // store token failed in db return false; unset session, remove cookies, log any prepare statement errors
             // secure_session_destroy();
             log_error($timelocal,'prepare', $i_stmt02, $mysqli);
             // echo json_encode('token_store_failed');
        }

		// if $return != '', return unknown error code (not implemented)

		if ($i_stmt02) {
			
		/*  mailresetlink($ecode, $token_send); */	                                              // all good, signin token created
			mailresetlink($ecode, 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr', $token_send);  // all good, signin token created

		} else {
		    // db error, unknown at this point, check log (send notification back to user?)
		}

		//   $i_stm01->store_result();
             $i_stmt02->close();
				
	}

	function is_blank($value) {
		trim($value);
    	return empty($value) && !is_numeric($value);
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


	function valid_username($username) {
    	return preg_match('/^[A-Za-z0-9_]{1,15}$/', $username);
	}
 
	function arrayfilter($var){
  		return ($var !== NULL && $var !== FALSE && $var !== '');
	}

	function isvalidtimestamp($timestamp) {
    	return ((string) (int) $timestamp === $timestamp) 
    	&& ($timestamp <= PHP_INT_MAX)
    	&& ($timestamp >= ~PHP_INT_MAX);
	}

	function valid_password($pwd) {
		$isValid = true;
		switch (true) {

			case (strlen($pwd) < 8): 			      // password too short
				  $isValid = false;
			break;

			case (strlen($pwd) > 20): 			      // password too long
				  $isValid = false;
			break;

			case (!preg_match("#[0-9]+#", $pwd)):     // Password must include at least one number!
				  $isValid = false;
			break;

			case (!preg_match("#[a-zA-Z]+#", $pwd)):  // Password must include at least one letter!
			      $isValid = false;
			break;
   
		}
		return $isValid;
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

    /*
    function mailresetlink($to, $token_send) {
		$subject = "Choose a new password for crowdcc";
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		$message = '
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Choose a new password for crowdcc</title>
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
		<p>Someone requested a new password for your crowdcc account
		<p>
		You can reset your
		<a href="'. $uri .'/verify?up='. $token_send[1] .'" style="color:#3d623b">password here</a>
		.
		</p>
		<p style="padding-bottom:10px;">If you didn\'t make this request then you can ignore this email.</p>
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">
		<span>Have a question or just want to say hello? <a href="https://twitter.com/crowdccHQ" style="color:#2F5BB7;">tweet us</a></span>
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
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: hello@crowdcc.com <hello@crowdcc.com>' . "\r\n";
		if(mail($to,$subject,$message,$headers)){
			// email pass (found in db, pass to token function)
			// social, no twitter account details found 
			// echo "We have sent the password reset link to your email id <b>".$to."</b>";
			rtnwebapp('error' , 'error_pass_ecode' , 'post', '', '');  
			 									                       
		} else {
		    // email pass (found in db, pass to token function), but have failed to be able to send it to
		    // to the email address provided, please try again !
		    // social, no twitter account details found
			rtnwebapp('error' , 'error_fail_ecode' , 'post', '', '');  
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
		$mail->setFrom('hello@crowdcc.com', 'hello@crowdcc.com');

		/* Set an alternative reply-to address */
		$mail->addReplyTo('noreply@crowdcc.com', 'no reply');

		/* Set who the message is to be sent to */
		// $mail->addAddress($to, $fullname);
		$mail->addAddress($to);

		/* Set the subject line */
		$mail->Subject = 'Choose a new password for crowdcc';

		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		
		$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Choose a new password for crowdcc</title>
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
		<p>Someone requested a new password for your crowdcc account
		<p>
		You can reset your
		<a href="'. $uri .'/verify?up='. $token_send[1] .'" style="color:#3d623b">password here</a>
		.
		</p>
		<p style="padding-bottom:10px;">If you didn\'t make this request then you can ignore this email.</p>		
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">
		<span>Have a question or just want to say hello? <a href="https://twitter.com/crowdccHQ" style="color:#2F5BB7;">tweet us</a></span>
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
			
			rtnwebapp('error' , 'error_fail_ecode' , 'post', '', '');

			/* email pass (found in db, pass to token function), but have failed to be able to send it to */
            /* to the email address provided, please try again ! */
            /* social, no twitter account details found */
			 									                                
		} else {

			/* log_found('mail check', ' mail sent' , 'errorhandle', __LINE__ ); */
												               	       
			rtnwebapp('error' , 'error_pass_ecode' , 'post', '', '');

			/* email pass (found in db, pass to token function) */                        
			/* social, no twitter account details found */
			/* echo "We have sent the password reset link to your email id <b>".$to."</b>"; */
		}
	}

?>
