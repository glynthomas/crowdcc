
<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* access token analytics management
*
* ccc auth token check:
* 
* - return count limit * (token uname optional - used for cross check)
* - return ccstore space that has been used
* - update api db with ccstore limit (count)
*
* use this at start of session in order to set-up the;
*
* - token limit * count
* - how much ccstore has been used  
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

/* db app api connection config file */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.api.conn.php');

/* access to api crowdcc api db */ 
//require_once('db/db_config_api.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

 case ('POST'):

      if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post', ''); exit(); }

      $post_in = $_POST;
      $post_in = explode(":", implode($post_in));

      /* check data */  
      /* print_r( base64_decode($post_in[0]) ); //  _flag   
         print_r('|');
         print_r( decrypt($post_in[1]) ); //  glynthom :: screen name  
         print_r('|');
         exit(); 
      */

      /* sanitize data */

      switch (true) {

         case ( $post_in[0] !== filter_var( $post_in[0] , FILTER_SANITIZE_STRING) ):
               /* $post_in[0] === _cau :: flag */
               rtnwebapp('error_obj' , 'cau-data-error-1' , 'post');
               exit();
         break;
         case( $post_in[1] !== filter_var( $post_in[1] , FILTER_SANITIZE_STRING) ):
               /* $post_in[1] === 80  :: numer of tweets to harvest */
               rtnwebapp('error_obj' , 'cau-data-error-2' , 'post');
               exit();
         break;

      }

      /* check data */

      try{
      
        $post_in[0] = base64_decode($post_in[0]);   /* _cau :: flag */
        $post_in[1] = decrypt($post_in[1]);         /* uname */

        rtnwebapp('cau', api_token_check( $post_in[1], $mysqli_api ) , 'post');
      
      } catch (Exception $e) {
        rtnwebapp( 'error_obj', 'cau-data-error-3', 'post');  
      }
   
 break;

}


function api_token_check( $uname , $mysqli_api ) {

  global $timelocal;

  /* ccc auth api key token check : stored in db : aee6abce45d62a3ad4-297d9d64-45d-3a798b67-2a3-6825-dcc0f5a54a */
  /* join_api_token() * juggle_api_token() * currently * withdrawn */
  /* $api_token_data = explode(',', check_api_token( (join_api_token( $_COOKIE['ccid'])) )); */

  $api_token_data = explode(',', check_api_token( $_COOKIE['ccid'] ) ); 
   
  /* uname : $api_token_data[0] | count : $api_token_data[1] */

  /* check data */
  /* 
  print_r( $uname ); // uname   
  print_r('|');
  print_r( $api_token_data[0] ); // uname
  print_r('|');
  exit(); 
  */
  
  switch (true) {

   case ($uname !== $api_token_data[0]):
         return 'cau-data-error-4';
   break;

  }

  /* if ($s_stmt01 = $mysqli_api->prepare("SELECT tweet_owner FROM tweets WHERE tweet_owner = ? ORDER BY tweet_owner")) { */
  /* may need to use order by on a primary key * indexed * for accurate num_rows count */
   if ($s_stmt01 = $mysqli_api->prepare("SELECT tweet_owner FROM tweets WHERE tweet_owner = ?")) { 
      $s_stmt01->bind_param('s', $api_token_data[0]);
      log_error_api($timelocal,'bind_param', $s_stmt01, $mysqli_api); 
      /* execute the prepared query. */
      $s_stmt01->execute();
      log_error_api($timelocal,'execute', $s_stmt01, $mysqli_api);
      $s_stmt01->store_result();
      $rows_affect = $s_stmt01->num_rows;

      $s_stmt01->close();

   } else {

      log_error_api($timelocal,'prepare', $s_stmt01, $mysqli_api);
      $s_stmt01->close();
      $rows_affect = 3; /* poisoned rows affect === 3 to indicate error condition */

   }

  if ($s_stmt02 = $mysqli_api->prepare("SELECT user_id, uname, ccc_store, ccc_limit, api_key, api_hit, api_hit_date FROM members WHERE uname = ?")) {
      $s_stmt02->bind_param('s', $api_token_data[0]);
      log_error_api($timelocal,'bind_param', $s_stmt02, $mysqli_api); 
      /* execute the prepared query. */
      $s_stmt02->execute();
      $s_stmt02->store_result();  
      $s_stmt02->bind_result($db_user_id, $db_uname, $db_ccc_store, $db_ccc_limit, $db_api_key, $db_api_hit, $db_api_hit_date);      // get variables from result.
      $s_stmt02->fetch();
      // $s_stmt02->close();

  } else {

      log_error_api($timelocal,'prepare', $s_stmt02, $mysqli_api);
      $s_stmt02->close();

  }

  /* ccc store update */
  $ccc_store = $rows_affect;
  
  /* api hit date */
  date_default_timezone_set("UTC");
  /* $api_hit_date = now() */
  $api_hit_date = $timelocal;

  /* increment api hit */
  $api_hit = $db_api_hit + 1;


  if  ($i_stmt03 = $mysqli_api->prepare("REPLACE INTO members (user_id, uname, ccc_store, ccc_limit, api_key, api_hit, api_hit_date) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
       $i_stmt03->bind_param('issssss', $db_user_id, $db_uname, $ccc_store, $db_ccc_limit, $db_api_key, $api_hit, $api_hit_date);
       log_error_api($timelocal,'bind_param', $i_stmt03, $mysqli_api); 
       /* execute the prepared query. */
       $i_stmt03->execute();
       log_error_api($timelocal,'execute', $i_stmt03, $mysqli_api);
       /* $insert_stmt->store_result(); */
       $i_stmt03->close();
       /* return true; */

  } else {
       
       log_error_api($timelocal,'prepare', $i_stmt03, $mysqli_api);
       $i_stmt03->close();
       rtnwebapp( 'error_obj', 'cau-data-error-5', 'post');
       $s_stmt02->close();

       exit();
  }

       $s_stmt02->close();

   return $api_token_data[0] . ',' . $api_token_data[1] . ',' . $rows_affect;
 
}



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

        case ($flag === 'cau'):
              echo json_encode( $token );
              exit();
        break;

        case ($token === 'cau-data-error'):
              /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;

      }

    break;
  }

}


?>