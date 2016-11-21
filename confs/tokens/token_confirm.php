<?php

/* re work the signin token function for confirm :: callin.php */

/* found log * log failure outside the JS console ( please comment out / remove later ) */

require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php');

echo 'hello world : testing the signin token :: confirm :: calling :: fe :: verify :: token system for confirm :: crowdcc';
echo '<p>';
echo '<p>';
echo '<p>';

echo 'token_confirm :: token system for confirm : full email ( unbiosed@gmail.com ) is required for check / compare, only accept a maximum length of email!';

echo '<p>';

echo 'token_callreset :: token system for hacked : shortest possible email is a@b.com and .com could be .co, .uk or .uk.org (mixed extentions), so therefore take a sample of email x@x and compare!';

echo '<p>';
echo '<p>';
echo '<p>';

$token_send   = getrandomstr(dechex(time()),'t');
$token_store  = getrandomstr('reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com','e');

echo 'reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com';

echo '<p>';

echo 'email input length => ';

echo strlen('reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com');

$dextime = getstrmsg($token_send[1], 't');    // time in string revealed (should be same as time in emailed string) (use as a check)

$dextime = hexdec($dextime);
$token_time = date("Y-m-d H:i:s", $dextime);
$token_time = (string)$token_time;

echo '<p>';

echo '$token_time => ' . $token_time . ' found and matches email token (true) (time in the email token)';

echo '<p>';

echo 'token send [0] : ';

print($token_send[0]);

echo '<p>';

echo 'signin_token : token send [1] => ';

print($token_send[1]);

echo '<p>';

echo ' length => ';

echo strlen($token_send[1]);

echo '<p>';

echo 'signin_token : timeissued : token send [2] => ';

print($token_send[2]);

echo '<p>';

echo 'token store [0] : ';

print($token_store[0]);

echo '<p>';

echo 'signin_token : sendtweet : token store [1] => ';

print($token_store[1]);

echo '<p>';

echo ' length => ';

echo strlen($token_store[1]);

echo '<p>';

echo 'signin_token : sendtweet : token store [1] : substr email => ';

print( getstrmsg($token_store[1], 'e') );

echo '<p> email => ';

print( getstrmsg(':mngccimngccimngccimngccimngccimngccimngccimngccimngccimngccimngccimngc!argyc$lhr2:f', 'e') );

echo '<p> time => ';

print( date("Y-m-d H:i:s", hexdec(getstrmsg('bl;!li!:w9:6boaqcib7:BcU:xa6bpbA:pbmbI:Xa$:HcQaWav:ZbC:0:$bScn:CaJbqbOa1bBcg:B:AcIbd', 't'))) );

echo '<p>';

echo 'length => ';

echo strlen(getstrmsg($token_store[1], 'e'));

echo '<p>';

echo '<p> length => ';

echo  strlen('clmhdwllzngr!argyc$lhr4bEcdaUaIcEaVa::Vcda5:hcBaTc:adbmaEbIchcmbOcWcHbMavafau:2:yb6c');

echo '<p>';

echo 'clmhdwllzngr!argyc$lhr4bEcdaUaIcEaVa::Vcda5:hcBaTc:adbmaEbIchcmbOcWcHbMavafau:2:yb6c';

echo '<p>';

echo 'crowdcc system only supports an email address of max length => 80 characters in length (max 254),';

echo '<p>';

echo 'this is because this would increase the size of the token to over 200 varchar !';

echo '<p>';

if (strpos('reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com', getstrmsg( $token_store[1], 'e') ) !== false) {
    echo 'email string => reallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreallyreal@gmail.com in given twitter token found';
}

echo '<p>';

echo '( time now ) token store [2] : ';

print($token_store[2]);

echo '<p>';



/* functions * mod * start */


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



/* functions * mod * end */



