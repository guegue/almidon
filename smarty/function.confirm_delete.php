<?php
/**
 * Smarty {confirm_delete} function plugin
 *
 * File:   function.confirm_delete.php
 * Type:   function
 * Name:   confirm_delete
 * Date:   23.abr.2009
 */

include dirname(__FILE__) . '/shared.lang.php';

define('SCRIPT', "
<script type=\"text/javascript\" language=\"javascript\">
  function confirm_delete(o, idfield, id, desc) {
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
        location.href = '?o='+o+'&amp;action=delete&amp;'+idfield+'='+id;
    }
  }

  function confirm_delete_det(od, idfield, id, desc) {
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
        location.href = '?od='+od+'&amp;actiond=delete&amp;'+idfield+'='+id;
    }
  }

  function confirm_delete2(o, idfield1, idfield2, id1, id2, desc) {
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
        location.href = '?o='+o+'&amp;action=delete&amp;'+idfield1+'='+id1+'&amp;'+idfield2+'='+id2;
    }
  }
</script>
");

function smarty_function_confirm_delete($params, &$smarty)
{
  return SCRIPT;
}
