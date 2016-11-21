<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* functions.php  * functions for crowdcc platform
*
*
*/

require_once('secure_session.php');

function decrypt($msg) {
    $rsa = new Crypt_RSA();
    $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
    $rsa->loadKey(KEY_PRIVATE, CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
    $s = new Math_BigInteger($msg, 16);
    return $rsa->decrypt($s->toBytes());
}

function secure_session_start() {

  if(!isset($_SESSION)) {

  /*
  set in php ini ...
  expose_php = Off
  display_errors = Off
  log_errors = On
  register_globals = Off
  */
  
  $session_name = '_crowdcc_sess';               
  /* set a custom session name */
  
  $secure = FALSE;                               
  /* set SSL level, true if using https. */
  $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

  /* Make sure the session cookie is not accessable via javascript. */
  $httponly = TRUE;

  /* Set the domain to default to the current domain. */
  /* $domain = isset($domain) ? $domain : isset($_SERVER['SERVER_NAME']); */
  /* $domain = '.example.com'; // cookies be available across all sub-domains */
    
  $domain =  $_SERVER['SERVER_NAME'];  // http://localhost/

  /* set the domain path (http page path). */
  $path = '/';

  /* Set the cookie lifetime for domain and path. Set to 0 if you want the session cookie to be set until the user closes */
  /* the browser. Use time() + seconds otherwise. */
  /* $lifetime = 0; */

  $lifetime = 2700;

  /* forces sessions not to include the identifier in the URL, and not to read the URL for identifier. */
  ini_set('session.use_trans_sid', 0);           
  
  /* hash algorithm to use for the sessionid. (use hash_algos() to get a list of available hashes.) */
  $session_hash = 'sha512';
     
  /* check if hash is available */
  if (in_array($session_hash, hash_algos())) {
      /* set the has function. */
      ini_set('session.hash_function', $session_hash);
  }

  /* ref : http://php.net/manual/en/function.ini-set.php * hide hide the data in your session variables from other users */
  /* requires php to be compiled with the --mm option or look at memcache for production server */

  /* PHP version is PHP 5.4.28. memcache lib installed from the AWS repo is php54-pecl-memcache-3.0.8-1.11.amzn1.x86_64 */
  /* ref: http://stackoverflow.com/questions/24184568/php-sessions-not-being-saved-in-memcache */
  /* check : override installed by default in /etc/httpd/conf.d/php.conf */ 
  /* session.save_handler="memcache" */
  /* session.save_path="tcp://<elasticache-endpoint>:11211"/ */

  if (ini_get('session.save_handler') === 'mm') {
      ini_set('session.save_handler','mm');
  } else {
      ini_set('session.save_handler','user');
  }

  /* How many bits per character of the hash. */
  /* The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ","). */
  ini_set('session.hash_bits_per_character', 5);
  
  /* force the session to only use cookies, not URL variables. */
  ini_set("session.use_cookies", 1);
  ini_set("session.use_only_cookies", 1);
  ini_set("session.use_trans_sid", 1);               // try and prevent addtional PHPSESSID being generated
     
  /* HTTP Only */
  ini_set('session.cookie_httponly', 1);

  /* ini_set('session.cache_expire', 45); */
  ini_set('session.cookie_lifetime', 0);
  ini_set("session.gc_maxlifetime", $lifetime);

  ini_set("session.gc_probability", 1);
  ini_set("session.gc_divisor", 100);
     
  ini_set('session.cookie_domain', '.localhost');     // domain
  ini_set('url_rewriter.tags', '');                   // ensure that the session id is *not* passed on the url.

  /* Get session cookie parameters */
  $cookieParams = session_get_cookie_params();

  // if($remember) {
  // Generate new auth key for each log in (so old auth key can not be used multiple times in case of cookie hijacking)
  // $cookie_auth= rand(10) . $username;
  // $auth_key = session_encrypt($cookie_auth);
  // $auth_query = mysql_query("UPDATE users SET auth_key = '" . $auth_key . "' WHERE username = '" . $username . "'");
  // setcookie("auth_key", $auth_key, time() + 60 * 60 * 24 * 7, "/", "example.com", false, true)
  // }
     
  /* set the parameters */
  session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $domain, $https, $httponly); 
        
  /* change the session name */
  session_name($session_name);

  /* now we can start the session */
  if (session_status() === PHP_SESSION_NONE ) {

      @session_start();

  }; // recommended way for versions of PHP >= 5.4.0

  // Manually set the cookie
  // setcookie("sid", session_id(), strtotime("+1 hour"), "/", ".wblinks.com", true, true);
  // if ( PHP_VERSION < 5.2 ){@setcookie( $session_name, $value, $expires, $path, $domain. '; HttpOnly' );}else{@setcookie( $session_name, $value, $expires, $path, $domain, NULL, TRUE );}
  // setcookie("sid", session_id($session_name), strtotime("+1 hour"), "/", ".localhost", true, true);
  // setcookie(session_name($session_name),session_id(),time()+$lifetime);

  /* this line regenerates the session and delete the old one. 
     it also generates a new encryption key in the database. */
     session_regenerate_id(true);

  }

}

