<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* app.error.php  * crowdcc: error handler
*
*
*/

/* found.app.notice * log * log failure outside the JS console ( please comment out / remove later ) */
// require_once('found.app.notice.php');

/* optional * phpmailer * auth mail lib files * ccmail error logs to admin user * see cemail */
// require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');

/* Error log info : Many stock installations of PHP do not set an error_log value. error_log -- no value
   If your error_log value is blank, PHP will pass the error message onto the web server.
   This means your PHP error message will end up in your web server’s error log.
   In apache, this is configured (in httpd.conf or one of its include files) with;
   
   ErrorLog "/var/log/apache2/error_log"
   ErrorLog /var/www/crowdcc.com/logs/apache.error.log

   DEBUG_ERROR 0, leaves all errors in this file by default, which maybe useful for development as you can
   see ALL errors together and any startup or fatal errors are displayed to the screen.

   DEBUG_ERROR 1, hides display or fatal errors and by using an error handle you gain more contol over the
   type of error useful for production.   
*/

/* db config file for app.error.php ( app errorhandle ) */
/* require_once('db.app.error.php'); */

function simpleflakerr($timestamp = null, $randombits = null, $epoch = 946702800) {
  /**
   * Generate a 64 bit, roughly-ordered, globally-unique ID.
   *
   * @param int|null $timestamp
   * @param int|null $randomBits
   * @param int $epoch
   * @return int
  */

  /* twitter epoch: 1288834974657 (Long) */
  $epoch = 946702800;
  $timestamp_shift = 23;
  $random_max_value = 4194303;
 
  $timestamp = ($timestamp !== null) ? $timestamp: microtime(true);
  $timestamp -= $epoch;
  $timestamp *= 1000;
  $timestamp = (int) $timestamp;

  if ($randombits !== null) {
      $randombits = (int) $randombits;
  } else if (function_exists("mt_rand")) {
      $randombits = mt_rand(0, $random_max_value);
  } else {
      $randombits = (int) rand() * $random_max_value;
  }

  $flake = ($timestamp <<  $timestamp_shift) | $randombits . mt_rand_str_err(1);
  return $flake;

}

function mt_rand_str_err ($l, $c = '123456789') {
  for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
  return $s;
}

function log_errorhand($timelocal, $function, $query, $mysqli_hand) {

  /* $query is an object, to get query string, you will require seperate $query variable to pass back
     'query: ' . serialize($query) . "\n" . */
   
  mysqli_report(MYSQLI_REPORT_OFF);         /* turn off irritating default messages */

  $err = ''; $err_trace = '';

    switch(true) {

        case($mysqli_hand->error):

          try {   
               throw new Exception();   
          }    catch(Exception $err) {
               $err_trace = nl2br($err->getTraceAsString());
          }

          $fp = fopen($_SERVER["DOCUMENT_ROOT"].'/../logs/' . 'db.app.error.log','a');
          fwrite($fp,

          'UTC time: ' . $timelocal . "\n" .
          /* 'query: ' . $query . "\n" . */
          'error no: ' . htmlspecialchars($mysqli_hand->errno)  . "\n" .
          'file name: ' . $_SERVER["SCRIPT_NAME"] . ' ==> ' . $function . '() failed ' . "\n" .
          'error: ' . htmlspecialchars($mysqli_hand->error) . "\n" .
          'error trace: ' . "\n" . html2txt_err($err_trace) 

          );  
             
          fclose($fp);

          $err = null; $err_trace = null;
        
      break;
  }
}

function html2txt_err($document) {
  $search = array('@<script[^>]*?>.*?</script>@si',   // Strip out javascript
                      '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                      '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                      '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
                      );
  $text = preg_replace($search, '', $document);
  return $text;
}

define('DEBUG_ERROR', 1 ); /* 1 true on production, 0 false in development */

if (DEBUG_ERROR) { 
    /* true on production, false in development * include * php.ini possible shared server webapp overrides */

    /* ini_set('error_reporting', E_ALL | E_STRICT); */
    ini_set('error_reporting', E_ALL);                                            /* all errors logged * E_ALL includes E_STRICT in 5.4.0 and up. */
    ini_set("display_errors", 0);                                                 /* display errors displayed to user. */
    ini_set('display_startup_errors',0);                                          /* display errors to user on start up. */
    ini_set('log_errors', 1);                                                     /* log all errors  */    
    /* set_error_handler('ccerrhandler',E_ALL & ~E_NOTICE & ~E_USER_NOTICE);      /* log all errors, except notices and user notices  */
    set_error_handler('cchand');                                                  /* report all PHP errors */
    /* set_exception_handler( "log_exception" ); */
    register_shutdown_function('ccdown');                                     /* fatal execution shutdown */
}

