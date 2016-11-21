<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* crowdcc: API v1.0
* this script provides a RESTful API interface for a web application
* input:
*
* $_GET['format'] = [ json | html ]
* $_GET['method'] = []
* 
* output:  a formatted HTTP response
*
*/

/* load crowdcc app.error ( error handle ) && app.functions ( general app functions ) */
require_once('ccpath.php');

/* load crowdcc err handle */
// require_once('db/errorhandle.php');

/* load required lib files. */
// include 'db/functions.php';

/* access to crowdcc api db */
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.api.conn.php');

/* access to api crowdcc api db */ 
//require_once('db/db_config_api.php');

$errors = '';

/*
 * mysql Configuration Connection test
 */
		
 if (!@mysqli_connect(HOST_API, USER_API, PASSWORD_API, DATABASE_API)) {
	 $results = Array(
	 'head' => Array(
	 'status' 		=> '0',
	 'error_number'	=> '500', 
	 'error_message' => 'Temporary Error.'.
	 'Our server might be down, please try again later.'
     ),
	 'body' => Array ()
	 );
	 $errors=1;
 }
				
 /*
  * If no errors were found during connection, let's proceed with our queries
  */

 $api_screen_name = '';
 $api_token = '';
 $api_token_data = '';

 $api_same = '0';
 $api_most = '0';
 $api_start = '0';
 $api_max_id = '0';
 $api_count = '50';
 $api_results = '';


 // $_SERVER['QUERY_STRING'];

 // filter_input(INPUT_GET,"link",FILTER_SANITIZE_STRING);

 if (!$errors) 

 	/* token check */

 	if ( !isset($_GET['token']) ) {
 	     /* no private access token, public access only */
 	} else {
 	     $api_token = $_GET['token'];
 	     $api_token = (string)$api_token;

 	     /* token check * sanitize * check format ( token size ) */

 	     switch (true) {

 	    /* note : valid token ( api key ) : d67de372e38572b855-297d9d64-e38-3a798b67-72b-6c25c2-f738ecdd
		          failed this test ! php version 5.6.7
 	 	 
 	 	   case( base64_decode($api_token) !== filter_var( base64_decode($api_token) , FILTER_SANITIZE_STRING) ):
                 //  api token error
                 rtnwebapp('error_obj' , 'token-error' , 'post');
                 exit();
           break;
        */   

           case (strlen(utf8_decode($api_token)) !== 60 ):
 				 /*  api token error */
 	             rtnwebapp('error_obj' , 'token-error' , 'post');
 		         exit();
 	       break;
		   
    	 }

    	 /* token good * extract token data * check token */

    	 $api_token_data = check_api_token( $api_token );
    	 $api_token_data = explode(',', check_api_token( $api_token ));

    	 /* $api_token_data[0] uname * $api_token_data[1] count (default 50) */


 	}

    /* screen_name check */

    if ( !isset($_GET['screen_name']) ) {
 	     /* point to error handle -- screen_name missing! */
 	     // $_SERVER['QUERY_STRING'];
		 rtnwebapp('error_obj' , 'screen-name-error' , 'post');
 	} else {
 	     $api_screen_name = $_GET['screen_name'];
 	     $api_screen_name = (string)$api_screen_name;

 	     switch (true) {

 	 	   case( base64_decode($api_screen_name) !== filter_var( base64_decode($api_screen_name) , FILTER_SANITIZE_STRING) ):
                 /* screen name error */
                 rtnwebapp('error_obj' , 'screen-name-error' , 'post');
                 exit();
           break;

    	 }

 	}

 	/* crowdcc same check (cw) */

    if ( !isset($_GET['same']) ) {
    	 $api_same = '0';
 	} else {
 	     $api_same = $_GET['same'];
 	     $api_same = (string)$api_same;

 	     switch (true) {

 	 	   case( $api_same !== filter_var( $api_same , FILTER_SANITIZE_STRING) ):
                 /* same error */
                 rtnwebapp('error_obj' , 'same-error' , 'post');
                 exit();
           break;

    	 }

 	}

 	/* crowdcc popular check (cp) */

    if ( !isset($_GET['most']) ) {
    	 $api_most = '0';
 	} else {
 	     $api_most = $_GET['most'];
 	     $api_most = (string)$api_most;

 	     switch (true) {

 	 	   case( $api_most !== filter_var( $api_most , FILTER_SANITIZE_STRING) ):
                 /* same error */
                 rtnwebapp('error_obj' , 'most-error' , 'post');
                 exit();
           break;

    	 }

 	}

 	/* start check */

 	if ( !isset($_GET['start']) ) {
 	     /* start missing : default start 0 */
 	     $api_start = '0';
 	} else {
 	     $api_start = $_GET['start'];
 	     $api_start = (string)$api_start;

 	     switch (true) {

 	 	   case( base64_decode($api_start) !== filter_var( base64_decode($api_start) , FILTER_SANITIZE_STRING) ):
                 /* start error */
                 rtnwebapp('error_obj' , 'start-error' , 'post');
                 exit();
           break;

    	 }

 	}

 	/* max_id check */

 	if ( !isset($_GET['max_id']) ) {
 	     /* count missing : default count 50 */
 	     $api_max_id = '0';
 	} else {
 	     $api_max_id = $_GET['max_id'];
 	     $api_max_id = (string)$api_max_id;

 	     switch (true) {

 	 	   case( base64_decode($api_max_id) !== filter_var( base64_decode($api_max_id) , FILTER_SANITIZE_STRING) ):
                 /* max id error */
                 rtnwebapp('error_obj' , 'max-id-error' , 'post');
                 exit();
           break;

    	 }

 	}

 	/* count check */

 	if ( !isset($_GET['count']) ) {
 	     /* count missing : default count 50 */
 	     $api_count = '0';
 	} else {
 	     $api_count = $_GET['count'];
 	     $api_count = (string)$api_count;

 	     switch (true) {

 	 	   case( base64_decode($api_count) !== filter_var( base64_decode($api_count) , FILTER_SANITIZE_STRING) ):
                 /* count error */
                 rtnwebapp('error_obj' , 'count-error' , 'post');
                 exit();
           break;

    	 }

 	}

 	switch (true) {

 	 case ($api_count > '200'):
 		   /* point to error handle -- token bad */
 		   // echo 'overload count';
 		   rtnwebapp('error_obj' , 'count-overload-error' , 'post');
 	 break;

    }


    switch ( true ) {

      case ($api_max_id === '0'):
      case ($api_same === '0'):
      case ($api_most === '0'):

	  switch ($_GET['method']) {
				
	  /* get method (read data) :*/
		
			 case ('get'):
						$query = "
							SELECT tweets.tweet_owner,
								   tweets.tweet_create_date,  
								   tweets.tweet_id,
								   tweets.tweet_text,
								   tweets.source_url,
								   tweets.retweet_count,
								   tweets.favorite_count,
								   tweets.from_user_id,
								   tweets.from_user,
								   tweets.from_user_name,
								   tweets.from_location,
								   tweets.from_description,
								   tweets.from_url,
								   tweets.followers_count,
								   tweets.friends_count,
								   tweets.listed_count,
								   tweets.created_at,
								   tweets.favourites_count,
								   tweets.time_zone,
								   tweets.statuses_count,
								   tweets.profile_image_url,					       	   
								   tweets.entities_urls,
			 					   tweets.entities_hashtags,
			 					   tweets.entities_media_url,
			 					   tweets.entities_url  
							FROM tweets
							WHERE tweets.tweet_owner
							LIKE ?
							ORDER by tweets.tweet_id DESC
							LIMIT ?, ?";

						$s_stmt01 = $mysqli_api->prepare($query);

						log_error_api($timelocal,'prepare', $s_stmt01, $mysqli_api);

						// $api_screen_name = '%'.$_GET['screen_name'].'%';

						$s_stmt01->bind_param('sii', $api_screen_name, $api_start, $api_count);  //  bind to parameters.

						$s_stmt01->execute();

						log_error_api($timelocal,'execute', $s_stmt01, $mysqli_api);

						/* store result */
    					// $s_stmt01->store_result();

    					$s_stmt01->bind_result($tweet_owner,
    										   $tweet_create_date,
    										   $tweet_id,
    										   $tweet_text,
    										   $tweet_source_url,
    										   $tweet_retweet_count,
    										   $tweet_favorite_count,
    										   $tweet_from_user_id,
    										   $tweet_from_user,
    										   $tweet_from_user_name,
    										   $tweet_from_location,
    										   $tweet_from_description,
    										   $tweet_from_url,
    										   $tweet_followers_count,
    										   $tweet_friends_count,
    										   $tweet_listed_count,
    										   $tweet_created_at,
    										   $tweet_favourites_count,
    										   $tweet_time_zone,
    										   $tweet_statuses_count,
    										   $tweet_profile_image_url,
											   $tweet_entities_urls,
											   $tweet_entities_hash_tags,
											   $tweet_entities_media_url,
											   $tweet_entities_url 
											   );


    				   while($s_stmt01->fetch()) {

						    if (strpos($tweet_profile_image_url, '_normal') == false) {
						               $tweet_profile_image_url = $tweet_profile_image_url.'_normal.jpeg';
						    }

							$api_results[] = Array(

									'tweet_owner' => $tweet_owner,  
									'created_at' => $tweet_create_date,
									'id_str' => $tweet_id,
									'text' =>  $tweet_text,
									'source' =>  $tweet_source_url,
									'retweet_count' => $tweet_retweet_count,
									'favorite_count' => $tweet_favorite_count,

  								'user' => Array(
									'id' => $tweet_from_user_id,
									'id_str' => $tweet_from_user_id,
									'name' => $tweet_from_user,
									'screen_name' => $tweet_from_user_name,
									'location' => $tweet_from_location,
									'description' => $tweet_from_description,
									'url' => $tweet_from_url,

								'entities' => Array('url' => Array('urls' => 

								Array('url' => $tweet_entities_urls,'expanded_url' => $tweet_entities_urls))),
									
                					'followers_count' => $tweet_followers_count,
                					'friends_count' => $tweet_friends_count,
                					'listed_count' =>  $tweet_listed_count,
                					'created_at' => $tweet_created_at,
                					'favourites_count' => $tweet_favourites_count,               					
                					'time_zone' => $tweet_time_zone,
                					'statuses_count' => $tweet_statuses_count,
                					'profile_image_url' => $tweet_profile_image_url),
                						
								'entities' => Array(
									'urls' => $tweet_entities_urls,
									'hashtags' => $tweet_entities_hash_tags,
								'media' => Array(
									'media_url' => $tweet_entities_media_url,
									'url' => $tweet_entities_url)),

									'possibly_sensitive' => 'false',
									'lang' => 'en'
							);

							
						}

					break;

				$s_stmt01->close(); 
			}
		
	  break;

	}

	switch ( true ) {

	  case ($api_max_id !== '0'):
	  break;

	  case ($api_same !== '0'):
	  /* cw * same (or duplicate) records, display most frequent tweets with crowdcc  */
            $api_count = '30';
	  	  switch ($_GET['method']) {
				
	  /* cw * get method (read data) :*/
		
			 case ('get'):
						$query = "
						    SELECT tweets.tweet_owner,
								   tweets.tweet_create_date,  
								   tweets.tweet_id,
								   tweets.tweet_text,
								   tweets.source_url,
								   tweets.retweet_count,
								   tweets.favorite_count,
								   tweets.from_user_id,
								   tweets.from_user,
								   tweets.from_user_name,
								   tweets.from_location,
								   tweets.from_description,
								   tweets.from_url,
								   tweets.followers_count,
								   tweets.friends_count,
								   tweets.listed_count,
								   tweets.created_at,
								   tweets.favourites_count,
								   tweets.time_zone,
								   tweets.statuses_count,
								   tweets.profile_image_url,					       	   
								   tweets.entities_urls,
			 					   tweets.entities_hashtags,
			 					   tweets.entities_media_url,
			 					   tweets.entities_url,
			 				COUNT(*)
							FROM tweets
							GROUP BY tweets.tweet_id
							HAVING COUNT(*) >1 
							ORDER by tweets.tweet_id DESC
							LIMIT ?";

						$s_stmt01 = $mysqli_api->prepare($query);

						log_error_api($timelocal,'prepare', $s_stmt01, $mysqli_api);

						// $api_screen_name = '%'.$_GET['screen_name'].'%';

						$s_stmt01->bind_param('i', $api_count);  //  bind to parameters.

						$s_stmt01->execute();

						log_error_api($timelocal,'execute', $s_stmt01, $mysqli_api);

						/* store result */
    					// $s_stmt01->store_result();

    					$s_stmt01->bind_result($tweet_owner,
    										   $tweet_create_date,
    										   $tweet_id,
    										   $tweet_text,
    										   $tweet_source_url,
    										   $tweet_retweet_count,
    										   $tweet_favorite_count,
    										   $tweet_from_user_id,
    										   $tweet_from_user,
    										   $tweet_from_user_name,
    										   $tweet_from_location,
    										   $tweet_from_description,
    										   $tweet_from_url,
    										   $tweet_followers_count,
    										   $tweet_friends_count,
    										   $tweet_listed_count,
    										   $tweet_created_at,
    										   $tweet_favourites_count,
    										   $tweet_time_zone,
    										   $tweet_statuses_count,
    										   $tweet_profile_image_url,
											   $tweet_entities_urls,
											   $tweet_entities_hash_tags,
											   $tweet_entities_media_url,
											   $tweet_entities_url,
											   $tweet_count 
											   );


    				   while($s_stmt01->fetch()) {

						    if (strpos($tweet_profile_image_url, '_normal') == false) {
						               $tweet_profile_image_url = $tweet_profile_image_url.'_normal.jpeg';
						    }

							$api_results[] = Array(

									'tweet_owner' => $tweet_owner,
									'count' => $tweet_count,   
									'created_at' => $tweet_create_date,
									'id_str' => $tweet_id,
									'text' =>  $tweet_text,
									'source' =>  $tweet_source_url,
									'retweet_count' => $tweet_retweet_count,
									'favorite_count' => $tweet_favorite_count,

  								'user' => Array(
									'id' => $tweet_from_user_id,
									'id_str' => $tweet_from_user_id,
									'name' => $tweet_from_user,
									'screen_name' => $tweet_from_user_name,
									'location' => $tweet_from_location,
									'description' => $tweet_from_description,
									'url' => $tweet_from_url,

								'entities' => Array('url' => Array('urls' => 

								Array('url' => $tweet_entities_urls,'expanded_url' => $tweet_entities_urls))),
									
                					'followers_count' => $tweet_followers_count,
                					'friends_count' => $tweet_friends_count,
                					'listed_count' =>  $tweet_listed_count,
                					'created_at' => $tweet_created_at,
                					'favourites_count' => $tweet_favourites_count,               					
                					'time_zone' => $tweet_time_zone,
                					'statuses_count' => $tweet_statuses_count,
                					'profile_image_url' => $tweet_profile_image_url),
                						
								'entities' => Array(
									'urls' => $tweet_entities_urls,
									'hashtags' => $tweet_entities_hash_tags,
								'media' => Array(
									'media_url' => $tweet_entities_media_url,
									'url' => $tweet_entities_url)),

									'possibly_sensitive' => 'false',
									'lang' => 'en'
							);

							
						}

					break;

				$s_stmt01->close(); 
			}	

	  break;

	  case ($api_most !== '0'):
	  /* cp * most (or duplicate) records, display users who have stored the most data with crowdcc  */
	  /* for now just lookup of current record set, but this should be members table lookup */
	  		$api_screen_name = 'glynthom';
	  		$api_count = '30';
      /* cp * set count to 30 */

	  	  switch ($_GET['method']) {
				
	  /* cp * get method (read data) :*/
		
			 case ('get'):
						$query = "
						    SELECT tweets.tweet_owner,
								   tweets.tweet_create_date,  
								   tweets.tweet_id,
								   tweets.tweet_text,
								   tweets.source_url,
								   tweets.retweet_count,
								   tweets.favorite_count,
								   tweets.from_user_id,
								   tweets.from_user,
								   tweets.from_user_name,
								   tweets.from_location,
								   tweets.from_description,
								   tweets.from_url,
								   tweets.followers_count,
								   tweets.friends_count,
								   tweets.listed_count,
								   tweets.created_at,
								   tweets.favourites_count,
								   tweets.time_zone,
								   tweets.statuses_count,
								   tweets.profile_image_url,					       	   
								   tweets.entities_urls,
			 					   tweets.entities_hashtags,
			 					   tweets.entities_media_url,
			 					   tweets.entities_url,
			 			    COUNT(*) 
							FROM tweets
						    GROUP BY tweets.tweet_owner
							ORDER by tweets.tweet_id DESC
							LIMIT ?";
						$s_stmt01 = $mysqli_api->prepare($query);

						log_error_api($timelocal,'prepare', $s_stmt01, $mysqli_api);

						// $api_screen_name = '%'.$_GET['screen_name'].'%';

						$s_stmt01->bind_param('i', $api_count);  //  bind to parameters.

						$s_stmt01->execute();

						log_error_api($timelocal,'execute', $s_stmt01, $mysqli_api);

						/* store result */
    					// $s_stmt01->store_result();

    					$s_stmt01->bind_result($tweet_owner,
    										   $tweet_create_date,
    										   $tweet_id,
    										   $tweet_text,
    										   $tweet_source_url,
    										   $tweet_retweet_count,
    										   $tweet_favorite_count,
    										   $tweet_from_user_id,
    										   $tweet_from_user,
    										   $tweet_from_user_name,
    										   $tweet_from_location,
    										   $tweet_from_description,
    										   $tweet_from_url,
    										   $tweet_followers_count,
    										   $tweet_friends_count,
    										   $tweet_listed_count,
    										   $tweet_created_at,
    										   $tweet_favourites_count,
    										   $tweet_time_zone,
    										   $tweet_statuses_count,
    										   $tweet_profile_image_url,
											   $tweet_entities_urls,
											   $tweet_entities_hash_tags,
											   $tweet_entities_media_url,
											   $tweet_entities_url,
											   $tweet_count 
											   );


    				   while($s_stmt01->fetch()) {

						    if (strpos($tweet_profile_image_url, '_normal') == false) {
						               $tweet_profile_image_url = $tweet_profile_image_url.'_normal.jpeg';
						    }

							$api_results[] = Array(

									'tweet_owner' => $tweet_owner,
									'count' => $tweet_count,  
									'created_at' => $tweet_create_date,
									'id_str' => $tweet_id,
									'text' =>  $tweet_text,
									'source' =>  $tweet_source_url,
									'retweet_count' => $tweet_retweet_count,
									'favorite_count' => $tweet_favorite_count,

  								'user' => Array(
									'id' => $tweet_from_user_id,
									'id_str' => $tweet_from_user_id,
									'name' => $tweet_from_user,
									'screen_name' => $tweet_from_user_name,
									'location' => $tweet_from_location,
									'description' => $tweet_from_description,
									'url' => $tweet_from_url,

								'entities' => Array('url' => Array('urls' => 

								Array('url' => $tweet_entities_urls,'expanded_url' => $tweet_entities_urls))),
									
                					'followers_count' => $tweet_followers_count,
                					'friends_count' => $tweet_friends_count,
                					'listed_count' =>  $tweet_listed_count,
                					'created_at' => $tweet_created_at,
                					'favourites_count' => $tweet_favourites_count,               					
                					'time_zone' => $tweet_time_zone,
                					'statuses_count' => $tweet_statuses_count,
                					'profile_image_url' => $tweet_profile_image_url),
                						
								'entities' => Array(
									'urls' => $tweet_entities_urls,
									'hashtags' => $tweet_entities_hash_tags,
								'media' => Array(
									'media_url' => $tweet_entities_media_url,
									'url' => $tweet_entities_url)),

									'possibly_sensitive' => 'false',
									'lang' => 'en'
							);

							
						}

					break;

				$s_stmt01->close(); 
			}	

	  break;

	}

	/* format check */

	if ( !isset($_GET['format']) ) {
 	     /* point to error handle -- format missing! */
 	} else {
 		 /* format validate string check handle */
 	}


	switch ($_GET['format']) {

		case 'json' :
					
			  // @header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
			  // @header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			  // @header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			  // @header("Cache-Control: post-check=0, pre-check=0", false);
			  // @header("Pragma: no-cache");

			  if (!headers_sent()) { @header ("content-type: text/json charset=utf-8"); }

			  /* ** in PHP 5.4, you can use JSON_UNESCAPED_SLASHES:   */
			  /* echo json_encode($results, JSON_UNESCAPED_SLASHES);  */
			  /* however for local host -> PHP 5.3.1 */
			  /* str_replace('\\/', '/', json_encode($results));
				 echo str_replace('\\/', '/', json_encode($results)); */

			  /* json cc data */

			  echo json_encode($api_results);

			  /* testing json error handle */
			
			  /* echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) ); */
			  /* echo json_encode( Array('errors' => Array( Array('message' => 'Over capacity', 'code' => '130'))) ); */

		break;
		case 'php' :
			  if (!headers_sent()) { @header ("content-type: text/php charset=utf-8"); }  
			  echo serialize($api_results);  
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

	  switch ($token) {

	  	case ('token-error'):
	  	      /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
	  		  echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
	  	      exit();
	  	break;

	  	case ('screen-name-error'):
	  	case ('same-error'):
	  	case ('most-error'):
	    case ('start-error'):
	    case ('max-id-error'):
	    case ('count-error'):
	  		  /* 403: {"errors":[{"message":"Sorry, that page does not exist","code":34}]} */
	  		  echo json_encode( Array('errors' => Array( Array('message' => 'Sorry, that page does not exist', 'code' => '34'))) );
	  	      exit();
	  	break;

	  	case ('count-overload-error'):
	  		  /* 503: {"errors":[{"message":"Over capacity","code":130}]} */
 			  echo json_encode( Array('errors' => Array( Array('message' => 'Over capacity', 'code' => '130'))) );
	  	      exit();
	  	break;

	  }
	
	break;
  }

}



?>
