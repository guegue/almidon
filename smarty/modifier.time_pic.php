<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* Type:    modifier
* Name:    time_pic
* Version:    1
* Date:    2007-07-26
* Author:    Christian Torres
* Purpose: formats a filesize (in bytes) to human-readable format
* Params:    
* -------------------------------------------------------------
*/
require_once $smarty->_get_plugin_filepath('modifier','date_format');

function smarty_modifier_time_pic($picname,$format = '')
{
    $timemark = substr($picname,0,strpos($picname,'_'));
    return smarty_modifier_date_format($timemark,$format);
} //~ end function
?>
