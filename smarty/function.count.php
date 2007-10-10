<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.count.php
 * Type:     function
 * Name:     count
 * Purpose:  count the number of item of the a array
 * -------------------------------------------------------------
 */
function smarty_function_count($params, &$smarty)
{
    if (empty($params['var'])) {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }

    if (!in_array('value', array_keys($params))) {
        $smarty->trigger_error("assign: missing 'value' parameter");
        return;
    }

    $smarty->assign($params['var'], count($params['value']));
}
?>
