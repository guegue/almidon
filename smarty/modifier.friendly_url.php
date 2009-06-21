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
 * Name:     friendly_url<br>
 * Date:     Mar 15, 2005
 * Purpose:  convert plain text url to string for url
 * Example:  {$text|url}
 * @return string
 */
function smarty_modifier_friendly_url($str,$max=32)
{
  $search = array('_',' ','á','à','â','ã','ª','Á','À',
                  'Â','Ã', 'é','è','ê','É','È','Ê','í','ì','î','Í',
                  'Ì','Î','ò','ó','ô', 'õ','º','Ó','Ò','Ô','Õ','ú',
                  'ù','û','Ú','Ù','Û','ç','Ç','Ñ','ñ');
  $replace = array('-','-','a','a','a','a','a','A','A',
                   'A','A','e','e','e','E','E','E','i','i','i','I',
                   'I','I','o','o','o','o','o','O','O','O','O','u',
                   'u','u','U','U','U','c','C','N','n');
  # First replace several whitespace by just one, Second replace the chars with accent by one without accent (eg. ó by o)
  # Third delete the space from the beginning and end, Fourth convert the string to low chars
  # Fifth delete the chars which are not numbers or are not between a-z
  $str = preg_replace('/[^a-z0-9\-]/', '',strtolower(trim(str_replace($search, $replace, preg_replace("/(\s){2,}/",'$1',$str)))));
  # If the string is longer than $max chars delete the others after the first $max chars.
  return substr($str,0,$max);
}

/* vim: set expandtab: */
?>
