<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* found.app.notice.php  * simple record php found log
*
*
*/

 /*

  src : http://stackoverflow.com/questions/4645082/get-absolute-path-of-current-script

  ===== common codes ====

  For example, you are executing http://example.com/folder1/folder2/yourfile.php?var=blabla#123

  $_SERVER["DOCUMENT_ROOT"] === /home/user/www
  $_SERVER["SERVER_ADDR"]   === 143.34.112.23
  $_SERVER['HTTP_HOST']     === example.com (or with WWW)
  $_SERVER["REQUEST_URI"]   === /folder1/folder2/yourfile.php?var=blabla#123
  __FILE__                  === /home/user/www/folder1/folder2/yourfile.php  --->//p.s. ON WINDOWS SERVERS, instead of / is \
  basename(__FILE__)        === yourfile.php
  __DIR__                   === /home/user/www/folder1/folder2 [same: dirname(__FILE__)]
  $_SERVER["QUERY_STRING"]  === var=blabla#123

  $_SERVER["REQUEST_URI"]   === /folder1/folder2/yourfile.php?var=blabla#123 
  parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)  === /folder1/folder2/yourfile.php 
  $_SERVER["PHP_SELF"]      === /folder1/folder2/yourfile.php

  //if "parentfile.php" includes this "yourfile.php"(and inside it are the codes written), and "parentfile.php?a=123" is opened, then
  $_SERVER["PHP_SELF"]       === /parentfile.php
  $_SERVER["REQUEST_URI"]    === /parentfile.php?a=123
  $_SERVER["SCRIPT_FILENAME"]=== /home/user/www/parentfile.php
  str_replace($_SERVER["DOCUMENT_ROOT"],'', str_replace('\\','/',__FILE__ ) )  === /folder1/folder2/yourfile.php

  define('domainURL',                 (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' ).$_SERVER['HTTP_HOST']);
  define('homeURL',                   home_url());
  define('homeFOLD',                  str_replace(domainURL,'',   homeURL));
  define('requestURI',                $_SERVER["REQUEST_URI"]);               
  define('requestURIfromHome', str_replace(homeFOLD, '',requestURI) );    
  define('requestURIfromHomeWithoutParameters',parse_url(requestURIfromHome, PHP_URL_PATH));
  define('currentURL',                domainURL.requestURI);
  define('THEME_URL_nodomain',        str_replace(domainURL, '', get_template_directory_uri()) );
  define('PLUGIN_URL_nodomain',       str_replace(domainURL, '', plugin_dir_url(__FILE__)) );

 */

 /* server path for scripts within the framework to reference each other */

  /* found log * usage * example */

  /* log_found('condition1', 'condition2', 'fail_log_working_test', __LINE__ ); */

  /* found log * log failure outside the JS console ( please comment out / remove later ) */
  /* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */
  /* log_found('re-favor_error', ' re-favour lastHttpCode()' . $connection->lastHttpCode() , 'start:tw.php', __LINE__ ); */

  /* define('LOG_NOTICE_FOUND', '../logs/');  // or use define('LOG_CODE_DIR', $_SERVER["DOCUMENT_ROOT"] . '../logs/'); ensure log file is R/W */
  define('LOG_NOTICE_FOUND', $_SERVER["DOCUMENT_ROOT"].'/../logs/');  /* use define('LOG_CODE_DIR', $_SERVER["DOCUMENT_ROOT"] . '../logs/'); ensure log file is R/W */

  /* log_found('condition1', 'condition2', 'fail_log_working_test', __LINE__ ); */

  function log_found($cond1, $cond2, $function, $line) {

   date_default_timezone_set("Europe/London");
   $timezone = 'Europe/London';
   $now = time();
   $date = new DateTime(null, new DateTimeZone($timezone));
   $timelocal = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));

   $fp = fopen(LOG_NOTICE_FOUND . 'found.app.notice.log','a');
	 
	 fwrite($fp,

             "\n" .
             'UTC time : ' . $timelocal . "\n" . 
             'condition 1: ' .$cond1 . "\n" .
             'condition 2: ' .$cond2 . "\n" .
             'file name: ' . $_SERVER["SCRIPT_NAME"] . ' ==> ' . $function . '()' . "\n" .
             'found @ : line ' . $line . "\n" .
             "\n" . '==================' . "\n"

              );  
	       		 
   fclose($fp);

  }