<?php
/**
 * Smarty {confirm_delete} function plugin
 *
 * File:   function.confirm_delete.php
 * Type:   function
 * Name:   confirm_delete
 * Date:   2010-09-28
 */

include dirname(__FILE__) . '/shared.lang.php';

define('ALM_DELETE_SCRIPT', "
<script type=\"text/javascript\" language=\"javascript\">
  function confirm_delete(o, key_ids, desc) {
    keys_url = '';
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
      for (i in key_ids) { 
        keys_url += i + '=' + key_ids[i] + '&';
      }
      location.href = '?o='+o+'&action=delete&'+keys_url;
    }
  }
  function confirm_delete_det(od, key_ids, desc) {
    keys_url = '';
    if (window.confirm('\"'+desc+'\": ". ALM_AL_MSG_DEL ."')) {
      for (i in key_ids) { 
        keys_url += i + '=' + key_ids[i] + '&';
      }
      location.href = '?od='+od+'&actiond=delete&'+keys_url;
    }
  }
</script>
");

function smarty_function_confirm_delete($params, &$smarty) {
  return ALM_DELETE_SCRIPT;
}
