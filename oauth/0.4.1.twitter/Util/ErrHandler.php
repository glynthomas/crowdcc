<?php

namespace crowdcc\TwitterOAuth\Util;

/**
 * @author glyn <glynthoma@gmail.com>
 */
class ErrHandler
{
    /**
     * Provides custom error exception / handling
     *
     * @param string $string
     * @param bool   $asArray
     *
     * @return redirect * exit class
     */
    public static function errhandle($string)
    {
       switch ($string) {
        
          case 28:
              /* throw new TwitterOAuthException('Request timed out.'); */
               if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
               session_destroy();
               header( 'Location: ./?errors=error_tamper' );  /* request timed out! * request user to refresh browser and try again! */
               exit(); 
          break;
          case 51:
              /* throw new TwitterOAuthException('The remote servers SSL certificate or SSH md5 fingerprint failed validation.'); * request user to refresh browser and try again! */
               if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
               session_destroy();
               header( 'Location: ./?errors=error_tamper' );  /* failed validation! * request user to refresh browser and try again! */
               exit();
          break;
          case 56:
              /* throw new TwitterOAuthException('Response from server failed or was interrupted.'); */
               if (session_status() == PHP_SESSION_NONE ) {  session_start(); }; // recommended way for versions of PHP >= 5.4.0
               session_destroy();
               header( 'Location: ./?errors=error_tamper' );  /* response from server failed! * request user to refresh browser and try again! */
               exit();
          break;

       }
    }
}
