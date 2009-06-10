<?php
/**
 * Smarty {confirm_delete} function plugin
 *
 * File:   function.confirm_delete.php<br>
 * Type:   function<br>
 * Name:   confirm_delete<br>
 * Date:   23.abr.2009<br>
 */

include dirname(__FILE__) . '/shared.lang.php';

define('SCRIPT', "
<script language=\"javascript\">
  function confirm_delete(o, idfield, id, desc) {
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
        location.href = '?o='+o+'&action=delete&'+idfield+'='+id;
    }
  }

  function confirm_delete_det(od, idfield, id, desc) {
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
        location.href = '?od='+od+'&actiond=delete&'+idfield+'='+id;
    }
  }

  function confirm_delete2(o, idfield1, idfield2, id1, id2, desc) {
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
        location.href = '?o='+o+'&action=delete&'+idfield1+'='+id1+'&'+idfield2+'='+id2;
    }
  }
</script>
");

function smarty_function_confirm_delete($params, &$smarty)
{
  return SCRIPT;
}

?>
