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
function smarty_modifier_txt2html($string)
{
  $string = preg_replace('/<\/h(.*?)>\s\s/','</h\1>', $string);
  $string = preg_replace('/<\/li>\s\s/','</li>', $string);
  $string = preg_replace('/<\/ul>\s\s/','</ul>', $string);
  $string = preg_replace('/<ul>\s\s/','<ul>', $string);
  $string = preg_replace('/<td(.*?)>\s\s/','<td $1>', $string);
  $string = preg_replace('/<\/td>(\s*)/','</td>', $string);
  $string = preg_replace('/<tr(.*?)>\s\s/','<tr $1>', $string);
  $string = preg_replace('/<\/tr>\s\s/','</tr>', $string);
  return nl2br($string);
}

/* vim: set expandtab: */

?>
