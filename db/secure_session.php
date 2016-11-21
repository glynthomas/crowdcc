<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
* secure_session.php  * secure session management
*
*
*/

/* GRANT SELECT, INSERT, UPDATE, DELETE ON `crowdcc_sessions`.sessions TO 'ccsessn'@'localhost' */

/* access to crowdcc signin db */	
require_once($_SERVER["DOCUMENT_ROOT"].'/../db/db.app.conn.php');

class secure_session {

   function __construct() {

   	   /* ini_set('session.cache_expire', 45); */

	   ini_set('session.cookie_lifetime', 0);
	   ini_set('session.gc_maxlifetime', 2700);

   	   ini_set('session.gc_probability', 1);
   	   ini_set('session.gc_divisor', 100);

   	   $max = 2700; // 45 mins (1800 is 30 mins, you will probably want the default to be much higher than this)

	   // set our custom session functions.
	   session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
	 
	   // This line prevents unexpected effects when using objects as save handlers.
	   register_shutdown_function('session_write_close');
	}

/*

	function start_session($session_name, $secure) {
	   // Make sure the session cookie is not accessable via javascript.
	   $httponly = true;
	 
	   // Hash algorithm to use for the sessionid. (use hash_algos() to get a list of available hashes.)
	   $session_hash = 'sha512';
	 
	   // Check if hash is available
	   if (in_array($session_hash, hash_algos())) {
	      // Set the has function.
	      ini_set('session.hash_function', $session_hash);
	   }
	   // How many bits per character of the hash.
	   // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
	   ini_set('session.hash_bits_per_character', 5);
	 
	   // Force the session to only use cookies, not URL variables.
	   ini_set('session.use_only_cookies', 1);
	 
	   // Get session cookie parameters 
	   $cookieParams = session_get_cookie_params(); 
	   // Set the parameters
	   session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
	   // Change the session name 
	   session_name($session_name);
	   // Now we can start the session
	   session_start();
	   // This line regenerates the session and delete the old one. 
	   // It also generates a new encryption key in the database. 
	   session_regenerate_id(true);

	}
*/

	function open() {
	   // CREATE USER 'ccsessn'@'localhost' IDENTIFIED BY 'r0ll1ngSt0nEl1veSess10n';
       // GRANT SELECT, INSERT, UPDATE, DELETE ON `crowdcc_sessions`.* TO 'ccsessn'@'localhost';
	   // $host = 'localhost';
	   // $host = strpos($_SERVER["HTTP_HOST"],$_SERVER["SERVER_NAME"])!==false);

	   // $host = $_SERVER['SERVER_NAME'];  // http://localhost/ // security update bind=127.0.0.1
       $host = 'localhost';
	   $user = 'ccsessn';
	   $pass = 'back2sessn4m32u';
	   $name = 'crowdcc_sessions';
	   $mysqli = new mysqli($host, $user, $pass, $name);

	   mysqli_query($mysqli, 'SET NAMES "utf8"');
	   mysqli_query($mysqli, 'SET CHARACTER SET "utf8"');
	   mysqli_query($mysqli, 'SET character_set_results = "utf8",' .
	   'character_set_client = "utf8", character_set_connection = "utf8",' .
	   'character_set_database = "utf8", character_set_server = "utf8"');

	   date_default_timezone_set("Europe/London");
	   /* $timezone = date_default_timezone_get(); */
	   $timezone = 'Europe/London';
	   $now = time();
	   $date = new DateTime(null, new DateTimeZone($timezone));
	   $timelocal = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));
	   /* date_default_timezone_set("UTC"); */

	   // log_error($timelocal,'session', $stmtr, $mysqli);

	   log_error($timelocal,'session', $this, $mysqli);

	   $this->db = $mysqli;
	   return true;
	}

	function close() {
	   $this->db->close();
	   return true;
	}


	function read($session_id) {
	   if(!isset($this->read_stmt)) {
	      $this->read_stmt = $this->db->prepare("SELECT data FROM sessions WHERE session_id = ? LIMIT 1");
	   }
	   $this->read_stmt->bind_param('s', $session_id);
	   // log_error($timelocal,'bind_param', $this, $mysqli); 
	   $this->read_stmt->execute();
	   $this->read_stmt->store_result();
	   $this->read_stmt->bind_result($data);
	   $this->read_stmt->fetch();
	   $key = $this->getkey($session_id);
	   $data = $this->decrypt($data, $key);
	   return $data;
	}

	function write($session_id, $data) {	   
	   // Get unique key
	   $key = $this->getkey($session_id);
	
	   // IP address 
	   $ip_address = $_SERVER['REMOTE_ADDR'];

	   // Encrypt the data
	   $data = $this->encrypt($data, $key);
	   
	   $time = time();
	   if(!isset($this->w_stmt)) {
	   	  $this->w_stmt = $this->db->prepare("REPLACE INTO sessions (session_id, ip_address, set_time, data, session_key) VALUES (?, ?, ?, ?, ?)");
	   }
	   $this->w_stmt->bind_param('ssiss', $session_id, $ip_address, $time, $data, $key);
	   $this->w_stmt->execute();
	   return true;
	}

	function destroy($session_id) {
	   if(!isset($this->delete_stmt)) {
	      $this->delete_stmt = $this->db->prepare("DELETE FROM sessions WHERE session_id = ?");
	   }
	   $this->delete_stmt->bind_param('s', $session_id);
	   $this->delete_stmt->execute();
	   return true;
	}

	function gc($max) {
	   if(!isset($this->gc_stmt)) {
	      $this->gc_stmt = $this->db->prepare("DELETE FROM sessions WHERE set_time < ?");
	   }
	   $old = time() - $max;
	   $this->gc_stmt->bind_param('s', $old);
	   $this->gc_stmt->execute();
	   return true;
	}

    // need to check when the gc() override function is called, as don't want to delete session data from a
    // live active session ... what value should $max be ?
    // 
	// function gc() {
    // Garbage Collection
	// if(!isset($this->gc_stmt)) {
	//       $this->gc_stmt = $this->db->prepare("DELETE FROM sessions WHERE set_time < UNIX_TIMESTAMP();");
	// }
    //   $this->gc_stmt->execute();
	//   return true;
    // }

	private function getkey($session_id) {
	   if(!isset($this->key_stmt)) {
	      $this->key_stmt = $this->db->prepare("SELECT session_key FROM sessions WHERE session_id = ? LIMIT 1");
	   }
	   $this->key_stmt->bind_param('s', $session_id);
	   $this->key_stmt->execute();
	   $this->key_stmt->store_result();
	   if($this->key_stmt->num_rows == 1) { 
	      $this->key_stmt->bind_result($key);
	      $this->key_stmt->fetch();
	      return $key;
	   } else {
	      $random_key = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	      return $random_key;
	   }
	}

	private function rands($length = 64) {
	   $s = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzABCDEFEGHIJKLMNOPQRSTUVWXYZ!*@-", $length)), 0, $length);
	   return $s;
	}

	private function encrypt($data, $key) {
	   
	   $salt = 'gF!dyq!rjtReGu7W6bEDRop7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ps@pH';
	   
	   $key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));
	   return $encrypted;
	}

	private function decrypt($data, $key) {
	   
	   $salt = 'gF!dyq!rjtReGu7W6bEDRop7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ps@pH';
	   
	   $key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv);
	   return $decrypted;
	}

}

new secure_session();

