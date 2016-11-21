<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* tw.php  * twitter API actions
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

/* access to crowdcc signin db */ 
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

/* found log * log failure outside the JS console ( please comment out / remove later ) */
/* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */

/* phpmailer lib * auth mail lib files * mailresetlink($to, $token_send) * see ccmail app.error.php */
require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');

/* twitter oauth lib * source: https://twitteroauth.com * version: v0.4.1 * modified v0.1 */
require_once('tweetpath.php');
use crowdcc\TwitterOAuth\TwitterOAuth;

if (!isset($_POST) ) { rtnwebapp('error' , 'error_tamper' , 'post'); exit(); }

$post_in = $_POST;
$post_in = explode(":", implode($post_in));

 switch (true) {

    case ( base64_decode($post_in[0]) !== filter_var( base64_decode($post_in[0]) , FILTER_SANITIZE_STRING ) ):
          /*  flag error */
          rtnwebapp('error' , 'valid_flag-error' , 'post');
          exit();
    break;
    case ( decrypt($post_in[1]) !== filter_var( decrypt($post_in[1]) , FILTER_SANITIZE_STRING ) ):
          /*  screen name error */
          rtnwebapp('error' , 'valid_screen_name-error' , 'post');
          exit();
    break;
    case ( base64_decode($post_in[2]) !== filter_var( base64_decode($post_in[2]) , FILTER_SANITIZE_STRING ) ):
          /*  tweet $accesstokensecret fail */
          rtnwebapp('error' , 'valid_token-error' , 'post');
          exit();
    break;

    case ( base64_decode($post_in[3]) !== filter_var( base64_decode($post_in[3]) , FILTER_SANITIZE_STRING ) ):
          /*  tweet id error */
          rtnwebapp('error' , 'valid_id-error' , 'post');
          exit();
    break;

}

/* decrypt sanitized data before final data presentation to API */

$post_in[0] = base64_decode($post_in[0]);    /*  flag         ::  _rt, _ft, _rp, _cm, _st, _sm  */
$post_in[1] = decrypt($post_in[1]);          /*  usr          ::  screen_name */
$post_in[2] = base64_decode($post_in[2]);    /*  tok          ::  auth_token ($accesstoken)        :: 6f2cc738bb32f81e5bdd41c0fe84a3d2767b866712eb867029.. */
$post_in[2] = ccrypt( $post_in[2], 'AES-256-OFB', 'de' );  /* ::  un-encrypt auth_token ($accesstoken) using key */

$post_cauth = $_COOKIE['cauth_token'];       /*  ---          ::  auth_secret ($accesstokensecret) :: 1651ab7fcf649b6f06b379ade693b9cd7460847845bfeb7405.. */
$post_cauth = ccrypt( $post_cauth, 'AES-256-OFB', 'de' );  /* ::  un-encrypt auth_secret ($accesstokensecret) using key */

$post_in[3] = base64_decode($post_in[3]);    /*  tid  ||  to  ::  twitter id  ||  to: email address  ( when mail tweet ) or share / create content tweet */

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
print_r('thats all she wrote');
exit();
*/

/*
$consumerkey = "7xj9EAJAoLtCbEUlI85JA";
$consumersecret = "BY9WTuQy0sGISrK8yY2EAm1mvdaBPcdoqFb6jF3cA";
$accesstoken = "295131454-6BOZWhDOAS63gXSAyN6HW6Jr1xTbc9dqfdvuNemQ";
$accesstokensecret = "7HBddc8WsBJajM7mER5Rp8fe40i1274E5D571911FdQ";
*/

/* $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret); */

/* log_found(CONSUMER_KEY .'|'. CONSUMER_SECRET .'|'. $post_in[2], $post_cauth ,'getConnectionWithAccessToken', __LINE__ ); */

$connection = getConnectionWithAccessToken(CONSUMER_KEY, CONSUMER_SECRET, $post_in[2], $post_cauth);