function validatesession() {

  if( isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']) )
        return false;

  if(isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time())
        return false;

  return true;
}

function preventhijacking() {
  
  if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
    return false;

    if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']
        && !( strpos($_SESSION['userAgent'], ÔTridentÕ) !== false
        && strpos($_SERVER['HTTP_USER_AGENT'], ÔTridentÕ) !== false))
        {
        return false;
        }

        $sessionIpSegment = substr($_SESSION['IPaddress'], 0, 7);

        $remoteIpHeader = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

        $remoteIpSegment = substr($remoteIpHeader, 0, 7);

        if($_SESSION['IPaddress'] != $remoteIpHeader && $remoteIpSegment)
        {
        return false;
        }

        if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
        return false;

        return true;

}

function regeneratesession() {
    // If this session is obsolete it means there already is a new id
    if(isset($_SESSION['OBSOLETE']) || $_SESSION['OBSOLETE'] == true)
      return;

      // Set current session to expire in 10 seconds
      $_SESSION['OBSOLETE'] = true;
      $_SESSION['EXPIRES'] = time() + 10;

      // Create new session without destroying the old one
      session_regenerate_id(false);

      // Grab current session ID and close both sessions to allow other scripts to use them
      $newSession = session_id();
      session_write_close();

      // Set session ID to the new one, and start it back up again
      session_id($newSession);
      if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0

      // Now we unset the obsolete and expiration values for the session we want to keep
      unset($_SESSION['OBSOLETE']);
      unset($_SESSION['EXPIRES']);
}


function secure_session_timeout() {

    // last request was more than 30 minutes ago
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    
    // unset $_SESSION variable for the run-time                                           
    session_unset();
    
    // destroy session data in storage
    session_destroy();}
    
    // update last activity time stamp  
    $_SESSION['LAST_ACTIVITY'] = time(); 
    
    if (!isset($_SESSION['CREATED'])) {
          $_SESSION['CREATED'] = time();
    // session started more than 30 minutes ago  
    } else if (time() - $_SESSION['CREATED'] > 1800) {

    // change session ID for the current session and invalidate old session ID                                          
    session_regenerate_id(true);        
    
    // update creation time
    $_SESSION['CREATED'] = time();       
    }

}


function secure_session_destroy() {

    // Unset all of the session variables.
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {

        $session_name = '_crowdcc_sess'; // Set a custom session name
        $params = session_get_cookie_params();

        setcookie(session_name($session_name), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);       
        setcookie('_crowdcc_sess', '', time() - 3600, '/');

    }

    if (session_name() != '') { 
        session_destroy();
    }
  
}


function secure_session_cookie_timeout() {

    // Unset all of the session variables.
    // $_SESSION = array();

    $session_name = '_crowdcc_sess';               
    // Set a custom session name
    $secure = null;                                
    // Set SSL level, true if using https.
    $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);
    // Make sure the session cookie is not accessable via javascript.
    $httponly = true;
    // Set the domain to default to the current domain.
    $domain = isset($domain) ? $domain : isset($_SERVER['SERVER_NAME']);
    // Set the domain path (http page path).
    $path = '/';
    // Set the cookie lifetime for domain and path. Set to 0 if you want the session cookie to be set until the user closes
    // the browser. Use time() + seconds otherwise.
    // Get session cookie parameters 
    $cookieParams = session_get_cookie_params();
    // Set the parameters
    $lifetime=600;

    session_name($session_name);
    session_set_cookie_params($cookieParams[$lifetime], $cookieParams[$path], $cookieParams[$domain], $https, $httponly);
    setcookie($session_name, session_id(), time() + $lifetime, $path, 'localhost', $secure, $httponly);  
  
} 


/* ref: http://stackoverflow.com/questions/1545357/how-to-check-if-a-user-is-logged-in-in-php */

