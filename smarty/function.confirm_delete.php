<?php
/**
 * Smarty {confirm_delete} function plugin
 *
 * File:   function.confirm_delete.php<br>
 * Type:   function<br>
 * Name:   confirm_delete<br>
 * Date:   23.abr.2009<br>
 */
# Choose the language base in the webbrowser
if(DETECT_LANG===true) {
  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  if(file_exists(dirname(__FILE__) . '/shared.lang_' . $lang . '.php'))  include dirname(__FILE__) . '/shared.lang_' . $lang . '.php';
} elseif(defined(ALM_LANG)) {
  if(file_exists(dirname(__FILE__) . '/shared.lang_' . ALM_LANG . '.php'))  include dirname(__FILE__) . '/shared.lang_' . ALM_LANG . '.php';
}
# End

if(!defined('ALM_AL_MSG_DEL'))  define('ALM_AL_MSG_DEL','Estas seguro de querer borrar este registro?');

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