function cchand( $error_level, $error_message, $error_file, $error_line, $timezone = "Europe/London" ) {

  $errormessage = 'unknown error';
  $ip_address = $_SERVER['REMOTE_ADDR'];

  /* get utc timezone */
  // date_default_timezone_set("UTC");
  $timezone = date_default_timezone_get();
  $time = date("Y-m-d H:i:s", time()); 

  /* get local timezone */
  $now = time();
  
  /* reset to default timezone */
  // date_default_timezone_set($timezone);
  $logdate =  date("D M j G:i:s Y");
  $timelocal = date("Y-m-d H:i:s");

  switch ($error_level) {
 
    case E_PARSE:
         $error_notice = 'parse';
    break;
    case E_ERROR:
    case E_CORE_ERROR:
    case E_COMPILE_ERROR:
    case E_USER_ERROR:
         // error_log($error, 'fatal');
         $error_notice = 'fatal';
    break;
    case E_RECOVERABLE_ERROR:
         // error_log($error, "error");
         $error_notice = 'error';
    break;
    case E_WARNING:
    case E_CORE_WARNING:
    case E_COMPILE_WARNING:
    case E_USER_WARNING:
         $error_notice = 'warning';
    break;
    case E_NOTICE:
    case E_USER_NOTICE:
         $error_notice = 'notice';
    break;
    case E_STRICT:
         $error_notice = 'debug';
    break;
    default:
         $error_notice = 'unknown error';
    }

    $error = "[" . $logdate . "] [" . $error_notice . "] [client " . $ip_address . "] PHP " . $error_notice . " error: " . mb_strtolower($error_message,'UTF-8') . " in " . $error_file . " on line " . $error_line ."\n";

    $error_log =  "[". $error_notice . "] [client PHP " . $error_notice . " error: " . mb_strtolower($error_message,'UTF-8') . " in " . $error_file . " on line " . $error_line ;

    error_log( $error_log ); /* native php error log * ensure that error logging is turned on and that you uncomment the error_log directive in php.ini. */

    //ccscreen( $error );
    
    ccdb( $error_notice, $error_message, $error_file, $error_line , $ip_address , $time, $timezone , $timelocal );
    
    cclog( $error );

    // cemail('glynthoma@gmail.com','Glyn Thomas', $error);       /* email php errors to webapp admin person */

    // phpmail('glynthoma@gmail.com',$error);                   /* email php errors to webapp admin person */

}

/* ccdown() * checks for a fatal error, work around for set_error_handler not working on fatal errors. */

function ccdown() {
  $last_error = error_get_last();
  if ($last_error['type'] === E_ERROR) {
    /* fatal error */
    cchand( E_ERROR, $last_error['message'], $last_error['file'], $last_error['line'], $timezone = "Europe/London" );
  }
}

function ccscreen( $error ) {
  echo "<div style='font-family: Sans-Serif;'>". $error ."</div> \n";
}

function ccdb( $error_notice, $error_message, $error_file, $error_line , $ip_address , $time, $timezone = "Europe/London", $timelocal) {
  
  $error_message = mb_strtolower($error_message,'UTF-8');
  
  $mysqli_hand = new mysqli("localhost", "ccerror", "back2error4m32u", "crowdcc_errorhandle");

  mysqli_query($mysqli_hand, 'SET NAMES "utf8"');
  mysqli_query($mysqli_hand, 'SET CHARACTER SET "utf8"');
  mysqli_query($mysqli_hand, 'SET character_set_results = "utf8",' .
  'character_set_client = "utf8", character_set_connection = "utf8",' .
  'character_set_database = "utf8", character_set_server = "utf8"');

  /* crowdcc db errorhandle logging */

  date_default_timezone_set("Europe/London");
  $timezone = 'Europe/London';
  $now = time();
  $date = new DateTime(null, new DateTimeZone($timezone));
  $timelocal = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));

  log_errorhand($timelocal,'connect', mysqli_connect_errno(), $mysqli_hand);

  $error_id = simpleflakerr();

  if  ($i_stmt01 = $mysqli_hand->prepare("INSERT INTO errorhandle (error_id ,error_notice, error_message, error_file, error_line, ip_address, time, timezone, timelocal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
       
       $i_stmt01->bind_param('sssssssss', $error_id, $error_notice, $error_message,  $error_file, $error_line, $ip_address, $time, $timezone, $timelocal);
       log_errorhand($timelocal,'bind_param', $i_stmt01, $mysqli_hand); 
       /* execute the prepared query. */
       $i_stmt01->execute();
       log_errorhand($timelocal,'execute', $i_stmt01, $mysqli_hand);
       // $i_stmt01->store_result();
       $i_stmt01->close();

  } else {
       
       log_errorhand($timelocal,'prepare', $i_stmt01, $mysqli_hand);
       cclog( 'db errorhandle database cannot be updated error!' );
       $i_stmt01->close();

  }
}

