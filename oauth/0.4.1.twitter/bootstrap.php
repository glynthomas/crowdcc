<?php

/**
* bootstrap required to provide consumer crowdcc web app keys for user twitter/crowdcc signin.
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* @param string twitter app keys.
* @return void
*
*/

/* access to crowdcc err handle */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/app.error.php');

/* manage crowdcc and associated twitter API keys */

/* crowdcc app * crowdcc.apc * crowdcc.dev * crowdcc.com * twitter API access */
define('CONSUMER_KEY', 'vvZlSJ7RFXrMS34IbmSoF9ztJ');
define('CONSUMER_SECRET', '6k6mvtxceSX0K8z1wBT7sWMmsf43M6S4ZXUwd2nwRSowYnNHmi');
define('OAUTH_CALLBACK', "http://" . $_SERVER['HTTP_HOST'] . "/callback.php");

/* alt crowdcc * crowdcc.com * internet twitter API access : crowdcc.com app development. */
// define('CONSUMER_KEY', 'xInEG65wyXg1BOVOazNmDQ');
// define('CONSUMER_SECRET', 'IaYmFMhMQzvIoK5JYOeE4PQFK5k1YwB6ZHasdhDYHjw');
// define('OAUTH_CALLBACK', "http://" . $_SERVER['HTTP_HOST'] . "/callback.php");

/*
define('PROXY', getenv('TEST_CURLOPT_PROXY'));
define('PROXYUSERPWD', getenv('TEST_CURLOPT_PROXYUSERPWD'));
define('PROXYPORT', getenv('TEST_CURLOPT_PROXYPORT'));
*/