switch ( $post_in[0] ) {

	case ( '_rt' ):
          /* retweet tweet * please note you cannot * re-tweet your own tweet or re-tweet a protected account tweet * error message(s) : lastHttpCode() 403 & lastResponse() 328 */
          /* client updated to prevent * re-tweet of your own tweets * but re-tweet protected tweets requires passing array with addtional $connection->lastResponse() */

          /* $post_in[0] === rt
             $post_in[1] === glynthom 
             $post_in[2] === twitter id */

          /* log_found( $post_in[2] ,  $post_in[3] , '_rt', __LINE__ ); */

          /* use twitter "id_str" of tweet */

          /* $connection->post('statuses/retweet/' .  $post_in[2] ); */
          $connection->post('statuses/retweet/' .  $post_in[3] );

          if ( ($connection->lastHttpCode()) === 200 ) {
                rtnwebapp('rt' , 'retweet_success_' . $connection->lastHttpCode() , 'post');
          } else {
                /* rtnwebapp('error' , 'retweet_error_' . $connection->lastHttpCode() , 'post'); */
                rtnwebapp('retweet_error_' , array('retweet_error_' . $connection->lastHttpCode(), $connection->lastResponse()) , 'post');
          }
          
          /*
          if ( ($connection->http_code) === 200 ) {
                rtnwebapp('rt' , 'retweet_success_' . $connection->http_code , 'post');
          } else {
                rtnwebapp('error' , 'retweet_error_' . $connection->http_code , 'post');
          }
          */
	break;

  case ( '_rf' ):
          /* retweet * favor tweet */

          /* $post_in[0] === rt
             $post_in[1] === glynthom 
             $post_in[2] === twitter id */

          /* use twitter "id_str" of tweet */

          /* $connection->post('statuses/retweet/' .  $post_in[2] ); */
          $connection->post('statuses/retweet/' .  $post_in[3] );

          if ( ($connection->lastHttpCode()) !== 200 ) {
                /* rtnwebapp('sm' , 'retweet_error_' . $connection->lastHttpCode() , 'post'); */
                rtnwebapp('retweet_error_' , array('retweet_error_' . $connection->lastHttpCode(), $connection->lastResponse()) , 'post');
          } else {
                $connection->post('favorites/create', array('id' => $post_in[3]) );
                /* log_found('re-favor_error', ' re-favour lastHttpCode()' . $connection->lastHttpCode() , 'start:tw.php', __LINE__ ); */
          }

          if ( ($connection->lastHttpCode()) !== 200 ) {
                /* rtnwebapp('refavor_error_' , 'favor_error_' . $connection->lastHttpCode() , 'post'); */
                rtnwebapp('favor_error_' , array('favor_error_' . $connection->lastHttpCode(), $connection->lastResponse()) , 'post');
          } else {
                rtnwebapp('rf' , 'refavor_success_' . $connection->lastHttpCode() , 'post');
          }

          /*
          if (($connection->http_code) !== 200 ) {
               rtnwebapp('error' , 'retweet_error_' . $connection->http_code , 'post');
          } else { 
               $connection->post('favorites/create', array('id' => $post_in[3]) );
          }

          if (($connection->http_code) !== 200 ) {
               rtnwebapp('error' , 'favor_error_' . $connection->http_code , 'post');
          } else { 
               rtnwebapp('rf' , 'refavor_success_' . $connection->http_code , 'post');
          }
          */

  break;

  case ( '_ft' ):
          /* favor tweet */

          /* $post_in[0] === ft
             $post_in[1] === glynthom 
             $post_in[2] === twitter id */

          /* use twitter "id_str" of tweet */

          /* $connection->post('favorites/create', array('id' => $post_in[2]) ); */
          $connection->post('favorites/create', array('id' => $post_in[3]) );

          if ( ($connection->lastHttpCode()) === 200 ) {
                rtnwebapp('sm' , 'favor_success_' . $connection->lastHttpCode() , 'post');
          } else {
                /* rtnwebapp('favor_error_' , 'favor_error_' . $connection->lastHttpCode() , 'post'); */
                rtnwebapp('favor_error_' , array('favor_error_' . $connection->lastHttpCode(), $connection->lastResponse()) , 'post');
          }

          /*
          if ( ($connection->http_code) === 200 ) {
                rtnwebapp('ft' , 'favor_success_' . $connection->http_code , 'post');
          } else {
                rtnwebapp('error' , 'favor_error_' . $connection->http_code , 'post');
          }
          */

  break;

  case ( '_rp' ):
          /* reply tweet */

          /* $post_in[0] === rp
             $post_in[1] === glynthom 
             $post_in[2] === twitter id */

          /* use twitter "id_str" of tweet */    

          /* This parameter will be ignored unless the author of the tweet this parameter references is mentioned within the status text.
             Therefore, you must include @username, where username is the author of the referenced tweet, within the update. 

             $status_id = '480775609728385024';
             $twitt_reply = '@username the replay text';

          */

          /* $post_in[3] = base64_decode($post_in[3]);    /*  rpl or frm ::  reply tweet field or from: twitter user ( when mail tweet ) */
          $post_in[4] = base64_decode($post_in[4]);    /*  rpl or frm ::  reply tweet field or from: twitter user ( when mail tweet ) 

          /* for mocking */
          // echo $post_in[3];

          // mock out below ... 
          
          /* $connection->post('statuses/update', array('in_reply_to_status_id'=> $post_in[2] , 'status' => htmlspecialchars_decode($post_in[3]) )); */

          $connection->post('statuses/update', array('in_reply_to_status_id'=> $post_in[3] , 'status' => htmlspecialchars_decode($post_in[4]) ));
      
          if ( ($connection->lastHttpCode()) === 200 ) {
                rtnwebapp('sm' , 'reply_success_' . $connection->lastHttpCode() , 'post');
          } else {
                rtnwebapp('reply_error_' , 'reply_error_' . $connection->lastHttpCode() , 'post');
          }

          /*
          if ( ($connection->http_code) === 200 ) {
                rtnwebapp('rp' , 'reply_success_' . $connection->http_code , 'post');
          } else {
                rtnwebapp('error' , 'reply_error_' . $connection->http_code , 'post');
          }
          */
  break;

  case ( '_rm' ):
          /* reply tweet with media */

          $handle = ''; $image= '';

          /* http://stackoverflow.com/questions/10530385/update-with-media-using-abrahams-twitteroauth */

          /* $post_in[0] === rm
             $post_in[1] === glynthom 
             $post_in[2] === twitter id */

          /* use twitter "id_str" of tweet */    

          /* This parameter will be ignored unless the author of the tweet this parameter references is mentioned within the status text.
             Therefore, you must include @username, where username is the author of the referenced tweet, within the update. 

             $status_id = '480775609728385024';
             $twitt_reply = '@username the replay text';

          */

          $upload_dir = "upimg/";

          $post_in[4] = base64_decode($post_in[4]);    /*  rpl || frm ::  reply tweet field or from: twitter user ( when mail tweet )        */
          $post_in[5] = base64_decode($post_in[5]);    /*  msg || img ::  email message to send with tweet or image  (when reply with media) */

          // $handle = fopen($image_path=,'rb');

          $post_in[5] = str_replace('data:image/png;base64,', '', $post_in[5]);
          $post_in[5] = str_replace(' ', '+', $post_in[5]);
          $post_in[5] = base64_decode($post_in[5]);

          /* img file name convention : USER_YYYY-MM-DD_HOUR-MINUTES-SECOND.png OR YYYY-MM-DD_HOUR-MINUTES-SECOND_USER.png */

          /* $file = $upload_dir . $post_in[1] . '_' . $post_in[2] . ".png"; */

          /* natrual order by user name */ 
          $file = $upload_dir . $post_in[1] .'_' . date("Y-m-d_H-i-s") . ".png";

          /* natural order by file date */ 
          /* $file = $upload_dir . date("Y-m-d_H-i-s") . '_' . $post_in[1] . ".png"; */

          $success = file_put_contents($file, $post_in[5]);
          /* print $success ? $file : 'Unable to save the file.'; */

          switch (true) {
           case ($success === false):
                 rtnwebapp('reply_media_error_' , 'reply_media_error_' . $connection->lastHttpCode() , 'post');
           break;
           case ($success === 0):
                rtnwebapp('reply_media_error_' , 'reply_media_error_' . $connection->lastHttpCode() , 'post');
           break;
          }

          /* update method * media/upload * statues/update */

          $msg = htmlspecialchars_decode($post_in[4]);

          $result = $connection->upload('media/upload', array('media' => $file));
          /* $media1 = $connection->upload('media/upload', array('media' => '/path/to/file/kitten1.jpg')); */
          /* $media2 = $connection->upload('media/upload', array('media' => '/path/to/file/kitten2.jpg')); */

          switch (true) {
           case (200 === $connection->lastHttpCode()):
                 $parameters = array('status' => ''.$msg.'', 'in_reply_to_status_id'=>''.$post_in[3].'', 'media_ids' => $result->media_id_string);
                 /* $parameters = array('status' => 'Meow Meow Meow','in_reply_to_status_id'=>''.$post_in[3].'', 'media_ids' => implode(',', array($media1->media_id_string, $media2->media_id_string)),); */
                 $result = $connection->post('statuses/update', $parameters);
           break;
           case (200 !== $connection->lastHttpCode()):
                 rtnwebapp('error' , 'reply_media_error_' . $connection->lastHttpCode() , 'post');
           break;
          } 
        
          if ( ($connection->lastHttpCode()) === 200 ) {
               rtnwebapp('rm' , 'reply_media_success_' . $connection->lastHttpCode() , 'post');
          } else {
               rtnwebapp('reply_media_error_' , 'reply_media_error_' . $connection->lastHttpCode() , 'post');
          }

          /*  older method * reply tweet with media * update_with_media */
        
          /*
          $handle = fopen($file,'rb');
          $image  = fread($handle,filesize($file));
          fclose($handle);
          $msg = htmlspecialchars_decode($post_in[4]);
          $connection->post('statuses/update_with_media', array('media[]' => "{$image};type=image/jpeg;filename={$file}", 'in_reply_to_status_id'=> "".$post_in[3]."", 'status'  => " ".$msg.""),true);

          if ( ($connection->http_code) === 200 ) {
                rtnwebapp('rm' , 'reply_media_success_' . $connection->http_code , 'post');
          } else {
                rtnwebapp('error' , 'reply_media_error_' . $connection->http_code , 'post');
          }
          */

  break;

  case ( '_cm' ):
         /* ccmail tweet */

       /*
       print_r( $post_in[0] ); //  _cm        
       print_r('|');
       print_r( $post_in[1] ); //  glynthom   
       print_r('|');
       print_r( $post_in[2] ); // glynthoma@gmail.com 
       print_r('|');
       print_r( base64_decode($post_in[3]) ); // glynthoma@gmail.com 
       print_r('|');
       print_r( base64_decode($post_in[4]) ); // message text 
       print_r('|');
       print_r( base64_decode($post_in[5]) ); // https://twitter.com/TechCrunch 
       print_r('|');
       print_r( base64_decode($post_in[6]) ); // TechCrunch
       print_r('|');
       print_r( base64_decode($post_in[7]) ); // https://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png 
       print_r('|');
       print_r( htmlspecialchars_decode( base64_decode($post_in[8]) ) ); // <a href="http://t.co/NCNLvurd0g" target="_blank"><img src="http://pbs.twimg.com/media/BtVFXbGIgAAEP8h.jpg" style="position:relative;float:right;vertical-align:middle;border-radius: 3px 3px 3px 3px" height="50" width="50"></a>Samsung might have found a shortcut to mobile virtual reality through Oculus VR <a href="http://t.co/bbSzt4pkdI" target="_blank">http://t.co/bbSzt4pkdI</a> <a href="http://t.co/NCNLvurd0g" target="_blank">http://t.co/NCNLvurd0g</a> 
       print_r('|');
       print_r( base64_decode($post_in[9]) ); // https://twitter.com/TechCrunch/status/492376003143286784 (for tweet time) 
       print_r('|');
       print_r( base64_decode($post_in[10]) ); // Thu Jul 24 18:29:21 +0000 2014 (for tweet time) 
       print_r('|');
       print_r( base64_decode($post_in[11]) ); // 20h (for tweet time)
       print_r('|');

       print_r('thats all she wrote');
       exit();
       */
   
     /* $post_in[3] = base64_decode($post_in[3]);    /*  rpl or frm ::  reply tweet field or from: twitter user   ( when mail tweet )  */
     $post_in[4] = base64_decode($post_in[4]);    /*  msg or img ::  email message to send with tweet or image (when reply with media) */
     $post_in[5] = base64_decode($post_in[5]);    /*  turl       ::  tweet screen_name URL */
     $post_in[6] = base64_decode($post_in[6]);    /*  tusr       ::  tweet screen_name */
     $post_in[7] = base64_decode($post_in[7]);    /*  timg       ::  tweet screen_name img */
     $post_in[8] = base64_decode($post_in[8]);    /*  twt        ::  tweet */
     $post_in[9] = base64_decode($post_in[9]);    /*  ttf        ::  tweet time a tag href */
     $post_in[10] = base64_decode($post_in[10]);  /*  ttt        ::  tweet time a tag title */
     $post_in[11] = base64_decode($post_in[11]);  /*  tta        ::  tweet time a tag */

     /* tweetmsg( $post_in[1], $post_in[3], htmlspecialchars_decode($post_in[4]), $post_in[5], $post_in[6], $post_in[7], htmlspecialchars_decode($post_in[8]), $post_in[9], $post_in[10], $post_in[11] ); */
     /* tweetmsg( $frm, $frm_mail, $to_mail, $msg_txt, $twt_usr_url, $twt_usr, $twt_img, $twt_html, $twt_href, $twt_title, $twt_atag ); */

     tweetmsg( $post_in[1], $post_in[3], 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr', htmlspecialchars_decode($post_in[4]), $post_in[5], $post_in[6], $post_in[7], htmlspecialchars_decode($post_in[8]), $post_in[9], $post_in[10], $post_in[11] );
     /* tweetmsg( $frm, $to_mail, $smtpmail, $stmppass, $msg_txt, $twt_usr_url, $twt_usr, $twt_img, $twt_html, $twt_href, $twt_title, $twt_atag ) */

  break;

  case ( '_st' ):
         /* share * create tweet */

         /* $post_in[0] === st
            $post_in[1] === glynthom
            $post_in[2] === javascript unix timestamp
            $post_in[3] === share / create content tweet */

         /* $post_in[3] = base64_decode($post_in[3]);    /*  rpl || frm || st ::  reply tweet field or from: twitter user ( when mail tweet ) or share / create content tweet */

         $connection->post('statuses/update', array('status' => htmlspecialchars_decode($post_in[3]) ));


         if ( ($connection->lastHttpCode()) === 200 ) {
               rtnwebapp('st' , 'share_success_' . $connection->lastHttpCode() , 'post');
         } else {
               rtnwebapp('share_error_' , 'share_error_' . $connection->lastHttpCode() , 'post');
         }
        
         /*
         if ( ($connection->http_code) === 200 ) {
               rtnwebapp('st' , 'share_success_' . $connection->http_code , 'post');
         } else {
               rtnwebapp('error' , 'share_error_' . $connection->http_code , 'post');
         }
         */

  break;

  case ( '_sm' ):
         /* share * create tweet with media */

         $handle = ''; $image= '';

         /* http://stackoverflow.com/questions/10530385/update-with-media-using-abrahams-twitteroauth */

         /* $post_in[0] === sm
            $post_in[1] === glynthom 
            $post_in[2] === time string gernerated from the javascript client */

         /* use twitter "id_str" of tweet */    

         /* This parameter will be ignored unless the author of the tweet this parameter references is mentioned within the status text.
            Therefore, you must include @username, where username is the author of the referenced tweet, within the update. 

            $status_id = '480775609728385024';
            $twitt_reply = '@username the replay text';

         */

         $upload_dir = "upimg/";

         $post_in[4] = base64_decode($post_in[4]);    /*  msg || img       ::  email message to send with tweet or image  (when reply with media)       */

         $post_in[4] = str_replace('data:image/png;base64,', '', $post_in[4]);
         $post_in[4] = str_replace(' ', '+', $post_in[4]);
         $post_in[4] = base64_decode($post_in[4]);

         /* img file name convention : USER_YYYY-MM-DD_HOUR-MINUTES-SECOND.png OR YYYY-MM-DD_HOUR-MINUTES-SECOND_USER.png */
         /* $file = $upload_dir . $post_in[1] . '_' . $post_in[2] . ".png"; */

         /* natural order by user name */ 
         $file = $upload_dir . $post_in[1] .'_' . date("Y-m-d_H-i-s") . ".png";

         /* natural order by file date */ 
         /* $file = $upload_dir . date("Y-m-d_H-i-s") . '_' . $post_in[1] . ".png"; */

         $success = file_put_contents($file, $post_in[4]);
         /* print $success ? $file : 'Unable to save the file.'; */

         switch (true) {
          case ($success === false):
                rtnwebapp('share_media_error_' , 'share_media_error_' . $connection->lastHttpCode() , 'post');
          break;
          case ($success === 0):
                rtnwebapp('share_media_error_' , 'share_media_error_' . $connection->lastHttpCode() , 'post');
          break;
         }

         /* update method * media/upload * statues/update */

         $msg = htmlspecialchars_decode($post_in[3]);

         $result = $connection->upload('media/upload', array('media' => $file));
         /* $media1 = $connection->upload('media/upload', array('media' => '/path/to/file/kitten1.jpg')); */
         /* $media2 = $connection->upload('media/upload', array('media' => '/path/to/file/kitten2.jpg')); */
         
         switch (true) {
           case (200 === $connection->lastHttpCode()):
                 $parameters = array('status' => ''.$msg.'', 'media_ids' => $result->media_id_string);
                 /* $parameters = array('status' => 'Meow Meow Meow', 'media_ids' => implode(',', array($media1->media_id_string, $media2->media_id_string)),); */
                 $result = $connection->post('statuses/update', $parameters);
           break;
           case (200 !== $connection->lastHttpCode()):
                 rtnwebapp('share_media_error_' , 'share_media_error_' . $connection->lastHttpCode() , 'post');
           break;
         } 
        
         if ( ($connection->lastHttpCode()) === 200 ) {
               rtnwebapp('sm' , 'share_media_success_' . $connection->lastHttpCode() , 'post');
         } else {
               rtnwebapp('share_media_error_' , 'share_media_error_' . $connection->lastHttpCode() , 'post');
         }

         /*  older method * create tweet with media * update_with_media */

         /*
         $handle = fopen($file,'rb');
         $image  = fread($handle,filesize($file));
         fclose($handle);
         $msg = htmlspecialchars_decode($post_in[3]);
         $connection->post('statuses/update_with_media', array('media[]' => "{$image};type=image/jpeg;filename={$file}", 'status'  => " ".$msg.""),true);
         if ( ($connection->http_code) === 200 ) {
               rtnwebapp('sm' , 'share_media_success_' . $connection->http_code , 'post');
         } else {
               rtnwebapp('error' , 'share_media_error_' . $connection->http_code , 'post');
         }
         */

  break;

}

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
    $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
    return $connection;
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

			  // echo json_encode( $flag . ciphermod($token) );
			  // if ($flag === 'cbb') { echo json_encode( $token );} else { echo json_encode( $flag . ':*:' . $token );}
 
      switch (true) {

        case ( $flag === 'retweet_error_' ):
        case ( $flag === 'refavor_error_' ):
        case ( $flag === 'favor_error_' ):
        case ( $flag === 'reply_error_' ):
        case ( $flag === 'reply_media_error_' ):
        case ( $flag === 'share_error_' ):
        case ( $flag === 'share_media_error_'):

              switch (true) {

               case (is_object($token)):
                     /* log_found('reprotect_error', 'Retweet is not permissible for this status:' . json_encode( $token[1]->errors[0]->code ), 'start:tw.php', __LINE__ ); */
                     echo json_encode( 'error' . ':*:' . $flag . $token[1]->errors[0]->code );
               break;
               case (is_array($token)):
                     /* $token is an array &&  {"errors":[{"code":328,"message":"Retweet is not permissible for this status."}]} */ 
                     echo json_encode( 'error'. ':*:' . $token[0] );
                                        
               break;
               case (!is_array($token) ):
                      /* test token to see if token is not an array * detect re-tweet a protected account tweet * error message(s) */
                      echo json_encode( 'error' . ':*:' . $token );
               break;

              }

        break;

        case ( $flag === 'error'):  /* input filter errors detected ! */
               echo json_encode( $flag . ':*:' . $token );
        break;

        case ( $flag !== 'error' ): /* no errors detected ! */
               echo json_encode( $flag . ':*:' . $token );
        break;

      }

		break;
	}

  # unset vars
  # unset($method, );
  exit();

}

