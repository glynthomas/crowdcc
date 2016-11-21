<?php

namespace crowdcc\TwitterOAuth;

/**
 * @author Abraham Williams <abraham@abrah.am>
 */
class TwitterOAuthException extends \Exception
{

   public function __toString() {
	   return 'Twitter Response: [' . $this->code . '] ' . $this->message . ' (' . __CLASS__ . ') ';
   }

}