/*
* signins are not too complicated, but there are some specific pieces that almost all login processes need.
* First, make sure you enable the session variable on all pages that require knowledge of logged-in status by putting this at the beginning of those pages:
*
* session_start();
*
* Next, when the user submits their username and password via the login form, you will typically check their username and password by querying a database containing
* username and password information, such as MySQL. If the database returns a match, you can then set a session variable to contain that fact.
* You might also want to include other information:
*
* if (match_found_in_database()) {
*    $_SESSION['loggedin'] = true;
*    $_SESSION['username'] = $username; // $username coming from the form, such as $_POST['username']
*                                       // something like this is optional, of course
* }
*
* Then, on the page that depends on logged-in status, put the following ( don't forget the session_start() ):
*
*  if (isset($_SESSION['callin']) && $_SESSION['callin'] == true) {
*      echo "Welcome to the member's area, " . $_SESSION['username'] . "!";
*  } else {
*      echo "Please log in first to see this page.";
*  }
*
*/

function signin_check($mysqli) {

  /*
  *
  *  $_SESSION['ucode'] === 'glynthom';
  *
  *  $_SESSION['ccode'] === 'soc';
  *  $_SESSION['ccode'] === 'ccn';
  *  $_SESSION['ccode'] === 'ccc';
  *
  */

  if (isset($_SESSION['ucode'])) {

         $uname = $_SESSION['ucode'];
  
    switch (true) {
        
      case ($stmt = $mysqli->prepare("SELECT uname FROM regist_members WHERE uname = ? LIMIT 1") ):
 
            $stmt->bind_param('s', $uname_db);           // bind "$uname_db" to parameter.
            $stmt->execute();                            // execute the prepared query.
            $stmt->store_result();

        if ($stmt->num_rows == 1) {

            $stmt->bind_result($uname_db);               // get variables from result.
            $stmt->fetch();
            $stmt->close();
            
        } else {
            return false;
        }

      case ($uname === $uname_db):

        if ( isset($_SESSION['ccode']) ) {

          switch (true) {

             case ( $_SESSION['ccode'] === 'soc' ):
             case ( $_SESSION['ccode'] === 'ccn' ):
             case ( $_SESSION['ccode'] === 'ccc' ):
                    return true;
             break;
          }
        }

      break;

      default:

      return false;
      break;

    }
    
  }

}


function check_email($isemail){

  if(valid_email($isemail)){
      $email = $isemail;
      // $u = $isemail;
      $uname = 'username unknown';
  }else{
      $uname = $isemail;
      // $u = $isemail;
      $email = 'email unknown';
  }
      return array($isemail, $uname, $email);
}

/*
*
* Douglas Lovell
*
* http://www.linuxjournal.com/article/9585?page=0,3
* Validate an email address, provide email address (raw input)
* Returns true if the email address has the email address format and
* the domain exists.
*/


function valid_email($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex) {
      $isValid = false;
   } else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64) {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255) {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.') {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local)) {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain)) {
         // domain part has two consecutive dots
         $isValid = false;
      } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
        // character not valid in local part unless 
        // local part is quoted
        if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
            $isValid = false;
        }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
          // domain not found in DNS
          $isValid = false;
      }
   }
   return $isValid;
}



function ccrypt($string, $encryptionmethod, $option) {

  $secrethash = '7d9!y8y4=h7v2v8v*1|1';  /* because everyone loves salt */

  /* $encryptionmethod = 'AES-256-OFB'; currently selected */
  
  /* ref: http://www.synet.sk/php/en/320-benchmarking-symmetric-cyphers-openssl-vs-mcrypt-in-php
     top three fastest execution time

  1.  [AES-256-OFB] => 0.059
  2.  [AES-256-CFB] => 0.059
  3.  [AES-192-CFB] => 0.059
  4.  [AES-192-OFB] => 0.059

  */

  $raw = true; /* no padding */
  $password = 'p0p4p1pw0w4m32U';

 /*  function uses open SSL encrypt * decrypt and is passed the following ;
  *
  *  ref: http://www.synet.sk/php/en/320-benchmarking-symmetric-cyphers-openssl-vs-mcrypt-in-php
  *
  *  $data    -> text to be encypted
  *  $option  -> enc _rypt || dec _rypt
  *  $encryptionMethod = 'AES-256-OFB'
  *  $raw = true -> no padding, no base64_encode();
  *  $password -> must be the same for both encryption / decryption
  *
  *  **oauth tokens should be encrypted before being stored in the db
  *
  */

  $length = openssl_cipher_iv_length($encryptionmethod);
  $iv = substr(md5($password), 0, $length);
  
  switch ($option) {
     case ('en'):
           /* encrypt the data */
           return bin2hex(openssl_encrypt($string, $encryptionmethod, $secrethash, $raw, $iv));
     break;
     case ('de'):
           /* decrypt the data */
           $string = pack('H*', $string);
           return openssl_decrypt($string, $encryptionmethod, $secrethash, $raw, $iv);
     break;
  }

}