/*
function tweetmsg( $frm, $to_mail, $msg_txt, $twt_usr_url, $twt_usr, $twt_img, $twt_html, $twt_href, $twt_title, $twt_atag ) {
  $headers = '';
  $message = '';
  if ($to_mail) {
    $subject = '@' . $frm . " shared a tweet";
    $uri = 'http://'. $_SERVER['HTTP_HOST'] ;
    $message = '
    <html>
    <head>
    <meta name="viewport" content="width=device-width" />
    <title>' . $subject . '</title>
    </head>
    <body bgcolor="#FFFFFF" style="-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;">
    <!-- header -->
    <table class="head-wrap" style="max-width: 95%;" cellspacing="0" cellpadding="0" border="0" align="center" >
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
    <a href="http://www.crowdcc.com">
    <img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" style="width:135px;height:34px;position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;cursor:pointer;">
    </a>   
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
    <h4>Message sent from <a href="https://twitter/'. $frm .'" style="font-weight:normal;text-decoration:none;">@' . $frm . '</a></h4>
    <p>
    ' . $msg_txt . '
    <table class="head-wrap" style="max-width: 95%; border-collapse:collapse" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody>
    <tr>
    <td valign="top" colspan="2">&nbsp;</td>
    </tr>
    <tr>
    <td valign="top">
    <a target="_blank" href="'. $twt_usr_url .'">
    <img width="42" height="42" style="border-radius: 3px; margin-right: 7px; border: medium none;" alt="twitter icon" src="'. $twt_img .'">
    </a>
    </td>
    <td valign="top">
    <strong>
    <a class="urlprofilelink" style="color: #444444; text-decoration: none;" target="_blank" title="'. $twt_usr .'" href="'. $twt_usr_url .'">'. $twt_usr .'</a>
    </strong>
    <span class="tweet-time" style="float: right; font-size: 12px; margin-right: 0; position: relative;">
    <a target="_blank" style="color: #878787;text-decoration: none;" href="'. $twt_href .'" title="'. $twt_title .'">'. $twt_atag .'</a>
    </span>
    <br>
    '. $twt_html .'
    <span style="margin-left:7px"></span>
    </td>
    </tr>
    </table>
    </p>
    <p style="padding-bottom:10px;"><b><a href="http://crowdcc.com" style="text-decoration: none; color:#000000; cursor:pointer;">Sent via crowdcc</a></b></p>
    <h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">Crowdcc</a>
    </h4>
    <p style="padding-bottom:10px;">
    <tr>
    <td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
    </tr>
    <tr>
    <td valign="top" height="20" style="min-height:20px"> </td>
    </tr>
    <td valign="top" colspan="2">
    <span>Have a question or just want to say hello? <a href="https://twitter.com/crowdccHQ" style="cursor: pointer;">tweet us</a></span>
    <p style="padding-top:10px;padding-bottom:10px;">
    <!-- footer -->
    <span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message sent from crowdcc, please don\'t reply directly to this email.</span>
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
    }
    if (mail($to_mail,$subject,$message,$headers,"-fbounce@crowdcc.com")) {
      rtnwebapp('cm' , 'mail_success_200' , 'post');
      // to the email address provided, please try again !
    } else {
      rtnwebapp('error' , 'mail_error_' . $to_mail , 'post');
      // to the email address provided, please try again !
    }

} 
*/

