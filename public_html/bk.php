<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* bk navigate backwards throught twitter API
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

/* db config files */
//require_once('db/db_config.php');

/* twitter oauth lib * source: http://abrah.am org * version: v0.1.2 * modified v0.2 */
/* require_once('oauth/config.php'); */
/* require_once('oauth/twitteroauth.php'); */


/* twitter oauth lib * source: https://twitteroauth.com * version: v0.4.1 * modified v0.1 */
require_once('tweetpath.php');
use crowdcc\TwitterOAuth\TwitterOAuth;

 
if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post'); exit(); }

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case ('PUT'):
        /* rest_put($request); */  
  break;
  case ('POST'):

       if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post', ''); exit(); }

       $post_in = $_POST;
       $post_in = explode(":", implode($post_in));

       $post_in[0] = base64_decode($post_in[0]);   /* _cb :: flag */
       $post_in[1] = base64_decode($post_in[1]);   /* 80 numer of tweets to harvest :: 80 (current default, optimised at 120) */
       $post_in[2] = decrypt($post_in[2]);         /* screen name :: glynthom */
       $post_in[3] = base64_decode($post_in[3]);   /* :: max id :: 502486030151602176 */

       $post_in[4] = base64_decode($post_in[4]);   /* auth_token ($accesstoken) :: 6f2cc738bb32f81e5bdd41c0fe84a3d2767b866712eb867029.. */
       $post_cauth = $_COOKIE['cauth_token'];      /* auth_token_secret ($accesstokensecret) :: 1651ab7fcf649b6f06b379ade693b9cd7460847845bfeb7405.. */

       /* sanitize all data in check before final data presentation to API */

       switch (true) {

         case ( $post_in[0] !== filter_var( $post_in[0] , FILTER_SANITIZE_STRING) ):
               /* $post_in[0] === _cb :: flag */
               rtnwebapp('error_obj' , 'cfw-num-error' , 'post');
               exit();
         break;
         case( $post_in[1] !== filter_var( $post_in[1] , FILTER_SANITIZE_STRING) ):
               /* $post_in[1] === 80  :: numer of tweets to harvest */
               rtnwebapp('error_obj' , 'cfw-num-error' , 'post');
               exit();
         break;
         case( $post_in[2] !== filter_var( $post_in[2] , FILTER_SANITIZE_STRING) ):
               /* screen name fail */
               rtnwebapp('error_obj' , 'cfw-name-error' , 'post');
               exit();
         break;
         case( $post_in[3] !== filter_var( $post_in[3] , FILTER_SANITIZE_STRING) ):
               /* 502486030151602176  :: max id */
               rtnwebapp('error_obj' , 'cfw-name-error' , 'post');
               exit();
         break;
         case( $post_in[4] !== filter_var( $post_in[4] , FILTER_SANITIZE_STRING) ):
               /* $accesstokensecret fail */
               rtnwebapp('error_obj' , 'cfw-name-error' , 'post');
               exit();
         break;
         case( $post_cauth !== filter_var( $post_cauth , FILTER_SANITIZE_STRING) ):
               /* auth_token_secret fail */
               rtnwebapp('error_obj' , 'cfw-name-error' , 'post');
               exit();
         break;

       }

       /* decrypt sanitized data before final data presentation to API */

       /* $post_in[1] numer of tweets to harvest   */
       /* $post_in[2] screen name :: glynthom      */
       /* $post_in[3] max id :: 502486030151602176 */
       $post_in[4] = ccrypt( $post_in[4], 'AES-256-OFB', 'de' );  /* un-encrypt auth_token ($accesstoken) using key */
       $post_cauth = ccrypt( $post_cauth, 'AES-256-OFB', 'de' );  /* un-encrypt auth_token_secret ($accesstokensecret) using key */

       /*
       print_r( $post_in[0] );
       print_r('|');
       print_r( $post_in[1] );
       print_r('|');
       print_r( $post_in[2] );
       print_r('|');
       print_r( $post_in[3] );
       print_r('|');
       print_r( $post_in[4] );
       print_r('|');
       print_r( $post_cauth );
       print_r('|');
       print_r('thats all she wrote');
       exit();
       */

  break;

}