function create_api_token($uname, $limit) {
 static $guid = ''; $ulen = 1; $upad = 1; $npad = 1;
 /* base token layout on GUID, staic and random alt compontents
    $uname 15 chars max * see twitter username rules */
 /* simple split on uname */
 $ulen = strlen(utf8_decode($uname));
 $chrspit = round( ($ulen /2) ,0,PHP_ROUND_HALF_DOWN) ;
 $remains = ($ulen - $chrspit);
 $first = mb_substr($uname, 0, $chrspit);
 $second = mb_substr($uname, $chrspit, $remains);
 /* name crunch figure out upad length compensation */
 switch (true) {
    case ($ulen === 15): /* max username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' ); 
          $upad = 4;
    break;
    case ($ulen === 14): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );        
          $upad = 6;
    break;
    case ($ulen === 13): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );          
          $upad = 8;
    break;
    case ($ulen === 12): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );       
          $upad = 10;
    break;
    case ($ulen === 11): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' ); 
          $upad = 12;
    break;
    case ($ulen === 10): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );         
          $upad = 14;
    break;
    case ($ulen === 9): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );       
          $upad = 16;
    break;
    case ($ulen === 8): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );          
          $upad = 18;
    break;
    case ($ulen === 7): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );          
          $upad = 20;
    break;
    case ($ulen === 6): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );       
          $upad = 22;
    break;
    case ($ulen === 5): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );             
          $upad = 24;
    break;
    case ($ulen === 4): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );             
          $upad = 26;
    break;
    case ($ulen === 3): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );      
          $upad = 28;
    break;
    case ($ulen === 2): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );             
          $upad = 30;
    break;
    case ($ulen === 1): /* username */
          $first   = ccrypt( $first,   'AES-256-OFB', 'en' );
          $second  = ccrypt( $second,  'AES-256-OFB', 'en' );             
          $upad = 32;
    break;
 }
 $nlen = strlen(utf8_decode($limit));
 /* number crunch figure out npad length compensation */
 switch (true) {
    case ($nlen === 7): /* max 1000 000 */
          $limit  = ccrypt( $limit,  'AES-256-OFB', 'en' );
          $npad = 0;
    break;
    case ($nlen === 6): /* max 100 000 */
          $limit  = ccrypt( $limit,  'AES-256-OFB', 'en' );
          $npad = 2;
    break;
    case ($nlen === 5): /* 10 000 */
          $limit  = ccrypt( $limit,  'AES-256-OFB', 'en' );          
          $npad = 4;
    break;
    case ($nlen === 4): /* 1 000 */
          $limit  = ccrypt( $limit,  'AES-256-OFB', 'en' );          
          $npad = 6;
    break;
    case ($nlen === 3): /* 100 */
          $limit  = ccrypt( $limit,  'AES-256-OFB', 'en' );       
          $npad = 8;
    break;
    case ($nlen === 2): /* 50 public access */
          $limit  = ccrypt( $limit,  'AES-256-OFB', 'en' );          
          $npad = 10;
    break;
 }
 /* build random api token elements */ 
 $uid = uniqid("", true);
 $data = '';
 $data .= $_SERVER['REQUEST_TIME'];
 $data .= $_SERVER['HTTP_USER_AGENT'];
 $data .= array_key_exists('SERVER_ADDR',$_SERVER) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
 $data .= array_key_exists('SERVER_PORT',$_SERVER) ? $_SERVER['SERVER_PORT'] : $_SERVER['LOCAL_PORT'];
 $data .= $_SERVER['REMOTE_ADDR'];
 $data .= $_SERVER['REMOTE_PORT'];
 /* $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data))); */
 $hash = hash('ripemd128', $uid . $guid . md5($data));
 $guid = '' . 
         substr($hash,  0,  $upad) .
         '-' .
         /* strtoupper($second) . */
         $second .
         '-' .
         substr($hash,  8,  3) .
         '-' .
         /* strtoupper($first) . */
         $first .
         '-' .
         substr($hash, 12,  3) .
         '-' .
         /* strtoupper($limit) . */
         $limit .
         '-' .
         substr($hash, 20, $npad) .
         '';
 /* return $guid . ' :: string length :' . strlen(utf8_decode($guid)) . ' $ulen :' .$ulen . ' $nlen :' . $nlen ; */
 return $guid;

}


