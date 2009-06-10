<?php
/**
 * Smarty {confirm_delete} function plugin
 *
 * File:   function.lang_const.php<br>
 * Type:   function<br>
 * Name:   confirm_delete<br>
 * Date:   23.abr.2009<br>
 */

include dirname(__FILE__) . '/shared.lang.php';

function smarty_function_lang_const($params, &$smarty)
{
  if(empty($params['name'])) {
    $smarty->trigger_error("lang_const: missing 'name' parameter");
    return;
  }
  if(!defined($params['name']))
    return false;
  else  return constant($params['name']);
}
?>
