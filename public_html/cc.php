<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* cc.php  * crowdcc API actions
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

/* db config files */
//require_once('db/db_config_api.php');

/* found log * log failure outside the JS console ( please comment out / remove later ) */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php');

/* found log * working test ( test please remove later ) */
/* log_found('condition1', 'condition2', 'start:fw.php', __LINE__ ); */

if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post'); exit(); }

$post_in = $_POST;
$post_in = explode(":", implode($post_in));

$post_in[0] = base64_decode($post_in[0]);    /*  flag         ::  _cc,  */

switch ( $post_in[0] ) {

	case ( '_co' ):
        /* read tweets */

        switch (true) {

          case ( decrypt($post_in[1]) !== filter_var( decrypt($post_in[1]) , FILTER_SANITIZE_STRING ) ):
          /*  screen_name error */
                rtnwebapp('error_obj' , 'tweet-owner-error' , 'post');
                /* log_found('error_obj', 'tweet-owner-error' , 'start:cc.php', __LINE__ ); */
                exit();
          break;
          case ( base64_decode($post_in[2]) !== filter_var( base64_decode($post_in[2]) , FILTER_SANITIZE_STRING ) ):
          /*  start count error */
                rtnwebapp('error_obj' , 'tweet-start-error' , 'post');
                /* log_found('error_obj', 'tweet-start-error' , 'start:cc.php', __LINE__ ); */
                exit();
          break;
          case ( base64_decode($post_in[3]) !== filter_var( base64_decode($post_in[3]) , FILTER_SANITIZE_STRING ) ):
          /*  count error */
                rtnwebapp('error_obj' , 'tweet-count-error' , 'post');
                /* log_found('error_obj', 'tweet-count-error' , 'start:cc.php', __LINE__ ); */
                exit();
          break;
   
        }

        $post_in[1] = decrypt($post_in[1]);          /*  usr               ::  screen_name */
        $post_in[2] = base64_decode($post_in[2]);    /*  start or count    ::  records start or count, for start zero is begining return */
        $post_in[3] = base64_decode($post_in[3]);    /*  count or null     ::  number of records to return */
   
        $post_ccid  = $_COOKIE['ccid'];              /*  ccid              ::  297d9d64-45d-3a798b67-2a3-dcc0f5a54a-6825-aee6abce45d62a3ad4 : ccid */

        /* crowdcc access token build / check */
        $api_token = join_api_token( $post_ccid );


        /* 
         
        $post_in[0] === co
        $post_in[1] === glynthom 
        $post_in[2] === records start, zero is begining return
        $post_in[3] === forward count records to return

        access api.php (which is read only, no write or delete) 
		    not using the unix timestamp for potential recording client requests	
        
        */

        if ($post_in[2] === '') { $post_in[2] = '0';}

        if ($post_in[3] === '') {
        
            /* $data = @file_get_contents("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=". $api_token ."&method=get&format=json&screen_name=". $post_in[1] ."&count=". $post_in[2]); */
            $data = @file_get_contents_curl("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=". $api_token ."&method=get&format=json&screen_name=". $post_in[1] ."&count=". $post_in[2]);

        } else {

            /* $data = @file_get_contents("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=". $api_token ."&method=get&format=json&screen_name=". $post_in[1] ."&start=". $post_in[2] ."&count=". $post_in[3]); */
            $data = @file_get_contents_curl("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=". $api_token ."&method=get&format=json&screen_name=". $post_in[1] ."&start=". $post_in[2] ."&count=". $post_in[3]);
        }


		    if ( $data !== FALSE ) {
		         rtnwebapp('co' , $data , 'post');
		    } else {
		   	     rtnwebapp('error_obj' , 'ccstore-error' , 'post');
             /* log_found('error_obj', 'ccstore-error' , 'start:cc.php', __LINE__ ); */
		    }
    
	break;

	case ( '_sh' ):
         /* tra_sh tweet */
         $trshrtn = 0;

         switch (true) {

          case( decrypt($post_in[1]) !== filter_var( decrypt($post_in[1]) , FILTER_SANITIZE_STRING ) ):
          /*  screen_name fail */
                rtnwebapp('error_obj' , 'tweet-owner-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[2]) !== filter_var( base64_decode($post_in[2]) , FILTER_SANITIZE_STRING ) ):
          /*  timestamp! fail */
                rtnwebapp('error_obj' , 'tweet-time-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[3]) !== filter_var( base64_decode($post_in[3]) , FILTER_SANITIZE_STRING ) ):
          /*  tweet id fail */
                rtnwebapp('error_obj' , 'tweet-id-error' , 'post');
                exit();
          break;

         }

         $post_in[1] = decrypt($post_in[1]);          /*  usr          ::  screen_name */
         
         $post_in[2] = base64_decode($post_in[2]);    /*  timestamp    ::  unix format */

         $post_in[3] = base64_decode($post_in[3]);    /*  rpl || frm || sh ::  reply tweet field or from: twitter user ( when mail tweet ) or tweet ID  */
         /* tweet ID to be trashed, need to connected to db with user name and lookup tweet ID and delete record :: success / failure  */

          // print_r( $post_in[0] ); //  _flag   
          // print_r('|');
          // print_r( $post_in[1] ); //  glynthom :: screen name  
          // print_r('|');
          // print_r( $post_in[2] ); //  unix time ?
          // print_r('|');
          // print_r( $post_in[3] ); //  twitter ID
          // print_r('|');

  
          /* log_found( $post_in[1],  $post_in[3], 'start:cc.php', __LINE__ ); */


          $trshrtn = trashcc ('tweets', $post_in[1] ,$post_in[3] , $mysqli_api);

          switch (true) {

            case ($trshrtn === 1):
                rtnwebapp( 'sh', 'trash_media_success_200', 'post' );
            break;

          	case ($trshrtn === 0):
          		  rtnwebapp( 'sh', 'trash_media_error_205', 'post' );
          	break;

            case ($trshrtn > 1):
          		  rtnwebapp( 'sh', 'trash_media_error_207', 'post' );
          	break;

          }
         
  break;

  case ( '_on' ):
         /* carb_on tweet */
         $updatrtn = 0;
  
         switch (true) {

          case( base64_decode($post_in[1]) !== filter_var( base64_decode($post_in[1]) , FILTER_SANITIZE_STRING ) ):
          /* tweet_id fail */
                rtnwebapp('error_obj' , 'tweet-id-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[2]) !== filter_var( base64_decode($post_in[2]) , FILTER_SANITIZE_STRING ) ):
          /*  from_user id fail */
                rtnwebapp('error_obj' , 'from-user-id-error' , 'post');
                exit();
          break;
          case( decrypt($post_in[3]) !== filter_var( decrypt($post_in[3]) , FILTER_SANITIZE_STRING ) ):
          /*  tweet owner fail */
                rtnwebapp('error_obj' , 'tweet-owner-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[4]) !== filter_var( base64_decode($post_in[4]) , FILTER_SANITIZE_STRING ) ):
          /*  tweet create date fail */
                rtnwebapp('error_obj' , 'tweet-create-date-error' , 'post');
                exit();
          break;

          /* tweet text :: ($post_in[5]) */

          case( base64_decode($post_in[6]) !== filter_var( base64_decode($post_in[6]) , FILTER_SANITIZE_STRING ) ):
          /*  source fail */
                rtnwebapp('error_obj' , 'source-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[7]) !== filter_var( base64_decode($post_in[7]) , FILTER_SANITIZE_STRING ) ):
          /*  source url fail */
                rtnwebapp('error_obj' , 'source-url-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[8]) !== filter_var( base64_decode($post_in[8]) , FILTER_SANITIZE_STRING ) ):
          /*  retweet count fail */
                rtnwebapp('error_obj' , 'retweet-count-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[9]) !== filter_var( base64_decode($post_in[9]) , FILTER_SANITIZE_STRING ) ):
          /*  favourite count fail */
                rtnwebapp('error_obj' , 'favourite-count-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[10]) !== filter_var( base64_decode($post_in[10]) , FILTER_SANITIZE_STRING ) ):
          /*  from user fail */
                rtnwebapp('error_obj' , 'from-user-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[11]) !== filter_var( base64_decode($post_in[11]) , FILTER_SANITIZE_STRING ) ):
          /*  from user name fail */
                rtnwebapp('error_obj' , 'from-user-name-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[12]) !== filter_var( base64_decode($post_in[12]) , FILTER_SANITIZE_STRING ) ):
          /*  from location fail */
                rtnwebapp('error_obj' , 'from-location-error' , 'post');
                exit();
          break;

          /* from description :: ($post_in[13]) */
    
          case( base64_decode($post_in[14]) !== filter_var( base64_decode($post_in[14]) , FILTER_SANITIZE_STRING) ):
          /*  from url fail */
                rtnwebapp('error_obj' , 'from-url-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[15]) !== filter_var( base64_decode($post_in[15]) , FILTER_SANITIZE_STRING ) ):
          /*  followers count fail */
                rtnwebapp('error_obj' , 'followers-count-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[16]) !== filter_var( base64_decode($post_in[16]) , FILTER_SANITIZE_STRING ) ):
          /*  friends count fail  */
                rtnwebapp('error_obj' , 'friends-count-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[17]) !== filter_var( base64_decode($post_in[17]) , FILTER_SANITIZE_STRING ) ):
          /*  listed count fail */
                rtnwebapp('error_obj' , 'listed-count-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[18]) !== filter_var( base64_decode($post_in[18]) , FILTER_SANITIZE_STRING ) ):
          /*  created at fail */
                rtnwebapp('error_obj' , 'created-at-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[19]) !== filter_var( base64_decode($post_in[19]) , FILTER_SANITIZE_STRING ) ):
          /*  favourites count fail */
                rtnwebapp('error_obj' , 'favourites-count-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[20]) !== filter_var( base64_decode($post_in[20]) , FILTER_SANITIZE_STRING ) ):
          /*  time zone fail  */
                rtnwebapp('error_obj' , 'time-zone-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[21]) !== filter_var( base64_decode($post_in[21]) , FILTER_SANITIZE_STRING ) ):
          /*  status count fail */
                rtnwebapp('error_obj' , 'statuses-count-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[22]) !== filter_var( base64_decode($post_in[22]) , FILTER_SANITIZE_STRING ) ):
          /*  profile image url fail */
                rtnwebapp('error_obj' , 'profile-image-url-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[23]) !== filter_var( base64_decode($post_in[23]) , FILTER_SANITIZE_STRING ) ):
          /*  entities urls fail */
                rtnwebapp('error_obj' , 'entities-urls-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[24]) !== filter_var( base64_decode($post_in[24]) , FILTER_SANITIZE_STRING ) ):
          /*  entities hashtags fail */
                rtnwebapp('error_obj' , 'entities-hashtags-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[25]) !== filter_var( base64_decode($post_in[25]) , FILTER_SANITIZE_STRING ) ):
          /*  entities media_url fail  */
                rtnwebapp('error_obj' , 'entities-media_url-error' , 'post');
                exit();
          break;
          case( base64_decode($post_in[26]) !== filter_var( base64_decode($post_in[26]) , FILTER_SANITIZE_STRING ) ):
          /*  entities url fail */
                rtnwebapp('error_obj' , 'entities-url-error' , 'post');
                exit();
          break;
    

         }

         $post_in[1] = base64_decode($post_in[1]);
         $post_in[2] = base64_decode($post_in[2]);

         $post_in[3] = decrypt($post_in[3]);
         
         $post_in[4] = base64_decode($post_in[4]);

         $post_in[5] = base64_decode($post_in[5]); // tweet text */

         // $post_in[5] = addslashes( htmlspecialchars_decode( base64_decode($post_in[5])) ); /*  tweet_text  */
         
         $post_in[6] = base64_decode($post_in[6]);

         $post_in[7] = addslashes( htmlspecialchars_decode( base64_decode($post_in[7])) ); /*  source_url  */
         
         $post_in[8] = base64_decode($post_in[8]);
         $post_in[9] = base64_decode($post_in[9]);
         $post_in[10] = base64_decode($post_in[10]);
         $post_in[11] = base64_decode($post_in[11]);
         $post_in[12] = base64_decode($post_in[12]);

         $post_in[13] = base64_decode($post_in[13]); // from description */
     
         $post_in[14] = base64_decode($post_in[14]);
         $post_in[15] = base64_decode($post_in[15]);
         $post_in[16] = base64_decode($post_in[16]);
         $post_in[17] = base64_decode($post_in[17]);
         $post_in[18] = base64_decode($post_in[18]);
         $post_in[19] = base64_decode($post_in[19]);
         $post_in[20] = base64_decode($post_in[20]);
         $post_in[21] = base64_decode($post_in[21]);
         $post_in[22] = base64_decode($post_in[22]);
         $post_in[23] = base64_decode($post_in[23]);
         $post_in[24] = base64_decode($post_in[24]);
         $post_in[25] = base64_decode($post_in[25]);
         $post_in[26] = base64_decode($post_in[26]);


         /* start :: for display purpose only ...

         print_r( $post_in[1]); //  tweet_id
         print_r('|');
         print_r( $post_in[2]); //  from_user_id 
         print_r('|');
         print_r( $post_in[3]); //  tweet_owner
         print_r('|');
         print_r( $post_in[4]); //  tweet_create_date
         print_r('|');
         // print_r( htmlspecialchars_decode( $post_in[5] ) );  // tweet_text
         print_r( $post_in[5]); //  tweet_text
         print_r('|');
         print_r( $post_in[6]); //  source 
         print_r('|');
         // print_r( htmlspecialchars_decode( $post_in[7] ) );  //  source_url
         print_r( $post_in[7]); //  source_url
         print_r('|');
         print_r( $post_in[8]); //  retweet_count 
         print_r('|');
         print_r( $post_in[9]); //  favorite_count 
         print_r('|');
         print_r( $post_in[10]); // from_user   
         print_r('|');
         print_r( $post_in[11]); // from_user_name 
         print_r('|');
         print_r( $post_in[12]); // from_location 
         print_r('|');
         print_r( $post_in[13]); // from_description
         print_r('|');
         print_r( $post_in[14]); // from_url  
         print_r('|');
         print_r( $post_in[15]); // followers_count 
         print_r('|');
         print_r( $post_in[16]); // friends_count 
         print_r('|');
         print_r( $post_in[17]); // listed_count  
         print_r('|');
         print_r( $post_in[18]); // created_at 
         print_r('|');
         print_r( $post_in[19]); // favourites_count 
         print_r('|');
         print_r( $post_in[20]); // time_zone
         print_r('|');
         print_r( $post_in[21]); // statuses_count  
         print_r('|');
         print_r( $post_in[22]); // profile_image_url  
         print_r('|');
         print_r( $post_in[23]); // entities_urls 
         print_r('|');
         print_r( $post_in[24]); // entities_hashtags 
         print_r('|');
         print_r( $post_in[25]); // entities_media_url
         print_r('|');
         print_r( $post_in[26]); // entities_url
    
         end :: for display purpose only ... */
  
         $updatrtn = carboncc ( $post_in[1], $post_in[2], $post_in[3], $post_in[4], $post_in[5], $post_in[6], $post_in[7], $post_in[8], $post_in[9], $post_in[10], $post_in[11], $post_in[12], $post_in[13], $post_in[14], $post_in[15], $post_in[16], $post_in[17], $post_in[18], $post_in[19], $post_in[20], $post_in[21], $post_in[22], $post_in[23], $post_in[24], $post_in[25], $post_in[26], $mysqli_api );

          switch (true) {

            case ($updatrtn === 1):
                  rtnwebapp( 'on', 'carbon_media_success_200', 'post' );
            break;

            case ($updatrtn === 2):
                  rtnwebapp( 'on', 'carbon_media_error_205', 'post' );
            break;

            case ($updatrtn === 3):
                  rtnwebapp( 'on', 'carbon_media_error_207', 'post' );
            break;

            case ($updatrtn === 4):
                  rtnwebapp( 'on', 'carbon_media_error_208', 'post' );
            break;

            case ($updatrtn === 5):
                  rtnwebapp( 'on', 'carbon_media_error_209', 'post' );
            break;

            case ($updatrtn === 6):
                  rtnwebapp( 'on', 'carbon_media_error_210', 'post' );
            break;

            case ($updatrtn === 7):
                  rtnwebapp( 'on', 'carbon_media_error_211', 'post' );
            break;

          }
         
  break;


}


function carboncc ( $tweet_id, $from_user_id, $tweet_owner, $tweet_create_date, $tweet_text, $source, $source_url, $retweet_count, $favorite_count, $from_user, $from_user_name, $from_location, $from_description, $from_url, $followers_count, $friends_count, $listed_count, $created_at, $favourites_count, $time_zone, $statuses_count, $profile_image_url, $entities_urls, $entities_hashtags,  $entities_media_url, $entities_url, $mysqli_api ) { 
      /* updatecc ( $post_in[1], $post_in[2], $post_in[3],  $post_in[4],    $post_in[5], $post_in[6], $post_in[7], $post_in[8],     $post_in[9],  $post_in[10],  $post_in[11],   $post_in[12],   $post_in[13],  $post_in[14],   $post_in[15],   $post_in[16],   $post_in[17],   $post_in[18], $post_in[19],   $post_in[20], $post_in[21],     $post_in[22],      $post_in[23],       $post_in[24],        $post_in[25]      $post_in[26]           */

  global $timelocal;

  $i_stmt01 = ''; $s_stmt01 = ''; $ccid_token = ''; $rows_affect = 0; $rows_limit = 0;

  /*  
      let MySQL do the work. MySQL has functions we can use to convert the data at the point where we access the database.
      UNIX_TIMESTAMP will convert from DATETIME to PHP timestamp and FROM_UNIXTIME will convert from PHP timestamp to DATETIME.
      The methods are used within the SQL query. So we insert and update dates using queries like this ;

      $query = "UPDATE table SET
      datetimefield = FROM_UNIXTIME($phpdate)
      WHERE...";
      $query = "SELECT UNIX_TIMESTAMP(datetimefield)
      FROM table WHERE...";
  */

  date_default_timezone_set("UTC"); 
  $now = time();

  /* option to add unix timestamp to tweets, in order to select row with most recent date per user
   * http://stackoverflow.com/questions/17038193/select-row-with-most-recent-date-per-user
   *
   * all date and time columns shall be INT UNSIGNED NOT NULL, and shall store a Unix timestamp in UTC.
   * http://www.xaprb.com/blog/2014/01/30/timestamps-in-mysql/
   *
  /* $unix_time = UNIX_TIMESTAMP(); $tweet_id, $unix_time ... , cannot use most recent highest tweet ID * this is a problem because of duplicate id's */


  /* adding * priority * server side token limit checks */
  if (isset( $_COOKIE['ccid'] ) ) {
      
      $ccid_token = explode(',', check_api_token( $_COOKIE['ccid'] ));

  } else {

      $rows_affect = 4; /* poisoned rows affect === 4 * to indicate error condition * woaw, the ccid token is not set * mucho problamo */
      return $rows_affect;
  }

  /* need to add tweet storage limit check to prevent db from storing more tweets than authorised in ccid token */

  if ($s_stmt02 = $mysqli_api->prepare("SELECT media_id FROM tweets WHERE tweet_owner = ?")) {
      $s_stmt02->bind_param('s', $tweet_owner );
      log_error($timelocal,'bind_param', $s_stmt02, $mysqli_api); 
      /* execute the prepared query. */
      $s_stmt02->execute();
      log_error($timelocal,'execute', $s_stmt02, $mysqli_api);
      $s_stmt02->store_result();
      $rows_limit = $s_stmt02->num_rows; /* ccspace */
      $s_stmt02->close();

  } else {

      log_error($timelocal,'prepare', $s_stmt01, $mysqli_api);
      $s_stmt02->close();
      $rows_affect = 6; /* poisoned rows affect === 6 to indicate error condition */

  }

  /* check * print_r($ccid_token[0] . ',' . $tweet_owner . ',' . $rows_limit . ',' . $ccid_token[1] ); */

  switch (true) {

    case ($ccid_token[0] !== $tweet_owner):
          $rows_affect = 5; /* poisoned rows affect === 5 * to indicate error condition * woaw, the ccid token is not valid * mucho problamo */
          return $rows_affect;
    break;

    case ($rows_limit >= $ccid_token[1]):
          $rows_affect = 7; /* poisoned rows affect === 7 * to indicate the ccid token limit (ccspace) has been reached * mucho problamo */
          return $rows_affect;
    break;

    case ($rows_limit === $ccid_token[1]):
          $rows_affect = 7; /* poisoned rows affect === 7 * to indicate the ccid token limit (ccspace) has been reached * mucho problamo */
          return $rows_affect;
    break;
  
  } 

  /* need to add duplicate tweet id check to prevent SAME user from interting the SAME tweet */

  if ($s_stmt01 = $mysqli_api->prepare("SELECT tweet_id, tweet_owner FROM tweets WHERE tweet_id = ? AND tweet_owner = ? LIMIT 1")) {
      $s_stmt01->bind_param('ss', $tweet_id , $tweet_owner );
      log_error($timelocal,'bind_param', $s_stmt01, $mysqli_api); 
      /* execute the prepared query. */
      $s_stmt01->execute();
      log_error($timelocal,'execute', $s_stmt01, $mysqli_api);
      $s_stmt01->store_result();
      $rows_affect = $s_stmt01->num_rows;
      $s_stmt01->close();

  } else {

      log_error($timelocal,'prepare', $s_stmt01, $mysqli_api);
      $s_stmt01->close();
      $rows_affect = 3; /* poisoned rows affect === 3 to indicate error condition */
  }

  switch (true) {

    case ($rows_affect === 0):

        if ($i_stmt01 = $mysqli_api->prepare("REPLACE INTO tweets (time, tweet_id, from_user_id, tweet_owner, tweet_create_date, tweet_text, source, source_url, retweet_count, favorite_count, from_user, from_user_name, from_location, from_description, from_url, followers_count, friends_count, listed_count, created_at, favourites_count, time_zone, statuses_count, profile_image_url, entities_urls, entities_hashtags, entities_media_url, entities_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            $i_stmt01->bind_param('sssssssssssssssssssssssssss', $now, $tweet_id, $from_user_id, $tweet_owner, $tweet_create_date, $tweet_text, $source, $source_url, $retweet_count, $favorite_count, $from_user, $from_user_name, $from_location, $from_description, $from_url, $followers_count, $friends_count, $listed_count, $created_at, $favourites_count, $time_zone, $statuses_count, $profile_image_url, $entities_urls, $entities_hashtags, $entities_media_url, $entities_url);
            log_error_api($timelocal,'bind_param', $i_stmt01, $mysqli_api); 
            /* execute the prepared query. */
            $i_stmt01->execute();
            log_error_api($timelocal,'execute', $i_stmt01, $mysqli_api);
            $i_stmt01->store_result();
            $rows_affect = $i_stmt01->affected_rows;
            $i_stmt01->close();

        } else {

            log_error_api($timelocal,'prepare', $i_stmt01, $mysqli_api);
            $i_stmt01->close();
            $rows_affect = 3; /* poisoned rows affect === 3 to indicate error condition */
        }

    break;

    case ($rows_affect !== 0):

          $rows_affect = 2; /* poisoned rows affect === 2 to indicate error condition */
    
    break;

    }

  /* 
     return 1, ok : db updated with new record or, 
     return 2, error : db record already exist or,
     return 3, error : db duplicate tweet problem or, 
     return 4, error : no ccid token set or,
     return 5, error : ccid token invalid or,
     return 6, error : db tweet problem or,
     return 7, ok : ccid token limit (ccspace) has been reached
  */

  return $rows_affect;
}


function trashcc ($table, $owner, $id, $mysqli_api) {

	global $timelocal;

    $d_stmt01 = ''; $usr_trash = 0; $rows_affect = 0;

	if ($d_stmt01 = $mysqli_api->prepare("DELETE FROM tweets WHERE tweet_owner = ? AND tweet_id = ? LIMIT 1")) {
    	$d_stmt01->bind_param('ss', $owner, $id );                   //  bind to parameters
    	log_error_api($timelocal,'bind_param', $d_stmt01, $mysqli_api); 
    	$d_stmt01->execute();                                        //  execute the prepared query.
    	log_error_api($timelocal,'execute', $d_stmt01, $mysqli_api);
      $d_stmt01->store_result();      
    	$rows_affect = $d_stmt01->affected_rows;
    	$d_stmt01->close();

	} else {

		  log_error_api($timelocal,'prepare', $d_stmt01, $mysqli);
      $d_stmt01->close();
      $rows_affect = 3; /* poisoned rows affect === 3 to indicate error condition */
	}

    return $rows_affect;

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

        case ($flag === 'co'):
              echo $token;
              exit();
        break;

        case ($token === 'trash_media_success_200'):
              echo json_encode( Array('ok' => Array( Array('message' => 'Trash, media success', 'code' => '200'))) );
              exit();
        break;
                /* _sh trash rtn 2 errors */
        case ($token === 'trash_media_error_205'):
              echo json_encode( Array('ok' => Array( Array('message' => 'Trash, media not found', 'code' => '205'))) );
              exit();
        break;

        case ($token === 'trash_media_error_207'):
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;


        case ($token === 'carbon_media_success_200'):
              echo json_encode( Array('ok' => Array( Array('message' => 'Carbon, media success', 'code' => '200'))) );
              exit();
        break;
              /* _on carbon rtn 2 errors */
        case ($token === 'carbon_media_error_205'):
              echo json_encode( Array('ok' => Array( Array('message' => 'Carbon, media already posted', 'code' => '205'))) );
              exit();
        break;

        case ($token === 'carbon_media_error_207'):
        case ($token === 'carbon_media_error_208'):
        case ($token === 'carbon_media_error_209'):
        case ($token === 'carbon_media_error_210'):
              log_found('$token', $token , 'start:cc.php', __LINE__ );
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;

        case ($token === 'carbon_media_error_211'):
              echo json_encode( Array('ok' => Array( Array('message' => 'Carbon, media limit reached', 'code' => '211'))) );
              exit();
        break;

              /* _co validation 3 errors */ 
        case ($token === 'tweet-owner-error'):
        case ($token === 'tweet-start-error'):
        case ($token === 'tweet-count-error'):

              /* _sh validation 3 errors */
        case ($token === 'tweet-owner-error'): 
        case ($token === 'tweet-time-error'):
        case ($token === 'tweet-id-error'):
              log_found('$token', $token , 'start:cc.php', __LINE__ );
              /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;

              /* _co validation 4 errors including below */
        case ($token === 'ccstore-error'):
              log_found('$token', 'ccstore-error', 'start:cc.php', __LINE__ );
              /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;

              /* _on validation 24 errors */
        case ($token === 'tweet-id-error'):
        case ($token === 'from-user-id-error'):
        case ($token === 'tweet-owner-error'):
        case ($token === 'tweet-create-date-error'):
        case ($token === 'source-error'):
        case ($token === 'source-url-error'):
        case ($token === 'retweet-count-error'):
        case ($token === 'favourite-count-error'):
        case ($token === 'from-user-error'):
        case ($token === 'from-user-name-error'):
        case ($token === 'from-location-error'):
        case ($token === 'from-url-error'):
        case ($token === 'followers-count-error'):
        case ($token === 'friends-count-error'):
        case ($token === 'listed-count-error'):
        case ($token === 'created-at-error'):
        case ($token === 'favourites-count-error'):
        case ($token === 'time-zone-error'):
        case ($token === 'statuses-count-error'):
        case ($token === 'profile-image-url-error'):
        case ($token === 'entities-urls-error'):
        case ($token === 'entities-hashtags-error'):
        case ($token === 'entities-media_url-error'):
        case ($token === 'entities-url-error'):
              /* log_found('$token', $token , 'start:cc.php', __LINE__ ); */
              /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
              echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
              exit();
        break;
			
      }

		break;
	
  }

}





?>