function check_api_token($token) {

 /* format (1) : 60 length  : C2A783B3- [ 2nd part of uname ] -F494- [ 1st part of uname ] -8475- [ limit ] -57E74 */
 /* format (2) : crypt (1)  : ccrypt(  create_api_token('glynthom', '50') ,  'AES-256-OFB', 'en' ) ) */

 if (strlen(utf8_decode( $token )) === 60) {
  
   switch (true) {
     case (strlen(utf8_decode( $token )) === 120):
           $token = ccrypt( $token , 'AES-256-OFB', 'de' );
     case (strlen(utf8_decode( $token )) === 60):
     break;
   }

   $token = explode("-", $token);
   $token[0] = strlen(utf8_decode( $token[0] ));
   /* name de-crunch, figure out upad length compensation */

   switch (true) {
     case ( !isset($token[3]) ):
     case ( !isset($token[1]) ):
     case ( !isset($token[6]) ): 
           return 'token is invalid, error';
     break;  
   }

   switch (true) {
     case ($token[0] === 32): // max user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 30): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );   
     break;
     case ($token[0] === 28): // user pad 
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );         
     break;
     case ($token[0] === 26): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );    
     break;
     case ($token[0] === 24): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 22): // username 
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 20): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );    
     break;
     case ($token[0] === 18): // user pad 
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 16): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 14): // user pad 
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 12): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );       
     break;
     case ($token[0] === 10): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );       
     break;
     case ($token[0] === 8): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 6): // user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
     case ($token[0] === 4): // min user pad
           $token[3]  = ccrypt(  $token[3],  'AES-256-OFB', 'de' );
           $token[1]  = ccrypt(  $token[1],  'AES-256-OFB', 'de' );
     break;
  }

  /* number de-crunch figure out pad length compensation */

  /* if ( isset($token[6]) ) { */
       $nlen = strlen(utf8_decode( $token[6] ));

       switch (true) {
        case ($nlen === 10): // max 100 000
              $token[5]  = ccrypt(  $token[5],  'AES-256-OFB', 'de' );
        break;
        case ($nlen === 8): // 10 000 
              $token[5]  = ccrypt(  $token[5],  'AES-256-OFB', 'de' );
        break;
        case ($nlen === 6): // 1 000 
              $token[5]  = ccrypt(  $token[5],  'AES-256-OFB', 'de' );
        break;
        case ($nlen === 4): // 100
              $token[5]  = ccrypt(  $token[5],  'AES-256-OFB', 'de' );
        break;
        case ($nlen === 2): // 50 public access
              $token[5]  = ccrypt(  $token[5],  'AES-256-OFB', 'de' );
        break;

        case ($nlen > 10):
        case ($nlen < 2):
              $token[3] = 'token is '; $token[1] = 'invalid'; $token[5] = 'error';
        break;
       }

 } else { $token[3] = 'token is '; $token[1] = 'invalid'; $token[5] = 'error';}

 /* final sanity check */

 $options = array('options' => array('min_range' => 0));

  switch (true) {

    case ( !preg_match('/^[A-Za-z0-9_]{1,15}$/', ($token[3] . $token[1]) ) ):
          return 'token is invalid, error';
    break;
    case ( filter_var($token[5], FILTER_VALIDATE_INT, $options) === FALSE  ):
          return 'token is invalid, error';
    break;

  }
 
 return $token[3] . $token[1] . ',' .$token[5];

}


function join_api_token( $token ) {
 /* format (1) : 60 length  : C2A783B3- [ 2nd part of uname ] -F494- [ 1st part of uname ] -8475- [ limit ] -57E74 */
 /* format (2) : crypt (1)  : ccrypt(  create_api_token('glynthom', '100') ,  'AES-256-OFB', 'en' ) ) */

  $lump = explode("-", $token);

  /* six lumps * unshuffle */
  $lump[0];
  $lump[1];
  $lump[2];
  $lump[3];
  $lump[4];
  $lump[5];
  $lump[6];

  return $lump[6] .'-'. $lump[0] .'-'. $lump[1] .'-'. $lump[2] .'-'. $lump[3] .'-'. $lump[5] .'-'. $lump[4] ;

}