function textmute($number, $ocrypt, $arrayin) {

    /*

    1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37 38
    a  b  c  d  e  f  g  h  i  j  k  l  m  n  o  p  q  r  s  t  u  v  w  x  y  z  0  1  2  3  4  5  6  7  8  9  @  .
    g  p  l  w  n  u  a  o  y  :  k  c  r  e  h  b  v  m  x  z  f  q  d  s  i  t  6  3  7  1  9  8  0  2  5  4  !  $
    p  h  l  m  r  s  w  b  z  u  t  c  d  v  x  a  y  e  f  k  j  n  g  o  q  i  4  7  6  8  0  9  2  1  3  5  !  $
        
    */

    $transin = array("@" => "!", "." => "$");
    $arrayin = strtr($arrayin, $transin);


    /*  $number = (rand(0,9));  */

    /*  $number == 0            */
        $batch = array('a' => 'g',
                       'b' => 'p',
                       'c' => 'l',
                       'd' => 'w',
                       'e' => 'n',
                       'f' => 'u',
                       'g' => 'a',
                       'h' => 'o',
                       'i' => 'y',
                       'j' => ':',
                       'k' => 'k',
                       'l' => 'c',
                       'm' => 'r',
                       'n' => 'e',
                       'o' => 'h',
                       'p' => 'b',
                       'q' => 'v',
                       'r' => 'm',
                       's' => 'x',
                       't' => 'z',
                       'u' => 'f',
                       'v' => 'q',
                       'w' => 'd',
                       'x' => 's',
                       'y' => 'i',
                       'z' => 't',
                       '0' => '6',
                       '1' => '3',
                       '2' => '7',
                       '3' => '1',
                       '4' => '9',
                       '5' => '8',
                       '6' => '0',
                       '7' => '2',
                       '8' => '5',
                       '9' => '4',
                       '*' => '*',
                       '$' => '$',
                       '~' => '~',
                       '!' => '!'

                       );
    /*  $number == 1            */
        $natch = array('a' => 'p',
                       'b' => 'h',
                       'c' => 'l',
                       'd' => 'm',
                       'e' => 'r',
                       'f' => 's',
                       'g' => 'w',
                       'h' => 'b',
                       'i' => 'z',
                       'j' => 'u',
                       'k' => 't',
                       'l' => 'c',
                       'm' => 'd',
                       'n' => 'v',
                       'o' => 'x',
                       'p' => 'a',
                       'q' => 'y',
                       'r' => 'e',
                       's' => 'f',
                       't' => 'k',
                       'u' => 'j',
                       'v' => 'n',
                       'w' => 'g',
                       'x' => 'o',
                       'y' => 'q',
                       'z' => 'i',
                       '0' => '4',
                       '1' => '7',
                       '2' => '6',
                       '3' => '8',
                       '4' => '0',
                       '5' => '9',
                       '6' => '2',
                       '7' => '1',
                       '8' => '3',
                       '9' => '5',
                       '*' => '*',
                       '$' => '$',
                       '~' => '~',
                       '!' => '!'
                       );

        switch(true) {
                case($number % 2 == 0):
                     $afocus = $batch;
                break;
                case($number % 2 != 0):
                     $afocus = $natch;
                break;
        }
        $data = str_split($arrayin);
        foreach ($data as $value) {
            $arrayout[] = array_search($value, $afocus);
        }

        /* start array half flip experiment 

        $nsize = sizeof($arrayout);
        $flop = round(($nsize / 2), 0, PHP_ROUND_HALF_UP);  
        $arrayx1 = array_slice($arrayout, 0 ,$flop);

        switch(true) {
            case($nsize % 2 == 0):
                 $arrayx2 = array_slice($arrayout,($nsize - $flop), $flop);
                 $arrayout = array_merge($arrayx2, $arrayx1);
            break;
            case($nsize % 2 != 0):
                 $arrayx2 = array_slice($arrayout,($nsize - $flop), $flop);
                 $arrayi2 = array_pop($arrayx1);
                 $arrayout =  array(implode('', $arrayx1), implode('', $arrayx2));
            break;
        }
        
         * end array half flip experiment */

        $array = implode('', $arrayout);

        switch(true) {
            case($ocrypt == 'u'):
                 $transout = array("!" => "@", "$" => ".");
                 $array = strtr($array, $transout); 
            break;
        }

        $array = str_split($array);

    return $array;
    
    }


