<?php

/**
*
* @author    glyn thomas
* @version   1.00.00
* @copyright @crowdcc_ @glynthom
* 
*
*/

/* load crowdcc err handle */
require_once('db/errorhandle.php');

/* load required lib files. */
include 'db/functions.php';

print_r('<p> total api token :');

$token = create_api_token('crowdccHQ', '10');
// $token = '102027b54d7e20ab-39769141d9-4d7-3e679d7e-20a-5-e';

print_r($token);

print_r('<p> total api token length : ' . strlen(utf8_decode($token)));

print_r('<p>');

print_r('-----------');

print_r('<p>');

print_r('original test db token : aee6abce45d62a3ad4-297d9d64-45d-3a798b67-2a3-6825-dcc0f5a54a');

print_r('<p>');

print_r('juggled test db token : 297d9d64-45d-3a798b67-2a3-dcc0f5a54a-6825-aee6abce45d62a3ad4');

print_r('<p>');

print_r('joined test db token: ' . join_api_token( '297d9d64-45d-3a798b67-2a3-dcc0f5a54a-6825-aee6abce45d62a3ad4' ) );

print_r('<p>');

print_r('<p> -----------');

$uname = explode(',', check_api_token( $token ));

print_r('<p>' . $uname[0]  );

print_r('<p>' . $uname[1]  );

print_r('<p> -----------');

print_r('<p>check * guid test token');

print_r('<p>');

// $uname = explode(',', check_api_token( 'aee6abce45d62a3ad4-297d9d64-45d-3a798b67-2a3-6825-dcc0f5a54a' ));

/*  3457863f3401ee5ef7-297d9d64-340-3a798b67-ee5-6c25c2-b9b16163 */

/*  297d9d64-340-3a798b67-ee5-b9b16163-6c25c2-3457863f3401ee5ef7 */

$uname = explode(',', check_api_token( '5445a2ca1ee8e96039-3266976d-1ee-287b9060-e96-6c25-cec78527e3' ));

print_r('<p>' . $uname[0]  );

print_r('<p>' . $uname[1]  );

print_r('<p> -----------');

print_r('<p>check * db test token');

print_r('<p>');

print_r('<p> -----------');

print_r('<p>');

 if  ($uname[1] === '100000') {

      print_r('token limit is good');

 } else {

       print_r('token limit is bad');

 }
      
print_r('<p>');

print_r('-----------');

print_r('<p> juggle token:');

print_r('<p>' . juggle_api_token( $token )  );

print_r('<p> ----');

print_r('<p> join token: ' . join_api_token( juggle_api_token( $token ) ) );

print_r('<p>');

print_r('<p> re-check token: ' . check_api_token( join_api_token( juggle_api_token( $token ) ) ) );

print_r('<p>');

$flake = simpleflake();

print_r('<p>');

echo "id: $flake\n";

$parts = parse($flake);

echo "timestamp:  " . $parts["timestamp"] . "\n";
echo "randombits: " . $parts["randombits"] . "\n";

print_r('<p>');


print_r(flaketest());


print_r('<p>');

// print_r( parse_simpleflake(simpleflake($timestamp = null, $randombits = null, $epoch = 946702800), $epoch = 946702800));

print_r('<p> ---- <p>');


/* Array Creation */
$arr = array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9);
echo "plaintext array: ";
print_r($arr);
$input = "";
foreach($arr as $v){
    $input .= chr($v);
}

/* PHP Encryption */
$key = "this is a secret key";
$td = mcrypt_module_open('rijndael-256', '', 'ofb', '');
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
$ks = mcrypt_enc_get_key_size($td);
mcrypt_generic_init($td, $key, $iv);
$encrypted = mcrypt_generic($td, $input);
mcrypt_generic_deinit($td);

$result = array();
for($i = 0;$i < strlen($encrypted);$i++ ) {
   $result[] = ord($encrypted{$i});
}
echo "<br />enciphered array: ";
print_r($result);


/* PHP Decryption */
mcrypt_generic_init($td, $key, $iv);
$decrypted = mdecrypt_generic($td, $encrypted);
mcrypt_generic_deinit($td);
mcrypt_module_close($td);

$decrypt = array();
for($i = 0;$i < strlen($decrypted);$i++ ) {
   $decrypt[] = ord($decrypted{$i});
}
echo "<br />deciphered array: ";
print_r($decrypt);


/* start new scrach pad */

/*

function simpleflake($timestamp = null, $randombits = null, $epoch = 946702800) {
 /**
 * Generate a 64 bit, roughly-ordered, globally-unique ID.
 *
 * @param int|null $timestamp
 * @param int|null $randomBits
 * @param int $epoch
 * @return int
 */

 /*

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

  $flake = ($timestamp <<  $timestamp_shift) | $randombits;
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

 /*

 $epoch = 946702800;
 $timestamp_shift = 23;
 $random_max_value = 4194303;

 $timestamp = ($flake >>  $timestamp_shift) / 1000.0;
 $randombits = $flake & $random_max_value;

  return array(
      "timestamp" => $timestamp + $epoch,
      "randombits" => $randombits
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

 /*
   
   return parse($flake, $epoch);
}


function flaketest() {
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
*/