/*

 $twitteruser = "glynfoo";
 $num_tw = 80;
 $num_tw = 1;

*/

/*
$consumerkey = "7xj9EAJAoLtCbEUlI85JA";
$consumersecret = "BY9WTuQy0sGISrK8yY2EAm1mvdaBPcdoqFb6jF3cA";
$accesstoken = "295131454-6BOZWhDOAS63gXSAyN6HW6Jr1xTbc9dqfdvuNemQ";
$accesstokensecret = "7HBddc8WsBJajM7mER5Rp8fe40i1274E5D571911FdQ";
*/

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}
  
/* $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret); */

$connection = getConnectionWithAccessToken(CONSUMER_KEY, CONSUMER_SECRET, $post_in[4], $post_cauth);


/* $tweets = $connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?screen_name=".$twitteruser."&max_id=".$max_id."&count=".$num_tw); */
/* $tweets = $connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?screen_name=". $post_in[2] ."&max_id=". $post_in[3] ."&count=". $post_in[1] ); */
/* $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&max_id=".$max_id."&count=".$num_tw); // works ;-)) */
/* $tweets = $connection->get("https://api.twitter.com/1.1/statuses/show.json?id=420604183289802752"); // tests */
/* echo json_encode($num_tw + '|' + $max_id); */
/* echo json_encode($tweets); */


/* twitter api: */

/* $tweets = $connection->get("https://api.twitter.com/1.1/statuses/home_timeline.json?screen_name=". $post_in[2] ."&max_id=". $post_in[3] ."&count=". $post_in[1] ); */

$tweets = $connection->get("statuses/home_timeline", array("screen_name" => ''. $post_in[2] .'', "max_id=" => ''. $post_in[3] .'', "count" => ''. $post_in[1] .''));



rtnwebapp('cbb' , $tweets , 'post');

/* error message test :: rtnwebapp('error_obj' , 'cbb-name-error' , 'post'); */

// echo json_encode($notweets);
// $oDB = new db;
//	$field_values = 'tweet_id = ' . $tweet_id . ', ' .
//					'tweet_text = "' . $tweet_text . '", ' .
//					'created_at = "' . $created_at . '", ' .
//					'source = "' . $source . '", ' .
//					'source_url = "' . $source_url . '", ' .
//					'geocode = "' . $geocode . '", ' .
//					'location = "' . $location . '", ' .
//					'from_user_id = ' . $from_user_id . ', ' .				
//					'from_user = "' . $from_user . '", ' .
//					'from_user_name = "' . $from_user_name . '", ' .
//					'profile_image_url = "' . $profile_image_url . '", ' .
//					'search = "' . $search . '", ' .
//					'hash = "' . $hash . '", ' .
//					'geo_search = "' . $geo_search . '"';
//	$oDB->insert('tweets',$field_values);
// print_r($tweets);



function rtnwebapp( $flag, $token, $whofor ) {

  /*  function is passed the following ;
   *
   *  $flag    -> status or error
   *  $token   -> token data || status data || error data
   *  $whofor  -> post || get
   *
   */

  /* if ($flag === 'cbb') { echo json_encode( $token ); } else { echo json_encode( $flag . ':*:' . $token );} */

  /*  based on twitter API error code * messages ;
   *  
   *  403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]}
   *  503: {"errors":[{"message":"Over capacity","code":130}]}
   *  500: {"errors":[{"message":"Internal error","code":131}]}
   *
   */

  if (!headers_sent()) { @header ("content-type: text/json charset=utf-8"); }

  switch ($whofor) {
     
    case ('post'):
       
      switch (true) {

        case ($flag === 'cbb'):
              echo json_encode( $token );
              exit();
        break;

        case ($token === 'cbb-num-error'):
        case ($token === 'cbb-id-error'):
        case ($token === 'cbb-name-error'):
              /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;

      }

    break;
  }

}

?>