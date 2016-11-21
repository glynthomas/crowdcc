<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* db.app.shed.conn.php  * MySQL connection parameters for crowdcc database server
*
*
*/
  
  /* server path for scripts within the framework to reference each other */   
  
  define('LOG_SHED_DIR', $_SERVER["DOCUMENT_ROOT"].'/../logs/'); /* use define('LOG_API_DIR', $_SERVER["DOCUMENT_ROOT"] . '/log/'); ensure log file is R/W */
  define("HOST_SHED", "localhost");                              // The host you want to connect to.
 
  define("USER_SHED", "ccshed");                                 // The database username
  define("PASSWORD_SHED", "back2shed4m32u");                     // The database password. 
  define("DATABASE_SHED", "crowdcc_shed");                       // The database name.

  //define("USER_SHED", "root");                                     // The test database username
  //define("PASSWORD_SHED", "beta");                                 // The test database password. 
  //define("DATABASE_SHED", "shed");                                 // The test database name.

  define('TIME_WINDOW', 3600);// denomination is in seconds, so 3600 = 60 minute time frame window

  define('TIME_ZONE', 'Europe/London');// set time zone
  define('ERROR_LOG', TRUE);// prints successful runs and errors to log table

  // ** replaced in firepjs ( see move.list.org ) for constants.inc.php | include($_SERVER["DOCUMENT_ROOT"].'/../slib/phpjobscheduler.php');
  /* define('LOCATION', dirname(__FILE__) ."/");// used to open local files */
  define('LOCATION', $_SERVER["DOCUMENT_ROOT"].'/../slib/');// used to open local files

  define('PJS_TABLE','phpjobscheduler');// pjs table name
  define('LOGS_TABLE','phpjobscheduler_logs');// logs table name

  define('MAX_ERROR_LOG_LENGTH',1200);// maximum string length of output to record in error log table
  define('DEBUG', TRUE);                                         // set to false when done testing


  class DBi {
    public static $shed;
  }
  
  DBi::$shed = new mysqli(HOST_SHED, USER_SHED, PASSWORD_SHED, DATABASE_SHED);

  mysqli_query(DBi::$shed, 'SET NAMES "utf8"');
  mysqli_query(DBi::$shed, 'SET CHARACTER SET "utf8"');
  mysqli_query(DBi::$shed, 'SET character_set_results = "utf8",' .
  'character_set_client = "utf8", character_set_connection = "utf8",' .
  'character_set_database = "utf8", character_set_server = "utf8"');

  /* crowdcc db SHED error logging */

  date_default_timezone_set(TIME_ZONE);
  /* $timezone = date_default_timezone_get(); */

  $timezone = 'Europe/London';
  $now = time();
  $date = new DateTime(null, new DateTimeZone($timezone));
  $timelocal = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));
  /* date_default_timezone_set("UTC"); */


  log_error_shed($timelocal,'connect', mysqli_connect_errno());

       
  /* If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
     Write any errors into a text log include the date, calling script, function called, and query
     this should be used to report errors on insert or replace db changes only */
  
  function log_error_shed($timelocal, $function, $query) {

    /* $query is an object, to get query string, you will require seperate $query variable to pass back
    'query: ' . serialize($query) . "\n" . */
   
    mysqli_report(MYSQLI_REPORT_OFF);         /* turn off irritating default messages */

    $err = ''; $err_trace = '';

      switch(true) {

        case(DBi::$shed->error):

             try {   
                  throw new Exception();   
             }    catch(Exception $err) {
                  $err_trace = nl2br($err->getTraceAsString());
             }

             $fp = fopen(LOG_SHED_DIR . 'db.app.shed.conn.log','a');
             fwrite($fp,

             'UTC time : ' . $timelocal . "\n" . 
             'error no: ' . htmlspecialchars(DBi::$shed->errno)  . "\n" .
             'file name: ' . $_SERVER["SCRIPT_NAME"] . ' ==> ' . $function . '() failed ' . "\n" .
             'error: ' . htmlspecialchars(DBi::$shed->error) . "\n" .
             'error trace: ' . "\n" . html2txt_shed($err_trace) 

              );  
             
             fclose($fp);

             $err = null; $err_trace = null;
        
        break;
      }
      
  }

  function html2txt_shed($document) {
      $search = array('@<script[^>]*?>.*?</script>@si',   // Strip out javascript
                      '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                      '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                      '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
                      );
      $text = preg_replace($search, '', $document);
    return $text;
  } 
