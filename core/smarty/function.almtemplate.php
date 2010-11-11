<?php
/**
 * Smarty {almtemplate} function plugin
 *
 * File:   function.almtemplate.php<br>
 * Type:   function<br>
 * Name:   almtemplate<br>
 * Date:   11.nov.2010<br>
 * Author:   christian torres chtorrez at gmail.com<br>
 */

function smarty_function_almtemplate($params, &$smarty)
{
  if(empty($params['file'])) {
    $smarty->trigger_error("file: missing 'name' parameter");
    return;
  }
  $include_vars = array();
  if( count($params) > 1 )
    foreach( $params as $param => $value )
      $include_vars[$param] = $value;
  $smarty->_smarty_include(array('smarty_include_tpl_file'=>ALMIDONDIR . '/tpl/' . $params['file'],smarty_include_vars=>$include_vars));
}
?>
