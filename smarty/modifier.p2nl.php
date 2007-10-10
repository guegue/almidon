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
 * Name:     txt2html<br>
 * Date:     Feb 26, 2003
 * Purpose:  convert \r\n, \r or \n to <<br>>
 * Input:<br>
 *         - contents = contents to replace
 *         - preceed_test = if true, includes preceeding break tags
 *           in replacement
 * Example:  {$text|txt2html}
 * @link http://smarty.php.net/manual/en/language.modifier.txt2html.php
 *          txt2html (Smarty online manual)
 * @version  1.0
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_p2nl($string)
{
  $string = preg_replace('/\.(\s*?)\r(\w*)/',".\r\r\$2", $string);
  $string = preg_replace('/”(\s*?)\r(\w*)/',"”\r\r\$2", $string);
  $string = preg_replace('/"(\s*?)\r(\w*)/',"\"\r\r\$2", $string);
  //$string = preg_replace('/\.((\n|\r|\ )+)((\S|\ ){6,50})((\n|\r)+)((\S|\ ){50,})/',"<h3>\$3</h3>", $string);
  $string = preg_replace('/\.((\n|\r|\ )+)((\S|\ ){6,50})((\n|\r)+)((\S|\ ){50,})/',"<h3>\$3</h3>$7", $string);
  $string = preg_replace('/<\/h3>((\n|\r)+)/',"</h3>", $string);
  return $string;
}

/* vim: set expandtab: */

?>
