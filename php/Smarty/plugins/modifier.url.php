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
function smarty_modifier_url($string)
{
    $string = preg_replace("/(^|\s|\()http:\/\/(.*?)(\s|\)|\,|<|$)/", "$1<a target=\"_blank\" href=\"http://$2\">http://$2</a>$3", $string);
    $string = preg_replace("/(^|\s|\()(\w+?)@(\w+?)\.(\w+?)(\s|\)|\,|<|$)/", "$1<a target=\"_blank\" href=\"mailto:$2@$3.$4\">$2@$3.$4</a>$5", $string);
    $string = preg_replace("/(^|\s|\()((\w|\.)+?)@(\w+?)\.(\w+?)(\s|\)|\,|<|$)/", "$1<a target=\"_blank\" href=\"mailto:$2@$4.$5\">$2@$4.$5</a>$6", $string);
    $string = preg_replace("/(^|\s|\()(\w+?)@(\w+?)\.(\w+?)\.(\w+?)(\s|\)|\,|<|$)/", "$1<a target=\"_blank\" href=\"mailto:$2@$3.$4.$5\">$2@$3.$4.$5</a>$6", $string);
    #error_log($string);
    return $string;
}

/* vim: set expandtab: */

?>
