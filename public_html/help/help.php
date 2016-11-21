<?php

/**
 *
 * @author    glyn thomas
 * @version   1.0.0 : 28th April 2014
 * @copyright @crowdcc_ @glynthom
 * 
 * help.php  * crowdcc help * contact 
 *
 *
 * session check, public / private key check = > 
 *
 * post in :: email address * text message * client details
 *
 *
 */

/* crypt lib * require_once('crypt/RSA.php'); */
require_once($_SERVER["DOCUMENT_ROOT"] .'/../crypt/RSA.php');

define("KEY_PRIVATE", "-----BEGIN RSA PRIVATE KEY----- 
MIIBOgIBAAJBAIZPnO71UhxLWgDlVAJTkKX4SK7rtPw+fRz7dB8iq4ULEbx6uJrJ
AY0yOKpB36uEjI8kun5DfUFDLu8b8nidfg8CAwEAAQJAAbt/0rU8seYRlcwKIV2N
PWwjw93WdR/OjVPQ/ksm2zhxrvdq/NFhkHOT5owniwZn1i469CgIcp+yg2hWyelW
wQIhAItA9dgKEdDLM5MGbDbIpH6/LChT/whhP3BfRvWxpFi1AiEA9unSnq1F0BoT
8Oh2IxPzYGkv8yiOKpTd2RQ3p0z9yjMCIBvrhl8uhavrUgfkfcXuLK0M/3mGfdfc
R6/sKnoQh/cRAiEAyFyTkjmvJiCTP/GTNAHTg8+3nkyxmLI2mBoE01jxtOsCIBUO
lpientwfH+sLxnELlZHa3KIA4qxHMm2jAAX5oJB8 
-----END RSA PRIVATE KEY-----");

/* load required lib files. */

/* help app functions */
require_once($_SERVER["DOCUMENT_ROOT"] .'/../db/app.help.functions.php');

/* require_once($_SERVER["DOCUMENT_ROOT"].'/../db/found.app.notice.php'); */
/* log_found('found log test', ' checking' . 'log writes' , 'callin.php', __LINE__ ); */

/* phpmailer lib * auth mail lib files * mailresetlink($to, $token_send) * see ccmail app.error.php */
require_once($_SERVER["DOCUMENT_ROOT"].'/../mlib/PHPMailerAutoload.php');


if (!isset($_POST) ) { rtnwebapp('error_tamper' , 'error_tamper_1' , 'post'); exit(); }

$method = $_SERVER['REQUEST_METHOD'];

	switch (true) {

		 case (isset($_POST['_ccc'])):

		       $ecode_clean = '';
	  		   $ccmsg_clean = '';

	  		   $pltfrm_clean = '';
	  		   $browsr_clean = '';
	  		   $timezo_clean = '';
	  		   $kcode_clean = '';

		 	   $token_in = $_POST['_ccc'];
		
			   $token_in = explode( ":", $token_in );

			   /* ccmail intial filter */

	  		   $ecode_clean  = filter_var( $token_in[0] , FILTER_SANITIZE_STRING);      /* $ecode_clean (any problem chars stripped out) */
	  		   $ccmsg_clean  = filter_var( $token_in[1] , FILTER_SANITIZE_STRING);      /* $ccmsg_clean (any problem chars stripped out) */

	  		   /* other platform data sanitation */

	  		   $pltfrm_clean = filter_var( $token_in[2] , FILTER_SANITIZE_STRING);  	 /* $pltfrm_clean */
	           $browsr_clean = filter_var( $token_in[3] , FILTER_SANITIZE_STRING);  	 /* $browsr_clean */
	           $timezo_clean = filter_var( $token_in[4] , FILTER_SANITIZE_STRING);  	 /* $timezo_clean */
	           $kcode_clean = filter_var( $token_in[5] , FILTER_SANITIZE_STRING);  	     /* $tkode_clean */

			   /* validation checks */

			   switch (true) {

	    	 	case ($token_in[0] !== $ecode_clean):
	    	  		  // print_r('email fail' . $ecode_clean);
	    	  		  rtnwebapp( 'error_tamper', 'error_tamper_0' , 'post' );		    /* test for failure */	
	    		break;
    
	    		case ($token_in[1] !== $ccmsg_clean):
	    	  		  // print_r('data tamper' . $lcode_clean);							     
	    	  		  rtnwebapp( 'error_tamper', 'error_tamper_1' , 'post' );			 /* test for failure */	
	    		break;

				case ($token_in[2] !== $pltfrm_clean):
	    	          // print_r('data tamper ->' . $pltfrm_clean);
	    	          rtnwebapp( 'error_tamper', 'error_tamper_2' , 'post' );			 /* test for failure */	
	            break;

	    		case ($token_in[3] !== $browsr_clean):
	    	  		  // print_r('data tamper ->' . $pltfrm_clean);
	    	  		  rtnwebapp( 'error_tamper', 'error_tamper_3' , 'post' );			 /* test for failure */	
	    		break;

	    		case ($token_in[4] !== $timezo_clean):
	    	  		  // print_r('data tamper ->' . $browsr_clean);
	          		  rtnwebapp( 'error_tamper', 'error_tamper_4' , 'post' );			 /* test for failure */	
	    		break;

	    		case ($token_in[5] !== $kcode_clean):
	    	  		  // print_r('data tamper ->' . $timezo_clean);
	    	  		  rtnwebapp( 'error_tamper' , 'error_tamper_5' , 'post' );	         /* test for failure */	
	    		break;

	    	   }

	    	   /* process * ccmail */

			    $token_in[0] = decrypt($token_in[0]);  		         			 	/* ccmail address client validated check */

	  	        $ecode_clean  = filter_var( $token_in[0] , FILTER_SANITIZE_EMAIL);  /* $ecode_clean check (any problem chars stripped out) */

	  			if ($token_in[0] !== $ecode_clean) {								/* ccmail address server filter check */
	  				/* error * send ccmail * not clean * token ccmail */
	  				rtnwebapp( 'error_em4n' , 'error_em4n' , 'post' );	            /* test for failure */
	  			}

	  			if (!valid_email($token_in[0])) {								    /* ccmail address server validation check */
	  				/* error * send ccmail * not match * token ccmail */
	  				rtnwebapp( 'error_em4n' , 'error_em4n' , 'post' );	            /* test for failure */
	  			}

				// print_r( $token_in[0] );
				// print_r('|');

				$token_in[1] = decrypt($token_in[1]);                /* -> ccmail msg, filter * escaped on client only */
				$ccc_token = $token_in[1];

				// print_r( $token_in[1] );
				// print_r('|');
			 
	  			$token_in[2] = base64_decode($token_in[2]);  		 /* -> pltfrm */

	  			// print_r( $token_in[2] );
	  			// print_r('|');

				$token_in[3] = base64_decode($token_in[3]);  		 /* -> browsr */

				// print_r( $token_in[3] );
	  			// print_r('|');
			
				$token_in[4] = base64_decode($token_in[4]);  		 /* -> timezo */

				if ($token_in[4] !== '') {
	    			date_default_timezone_set("UTC");
	    			$now = time();
	    			$date = new DateTime(null, new DateTimeZone( $token_in[4] ));
	   				$timelocal_user = date("Y-m-d H:i:s",($date->getTimestamp() + $date->getOffset()));
	  			} else {
	    			rtnwebapp( 'error_em5n' , 'error_em5n' , 'post' ); /* test for failure */
	    			exit();
	    		}

				// print_r( $token_in[4] );
	  			// print_r('|');

	  		    $token_in[5] = ccrypt( $token_in[5], 'AES-256-CFB', 'de' );     /* decrypt token for msg validation */
	  		    $token_in[5] = substr( $token_in[5], ( $pos = strpos( $token_in[5], '|' ) ) === false ? 0 : $pos + 1 );

				// print_r( $token_in[5] );
	  			// print_r('|');

	  			/* xsfr check * validation check * email address send * should match the decrypted * token email address */
	  			if ($token_in[0] !== $token_in[5]) {
	  				/* error * send ccmail * not match * token ccmail */
	  				rtnwebapp( 'error_tamper' , 'error_tamper_6' , 'post' );	/* test for failure */
	  				exit();
	  			} 
	  		
	  			/* send_ccmail( $to, $from, $msg, $timelocal_user, $browser, $platform ); -> $to === target crowdcc admin email address */
	  			
	  			$to = 'hello@crowdcc.com';  // send to crowdcchq@gmail.com * crowdcc HQ (head quarters) * or hello@crowdcc.com

	  			/* send_ccmail( $to, $token_in[0], $token_in[1], $timelocal_user, $token_in[3], $token_in[2] ); */

	  			send_ccmail( $to, $token_in[0], 'ccsrvmail@gmail.com', 'p1nkp0nthErbEastsErvEr', $token_in[1], $timelocal_user, $token_in[3], $token_in[2] );

	  			/* send_ccmail( $to, $from, $smtpmail, $stmppass, $msg, $date, $bro, $plt ) */

	  			// print_r('thats all she wrote');
	  			// exit();
		 break;


		 case (isset($_GET['token'])):

       	    if (empty($_GET['token'])) { rtnwebapp('error_tamper' , 'error_tamper_7' , 'post'); exit(); }

  			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARTDED_FOR'] != '') { $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR']; } else { $ip_address = $_SERVER['REMOTE_ADDR']; }
            if (!filter_var($ip_address, FILTER_VALIDATE_IP)) { rtnwebapp('error_tamper' , 'error_tamper_7' , 'post'); exit(); }

            /* (1) check if no token received * (2) check ip address filter IP address */

       	    $token_uname = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
	        $token_uname = htmlspecialchars($token_uname, ENT_COMPAT | ENT_QUOTES | ENT_HTML5, 'UTF-8');
	        $token_uname = trim(decrypt($token_uname));

	   		/* date_default_timezone_set("UTC"); $key = 'back2crowdcc'; */
	   		/* ultD5FGzuit3sK4IfugwtGEfPjIdx4S6mWZYyBGplnw=   based on rails twitter token of 44 chars */
	   		/* date_default_timezone_set("UTC");
	   		   $rand_string = substr(md5( time() . mt_rand(1,100)), 0, 11);
	   		   $auth_string = $rand_string .'|'. $ip_address .'|'. time();
	   		   $token = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $auth_string, MCRYPT_MODE_CBC, md5(md5($key)))); */
	   		/* $token_safe = strtr( $token, "+/", "-_" ); original */
	   		/* $token_unsafe = strtr( $token_safe, "-_", "+/" ); original */
	   		/* $token_safe = strtr( $token, "+/", "$:" ); */
	   		/* $token_unsafe = strtr( $token_safe, "$:", "+/" ); /* warning :: must be base64 encodeded from client :: */
	   		/* $token_decrypt = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($token_unsafe), MCRYPT_MODE_CBC, md5(md5($key))), "\0"); */
	   		/* $token_safe = ccrypt( $token_uname .'|'. $ip_address .'|'. date("Y-m-d",time()) , 'AES-256-CFB', 'en' );  // encrypt token for storage */
	   		/* ultD5FGzuit3sK4IfugwtGEfPjIdx4S6mWZYyBGplnw=   based on rails twitter token of 44 chars */

	   		/* (3) check decrypted token * valid email * user input format */
	        
	   		/* log_found('found log', ' valid token : ' . $token_uname , 'help.php', __LINE__ ); */

	   		if (!valid_email( $token_uname )) {								                    /* ccmail additional address server validation check */
	  			/* error * send ccmail * not match * token ccmail */
	  			rtnwebapp( 'error_em4n' , 'error_em4n' , 'post' );	                            /* test for failure */
	  			exit();
	  		}
	   		
	   		$rand_string = substr(md5( time() . mt_rand(1,100)), 0, 10);
	   		$token_safe = ccrypt(  $rand_string .'|'. $token_uname , 'AES-256-CFB', 'en' );     /* encrypt token for storage */

	   		/* if (set_token_user($token_uname, $token_safe, $mysqli)) { rtnwebapp('correct' , $token_safe , '', '', 'post'); } */

	   		rtnwebapp('correct' , $token_safe , 'post');

	  	 break;
	  	 
	}


	function valid_email($email) {
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
	      	} else if ($domainLen < 1 || $domainLen > 255) {
	         // domain part length exceeded
	         $isValid = false;
	      	} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
	         // local part starts or ends with '.'
	         $isValid = false;
	        } else if (preg_match('/\\.\\./', $local)) {
	         // local part has two consecutive dots
	         $isValid = false;
	        } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
	         // character not valid in domain part
	         $isValid = false;
	        } else if (preg_match('/\\.\\./', $domain)) {
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


	function rtnwebapp($msgcode, $msgtoken, $whofor ) {

	/*  function is passed the following ;
	 *
	 *  $msgcode        -> 'ccc_ecode' (default) no failure
	 *  $msgtoken       -> tokenstore to check against the tokensend (other half)
	 *  $whofor         -> for twitter or for crowdcc
	 *
	 */

		switch($whofor) {

			case('get'):
				# unset vars
				exit();
			break;

			case('post'):

				switch ($msgcode) {

				    case ('error_tamper'):     /* string injection detected */

					case ('error_pc0de'):      /* email domain invalid ! */
	  			    case ('error_snd_ecode');  /* no reset array postions required, just inform user that the email has been sent to address. */
			        case ('error_end_ecode');  /* fatal error the original email address not found ! */
					case ('error_idb_ecode');  /* error new email address already in use ! * */

					case ('error_em7n'):       /* failure * email failed to be sent to address provided */

					case ('error_em6n'):       /* 'echo password match or not found failure or email already confirmed!'; */
					case ('error_em5n'):       /* failure timezone check */
					case ('error_em4n'):       /* failure email check */
					case ('error_em3n'):	   /* email no match ! */
					case ('error_em1n'):       /* error message token timestamp is too old */
					case ('error_em2n'):	   /* token invalid ! */
					case ('error_em0n'):       /* error message token timestamp invalid    */

					case ('error_rst_ecode'):  /* record for validation, send email confirmation */
					case ('pass_emin'):        /* update completed, email has been confirmed ;-) */
 					case ('correct'): 		   /* get token returned ;-) */

					case ('error_pc0de'):      /* token timestamp invalid (expired) */
					case ('error_ec0de'):      /* token timestamps do not match!  */
					case ('error_ec1de'):      /* password already updated or link expired */
					case ('error_ec2de'):      /* email record not found or bad */
					case ('error_tcode'):      /* social, no twitter account details found */

						  echo json_encode( $msgcode . ':*:' . $msgtoken );

					break;

				}

				# unset vars
				exit();
	
			break;
		}

		// clean vars (needs to be added to)
		// $query = NULL; $result = NULL;
    	// $db_from_screen_name = NULL;
    	// $db_from_user_uid = NULL;
    	// $db_access_token = NULL;
        // $db_access_token_secret = NULL;
        // $field_values = NULL;
    	// $row = NULL;

	}

	/*
	function send_ccmail($to, $from, $msg, $date, $bro, $plt ) {
		// send_ccmail( $to, $from, $msg, $timelocal_user, $browser, $platform ); 10/01/2015 11:08 GMT
		// trim msg for subject field ...
		$msg_trm  = strlen( utf8_decode($msg) ) > 10 ? substr( $msg, 0, 10 )."..." : $msg;
		$msg_trm  = html_entity_decode( $msg_trm, ENT_QUOTES );
		$subject = $date . " * ". $from . " * " . $msg_trm ;
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;
		$message = '
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Confirm your crowdcc contact email</title>
		</head>
		<body bgcolor="#FFFFFF" style="-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;">
		<!-- header -->
		<table class="head-wrap" style="max-width: 95%;" width="95%" cellspacing="0" cellpadding="0" border="0" align="center" >
		<tbody>
		<tr>
		<td valign="top">
		<tr>
		<td valign="top" height="30" style="min-height:30px"> </td>
		</tr>
		<tr>
		<td>
		<table cellspacing="0" cellpadding="0" border="0" align="center" style="font-family:\'Helvetica Neue\',sans-serif;border-collapse:collapse">
		<tbody>
		<tr>
		<td valign="top">	
		<img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" style="width:135px;height:34px;position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;cursor:pointer;">
		</td>
		<td valign="top" style="padding-top:10px;text-align:right">
		</td>
		</tr>		
        <!-- /header -->
		<tr>
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<!-- content str -->
		<h4>Contact us</h4>
		<h4>From:</h4> ' . $from .'<br>
        <h4>Sent:</h4> ' . $date .' UTC <br>
     	<h4>Message:</h4> ' .$msg . '<p>
		<!-- content end -->		
		<p>&nbsp;</p>
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">To the crowdcc team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">	
		<span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This message has been sent from the crowdcc contact us form.</span>		
		</td>
		</tr>
		</tr>		
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<!-- /footer -->
		</table>
		</body>
		</html>
		';
		$headers  = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: contact@crowdcc.com <contact@crowdcc.com>' . "\r\n";
		$headers .= "Return-path: <bounce@crowdcc.com>\r\n";
		$headers .= "Errors-To: <bounce@crowdcc.com>\r\n";	
		if (mail($to,$subject,$message,$headers,"-fbounce@crowdcc.com")) {
			rtnwebapp('pass_emin' , 'pass_emin' , 'post');	    // contact us * email pass * email has been sent to the crowdcc team
			// err msg test
		  	// rtnwebapp( 'error_em4n' , 'error_em4n' , 'post' );	    // test for failure check
		    // rtnwebapp( 'error_em5n' , 'error_em5n' , 'post' );       // test for failure check 
		    // rtnwebapp( 'error_em7n' , 'error_em7n' , 'post');        // test for failure check									  
			// rtnwebapp( 'error_tamper', 'error_tamper_0' , 'post' );  // test for failure check
		} else {
			rtnwebapp('error_em7n' , 'error_em7n' , 'post');	// contact us * email fail * email has failed to be sent to the crowdcc team, please try again ! 	
		}

	}
	*/

	function send_ccmail($to, $from, $smtpmail, $stmppass, $msg, $date, $bro, $plt ) {
		// send_ccmail( $to, $from, $msg, $timelocal_user, $browser, $platform ); 10/01/2015 11:08 GMT
		// trim msg for subject field ...

		/* SMTP needs accurate times, and the PHP time zone MUST be set */

        if (date_default_timezone_get() === '') {
            date_default_timezone_set('Europe/London');
        }

		/* Create a new PHPMailer instance */
		$mail = new PHPMailer;

		/* Tell PHPMailer to use SMTP */
		$mail->isSMTP();

		/* Enable SMTP debugging */
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;

		/* Ask for HTML-friendly debug output */
		$mail->Debugoutput = 'html';

		/* Set the hostname of the mail server */
		$mail->Host = 'smtp.gmail.com';
		// use
		// $mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6

		/* Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission */
		$mail->Port = 587;

		/* Set the encryption system to use - ssl (deprecated) or tls */
		$mail->SMTPSecure = 'tls';

		/* Whether to use SMTP authentication */
		$mail->SMTPAuth = true;

		/* Username to use for SMTP authentication - use full email address for gmail */
		$mail->Username = $smtpmail;

		/* Password to use for SMTP authentication */
		$mail->Password = $stmppass;

		/* Set who the message is to be sent from */
		$mail->setFrom($from , $from);

		/* Set an alternative reply-to address */
		$mail->addReplyTo('noreply@crowdcc.com', 'no reply');

		/* Set who the message is to be sent to */
		// $mail->addAddress($to, $fullname);
		$mail->addAddress($to);

		$msg_trm  = strlen( utf8_decode($msg) ) > 10 ? substr( $msg, 0, 10 )."..." : $msg;
		$msg_trm  = html_entity_decode( $msg_trm, ENT_QUOTES );

		/* Set the subject line */
		$mail->Subject = $date . " * ". $from . " * " . $msg_trm ;
		
		$uri = 'http://'. $_SERVER['HTTP_HOST'] ;

		$mail->msgHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
        <meta name="viewport" content="width=device-width" />
        <title>Confirm your crowdcc contact email</title>
		</head>
		<body bgcolor="#FFFFFF" style="-webkit-font-smoothing:antialiased; -webkit-text-size-adjust:none; font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;">
		<!-- header -->
		<table class="head-wrap" style="max-width: 95%;" width="95%" cellspacing="0" cellpadding="0" border="0" align="center" >
		<tbody>
		<tr>
		<td valign="top">
		<tr>
		<td valign="top" height="30" style="min-height:30px"> </td>
		</tr>
		<tr>
		<td>
		<table cellspacing="0" cellpadding="0" border="0" align="center" style="font-family:\'Helvetica Neue\',sans-serif;border-collapse:collapse">
		<tbody>
		<tr>
		<td valign="top">	
		<img width="135" height="34" src="http://unbios.com/img/ccc_icon_logo_170x42.png" alt="crowdcc" style="width:135px;height:34px;position:relative;left:-4px;display:block;border:none;text-decoration:none;outline:hidden;cursor:pointer;">
		</td>
		<td valign="top" style="padding-top:10px;text-align:right">
		</td>
		</tr>		
        <!-- /header -->
		<tr>
		<td valign="top" height="30" style="min-height:30px;border-bottom:1px solid #f1f1f1" colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<tr>
		<td valign="top" colspan="2">
		<!-- content str -->
		<h4>Contact us</h4>
		<h4>From:</h4> ' . $from .'<br>
        <h4>Sent:</h4> ' . $date .' UTC <br>
     	<h4>Message:</h4> ' .$msg . '<p>
		<!-- content end -->		
		<p>&nbsp;</p>
		<h4>
        <a href="https://twitter.com/crowdccHQ" style="text-decoration: none; color:#000000;">To the crowdcc team</a>
		</h4>
		<p style="padding-bottom:10px;">
		<tr>
		<td valign="top" style="min-height:30px;border-top:1px solid #E9E9E9"  colspan="2"> </td>
		</tr>
		<tr>
		<td valign="top" height="20" style="min-height:20px"> </td>
		</tr>
		<td valign="top" colspan="2">	
		<span>Have a question or just want to say hello? <b><a href="https://twitter.com/crowdccHQ" style="color:#1c1c2f;">tweet us</a></b></span>
		<p style="padding-top:10px;padding-bottom:10px;">
		<!-- footer -->
		<span style="min-height:40px;padding-top:30px;font-size:10pt;color:grey;">This message has been sent from the crowdcc contact us form.</span>		
		</td>
		</tr>
		</tr>		
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<!-- /footer -->
		</table>
		</body>
		</html>');

		/* Replace the plain text body with one created manually */
		$mail->AltBody = 'This is a plain-text message body';

		/* Attach an image file */
		// $mail->addAttachment('images/phpmailer_mini.png');

		/* send the message, check for errors */
		if (!$mail->send()) {
 		    // echo "Mailer Error: " . $mail->ErrorInfo;
 		    /* log_found('found log mail err', $mail->ErrorInfo , 'help.php', __LINE__ ); */
 		    rtnwebapp('error_em7n' , 'error_em7n' , 'post');	// contact us * email fail * email has failed to be sent to the crowdcc team, please try again ! 
            // exit();							 									  
		} else {
			rtnwebapp('pass_emin' , 'pass_emin' , 'post');	    // contact us * email pass * email has been sent to the crowdcc team
			// err msg test
		  	// rtnwebapp( 'error_em4n' , 'error_em4n' , 'post' );	    // test for failure check
		    // rtnwebapp( 'error_em5n' , 'error_em5n' , 'post' );       // test for failure check 
		    // rtnwebapp( 'error_em7n' , 'error_em7n' , 'post');        // test for failure check									  
			// rtnwebapp( 'error_tamper', 'error_tamper_0' , 'post' );  // test for failure check								  
		}

	}



?>