function tweetmsg( $frm, $to_mail, $smtpmail, $stmppass, $msg_txt, $twt_usr_url, $twt_usr, $twt_img, $twt_html, $twt_href, $twt_title, $twt_atag ) {

  if ($to_mail) {

  $headers = '';
  $message = '';

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
    //$mail->addReplyTo('noreply@crowdcc.com', 'no reply');

    /* Set who the message is to be sent to */
    // $mail->addAddress($to, $fullname);
    $mail->addAddress($to_mail);

    $subject = '@' . $frm . " shared a tweet";
    
    /* Set the subject line */
    $mail->Subject =  $subject;

    $uri = 'http://'. $_SERVER['HTTP_HOST'] ;

    $mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
    <meta name="viewport" content="width=device-width" />
    <title>' . $subject . '</title>
    </head>
    <body bgcolor="#FFFFFF" style="-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;">
    <!-- header -->
    <table class="head-wrap" style="max-width: 95%;" cellspacing="0" cellpadding="0" border="0" align="center" >
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
    <a href="http://www.crowdcc.com">
    <img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" style="width:135px;height:34px;position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;cursor:pointer;">
    </a>   
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
    <h4>Message sent from <a href="https://twitter/'. $frm .'" style="font-weight:normal;text-decoration:none;">@' . $frm . '</a></h4>
    <p>
    ' . $msg_txt . '
    <table class="head-wrap" style="max-width: 95%; border-collapse:collapse" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody>
    <tr>
    <td valign="top" colspan="2">&nbsp;</td>
    </tr>
    <tr>
    <td valign="top">
    <a target="_blank" href="'. $twt_usr_url .'">
    <img width="42" height="42" style="border-radius: 3px; margin-right: 7px; border: medium none;" alt="twitter icon" src="'. $twt_img .'">
    </a>
    </td>
    <td valign="top">
    <strong>
    <a class="urlprofilelink" style="color: #444444; text-decoration: none;" target="_blank" title="'. $twt_usr .'" href="'. $twt_usr_url .'">'. $twt_usr .'</a>
    </strong>
    <span class="tweet-time" style="float: right; font-size: 12px; margin-right: 0; position: relative;">
    <a target="_blank" style="color: #878787;text-decoration: none;" href="'. $twt_href .'" title="'. $twt_title .'">'. $twt_atag .'</a>
    </span>
    <br>
    '. $twt_html .'
    <span style="margin-left:7px"></span>
    </td>
    </tr>
    </table>
    </p>
    <p style="padding-bottom:10px;"><b><a href="http://crowdcc.com" style="text-decoration: none; color:#000000; cursor:pointer;">Sent via crowdcc</a></b></p>
    <h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">Crowdcc</a>
    </h4>
    <p style="padding-bottom:10px;">
    <tr>
    <td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
    </tr>
    <tr>
    <td valign="top" height="20" style="min-height:20px"> </td>
    </tr>
    <td valign="top" colspan="2">
    <span>Have a question or just want to say hello? <a href="https://twitter.com/crowdccHQ" style="cursor: pointer;">tweet us</a></span>
    <p style="padding-top:10px;padding-bottom:10px;">
    <!-- footer -->
    <span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This is an automated message sent from crowdcc, please don\'t reply directly to this email.</span>
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
        rtnwebapp('error' , 'mail_error_' . $to_mail , 'post');
        // to the email address provided, please try again!                               
    } else {
        rtnwebapp('cm' , 'mail_success_200' , 'post');
        // to the email address provided, success.        
    }

  }

}  


?>