function juggle_api_token( $token ) {
 /* format (1) : 60 length  : C2A783B3- [ 2nd part of uname ] -F494- [ 1st part of uname ] -8475- [ limit ] -57E74 */
 /* format (2) : crypt (1)  : ccrypt(  create_api_token('glynthom', '100') ,  'AES-256-OFB', 'en' ) ) */

  $lump = explode("-", $token);

  /* six lumps * shuffle */
  $lump[0];
  $lump[1];
  $lump[2];
  $lump[3];
  $lump[4];
  $lump[5];
  $lump[6];

  return $lump[1] .'-'. $lump[2] .'-'. $lump[3] .'-'. $lump[4] .'-'. $lump[6] .'-'. $lump[5] .'-'. $lump[0] ;
      
}



/* --------------------------- ****** ---------------------------- */


function simpleflake($timestamp = null, $randombits = null, $epoch = 946702800) {
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

  $flake = ($timestamp <<  $timestamp_shift) | $randombits . mt_rand_str(1);
  return $flake;

}

function parse($flake, $epoch = 946702800) {
 /**
 * Parses a simpleflake and returns a named tuple with the parts.
 *
 * @param int $flake
 * @param int $epoch
 * @return int
 */

 /* $shard = substr($flake, -1); */
 
 /* twitter epoch: 1288834974657 (Long) */
 $epoch = 946702800;
 $timestamp_shift = 23;
 $random_max_value = 4194303;

 $timestamp = ($flake >>  $timestamp_shift) / 1000.0;
 $randombits = $flake & $random_max_value;

  return array(
      "timestamp" => $timestamp + $epoch,
      "randombits" => $randombits
      /* "randombits" => $randombits . $shard */
  );
}


function parse_simpleflake($flake, $epoch = 946702800) {
 /**
 * Alias for parse to be "compatible" with the python idol :)
 *
 * @param int $flake
 * @param int $epoch
 * @return int
 */
   
   return parse($flake, $epoch);
}


function flaketest() {
 /**
 * flaketest, test for unique id's :)
 *
 */
 $i = 0;
 $storage = array();

  while ($i < 500) {
     $flake = simpleflake($timestamp = null, $randombits = null, $epoch = 946702800);
     if(array_key_exists('x'.$flake, $storage)){
        echo "Collision!";
        exit(1);
     }
     $storage['x'.$flake] = null;
     echo $flake . PHP_EOL;
     $i++;
  }
}


function mt_rand_str ($l, $c = '123456789') {
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}


/* --------------------------- ****** ---------------------------- */


/**
* RC4 symmetric cipher encryption/decryption
*
* @license Public Domain
* @param string key - secret key for encryption/decryption
* @param string str - string to be encrypted/decrypted
* @return string
*/

function rc4($key, $str) {
  $s = array();
    for ($i = 0; $i < 256; $i++) {
    $s[$i] = $i;
    }
    $j = 0;
    for ($i = 0; $i < 256; $i++) {
    $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
    $x = $s[$i];
    $s[$i] = $s[$j];
    $s[$j] = $x;
    }
    $i = 0;
    $j = 0;
    $res = '';
    for ($y = 0; $y < strlen($str); $y++) {
    $i = ($i + 1) % 256;
    $j = ($j + $s[$i]) % 256;
    $x = $s[$i];
    $s[$i] = $s[$j];
    $s[$j] = $x;
    $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
    }
  return $res;
}


function rands($length = 64) {
     $s = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFEGHIJKLMNOPQRSTUVWXYZ!*@-", $length)), 0, $length);
     return $s;
}


/* 

get_data($url)

replace : @file_get_contents
new use : $data = get_data("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=". $api_token ."&method=get&format=json&screen_name=". $post_in[1] ."&count=". $post_in[2]); 
old use : $data = @file_get_contents("http://" . $_SERVER['HTTP_HOST'] . "/api.php?token=". $api_token ."&method=get&format=json&screen_name=". $post_in[1] ."&count=". $post_in[2]);
example use: $response = get_data('http://images.google.com/images?hl=en&q=' . urlencode ($query) . '&imgsz=' . $size . '&imgtype=' . $type . '&start=' . (($page - 1) * 21)); 

*/

/*

function file_get_contents_curl($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

*/

/* ref: http://php.net/manual/en/function.curl-setopt.php#102121 */

