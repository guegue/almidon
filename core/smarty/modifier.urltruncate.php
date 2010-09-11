<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     urlr<br>
 * Date:     Mar 15, 2005
 * Purpose:  convert plain text url to html references
 * Example:  {$text|url}
 * @return string
 */
function smarty_modifier_urltruncate($string, $length = 20, $etc = '...', $break_words = false)
{
  if ($length == 0)
    return '';
  if (strlen($string) > $length) {
    $length -= strlen($etc);
    if (!$break_words) {
      $text = $string;
      $text = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
    }
    $string = '<a href="'.$string.'">'.$text.'</a>';
    return $string.$etc;
    #return substr($string, 0, $length).$etc;
  } else
    return $string;
}

/* vim: set expandtab: */

?>