function cclog( $error ) {
  file_put_contents($_SERVER["DOCUMENT_ROOT"].'/../logs/php.app.error.log', $error, FILE_APPEND);
}


function cemail($to, $fullname, $error) {

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
// date_default_timezone_set('Etc/UTC');

if (date_default_timezone_get() === '') {
    date_default_timezone_set('Europe/London');
}

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

/* Username to use for SMTP authentication - use full email address for gmail */
$mail->Username = "cccrowd@gmail.com";

/* Password to use for SMTP authentication */
$mail->Password = "back2email";

//Set who the message is to be sent from
$mail->setFrom('errors@crowdcc.com', 'Crowdcc error report');

//Set an alternative reply-to address
$mail->addReplyTo('bounce@crowdcc.com', 'no reply');

//Set who the message is to be sent to
$mail->addAddress($to, $fullname);

//Set the subject line
$mail->Subject = 'crowdcc server error';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body

// $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
  <html>
  <head>
  <meta name="viewport" content="width=device-width" />
  <title>Crowdcc server error</title>
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
  <td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
  </tr>
  <tr>
  <td valign="top" height="20" style="min-height:20px"> </td>
  </tr>
  <tr>
  <td valign="top" colspan="2">
  <h4>Hi</h4>
  <p>Oops, looks like we have found a php error</b>.
  <p>
  This error is recorded here;
  </p>
  <p> ' . $error . '</p>    
  <p>Please check your webapp, error_php_log.txt and server php log files for further details.</p>
  <h4>
  <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
  </h4>
  <p style="padding-bottom:10px;">
  <tr>
  <td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
  </tr>
  <tr>
  <td valign="top" height="20" style="min-height:20px"> </td>
  </tr>
  <td valign="top" colspan="2"> 
  <span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
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

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

//Attach an image file
// $mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
  if (!$mail->send()) {
     // echo "Mailer Error: " . $mail->ErrorInfo;
     log_found('mail check', ' mail fail ' , 'errorhandle', __LINE__ );
  } else {
     // echo "Message sent!";
     log_found('mail check', ' mail sent' , 'errorhandle', __LINE__ );
  }
}


function phpmail( $to, $error ) {
  /* postfix must be enabled and configured */
  $subject = "crowdcc php error";
  $uri = 'http://'. $_SERVER['HTTP_HOST'] ;
  $message = '
  <html>
  <head>
  <meta name="viewport" content="width=device-width" />
  <title>crowdcc PHP error</title>
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
  <td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
  </tr>
  <tr>
  <td valign="top" height="20" style="min-height:20px"> </td>
  </tr>
  <tr>
  <td valign="top" colspan="2">
  <h4>Hi</h4>
  <p>Oops, looks like we have found a php error</b>.
  <p>
  This error is recorded here;
  </p>
  <p> ' . $error . '</p>    
  <p>Please check your webapp, error_php_log.txt and server php log files for further details.</p>
  <h4>
  <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">The Crowdcc Team</a>
  </h4>
  <p style="padding-bottom:10px;">
  <tr>
  <td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
  </tr>
  <tr>
  <td valign="top" height="20" style="min-height:20px"> </td>
  </tr>
  <td valign="top" colspan="2"> 
  <span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
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
  
  $headers  = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
  $headers .= "Organization: crowdcc.com\r\n";
  $headers .= "From: errors@crowdcc.com <errors@crowdcc.com>\r\n";
  $headers .= "Reply-To: The Sender <bounce@crowdcc.com>\r\n";
  $headers .= "Return-path: <bounce@crowdcc.com>\r\n";  
  $headers .= "Errors-To: <bounce@crowdcc.com>\r\n";

  if (mail($to,$subject,$message,$headers,"-fbounce@crowdcc.com")) {
  } else {
    echo ('email fail * please check postfix'); /* email pass (found in db, pass to token function), but have failed to be able to send it to the email address provided, please try again ! */
  }

}

?>
