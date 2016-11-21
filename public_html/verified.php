<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* successfully authenticated with Twitter. Access tokens saved to session and DB.
*
*/

/* load crowdcc app.error ( error handle ) && app.functions ( general app functions ) */
require_once('ccpath.php');

/* load crowdcc err handle */
// require_once('db/errorhandle.php');

/* load required lib files. */
 
/* access to crowdcc signin db */ 
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

secure_session_start(); // custom secure way of starting a php session.

/* twitter oauth lib * source: https://twitteroauth.com * version: v0.4.1 * modified v0.1 */
require_once('tweetpath.php');
use crowdcc\TwitterOAuth\TwitterOAuth;


/* If the oauth_token is old redirect to the connect page. */
//if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    // header('Location: ./destroysessions.php');
    /* Load and clear sessions */
    if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
    session_destroy();
	header('Location: ./');
	exit();
}

/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];
 
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
 
/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

$ccode = $_SESSION['ccode'];
$ucode = $_SESSION['ucode'];
$ceode = $_SESSION['ceode'];

$fcode = $_SESSION['fcode'];

// $api_key = $_SESSION['ccid'];

$cauth = $_SESSION['cauth_token'];

/* $ceode = base64_encode($ceode); check -> signin.js line 168 :: 169 clear verify js script, clear _ccode */

$ccc_token = array();
$ccc_msg   = array();

// new way ...

$usr = array('ccuser' => $ccode,'ccname' => base64_encode($ucode),'ccmail0' => base64_encode($ceode),'ccmail1' => base64_encode($ceode), 'ccmail2' => base64_encode($ceode), 'ccfollow' => $fcode, 'cctoken' => $cauth, 'ccspace' => 0, 'cclimit' => 0 );
$output = array('usr' => $usr,'content' => $content);


$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;

/* $ccheck = $_SESSION['cauth_token_secret'];
   $ccheck = ccrypt( $_SESSION['cauth_token_secret'], 'en');
   $ccheck = ccrypt( $ccheck, 'de'); */

/* options -> setcookie('cauth_token', $secret, time()+60*60*24*365, '/', $domain, 'secure', 'httponly'); */

/* setcookie('cauth_token', $_SESSION['cauth_token_secret'], time()+60*60*24*365, '/', $domain, true, true); */

setcookie('cauth_token', $_SESSION['cauth_token_secret'], time()+60*60*24*365, '/', $domain, false, true);
setcookie('ccid', $_SESSION['ccid'], time()+60*60*24*365, '/', $domain, false, true);

// old way ...

//   $output = array(
//      'user'    => $ccode,
//    	'ucode'   => base64_encode($ucode),
//    	'ecode'   => base64_encode($ceode),
//      'fcode'   => $fcode,
//    	'content' => $content
//	 );

unset($_SESSION['ccid']);

unset($_SESSION['cauth_token_secret']);
unset($_SESSION['access_token']);

unset($_SESSION['ccode']);
unset($_SESSION['ceode']);

unset($_SESSION['fcode']);

/* Some example calls */
//$connection->get('users/show', array('screen_name' => 'PHPGang'));
//$connection->post('statuses/update', array('status' => "PHP Gang Testing..."));
//$connection->post('statuses/destroy', array('id' => 533297770));
//$connection->post('friendships/create', array('id' => 9322192));
//$connection->post('friendships/destroy', array('id' => 9436992));

include('html.inc');