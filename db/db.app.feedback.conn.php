<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* db.app.feedback.conn.php  * MySQL connection parameters for crowdcc database server
*
*
*/
  
  /* server path for scripts within the framework to reference each other */

  define('LOG_FEEDBACK_DIR', $_SERVER["DOCUMENT_ROOT"].'/../logs/');  /* use define('LOG_FEEDBACK_DIR', $_SERVER["DOCUMENT_ROOT"] . '/log/'); ensure log file is R/W */
  define("HOST_API", "localhost");                                    /* the host you want to connect to. */
  define("USER_API", "ccfeedb");                                      /* the database username */
  define("PASSWORD_API", "back2feed4m32u");                           /* the database password. */ 
  define("DATABASE_API", "crowdcc_feedback");                         /* the database name. */

  $mysqli_feedback = new mysqli(HOST_API, USER_API, PASSWORD_API, DATABASE_API);

  mysqli_query($mysqli_feedback, 'SET NAMES "utf8"');
  mysqli_query($mysqli_feedback, 'SET CHARACTER SET "utf8"');
  mysqli_query($mysqli_feedback, 'SET character_set_results = "utf8",' .
  'character_set_client = "utf8", character_set_connection = "utf8",' .
  'character_set_database = "utf8", character_set_server = "utf8"');

  /* crowdcc db API error logging */

  date_default_timezone_set("Europe/London");
  /* $timezone = date_default_timezone_get(); */
  $timezone = 'Europe/London';
  $now = time();
  $date = new DateTime(null, new DateTimeZone($timezone));
  $timelocal = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));
  /* date_default_timezone_set("UTC"); */

  // switch(true) {
      // case(mysqli_connect_errno()):
  log_error_feedback($timelocal,'connect', mysqli_connect_errno(), $mysqli_feedback);
      // break;
  // }
       
  date_default_timezone_set("UTC");

  /* If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
     Write any errors into a text log include the date, calling script, function called, and query
     this should be used to report errors on insert or replace db changes only */
  
  function log_error_feedback($timelocal, $function, $query, $mysqli_feedback) {

    /* $query is an object, to get query string, you will require seperate $query variable to pass back
    'query: ' . serialize($query) . "\n" . */
   
    mysqli_report(MYSQLI_REPORT_OFF);         /* turn off irritating default messages */

    $err = ''; $err_trace = '';

      switch(true) {

        case($mysqli_feedback->error):

             try {   
                  throw new Exception();   
             }    catch(Exception $err) {
                  $err_trace = nl2br($err->getTraceAsString());
             }

             $fp = fopen(LOG_FEEDBACK_DIR . 'db.app.feedback.conn.log','a');
             fwrite($fp,

             'UTC time: ' . $timelocal . "\n" .
             'error no: ' . htmlspecialchars($mysqli_feedback->errno)  . "\n" .
             'file name: ' . $_SERVER["SCRIPT_NAME"] . ' ==> ' . $function . '() failed ' . "\n" .
             'error: ' . htmlspecialchars($mysqli_feedback->error) . "\n" .
             'error trace: ' . "\n" . html2txt_feedback($err_trace) 

              );  
             
             fclose($fp);

             $err = null; $err_trace = null;
        
        break;
      }
      
  }


  function html2txt_feedback($document) {
      $search = array('@<script[^>]*?>.*?</script>@si',   /* Strip out javascript */
                      '@<[\/\!]*?[^<>]*?>@si',            /* Strip out HTML tags */
                      '@<style[^>]*?>.*?</style>@siU',    /* Strip style tags properly */
                      '@<![\s\S]*?--[ \t\n\r]*>@'         /* Strip multi-line comments including CDATA */
                      );
      $text = preg_replace($search, '', $document);
    return $text;
  } 