function curl_exec_follow(/*resource*/ $ch, /*int*/ &$maxredirect = null) {
  $mr = $maxredirect === null ? 5 : intval($maxredirect);
  if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
      curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
  } else {
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
      if ($mr > 0) {
          $newurl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
          $rch = curl_copy_handle($ch);
          curl_setopt($rch, CURLOPT_HEADER, true);
          curl_setopt($rch, CURLOPT_NOBODY, true);
          curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
          curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);
          do {
              curl_setopt($rch, CURLOPT_URL, $newurl);
              $header = curl_exec($rch);
              if (curl_errno($rch)) {
                  $code = 0;
              } else {
                  $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                  if ($code == 301 || $code == 302) {
                      preg_match('/Location:(.*?)\n/', $header, $matches);
                      $newurl = trim(array_pop($matches));
                  } else {
                      $code = 0;
                  }
              }
          } while ($code && --$mr);
              curl_close($rch);
              if (!$mr) {
                if ($maxredirect === null) {
                    trigger_error('Too many redirects. When following redirects, libcurl hit the maximum amount.', E_USER_WARNING);
                } else {
                    $maxredirect = 0;
                }
                return false;
              }
            curl_setopt($ch, CURLOPT_URL, $newurl);
          }
    }
  return curl_exec($ch);
} 


function file_get_contents_curl( $url ) {
  if (!filter_var($url, FILTER_VALIDATE_URL) ) { $url = $_SERVER['SERVER_NAME'] . '/' . $url; }
  /* test * not an absolute URL, assume must be a relative URL file path * add server * add path to $url */    
  /* $url === 'http://localhost/upload_img/kitten.jpg'; */
  $ch = curl_init();
  $timeout = 5; /* optional */

  curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch,CURLOPT_URL,$url);

  /* if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) { curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); } */
  /* curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); cannot be activated when an open_basedir is set * use * $response = TwitterOAuth::curl_exec_follow($ch); */

  /* disable SSL verification */
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  
  /* will return the response, if false it print the response */
  /* curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); */
  /* curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__). '/cacert.pem'); */
  
  /* trace curl connection */
  /* $fileHandle = fopen($_SERVER["DOCUMENT_ROOT"] . "/../logs/curl.app.error.log","w+"); curl_setopt($ch, CURLOPT_VERBOSE, TRUE); curl_setopt($ch, CURLOPT_STDERR, $fileHandle); */
  
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); /* optional */

  /* curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); curl_setopt(): CURLOPT_FOLLOWLOCATION cannot be activated when an open_basedir is set */
  /* twitter API currently * no 302 redirects * however in the future this may change */

  $response = curl_exec($ch);
  /* $response = curl_exec_follow($ch); */
  
  curl_close($ch);
  return $response;
}

/* 

allow hosts : crowdcc.dev (local dev server) , crowdcc.apc (local), crowdcc.com (internet host) ( or ip address mix ) 

*/

function get_hostadd() { $hostadd = '111';$hostadd = $_SERVER['SERVER_ADDR'];return $hostadd;$hostadd = null; }

function get_host() { $host = 'ccc';switch (true) {case (isset($_SERVER['HTTP_HOST'])):$host = $_SERVER['HTTP_HOST'];break;case (isset($_SERVER['SERVER_NAME'])):$host = $_SERVER['SERVER_NAME'];break;}return $host;$host = null;}

