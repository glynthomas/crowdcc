<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* db.app.error.php  * MySQL connection parameters for crowdcc database server ( error handler )
*
*
*/
  
  /* server path for scripts within the framework to reference each other */      
  
  define('LOG_HAND_DIR', $_SERVER["DOCUMENT_ROOT"].'/../logs/');         /* use define('LOG_HAND_DIR', $_SERVER["DOCUMENT_ROOT"] . '/log/'); ensure log file is R/W */
  define("HOST_HAND", "localhost");                                      // The host you want to connect to.
  define("USER_HAND", "ccsauce");                                        // The database username
  define("PASSWORD_HAND", "back2error4m32u");                            // The database password. 
  define("DATABASE_HAND", "crowdcc_errorhandle");                        // The database name.

  $mysqli_hand = new mysqli(HOST_HAND, USER_HAND, PASSWORD_HAND, DATABASE_HAND);

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
       
  /* If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
     Write any errors into a text log include the date, calling script, function called, and query
     this should be used to report errors on insert or replace db changes only */
  
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

             $fp = fopen(LOG_HAND_DIR . 'db.app.error.log','a');
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
