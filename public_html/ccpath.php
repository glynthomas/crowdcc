<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* ccpath required to access functions classes - require_once('autoloader.php');.
*
* @param string relative path names.
* @return void
*
*/

/* if relative paths are not working as expected ... require __DIR__ . "./oauth/twitter/TwitterOAuth.php " */

/* root of www, i.e  /Users/macbook/Sites/crowdcc.err/public_html */
$docroot = $_SERVER["DOCUMENT_ROOT"];                       

/* app error handler */
require_once($docroot .'/../db/app.error.php');

/* crypt lib * require_once('crypt/RSA.php'); */
require_once($docroot .'/../crypt/RSA.php');             

/* common app functions */
require_once($docroot .'/../db/app.functions.php');        


$docroot = null;

?>