function okcomputer( $whitelist0, $whitelist1, $whitelist2, $whitelist3, $whitelist4, $whitelist5 ) {
  /* found log * log failure outside the JS console ( please comment out / remove later ) */
  /* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */
  /* log_found('host check', $_SERVER['HTTP_HOST'], 'okcomputer()', __LINE__ );log_found('ip check', $_SERVER['REMOTE_ADDR'], 'okcomputer()', __LINE__ ); */

  $host = get_host();$fail = 0;

  /*
  log_found('host list0', $whitelist0, 'okcomputer()', __LINE__ );log_found('host list1', $whitelist1, 'okcomputer()', __LINE__ );log_found('host list2', $whitelist2, 'okcomputer()', __LINE__ );
  log_found('host list3', $whitelist3, 'okcomputer()', __LINE__ );log_found('host list4', $whitelist4, 'okcomputer()', __LINE__ );log_found('host list5', $whitelist5, 'okcomputer()', __LINE__ );
  log_found('host check', $host, 'okcomputer()', __LINE__ );
  */

  /* check authorized hosts && ip address * any one of the whitelist host names or ip address wins */

  switch (true) {

    case ($whitelist0 === $host):
          $fail = $fail + 1;
    case ($whitelist1 === $host):
          $fail = $fail + 1;
    case ($whitelist2 === $host):
          $fail = $fail + 1;
    case ($whitelist3 === $host):
          $fail = $fail + 1;
    case ($whitelist4 === $host):
          $fail = $fail + 1;
    case ($whitelist5 === $host):
          $fail = $fail + 1;
    break;

  }

  if ($fail === 0) { $host = 'unknown';}
    if ($host === 'unknown') {
        $host = get_hostadd();
 
      switch (true) {

         case ($whitelist0 === $host):
              $fail = $fail + 1;
         case ($whitelist1 === $host):
              $fail = $fail + 1;
         case ($whitelist2 === $host):
              $fail = $fail + 1;
         case ($whitelist3 === $host):
              $fail = $fail + 1;
         case ($whitelist4 === $host):
              $fail = $fail + 1;
         case ($whitelist5 === $host):
              $fail = $fail + 1;        
         break;

      }

      if ($fail === 0) { header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');exit();}
    }
  /*
  log_found('host unknown :: now check IP address!', get_host(), 'okcomputer()', __LINE__ );log_found('host unknown :: fail check !', $fail, 'okcomputer()', __LINE__ );
  log_found('ip unknown', get_hostadd(), 'okcomputer()', __LINE__ );log_found('ip unknown :: fail check !', $fail, 'okcomputer()', __LINE__ );
  */
  $host = null; $fail = null;

  /* $whitelist = array( $whitelist1, $whitelist2, $whitelist3 ); if ( !in_array( get_host() , $whitelist ) ) { header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');exit();} */
}

/*

$token_send   = getrandomstr(dechex(time()),'t');
$token_store  = getrandomstr($ecode_target,'e');

*/

function getrandomstr($msg, $tore) {
  /* getrandomstr * mod * getrandomstr('reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com','e'); */
  /* mod => max email size 80 char * fixed token size 84   */
  /* $token_send   = getrandomstr(dechex(time()),'t'); */
  /* $token_store  = getrandomstr('reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com','e'); */
  $msglength = strlen($msg);
  $length = 84 - $msglength;
  $chars = ':abcdfghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890:$:';
  $tmp_result = '';
  $output = array();
  for ($p = 0; $p < $length; $p++) { $tmp_result .= ($p%2) ? $chars[mt_rand(4, 64)] : $chars[mt_rand(0, 3)];}
  $rand_digits = 1;
  $output[0] = substr($tmp_result, 0, $rand_digits);
  $output[1] = substr($tmp_result, $rand_digits, strlen($tmp_result));  
  switch($tore) {
    case('t'):
        $arrayout = timemute('f', $msg);
        $getrand = array(strlen($output[0]), $output[0] . $arrayout[0] . $arrayout[1] . $arrayout[2] . $arrayout[3] . $arrayout[4] . $arrayout[5] . $arrayout[6] . $arrayout[7] . $output[1], date('Y-m-d H:i:s',time())); 
    break;
    case('e');
        $arrayout = textmute(0,'f',$msg);
        $arrayout = implode('', $arrayout); 
        $getrand = array(strlen($output[0]), $output[0] . $arrayout  . $output[1], time()); 
    break;
  }
    return $getrand;
} 

function getstrmsg($randstr, $tore) {
  /* mod => max email size 80 char * fixed token size 84   */
  /* getstrmsg(1, $token_store[1], 'e')  */
  /* $token_store  = getrandomstr('reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com','e'); */
  /* getstrmsg(1, ':mngccimngccimngccimngccimngccimngccimngccimngccimngccimngccimngccimngc!argyc$lhr2:f', 'e') */
  /* date("Y-m-d H:i:s", hexdec(getstrmsg(1, 'bl;!li!:w9:6boaqcib7:BcU:xa6bpbA:pbmbI:Xa$:HcQaWav:ZbC:0:$bScn:CaJbqbOa1bBcg:B:AcIbd', 't'))) */
  $start = 1;
  $return = 'token error';
  /* echo 'token size => ' . strlen($randstr); */
  if ( strlen($randstr) === 84 ) {
       $str2 = substr($randstr, $start, strlen($randstr));
       switch($tore) {
          case('t'):
                $str3 = substr($str2, 0, 8);
                $arrayout = timemute('u', $str3); 
                // $return = $arrayout[0] . $arrayout[1] . $arrayout[2] . $arrayout[3] . $arrayout[4] . $arrayout[5] . $arrayout[6] . $arrayout[7];
                $return = implode('', $arrayout); 
          break;
          case('e');
                $leftstr = strlen($str2);
                $pos = (strpos($str2, '$') + 4);
                $str3 = substr($str2, 0, $pos);
                $arrayout = textmute(0,'u',$str3);
                $return = implode('', $arrayout); 
          break;
        }
      }
    return $return;
  }


