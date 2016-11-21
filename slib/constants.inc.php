<?php
// ---------------------------------------------------------
 $app_name = "phpJobScheduler";
 $phpJobScheduler_version = "3.7";
// ---------------------------------------------------------


  define('TIME_WINDOW', 300);// denomination is in seconds, so 3600 = 60 minute time frame window

  define('TIME_ZONE', 'Europe/London');// set time zone

  define('ERROR_LOG', TRUE);// prints successful runs and errors to log table

  // ** replaced in firepjs ( see move.list.org ) for constants.inc.php | include($_SERVER["DOCUMENT_ROOT"].'/../slib/phpjobscheduler.php');
  define('LOCATION', dirname(__FILE__) ."/");// used to open local files

  define('PJS_TABLE','phpjobscheduler');// pjs table name
  define('LOGS_TABLE','phpjobscheduler_logs');// logs table name

  define('MAX_ERROR_LOG_LENGTH',1200);// maximum string length of output to record in error log table

?>
