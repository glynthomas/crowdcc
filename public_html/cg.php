<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* simple * connection get * cg * check connection to  twitter * google 
*
* use : cc_connect default server check to twitter
*
* returns true * no network connection (to default twitter) 
* returns false * connected (to default twitter)
*
* if ( cc_connect() ) { rtnwebapp('error' , 'error_tamper' , 'post', '', ''); exit(); }
*
*/

/* access to crowdcc err handle */  
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/app.error.php');

/* load crowdcc err handle */
//require_once('db/errorhandle.php');

/* print_r('<p> connect check :'); */
/* print_r( cc_connect() ); */

function cc_connect($scheckhost = 'www.twitter.com') {
   
  $fp = @fsockopen($scheckhost, 80, $errno, $errstr, 5);
        @fclose($fp);

  switch (true) {

  	case(!$fp):
  		  /* echo "$errstr ($errno)<br />\n"; */
  		  /* echo "true * not connected <br />\n"; */
        return true;
  	break;

  	case($fp):
  		  /* echo "false * connected <br />\n"; */
        return false;
  	break;
  	
  }

  $fp = null;

}



?>