function arrayfilter($var) {
    return ($var !== NULL && $var !== FALSE && $var !== '');
}


function timemute($ocrypt, $arrayin) {

  /*

  info: timesmute() for mixing up the UNIX time string 

  hex (A - F)   1 2 3 4 5 6 7 8 9 0
  A B C D E F | H G J I L K : M ! $ | 
  W E Y F R N | O P Q S ; V U T Z X |

  */

  $item = '';

  $data = preg_split('//', $arrayin, -1, PREG_SPLIT_NO_EMPTY);

    foreach($data as $onein) {

      $number = (rand(0,9));
      switch(true) {
        case($ocrypt == 'f'):
          switch($onein) {
            case('1'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = 'h';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 'o';
                break;
              }
              break;
            case('2'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = 'g';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 'p';
                break;
              }
              break;
            case('3'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = 'j';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 'q';
                break;
              }
              break;
            case('4'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = 'i';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 's';
                break;
              }
              break;
            case('5'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = 'l';
                break;
                case($number % 2 != 0):
                   $arrayout[] = ';';
                break;
              }
              break;
            case('6'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = 'k';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 'v';
                break;
              }
              break;
            case('7'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = ':';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 'u';
                break;
              }
              break;
            case('8'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = 'm';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 't';
                break;
              }
              break;
            case('9'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = '!';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 'z';
                break;
              }
              break;
            case('0'):
              switch(true) {
                case($number % 2 == 0):
                   $arrayout[] = '$';
                break;
                case($number % 2 != 0):
                   $arrayout[] = 'x';
                break;
              }
            break;
      
            case('A'):
              $arrayout[] = 'W';
              break;
            case('a'):
              $arrayout[] = 'w';
              break;
            case('B'):
              $arrayout[] = 'E';
              break;
            case('b'):
              $arrayout[] = 'e';
              break;
            case('C'):
              $arrayout[] = 'Y';
              break;
            case('c'):
              $arrayout[] = 'y';
              break;
            case('D'):
              $arrayout[] = 'F';
              break;
            case('d'):
              $arrayout[] = 'f';
              break;
            case('E'):
              $arrayout[] = 'R';
              break;
            case('e'):
              $arrayout[] = 'r';
              break;
            case('F'):
              $arrayout[] = 'N';
              break;
            case('f'):
              $arrayout[] = 'n';
            break;
          }
        break;
        case($ocrypt == 'u'):
          switch($onein) {
            case('h'):
            case('o'):
              $arrayout[] = '1';
              break;
            case('g'):
            case('p'):
              $arrayout[] = '2';
              break;
            case('j'):
            case('q'):
              $arrayout[] = '3';
              break;
            case('i'):  
            case('s'):
              $arrayout[] = '4';
              break;
            case('l'):  
            case(';'):
              $arrayout[] = '5';
              break;
            case('k'):    
            case('v'):
              $arrayout[] = '6';
              break;
            case(':'):      
            case('u'):
              $arrayout[] = '7';
              break;
            case('m'):      
            case('t'):
              $arrayout[] = '8';
              break;
            case('!'):      
            case('z'):
              $arrayout[] = '9';
              break;
            case('$'):      
            case('x'):
              $arrayout[] = '0';
            break;
            case('W'):
              $arrayout[] = 'A';
              break;
            case('w'):
              $arrayout[] = 'a';
              break;
            case('E'):
              $arrayout[] = 'B';
              break;
            case('e'):
              $arrayout[] = 'b';
              break;
            case('Y'):
              $arrayout[] = 'C';
              break;
            case('y'):
              $arrayout[] = 'c';
              break;
            case('F'):
              $arrayout[] = 'D';
              break;
            case('f'):
              $arrayout[] = 'd';
              break;
            case('R'):
              $arrayout[] = 'E';
              break;
            case('r'):
              $arrayout[] = 'e';
              break;
            case('N'):
              $arrayout[] = 'F';
              break;
            case('n'):
              $arrayout[] = 'f';
            break;
          }
        break;
      }

    $arrayout[] = $item;
    $arrayout   = array_filter($arrayout, "arrayfilter");

    }

  return $arrayout;

  }

