<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* cu.php  * crowdcc API actions
*
* request :: retweet * favorite * reply * email * create
*
* session check, public / private key check = > 
*
* post in :: screen_name, request, { tweet content }
*
*
*/

/* load crowdcc app.error ( error handle ) && app.functions ( general app functions ) */
require_once('ccpath.php');

/* load crowdcc err handle */
//require_once('db/errorhandle.php');

/* _ccu['ccu'] = '_ccu' + '=' + base64.encode( _crt['flg'] ) + ':' + base64.encode( _crt['usr'] ) ); */

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

$post_in = $_POST;
$post_in = explode(":", implode($post_in));

$post_in[0] = base64_decode($post_in[0]);    /*  flag         ::  _cu,  */

switch ( $post_in[0] ) {

	case ( '_cw' ):
    /* same (or duplicate) records, display most frequent tweets with crowdcc  */
    
        switch (true) {

          case ( base64_decode($post_in[1]) !== filter_var( base64_decode($post_in[1]) , FILTER_SANITIZE_STRING ) ):
          /*  screen_name error */
                rtnwebapp('error_obj' , 'tweet-owner-error' , 'post');
                exit();
          break;

        }

        $post_in[1] = base64_decode($post_in[1]);    /*  usr               ::  screen_name */
        
        
         /* $data = @file_get_contents("http://localhost/~macbook/storefront/api.php?token=123456&method=get&format=json&screen_name=". $post_in[1] ."&count=30&same=1"); */
         $data = @file_get_contents_curl("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=123456&method=get&format=json&screen_name=". $post_in[1] ."&count=30&same=1");

	
		if ( $data !== FALSE ) {
		     rtnwebapp('cw' , $data , 'post');
		} else {
		   	 rtnwebapp('error_obj' , 'ccstore-error' , 'post');
		}
    
	break;

	case ( '_cp' ):
    /* most (or duplicate) records, display users who have stored the most data with crowdcc  */

        switch (true) {

          case ( base64_decode($post_in[1]) !== filter_var( base64_decode($post_in[1]) , FILTER_SANITIZE_STRING ) ):
          /*  screen_name error */
                rtnwebapp('error_obj' , 'tweet-owner-error' , 'post');
                exit();
          break;

        }

        $post_in[1] = base64_decode($post_in[1]);    /*  usr               ::  screen_name */
        
        /* $data = @file_get_contents("http://localhost/~macbook/storefront/api.php?token=123456&method=get&format=json&screen_name=". $post_in[1] ."&count=30&most=1"); */
        $data = @file_get_contents_curl("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=123456&method=get&format=json&screen_name=". $post_in[1] ."&count=30&most=1");


    /* test $data = @file_get_contents("http://localhost/~macbook/storefront/api.php?token=123456&method=get&format=json&screen_name=glynthom&count=30"); */

		if ( $data !== FALSE ) {
		     rtnwebapp('cp' , $data , 'post');
		} else {
		   	 rtnwebapp('error_obj' , 'ccstore-error' , 'post');
		}
    
	break;

}


function rtnwebapp( $flag, $token, $whofor ) {

   /*  function is passed the following ;
   *
   *  $flag    -> status or error
   *  $token   -> token data || status data || error data
   *  $whofor  -> post || get
   *
   */

  /* if ($flag === 'co') { echo json_encode( $token ); } else { echo json_encode( $flag . ':*:' . $token );} */

  /*  based on twitter API error code * messages ;
   *  
   *  403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]}
   *  503: {"errors":[{"message":"Over capacity","code":130}]}
   *  500: {"errors":[{"message":"Internal error","code":131}]}
   *  429: {'errors':[{'message':'Rate limit exceeded','code':88}]}
   *
   */

  if (!headers_sent()) { @header ("content-type: text/json charset=utf-8"); }

	switch ($whofor) {
     
		case ('post'):

      switch (true) {

        case ($flag === 'cw'):
        case ($flag === 'cp'):
              echo $token;
              exit();
        break;

        case ($flag === 'on'):
              echo json_encode( $flag . ':*:' . $token );
              exit();
        break;

              /* _cu validation errors including below */
        case ($token === 'ccstore-error'):
              /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;
			
      }

	break;
	
  }

}


?>