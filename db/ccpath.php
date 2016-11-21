<?php

/**
 *
 * @author    glyn thomas
 * @version   1.00.00
 * @copyright @crowdcc_ @glynthom
 *
 * ccpath.php
 * 
 * function geturl() 
 * contains the central path function for the web app
 * current url taking into account https and port and optional page
 *
 * echo geturl(); returns the path (the current url)
 * echo geturl('page'); returns the path including the calling php file name (page)
 *
 * note: for security the return $url maybe replaced by a hardcoded absolute server path
 *       with $url relative paths being commented out ... !
 *
 * @link original link http://css-tricks.com/snippets/php/get-current-page-url/
 * @version 1.01 enhanced by @glynthom
 */


function geturl($page = 'url') {
    $msg  = '';
    $url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
    $url .= '://' . $_SERVER['SERVER_NAME'];
    $url .= in_array( $_SERVER['SERVER_PORT'], array('80', '443') ) ? '' : ':' . $_SERVER['SERVER_PORT'];
    // $url .= $_SERVER['REQUEST_URI'];
    $url .= substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1);
    if ($page == 'page') {$url .= str_ireplace(array('-', '.php'), array(' ', ''), basename($_SERVER['PHP_SELF']));}
    return $url;
}
