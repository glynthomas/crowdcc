<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* post-signout for lazy signin
*
* user signs out of app
* clear down all session info.
*
* @file
* Clears PHP sessions and optionally redirects to the signin page.
*
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

/* access to crowdcc api db */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.api.conn.php');

/* access to api crowdcc api db */ 
//require_once('db/db_config_api.php');

if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post'); exit(); }

$method = $_SERVER['REQUEST_METHOD'];
 
switch ($method) {
  case ('PUT'):
        /* rest_put($request);    */  
  break;
  case ('POST'):

    /* if ( empty($_SESSION['time']) ) { $_SESSION['time'] = time(); } */

  	if (!isset($_POST) ) { rtnwebapp('signout' , 'error_tamper' , 'post', '', ''); exit(); }

    $post_in = $_POST;
    $post_in = explode(":", implode($post_in));

    switch (true) {

		case ($post_in[0] === 'farewell'):

			  setcookie("cauth_token","",time()-3600,"/", $_SERVER['SERVER_NAME'], 1);       // delete cauth token cookie
			  setcookie("ccid","",time()-3600,"/", $_SERVER['SERVER_NAME'], 1);              // delete ccid  token cookie
		   /* setcookie("_crowdcc_sess","",time()-3600,"/", $_SERVER['SERVER_NAME'], 1);     // delete _crowdcc_ session cookie */
           /* session cookie not removed here, so that it can be deleted by the server in secure_session_destroy(); */
              secure_session_start('user unknown');
			  /* unset all session values, delete _crowdcc_sess secure server session cookie  */
			  secure_session_destroy();
 		 	  echo 'cookies eaten';
		break;
		
		case ($post_in[0] === 'goodbye'):

	     	  secure_session_start('user unknown');
			  /* unset all session values, delete _crowdcc_sess secure server session cookie  */
			  secure_session_destroy();
		 	  echo 'session eaten';
		break;

		default:
			  // logic to perform if we're being injected. 
			  echo 'That value is incorrect. What are you doing over there?';
		break;
		
	}

}


/* Redirect to page with the connect to Twitter option. */
/* header('Location: ./connect.php'); */


?>
