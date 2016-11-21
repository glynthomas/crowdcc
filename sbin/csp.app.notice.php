<?php

# get the report content

// if (_iscurl()) { $json = file_get_contents_curl('php://input') } else {
 $json = file_get_contents('php://input');
//}

 $csp = indent( $json );

 file_put_contents($_SERVER["DOCUMENT_ROOT"].'/../logs/csp.app.notice.log', $csp, FILE_APPEND);

# mail notice ?


function indent($json) {

  /**
  *
  * ref : http://www.daveperrett.com/articles/2008/03/11/format-json-with-php/
  *
  * Indents a flat JSON string to make it more human-readable.
  *
  * @param string $json The original JSON string to process.
  *
  * @return string Indented version of the original JSON string.
  */

  $result      = '';
  $pos         = 0;
  $strLen      = strlen($json);
  $indentStr   = '  ';
  $newLine     = "\n";
  $prevChar    = '';
  $outOfQuotes = true;

  for ($i=0; $i<=$strLen; $i++) {
       // Grab the next character in the string.
       $char = substr($json, $i, 1);
       // Are we inside a quoted string?
       if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;
        // If this character is the end of an element,
        // output a new line and indent the next line.
       } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
       }
       // Add the character to the result string.
       $result .= $char;
       // If the last character was the beginning of an element,
       // output a new line and indent the next line.
       if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
       }

      $prevChar = $char;
   }

  return $result;
}


function _iscurl() {
  if (in_array  ('curl', get_loaded_extensions())) {
      return true;
  }
  else {
      return false;
  }
}

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

?>