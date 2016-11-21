<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* tweetpath required to access classes, replace for autoloader.php - require_once('autoloader.php');.
*
* @param string relative path names.
* @return void
*
*/


/* root of www, i.e  /Users/macbook/Sites/crowdcc.err/public_html */
$docroot = $_SERVER["DOCUMENT_ROOT"];  

/* app error handler */
require_once($docroot .'/../db/app.error.php');

/* load crowdcc err handle */
//require_once('db/errorhandle.php');

/* if relative paths are not working as expected ... require __DIR__ . "./oauth/twitter/TwitterOAuth.php " */

require_once($docroot .'/../oauth/0.4.1.twitter/bootstrap.php');

require($docroot .'/../oauth/0.4.1.twitter/TwitterOAuth.php');
require($docroot .'/../oauth/0.4.1.twitter/SignatureMethod.php');
require($docroot .'/../oauth/0.4.1.twitter/HmacSha1.php');
require($docroot .'/../oauth/0.4.1.twitter/Consumer.php');
require($docroot .'/../oauth/0.4.1.twitter/Token.php');
require($docroot .'/../oauth/0.4.1.twitter/Request.php');
require($docroot .'/../oauth/0.4.1.twitter/Util.php');
require($docroot .'/../oauth/0.4.1.twitter/Util/JsonDecoder.php');

?>