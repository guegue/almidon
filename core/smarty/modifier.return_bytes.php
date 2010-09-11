<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* Type:    modifier
* Name:    return_bytes
* Version:    0.1
* Date:    2009-07-22
* Author:    Christian Torres, chtorrez at gmail dot com
* Purpose:  Convert values return by php_ini to Bytes (K-Kilobyte,M-Megabyte,G-Gigabyte)
* Params:    
            string    size
* -------------------------------------------------------------
*/
function smarty_modifier_return_bytes($size) {
  $val = trim($size);
  $last = strtolower(substr($size, -1));
  switch($last) {
    // The 'G' modifier is available since PHP 5.1.0
    case 'g':
      $val *= 1024;
    case 'm':
      $val *= 1024;
    case 'k':
      $val *= 1024;
  }
  return $val;
} //~ end function
?>
