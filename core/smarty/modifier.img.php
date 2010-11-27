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
function smarty_modifier_img($string)
{
  $imagen = '{if $row.imagen2}<table align="left" cellpadding="3" border="0" cellspacing="0"><tr><td><table width="100" cellpadding="0" cellspacing="0" border="0" ><tr><td><img src="/cms/pic/250x250/noticia/{$row.imagen2}" border="1" alt="Foto" align="top"/></td></tr>{if $row.pie2}<tr><td class="dark">{$row.pie2}</td></tr>{/if}'
  $string = preg_replace('/<img2>/',"$imagen", $string);
  $imagen = '{if $row.imagen3}<table align="left" cellpadding="3" border="0" cellspacing="0"><tr><td><table width="100" cellpadding="0" cellspacing="0" border="0" ><tr><td><img src="/cms/pic/250x250/noticia/{$row.imagen3}" border="1" alt="Foto" align="top"/></td></tr>{if $row.pie3}<tr><td class="dark">{$row.pie3}</td></tr>{/if}'
  $string = preg_replace('/<img3>/',"$imagen", $string);
  return $string;
}

/* vim: set expandtab: */

?>
