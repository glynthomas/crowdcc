<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
*/

/* access to crowdcc err handle */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/app.error.php');

/* load crowdcc err handle */
//require_once('db/errorhandle.php');

include 'oauth/twitter/Util/ErrHandler.php';

use crowdcc\TwitterOAuth\Util\ErrHandler;

ErrHandler::errhandle(28);